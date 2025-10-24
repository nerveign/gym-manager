<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\UserProgress;
use App\Models\Equipment; // Import Equipment model
use Illuminate\Http\Request;

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

    /**
     * Menampilkan daftar Equipment (Peralatan) untuk Customer
     */
    public function equipments(Request $request) 
    {
        $user = auth()->user();
        
        $query = Equipment::query();
        
        // Fitur search
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('brand', 'like', "%{$searchTerm}%");
        }
        
        $equipments = $query->latest()->paginate(10);

        return view('customer.equipments', compact('user', 'equipments'));
    }
}