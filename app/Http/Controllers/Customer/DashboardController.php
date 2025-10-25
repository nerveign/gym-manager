<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\UserProgress;
use App\Models\Equipment; // Import Equipment model
use App\Models\User; // Import User model for trainers query
use App\Models\Booking; // Import Booking model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import Auth facade

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $activeMembership = $user->activeMembership;

        // Ambil data detail booking yang akan datang (misal, 5 terdekat)
        $upcomingBookingsData = collect(); // Default collection kosong
        if ($activeMembership) { // Hanya ambil jika membership aktif
             $upcomingBookingsData = Booking::where('membership_id', $activeMembership->id)
                                    ->where('date', '>=', now()->format('Y-m-d')) // Mulai hari ini
                                    ->with('trainer') // Eager load data trainer
                                    ->orderBy('date', 'asc')
                                    ->orderBy('time', 'asc')
                                    ->take(5) // Ambil 5 booking terdekat
                                    ->get();
        }

        $data = [
            'user' => $user,
            'activeMembership' => $activeMembership,
            'enrolledClasses' => $user->enrolledClasses()->count(),
            // Hitung jumlah upcoming bookings dari data yang sudah diambil
            'upcomingBookingsCount' => $upcomingBookingsData->count(),
            'recentProgress' => $user->userProgress()->latest()->take(3)->get(),
            // Kirim data detail booking ke view
            'upcomingBookingsData' => $upcomingBookingsData,
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

        // Fitur search - PERBAIKI NAMA KOLOM
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            // Gunakan 'equipment_name' sesuai nama kolom di tabel
            $query->where('equipment_name', 'like', "%{$searchTerm}%")
                  ->orWhere('brand', 'like', "%{$searchTerm}%");
        }

        $equipments = $query->latest()->paginate(10);

        return view('customer.equipments', compact('user', 'equipments'));
    }

     /**
     * Menampilkan daftar Trainer untuk Customer
     */
    public function trainers(Request $request)
    {
        $user = auth()->user();

        $query = User::where('role', 'trainer');

        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        $trainers = $query->latest()->paginate(10);

        return view('customer.trainers', compact('user', 'trainers'));
    }
}