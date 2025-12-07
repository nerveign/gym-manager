<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\UserProgress;
use App\Models\Equipment;
use App\Models\User;
use App\Models\Booking;
use App\Models\Transaction;
use App\Models\GymClass;
use App\Models\ClassMember;
use App\Models\MemberClassProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    /**
     * Dashboard Utama Customer
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $activeMembership = $user->activeMembership;

        // 1. Logika Payment Redirect
        $comingFromSuccessPage = $request->has('from_success') || str_contains(url()->previous(), '/payment/success');

        if (!$comingFromSuccessPage) {
            $recentCompletedTransaction = Transaction::whereHas('membership', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
                ->where('status', 'completed')
                ->where('created_at', '>=', now()->subMinutes(10))
                ->orderBy('created_at', 'desc')
                ->first();

            if ($recentCompletedTransaction && !session()->has('seen_success_' . $recentCompletedTransaction->id)) {
                return redirect()->route('customer.payment.success');
            }
        }

        // 2. Data Dashboard
        $enrolledClasses = ClassMember::where('user_id', $user->id)->count();

        $upcomingBookingsCount = Booking::whereHas('membership', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->where('date', '>=', today())
            ->count();

        $recentProgress = UserProgress::where('user_id', $user->id)
            ->latest()
            ->take(4)
            ->get();

        $upcomingBookings = Booking::whereHas('membership', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->with('trainer')
            ->where('date', '>=', today())
            ->orderBy('date', 'asc')
            ->orderBy('time', 'asc')
            ->limit(5)
            ->get();

        $upcomingBookingsData = $upcomingBookings->map(function ($booking) {
            return (object) [
                'id' => $booking->id,
                'title' => 'Gym Session',
                'date' => $booking->date,
                'time' => $booking->time,
                'trainer' => $booking->trainer
            ];
        });

        // Data My Classes untuk widget di dashboard
        $myClasses = ClassMember::where('user_id', $user->id)
            ->with('gymClass.trainer')
            ->latest()
            ->get();

        $data = [
            'user' => $user,
            'activeMembership' => $activeMembership,
            'upcomingBookingsData' => $upcomingBookingsData,
            'enrolledClasses' => $enrolledClasses,
            'upcomingBookingsCount' => $upcomingBookingsCount,
            'recentProgress' => $recentProgress,
            'myClasses' => $myClasses,
        ];

        return view('customer.dashboard', $data);
    }

    /**
     * Halaman "My Classes" (Daftar Kelas Saya)
     */
    public function myClasses()
    {
        $user = auth()->user();

        $myClasses = ClassMember::where('user_id', $user->id)
            ->with('gymClass.trainer')
            ->latest()
            ->paginate(10);

        return view('customer.my-classes', compact('user', 'myClasses'));
    }

    /**
     * Halaman Cari Kelas Baru (Browse)
     */
    public function browseClasses()
    {
        $user = auth()->user();

        // 1. Ambil ID kelas yang sudah diikuti user
        $enrolledClassIds = ClassMember::where('user_id', $user->id)->pluck('class_id');

        // 2. Ambil kelas tersedia (belum diikuti & jadwal masa depan)
        $availableClasses = GymClass::whereNotIn('id', $enrolledClassIds)
            ->where('schedule', '>=', now())
            ->with(['trainer', 'agendas'])
            ->withCount('classMembers')
            ->orderBy('schedule', 'asc')
            ->paginate(9);

        return view('customer.browse-classes', compact('user', 'availableClasses'));
    }

    /**
     * Proses Join Kelas
     */
    public function joinClass($id)
    {
        $user = auth()->user();
        $gymClass = GymClass::withCount('classMembers')->findOrFail($id);

        // Validasi Kapasitas
        if ($gymClass->class_members_count >= $gymClass->capacity) {
            return back()->with('error', 'Maaf, kelas ini sudah penuh.');
        }

        // Validasi Double Join
        $exists = ClassMember::where('user_id', $user->id)->where('class_id', $id)->exists();
        if ($exists) {
            return back()->with('error', 'Anda sudah terdaftar di kelas ini.');
        }

        // [PERBAIKAN DISINI] Ganti 'active' menjadi 'registered'
        ClassMember::create([
            'user_id' => $user->id,
            'class_id' => $id,
            'status' => 'registered',
        ]);

        return redirect()->route('customer.my-classes')
            ->with('success', 'Berhasil mendaftar! Silakan cek detail kelas Anda.');
    }

    /**
     * Halaman Detail Progress Kelas
     */
    public function classDetail($id)
    {
        $userId = auth()->id();

        $classMember = ClassMember::where('class_id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();

        $progress = $classMember->calculateProgress();

        $gymClass = $classMember->gymClass()->with('agendas')->first();

        $completedAgendaIds = MemberClassProgress::where('user_id', $userId)
            ->whereIn('class_agenda_id', $gymClass->agendas->pluck('id'))
            ->pluck('class_agenda_id')
            ->toArray();

        return view('customer.class-detail', compact('gymClass', 'progress', 'classMember', 'completedAgendaIds'));
    }

    /**
     * Halaman Equipment List
     */
    public function equipments(Request $request)
    {
        $user = auth()->user();
        $query = Equipment::query();

        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where('equipment_name', 'like', "%{$searchTerm}%")
                ->orWhere('brand', 'like', "%{$searchTerm}%");
        }

        $equipments = $query->latest()->paginate(10);
        return view('customer.equipments', compact('user', 'equipments'));
    }

    /**
     * Halaman Trainer List
     */
    public function trainers(Request $request)
    {
        $user = auth()->user();
        $query = User::where('role', 'trainer');

        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        $trainers = $query->latest()->paginate(10);
        return view('customer.trainers', compact('user', 'trainers'));
    }
}
