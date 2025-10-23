<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Membership;
use App\Models\GymClass;
use App\Models\Booking;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $data = [
            'user' => $user,
            'activeMembership' => $user->activeMembership,
            'enrolledClasses' => $user->enrolledClasses()->count(),
            'upcomingBookings' => $user->membership ? $user->membership->bookings()->count() : 0,
            'recentProgress' => $user->userProgress()->latest()->take(3)->get(),
        ];

        return view('customer.dashboard', $data);
    }
}