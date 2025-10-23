<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\GymClass;
use App\Models\Membership;
use App\Models\User;
use App\Models\Equipment;

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

    public function users()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access for admin only.');
        }

        $user = auth()->user();
        $customers = User::with(['membership'])
            ->where('role', 'customer')
            ->latest()
            ->paginate(10);

        return view('admin.users', compact('user', 'customers'));
    }

    public function trainers() {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access for admin only.');
        }

        $user = auth()->user();

        $trainers = User::where('role', 'trainer')
            ->latest()
            ->paginate(10);

        return view('admin.trainers', compact('user', 'trainers'));
    }
    public function bookings() {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access for admin only.');
        }

        $user = auth()->user();
        return view('admin.bookings', compact('user'));
    }

     public function classes() {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access for admin only.');
        }

        $user = auth()->user();
        return view('admin.classes', compact('user'));
    }
      public function equipments() {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access for admin only.');
        }

        $user = auth()->user();
        return view('admin.equipments', compact('user'));
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