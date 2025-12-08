<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\GymClass;
use App\Models\ClassAgenda;
use App\Models\MemberClassProgress;
use App\Models\Equipment; // Import Model Equipment
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrainerController extends Controller
{
    /**
     * Display the trainer dashboard.
     */
    public function dashboard(): View
    {
        $user = auth()->user();

        // --- 1. Hitung Booking (Personal Trainer) ---
        $totalBookings = Booking::where('trainer_id', $user->id)->count();

        $todayBookings = Booking::where('trainer_id', $user->id)
            ->whereDate('date', today())
            ->count();

        $upcomingBookings = Booking::where('trainer_id', $user->id)
            ->where('date', '>', now())
            ->count();

        // --- 2. Hitung Kelas (Group Class) ---
        $totalClasses = GymClass::where('trainer_id', $user->id)->count();

        $todayClasses = GymClass::where('trainer_id', $user->id)
            ->whereDate('schedule', today())
            ->count();

        $upcomingClassesCount = GymClass::where('trainer_id', $user->id)
            ->where('schedule', '>', now())
            ->count();

        // --- 3. GABUNGKAN KEDUANYA UNTUK STATISTIK ---
        $todaySessions = $todayBookings + $todayClasses;
        $upcomingSessions = $upcomingBookings + $upcomingClassesCount;

        // Data List untuk tampilan bawah
        $upcomingClasses = GymClass::where('trainer_id', $user->id)
            ->orderBy('schedule', 'desc')
            ->limit(5)
            ->get();

        $recentBookings = Booking::where('trainer_id', $user->id)
            ->with(['membership.user'])
            ->latest('date')
            ->limit(5)
            ->get();

        return view('trainer.dashboard', compact(
            'user',
            'totalBookings',
            'totalClasses',
            'todaySessions',
            'upcomingSessions',
            'upcomingClasses',
            'recentBookings'
        ));
    }

    /**
     * Display trainer's booking list.
     */
    public function bookings(Request $request): View
    {
        $user = auth()->user();

        $query = Booking::where('trainer_id', $user->id)
            ->with(['membership.user']);

        // Filter by search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('membership.user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        // Filter by status
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'today':
                    $query->whereDate('date', today());
                    break;
                case 'upcoming':
                    $query->where('date', '>', today());
                    break;
                case 'past':
                    $query->where('date', '<', today());
                    break;
            }
        }

        $bookings = $query->orderBy('date', 'desc')
            ->orderBy('time', 'desc')
            ->paginate(10);

        return view('trainer.bookings', compact('user', 'bookings'));
    }

    /**
     * Menampilkan daftar semua kelas milik Trainer
     */
    public function myClasses(): View
    {
        $user = auth()->user();

        $classes = GymClass::where('trainer_id', $user->id)
            ->withCount('classMembers')
            ->orderBy('schedule', 'desc')
            ->paginate(10);

        return view('trainer.classes', compact('user', 'classes'));
    }

    /**
     * Halaman Detail Kelas untuk Trainer
     */
    public function classDetail($id): View
    {
        $user = auth()->user();

        $gymClass = GymClass::where('id', $id)
            ->where('trainer_id', $user->id)
            ->with(['agendas', 'classMembers.user'])
            ->firstOrFail();

        // Hitung progress untuk setiap siswa
        foreach ($gymClass->classMembers as $member) {
            $member->current_progress = $member->calculateProgress();
        }

        return view('trainer.class-detail', compact('user', 'gymClass'));
    }

    /**
     * Halaman Form Checklist Progress Siswa
     */
    public function studentProgress($classId, $userId): View
    {
        $user = auth()->user();

        $gymClass = GymClass::where('id', $classId)
            ->where('trainer_id', $user->id)
            ->firstOrFail();

        $studentMember = $gymClass->classMembers()
            ->where('user_id', $userId)
            ->with('user')
            ->firstOrFail();

        $agendas = $gymClass->agendas;

        $completedAgendaIds = MemberClassProgress::where('user_id', $userId)
            ->whereIn('class_agenda_id', $agendas->pluck('id'))
            ->pluck('class_agenda_id')
            ->toArray();

        return view('trainer.student-progress', compact('user', 'gymClass', 'studentMember', 'agendas', 'completedAgendaIds'));
    }

    /**
     * Proses Simpan Progress Siswa
     */
    public function updateStudentProgress(Request $request, $classId, $userId)
    {
        $user = auth()->user();
        $gymClass = GymClass::where('id', $classId)->where('trainer_id', $user->id)->firstOrFail();

        $submittedAgendas = $request->completed_agendas ?? [];
        $allAgendaIds = $gymClass->agendas->pluck('id')->toArray();

        foreach ($allAgendaIds as $agendaId) {
            if (in_array($agendaId, $submittedAgendas)) {
                MemberClassProgress::firstOrCreate(
                    [
                        'user_id' => $userId,
                        'class_agenda_id' => $agendaId
                    ],
                    [
                        'marked_by' => $user->id,
                        'completed_at' => now()
                    ]
                );
            } else {
                MemberClassProgress::where('user_id', $userId)
                    ->where('class_agenda_id', $agendaId)
                    ->delete();
            }
        }

        $member = $gymClass->classMembers()->where('user_id', $userId)->first();
        if ($member) {
            $member->calculateProgress();
        }

        return redirect()->route('trainer.class.detail', $classId)->with('success', 'Progress siswa berhasil diperbarui!');
    }

    /**
     * Display trainer profile edit form.
     */
    public function edit(): View
    {
        $user = auth()->user();
        return view('trainer.profile.edit', compact('user'));
    }

    /**
     * Update Profile Logic (Biodata Only - Tanpa Foto)
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        // Validasi input (Hapus validasi image)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'specialty' => 'nullable|string|max:100',
            'experience_years' => 'nullable|integer|min:0|max:50',
            'bio' => 'nullable|string|max:1000',
        ]);

        // Langsung update data text
        $user->update($validated);

        return redirect()->route('trainer.profile.edit')->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Menampilkan daftar equipment untuk Trainer.
     */
    public function equipments(Request $request): View
    {
        $user = auth()->user();
        $query = Equipment::query();

        // Fitur Search
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where('equipment_name', 'like', "%{$searchTerm}%")
                ->orWhere('brand', 'like', "%{$searchTerm}%");
        }

        $equipments = $query->latest()->paginate(10);

        return view('trainer.equipments', compact('user', 'equipments'));
    }

    /**
     * Menampilkan detail equipment spesifik.
     */
    public function equipmentDetail($id): View
    {
        $user = auth()->user();
        $equipment = Equipment::findOrFail($id);
        
        return view('trainer.equipment-detail', compact('user', 'equipment'));
    }
}