<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\GymClass;
use App\Models\Membership;
use App\Models\User;
use App\Models\Equipment;
use App\Models\Transaction;
use App\Models\ClassMember; // Pastikan Model ini di-import
use App\Models\ClassAgenda; // Pastikan Model ini di-import
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access for admin only.');
        }

        $user = auth()->user();

        // Mengambil statistik untuk kartu-kartu di atas
        $stats = [
            'equipment_count' => $this->getEquipmentStats(),
            'customer_count' => $this->getTotalCustomers(),
            'membership_count' => $this->getActiveMemberships(),
            'trainer_count' => $this->getTotalTrainers(),
            'class_count' => $this->getTotalClasses(),
            'booking_count' => $this->getTotalBookings()
        ];

        // Mengambil data revenue
        $revenue = [
            'monthly' => $this->getMonthlyRevenue(),
            'total' => $this->getTotalRevenue()
        ];

        // Mengambil 5 Membership terbaru (opsional, jika view membutuhkan)
        $recentMemberships = Membership::with('user')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($membership) {
                return (object)[
                    'id' => $membership->id,
                    'user_name' => $membership->user->name,
                    'user_email' => $membership->user->email,
                    'type' => $membership->type,
                    'status' => $membership->status,
                    'join_date' => $membership->created_at->format('d M Y')
                ];
            });

        // [PENTING] Mengambil Recent Transactions untuk tabel bawah
        $recentTransactions = Transaction::with(['user', 'membership.user']) // Eager load relationships
            ->latest()
            ->take(5)
            ->get();

        // [PENTING] Mengambil Recent Trainers untuk list di sebelah kanan
        // Ini yang menyebabkan error "Undefined variable $recentTrainers" jika tidak ada
        $recentTrainers = User::where('role', 'trainer')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'user',
            'stats',
            'revenue',
            'recentMemberships',
            'recentTransactions',
            'recentTrainers' // Pastikan variabel ini dikirim
        ));
    }

    // ... Method lainnya (users, userDetail, classes, equipments, trainers, transactions, bookings, classDetail, storeAgenda, trainerDetail) biarkan seperti sebelumnya ...

    public function users(Request $request)
    {
        $user = auth()->user();

        $query = User::where('users.role', 'customer')
            ->leftJoin('memberships', 'users.id', '=', 'memberships.user_id')
            ->select('users.*', 'memberships.status as membership_status', 'memberships.created_at as membership_created_at');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                    ->orWhere('users.email', 'like', "%{$search}%")
                    ->orWhere('users.phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->paginate(10);

        return view('admin.users', compact('user', 'customers'));
    }

    public function userDetail($id)
    {
        $user = auth()->user();
        $customer = User::with(['memberships', 'transactions', 'userProgress'])->findOrFail($id);

        if ($customer->role !== 'customer') {
            abort(404);
        }

        // Ambil data yang diperlukan untuk view
        $transactions = $customer->transactions()->latest()->get();
        $memberships = $customer->memberships;
        $userProgress = $customer->userProgress;

        // Ambil recent bookings untuk customer ini melalui memberships
        $membershipIds = $customer->memberships->pluck('id');
        $recentBookings = collect();

        if ($membershipIds->count() > 0) {
            $recentBookings = Booking::with('trainer')
                ->whereIn('membership_id', $membershipIds)
                ->latest()
                ->limit(5)
                ->get();
        }

        return view('admin.user-detail', compact('user', 'customer', 'transactions', 'memberships', 'userProgress', 'recentBookings'));
    }

    public function classes(Request $request)
    {
        $user = auth()->user();
        $query = GymClass::with('trainer');

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                // Cari berdasarkan Tipe Kelas ATAU Nama Trainer
                $q->where('type', 'like', "%{$search}%")
                    ->orWhereHas('trainer', function ($subQ) use ($search) {
                        $subQ->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $classes = $query->paginate(10);
        return view('admin.classes', compact('user', 'classes'));
    }

    /**
     * [BARU] Menampilkan Detail Kelas & Progress Member
     */
    public function classDetail($id)
    {
        // 1. Ambil data kelas beserta trainer, agenda, dan members
        $gymClass = GymClass::with(['trainer', 'agendas', 'classMembers.user'])->findOrFail($id);

        // 2. Hitung progress untuk SETIAP member di kelas ini
        foreach ($gymClass->classMembers as $member) {
            // Kita simpan hasil perhitungan ke properti sementara object member
            $member->current_progress = $member->calculateProgress();
        }

        return view('admin.class-detail', compact('gymClass'));
    }

    /**
     * [BARU] Menyimpan Agenda/Materi Baru
     */
    public function storeAgenda(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'order' => 'nullable|integer|min:1'
        ]);

        ClassAgenda::create([
            'gym_class_id' => $id,
            'title' => $request->title,
            'order' => $request->order ?? 1,
        ]);

        return redirect()->route('admin.class.detail', $id)
            ->with('success', 'Agenda berhasil ditambahkan!');
    }

    public function equipments(Request $request)
    {
        $user = auth()->user();
        $query = Equipment::query();

        if ($request->has('search')) {
            $query->where('equipment_name', 'like', "%{$request->search}%");
        }

        $equipments = $query->orderBy('updated_at', 'desc')->paginate(10);

        return view('admin.equipments', compact('user', 'equipments'));
    }

    public function trainers(Request $request)
    {
        $user = auth()->user();
        $query = User::where('role', 'trainer');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('specialization', 'like', "%{$search}%");
            });
        }

        $trainers = $query->paginate(10);
        return view('admin.trainers', compact('user', 'trainers'));
    }

    public function transactions(Request $request)
    {
        $user = auth()->user();

        $query = Transaction::with(['user', 'membership'])
            ->latest();

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('transaction_code', 'like', "%{$search}%");
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('date') && $request->date != '') {
            $query->whereDate('created_at', $request->date);
        }

        $transactions = $query->paginate(10);

        return view('admin.transactions', compact('user', 'transactions'));
    }

    public function bookings(Request $request)
    {
        $user = auth()->user();

        $query = Booking::with(['trainer', 'membership.user']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('trainer', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('membership.user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $bookings = $query->orderBy('date', 'desc')->paginate(10);

        return view('admin.bookings', compact('user', 'bookings'));
    }

    /**
     * [BARU] Update Agenda
     */
    public function updateAgenda(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'order' => 'required|integer|min:1'
        ]);

        $agenda = ClassAgenda::findOrFail($id);

        $agenda->update([
            'title' => $request->title,
            'order' => $request->order
        ]);

        return back()->with('success', 'Materi berhasil diperbarui!');
    }

    /**
     * [BARU] Hapus Agenda
     */
    public function destroyAgenda($id)
    {
        $agenda = ClassAgenda::findOrFail($id);
        $agenda->delete();

        return back()->with('success', 'Materi berhasil dihapus!');
    }

    public function trainerDetail($id)
    {
        $user = auth()->user();
        $trainer = User::where('role', 'trainer')->findOrFail($id);

        $classes = GymClass::where('trainer_id', $id)
            ->with('members')
            ->latest()
            ->limit(10)
            ->get();

        $recentActivities = Booking::where('trainer_id', $id)
            ->with(['membership.user'])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.trainer-detail', compact('user', 'trainer', 'classes', 'recentActivities'));
    }

    // --- Private Helper Methods ---

    private function getEquipmentStats()
    {
        return Equipment::count();
    }

    private function getTotalCustomers()
    {
        return User::where('role', 'customer')->count();
    }

    private function getTotalTrainers()
    {
        return User::where('role', 'trainer')->count();
    }

    private function getActiveMemberships()
    {
        return Membership::where('status', 'active')->count();
    }

    private function getTotalBookings()
    {
        return Booking::count();
    }

    private function getTotalClasses()
    {
        return GymClass::count();
    }

    private function getMonthlyRevenue()
    {
        return Transaction::whereIn('status', ['completed', 'success'])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');
    }

    private function getTotalRevenue()
    {
        return Transaction::whereIn('status', ['completed', 'success'])->sum('amount');
    }
}
