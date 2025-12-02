<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class CustomerTrainerController extends Controller
{
    public function show($id)
    {
        $trainer = User::where('role', 'trainer')->findOrFail($id);
        $user = Auth::user();
        
        // Get classes handled by this trainer
        $classes = $trainer->classes ?? collect();
        
        // Get recent bookings - gunakan query langsung
        $recentActivities = Booking::where('trainer_id', $trainer->id)
            ->with('membership.user')
            ->latest()
            ->take(5)
            ->get();
        
        return view('customer.trainer-detail', compact('trainer', 'user', 'classes', 'recentActivities'));
    }
}
