<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class CustomerBookingController extends Controller
{
    /**
     * Menampilkan daftar booking milik customer.
     * Terhubung ke rute: customer.bookings.index (GET)
     */
    // UBAH DISINI: Tambahkan parameter Request $request
    public function index(Request $request) 
    {
        $user = Auth::user();

        // Ambil ID semua membership yang dimiliki user
        $membershipIds = $user->membership()->pluck('id');

        // 1. Inisialisasi Query Dasar (Belum dieksekusi/di-get)
        $query = Booking::whereIn('membership_id', $membershipIds)
                        ->with('trainer'); // Eager load trainer data

        // 2. TAMBAHKAN LOGIC SEARCH DISINI
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            
            // Filter booking berdasarkan nama trainer (relasi 'trainer')
            $query->whereHas('trainer', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // 3. Eksekusi Query (Latest & Paginate)
        // Variabel $bookings sekarang menampung hasil yang sudah difilter (jika ada search)
        $bookings = $query->latest()->paginate(10);

        return view('customer.bookings.index', compact('user', 'bookings'));
    }

    /**
     * Menampilkan form untuk membuat booking baru.
     * (TIDAK DIUBAH)
     */
    public function create()
    {
        $user = Auth::user();
        $activeMembership = $user->activeMembership;

        if (!$activeMembership) {
            return redirect()->route('customer.bookings.index')
                           ->with('error', 'You must have an active membership to make a booking.');
        }

        $trainers = User::where('role', 'trainer')->orderBy('name')->get(['id', 'name']);

        return view('customer.bookings.create', compact('user', 'trainers', 'activeMembership'));
    }

    /**
     * Menyimpan booking baru.
     * (TIDAK DIUBAH)
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $activeMembership = $user->activeMembership;

        if (!$activeMembership) {
            return redirect()->route('customer.bookings.index')
                           ->with('error', 'You must have an active membership to make a booking.');
        }

        try {
            $validatedData = $request->validate([
                'trainer_id' => 'required|exists:users,id',
                'date' => [
                    'required',
                    'date',
                    'after_or_equal:today',
                    function ($attribute, $value, $fail) use ($activeMembership) {
                        if (\Carbon\Carbon::parse($value)->gt($activeMembership->end_time)) {
                            $fail('The booking date cannot be after your membership expires (' . $activeMembership->end_time->format('d M Y') . ').');
                        }
                    },
                    function ($attribute, $value, $fail) use ($activeMembership) {
                         if (\Carbon\Carbon::parse($value)->lt($activeMembership->start_time)) {
                             $fail('The booking date cannot be before your membership starts (' . $activeMembership->start_time->format('d M Y') . ').');
                         }
                    }
                ],
                'time' => [
                    'required',
                    'date_format:H:i',
                    Rule::unique('bookings')->where(function ($query) use ($request) {
                        return $query->where('trainer_id', $request->trainer_id)
                                     ->where('date', $request->date);
                    }),
                ],
                'duration' => 'required|integer|min:30|max:120',
            ], [
                'time.unique' => 'The selected trainer is already booked at this date and time. Please choose a different time slot.'
            ]);

        } catch (ValidationException $e) {
             return redirect()->route('customer.bookings.create')
                             ->withErrors($e->validator)
                             ->withInput();
        }

        Booking::create([
            'trainer_id' => $validatedData['trainer_id'],
            'membership_id' => $activeMembership->id,
            'duration' => $validatedData['duration'],
            'date' => $validatedData['date'],
            'time' => $validatedData['time'],
        ]);

        return redirect()->route('customer.bookings.index')
                       ->with('success', 'Booking created successfully!');
    }

    /**
     * Menghapus booking.
     * (TIDAK DIUBAH)
     */
    public function destroy(Booking $booking)
    {
        $user = Auth::user();

        if ($booking->membership->user_id !== $user->id) {
            return redirect()->route('customer.bookings.index')
                           ->with('error', 'Unauthorized action.');
        }

        $booking->delete();

        return redirect()->route('customer.bookings.index')
                       ->with('success', 'Booking deleted successfully!');
    }
}