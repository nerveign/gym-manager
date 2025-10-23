<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\GymClass;
use App\Models\Membership;
use App\Models\User;
use App\Models\Equipment;
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

        return view('admin.dashboard', compact('stats', 'recentMemberships', 'user'));
    }

    public function users(Request $request) 
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access for admin only.');
        }

        $user = auth()->user();
        
        // Query dengan search
        $query = User::with(['membership'])
            ->where('role', 'customer');

        // Fitur search
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%")
                  ->orWhere('phone', 'like', "%{$searchTerm}%");
            });
        }

        $customers = $query->latest()->paginate(10);

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
            $query->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
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
}