<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException; // Tambahkan ini
use Illuminate\Validation\Rule; // Tambahkan Rule untuk validasi unik

class CustomerBookingController extends Controller
{
    /**
     * Menampilkan daftar booking milik customer.
     * Terhubung ke rute: customer.bookings.index (GET)
     */
    public function index()
    {
        $user = Auth::user();

        // Ambil ID semua membership yang dimiliki user (bisa jadi user punya > 1 membership)
        // Pastikan relasi 'membership' adalah hasMany jika user bisa punya banyak membership
        // Jika hanya hasOne, cukup ambil ID dari $user->membership->id
        $membershipIds = $user->membership()->pluck('id'); // Asumsi relasi sudah benar

        // Ambil semua booking yang membership_id-nya ada di $membershipIds
        $bookings = Booking::whereIn('membership_id', $membershipIds)
                           ->with('trainer') // Eager load trainer data
                           ->latest() // Urutkan dari yang terbaru
                           ->paginate(10); // Batasi 10 per halaman

        return view('customer.bookings.index', compact('user', 'bookings'));
    }

    /**
     * Menampilkan form untuk membuat booking baru.
     * Terhubung ke rute: customer.bookings.create (GET)
     */
    public function create()
    {
        $user = Auth::user();
        $activeMembership = $user->activeMembership; // Menggunakan relasi

        // Jika tidak punya membership aktif, jangan biarkan membuat booking
        if (!$activeMembership) {
            return redirect()->route('customer.bookings.index') // Redirect ke index booking
                           ->with('error', 'You must have an active membership to make a booking.');
        }

        $trainers = User::where('role', 'trainer')->orderBy('name')->get(['id', 'name']);

        return view('customer.bookings.create', compact('user', 'trainers', 'activeMembership'));
    }

    /**
     * Menyimpan booking baru yang disubmit dari form ke database.
     * Terhubung ke rute: customer.bookings.store (POST)
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $activeMembership = $user->activeMembership;

        if (!$activeMembership) {
            return redirect()->route('customer.bookings.index') // Redirect ke index booking
                           ->with('error', 'You must have an active membership to make a booking.');
        }

        // Validasi data
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
                    // === TAMBAHKAN VALIDASI UNIK UNTUK DOUBLE BOOKING ===
                    Rule::unique('bookings')->where(function ($query) use ($request) {
                        return $query->where('trainer_id', $request->trainer_id)
                                     ->where('date', $request->date);
                                     // Time check is implied by date_format + unique rule on these 3 columns
                    }),
                    // ===================================================
                ],
                'duration' => 'required|integer|min:30|max:120',
            ], [
                // Pesan error kustom untuk validasi unique time
                'time.unique' => 'The selected trainer is already booked at this date and time. Please choose a different time slot.'
            ]);

        } catch (ValidationException $e) {
            // Jika validasi gagal, kembalikan ke form dengan error dan input lama
             return redirect()->route('customer.bookings.create')
                             ->withErrors($e->validator)
                             ->withInput();
        }


        Booking::create([
            'trainer_id' => $validatedData['trainer_id'],
            'membership_id' => $activeMembership->id,
            'duration' => $validatedData['duration'], // Ambil dari request
            'date' => $validatedData['date'],
            'time' => $validatedData['time'],
        ]);

        return redirect()->route('customer.bookings.index')
                       ->with('success', 'Booking created successfully!');
    }


    /**
     * Menghapus booking.
     * Terhubung ke rute: customer.bookings.destroy (DELETE)
     */
    public function destroy(Booking $booking) // Gunakan Route Model Binding
    {
        $user = Auth::user();

        // Pastikan booking yang akan dihapus benar-benar milik user yang login
        // Cek melalui relasi membership -> user
        if ($booking->membership->user_id !== $user->id) {
             // Jika bukan miliknya, kembalikan error unauthorized
            return redirect()->route('customer.bookings.index')
                           ->with('error', 'Unauthorized action.');
            // atau bisa juga: abort(403, 'Unauthorized action.');
        }

        // Hapus booking dari database
        $booking->delete();

        // Redirect kembali ke halaman daftar booking dengan pesan sukses
        return redirect()->route('customer.bookings.index')
                       ->with('success', 'Booking deleted successfully!');
    }

    // Metode lain seperti show, edit, update tidak kita perlukan untuk sekarang
}