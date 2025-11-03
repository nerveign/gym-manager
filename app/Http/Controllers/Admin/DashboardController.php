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

        // Tambahkan revenue calculation menggunakan data yang sama dengan transactions method
        $revenue = [
            'monthly' => $this->getMonthlyRevenue(),
            'total' => $this->getTotalRevenue()
        ];

        // Recent Members
        $recentMemberships = Membership::with('user')
            ->latest()
            ->take(5)
            ->get()
            ->map(function($membership) {
                return (object)[
                    'id' => $membership->id,
                    'user_name' => $membership->user->name,
                    'user_email' => $membership->user->email,
                    'user_image' => $membership->user->image_url,
                    'start_time' => $membership->start_time,
                    'end_time' => $membership->end_time,
                    'status' => $membership->status,
                    'payment_status' => $membership->payment_status,
                    'total_amount' => $membership->total_amount,
                    'created_at' => $membership->created_at,
                ];
            });

        return view('admin.dashboard', compact('stats', 'recentMemberships', 'user', 'revenue'));
    }

    public function users(Request $request) 
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access for admin only.');
        }

        $user = auth()->user();
        
        // Query dengan search - ambil customer beserta membership (jika ada)
        $query = User::leftJoin('memberships', 'users.id', '=', 'memberships.user_id')
            ->select('users.*', 'memberships.status as membership_status', 'memberships.created_at as membership_created_at')
            ->where('users.role', 'customer');

        // Fitur search
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('users.name', 'like', "%{$searchTerm}%")
                  ->orWhere('users.email', 'like', "%{$searchTerm}%")
                  ->orWhere('users.phone', 'like', "%{$searchTerm}%");
            });
        }

        $customers = $query->latest('users.created_at')->paginate(10);

        return view('admin.users', compact('user', 'customers'));
    }

    public function trainers(Request $request) 
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access for admin only.');
        }

        $user = auth()->user();

        $query = User::where('role', 'trainer');


        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%")
                  ->orWhere('phone', 'like', "%{$searchTerm}%");
            });
        }

        $trainers = $query->latest()->paginate(10);

        return view('admin.trainers', compact('user', 'trainers'));
    }

    public function bookings(Request $request) 
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access for admin only.');
        }

        $user = auth()->user();
        
        $query = Booking::query();
        
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->whereHas('user', function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }
        
        $bookings = $query->latest()->paginate(10);

        return view('admin.bookings', compact('user', 'bookings'));
    }

    public function classes(Request $request) 
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access for admin only.');
        }

        $user = auth()->user();
        
        // Query dengan search
        $query = GymClass::query();
        
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
        }
        
        $classes = $query->latest()->paginate(10);

        return view('admin.classes', compact('user', 'classes'));
    }

    public function equipments(Request $request) 
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access for admin only.');
        }

        $user = auth()->user();
        
        $query = Equipment::query();
        
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where('equipment_name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('brand', 'like', "%{$searchTerm}%");
        }
        
        $equipments = $query->latest()->paginate(10);

        return view('admin.equipments', compact('user', 'equipments'));
    }

    public function transactions(Request $request) 
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access for admin only.');
        }

        $user = auth()->user();
        
        $query = Membership::with('user');
        
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->whereHas('user', function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }
        
        $transactions = $query->latest()->paginate(10);

        return view('admin.transactions', compact('user', 'transactions'));
    }

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
        return User::where('role', 'trainer' )->count();
    }

    private function getActiveMemberships()
    {
        return Membership::where('status', 'active')->count();
    }

    private function getTotalBookings(){
        return Booking::count();
    }

    private function getTotalClasses()
    {
        return GymClass::count();
    }

    private function getMonthlyRevenue()
    {
        return Transaction::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');
    }

    private function getTotalRevenue()
    {
        return Transaction::where('status', 'completed')->sum('amount');
    }
}