<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Membership;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        DB::beginTransaction();
        try {
            // Create user with customer role by default
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'address' => $request->address,
                'role' => 'customer', // Set default role as customer
            ]);

            \Log::info('User created successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->role
            ]);

            // Automatically create inactive membership for new customer
            $membership = Membership::create([
                'user_id' => $user->id,
                'start_time' => null,
                'end_time' => null,
                'total_amount' => 200000, // Default membership price
                'status' => 'inactive', // Use correct status from migration
                'payment_status' => 'pending', // Use correct status from migration
            ]);

            \Log::info('Membership created successfully', [
                'membership_id' => $membership->id,
                'user_id' => $user->id,
                'status' => $membership->status,
                'payment_status' => $membership->payment_status
            ]);

            event(new Registered($user));

            Auth::login($user);

            DB::commit();
            
            \Log::info('New customer registered with inactive membership', [
                'user_id' => $user->id,
                'email' => $user->email,
                'membership_id' => $membership->id
            ]);

            return redirect(route('dashboard', absolute: false))
                         ->with('success', 'Akun berhasil dibuat! Silakan lakukan pembayaran untuk mengaktifkan membership.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to register user: ' . $e->getMessage());
            
            return redirect()->back()
                           ->withInput()
                           ->withErrors(['registration' => 'Registration failed. Please try again.']);
        }
    }
}
