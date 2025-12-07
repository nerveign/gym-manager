<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\GymClass; // Pastikan import ini ada
use App\Models\ClassAgenda; // Pastikan import ini ada
use App\Models\MemberClassProgress; // Pastikan import ini ada
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
        // Ini yang akan ditampilkan di card "Today's Sessions" dan "Upcoming Sessions"
        $todaySessions = $todayBookings + $todayClasses;
        $upcomingSessions = $upcomingBookings + $upcomingClassesCount;

        // Data List untuk tampilan bawah
        $upcomingClasses = GymClass::where('trainer_id', $user->id)
            // ->where('schedule', '>=', now()) // Filter dimatikan dulu agar list tetap muncul saat testing
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
            'todaySessions',    // [BARU] Kirim variabel gabungan
            'upcomingSessions', // [BARU] Kirim variabel gabungan
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

        // Filter by status (upcoming, today, past)
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
     * [BARU] Menampilkan daftar semua kelas milik Trainer
     */
    public function myClasses(): View
    {
        $user = auth()->user();

        // Ambil semua kelas, urutkan dari jadwal terbaru/mendatang
        $classes = GymClass::where('trainer_id', $user->id)
            ->withCount('classMembers') // Hitung jumlah siswa
            ->orderBy('schedule', 'desc')
            ->paginate(10);

        return view('trainer.classes', compact('user', 'classes'));
    }

    /**
     * [BARU] Halaman Detail Kelas untuk Trainer
     */
    public function classDetail($id): View
    {
        $user = auth()->user();

        // Ambil kelas, pastikan milik trainer yang sedang login
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
     * [BARU] Halaman Form Checklist Progress Siswa
     */
    public function studentProgress($classId, $userId): View
    {
        $user = auth()->user();

        // Validasi akses kelas
        $gymClass = GymClass::where('id', $classId)
            ->where('trainer_id', $user->id)
            ->firstOrFail();

        // Ambil data siswa di kelas tersebut
        $studentMember = $gymClass->classMembers()
            ->where('user_id', $userId)
            ->with('user')
            ->firstOrFail();

        $agendas = $gymClass->agendas;

        // Ambil ID agenda yang SUDAH selesai oleh user ini
        $completedAgendaIds = MemberClassProgress::where('user_id', $userId)
            ->whereIn('class_agenda_id', $agendas->pluck('id'))
            ->pluck('class_agenda_id')
            ->toArray();

        return view('trainer.student-progress', compact('user', 'gymClass', 'studentMember', 'agendas', 'completedAgendaIds'));
    }

    /**
     * [BARU] Proses Simpan Progress Siswa
     */
    public function updateStudentProgress(Request $request, $classId, $userId)
    {
        $user = auth()->user();
        $gymClass = GymClass::where('id', $classId)->where('trainer_id', $user->id)->firstOrFail();

        // Ambil agenda yang dicentang dari form
        $submittedAgendas = $request->completed_agendas ?? []; // Array ID agenda

        // Ambil semua agenda yang ada di kelas ini
        $allAgendaIds = $gymClass->agendas->pluck('id')->toArray();

        foreach ($allAgendaIds as $agendaId) {
            if (in_array($agendaId, $submittedAgendas)) {
                // Jika dicentang, pastikan data ada di database
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
                // Jika TIDAK dicentang, hapus data dari database (uncheck)
                MemberClassProgress::where('user_id', $userId)
                    ->where('class_agenda_id', $agendaId)
                    ->delete();
            }
        }

        // Recalculate progress untuk update status lulus otomatis
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
}
