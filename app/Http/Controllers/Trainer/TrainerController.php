<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
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
        
        // Get trainer stats
        $totalBookings = Booking::where('trainer_id', $user->id)->count();
        $todayBookings = Booking::where('trainer_id', $user->id)
            ->whereDate('date', today())
            ->count();
        $upcomingBookings = Booking::where('trainer_id', $user->id)
            ->where('date', '>=', today())
            ->count();

        // Get recent bookings
        $recentBookings = Booking::where('trainer_id', $user->id)
            ->with(['membership.user'])
            ->latest('date')
            ->limit(5)
            ->get();

        return view('trainer.dashboard', compact(
            'user',
            'totalBookings',
            'todayBookings', 
            'upcomingBookings',
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
            $query->whereHas('membership.user', function($q) use ($search) {
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
     * Display trainer profile edit form.
     */
    public function edit(): View
    {
        $user = auth()->user();
        return view('trainer.profile.edit', compact('user'));
    }
}
