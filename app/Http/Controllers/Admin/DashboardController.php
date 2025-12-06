<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\GymClass;
use App\Models\Membership;
use App\Models\User;
use App\Models\Equipment;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access for admin only.');
        }

        $user = auth()->user();

        $stats = [
            'equipment_count' => $this->getEquipmentStats(),
            'customer_count' => $this->getTotalCustomers(),
            'membership_count' => $this->getActiveMemberships(),
            'trainer_count' => $this->getTotalTrainers(),
            'class_count' => $this->getTotalClasses(),
            'booking_count' => $this->getTotalBookings()
        ];

        $revenue = [
            'monthly' => $this->getMonthlyRevenue(),
            'total' => $this->getTotalRevenue()
        ];

        // Recent Members
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

        // Recent Transactions
        $recentTransactions = Transaction::with('user')
            ->latest()
            ->take(5)
            ->get();

        // === TAMBAHAN: Recent Trainers (Mengatasi Error Undefined Variable) ===
        $recentTrainers = User::where('role', 'trainer')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('user', 'stats', 'revenue', 'recentMemberships', 'recentTransactions', 'recentTrainers'));
    }

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

        if ($request->has('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $classes = $query->paginate(10);
        return view('admin.classes', compact('user', 'classes'));
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
