<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\UserProgress;
use App\Models\Equipment; // Import Equipment model
use App\Models\User; // Import User model for trainers query
use App\Models\Booking; // Import Booking model
use App\Models\Transaction; // Import Transaction model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import Auth facade

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $activeMembership = $user->activeMembership;
        
        // Don't auto-redirect if user explicitly came from success page or from previous page is success
        $comingFromSuccessPage = $request->has('from_success') || str_contains(url()->previous(), '/payment/success');
        
        if (!$comingFromSuccessPage) {
            // Check for recent completed transactions that might need success page redirect
            $recentCompletedTransaction = Transaction::whereHas('membership', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subMinutes(10)) // Within last 10 minutes
            ->orderBy('created_at', 'desc')
            ->first();
            
            // If there's a recent completed transaction and user hasn't seen success page yet
            if ($recentCompletedTransaction && !session('payment_success_shown_' . $recentCompletedTransaction->id)) {
                // Mark as shown and redirect to success page
                session(['payment_success_shown_' . $recentCompletedTransaction->id => true]);
                
                \Log::info('Redirecting to success page from dashboard', [
                    'user_id' => $user->id,
                    'transaction_id' => $recentCompletedTransaction->id
                ]);
                
                return redirect()->route('customer.payment.success', [
                    'transaction_id' => $recentCompletedTransaction->id,
                    'order_id' => $recentCompletedTransaction->payment_gateway_id
                ]);
            }
        }

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
            'enrolledClasses' => $activeMembership ? $user->enrolledClasses()->count() : 0,
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