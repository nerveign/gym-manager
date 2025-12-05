<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\CustomerProgressController;
// === TAMBAHKAN CONTROLLER BOOKING ===
use App\Http\Controllers\Customer\CustomerBookingController;
use App\Http\Controllers\Customer\PaymentController as CustomerPaymentController;
use App\Http\Controllers\Admin\EquipmentController;
// === TAMBAHKAN CONTROLLER EQUIPMENT CUSTOMER ===
use App\Http\Controllers\Customer\CustomerEquipmentController;
// === TAMBAHKAN CONTROLLER TRAINER CUSTOMER ===
use App\Http\Controllers\Customer\CustomerTrainerController;
// ===================================
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Membership;
use App\Models\Transaction;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Middleware untuk merouting berdasarkan role setelah login
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'customer') {
            return redirect()->route('customer.dashboard');
        } elseif ($user->role === 'trainer') {
            return redirect()->route('trainer.dashboard');
        }
        return view('dashboard');
    })->name('dashboard');
});

// ADMIN ROUTES
Route::middleware(['auth', 'verified', 'check.role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/users', [AdminDashboardController::class, 'users'])->name('users_management');
    Route::get('/dashboard/trainer', [AdminDashboardController::class, 'trainers'])->name('trainers_management');
    Route::get('/dashboard/booking', [AdminDashboardController::class, 'bookings'])->name('bookings_management');
    Route::get('/dashboard/classes', [AdminDashboardController::class, 'classes'])->name('classes_management');

    // Detail Pages Routes
    Route::get('/users/{id}', [AdminDashboardController::class, 'userDetail'])->name('user.detail');
    Route::get('/trainers/{id}', [AdminDashboardController::class, 'trainerDetail'])->name('trainer.detail');

    // Equipment Routes
    Route::get('/dashboard/equipment', [AdminDashboardController::class, 'equipments'])->name('equipments_management');
    Route::get('/dashboard/equipment/create', [EquipmentController::class, 'create'])->name('equipments.create');
    Route::post('/dashboard/equipment', [EquipmentController::class, 'store'])->name('equipments.store');
    Route::get('/dashboard/equipment/{id}', [EquipmentController::class, 'show'])->name('equipments.show');
    Route::get('/dashboard/equipment/{id}/edit', [EquipmentController::class, 'edit'])->name('equipments.edit');
    Route::put('/dashboard/equipment/{id}', [EquipmentController::class, 'update'])->name('equipments.update');
    Route::delete('/dashboard/equipment/{id}', [EquipmentController::class, 'destroy'])->name('equipments.destroy');
    
    Route::get('/dashboard/transactions', [AdminDashboardController::class, 'transactions'])->name('transactions_management');
});

// CUSTOMER ROUTES
Route::middleware(['auth', 'verified', 'check.role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');

    // === EQUIPMENT ROUTES ===
    Route::get('/equipment', [CustomerDashboardController::class, 'equipments'])->name('equipments.index');
    Route::get('/equipment/{id}', [CustomerEquipmentController::class, 'show'])->name('equipments.show');
    // ========================

    // === TRAINER ROUTES ===
    Route::get('/trainers', [CustomerDashboardController::class, 'trainers'])->name('trainers.index');
    Route::get('/trainers/{id}', [CustomerTrainerController::class, 'show'])->name('trainers.show');
    // ======================

    // Progress Tracking Routes
    Route::resource('progress', CustomerProgressController::class);

    // === BOOKING ROUTES ===
    // Ini akan otomatis membuat rute: index, create, store, show, edit, update, destroy
    Route::resource('bookings', CustomerBookingController::class);
    // ======================

    // PAYMENT ROUTES untuk aktivasi membership
    Route::prefix('payment')->name('payment.')->group(function () {
        Route::get('/', [CustomerPaymentController::class, 'show'])->name('show');
        Route::post('/create-order', [CustomerPaymentController::class, 'createOrder'])->name('create-order');
        Route::post('/verify', [CustomerPaymentController::class, 'verifyPayment'])->name('verify');
        Route::get('/success', [CustomerPaymentController::class, 'success'])->name('success');
        Route::post('/activate', [CustomerPaymentController::class, 'verifyPayment'])->name('activate');
        Route::get('/simulator', function () {
            return view('customer.payment.manual-simulator');
        })->name('simulator');

        // Payment simulator untuk development testing
        Route::post('/simulate-success/{va_number}', [CustomerPaymentController::class, 'simulatePaymentSuccess'])
            ->name('simulate-success')
            ->where('va_number', '[0-9]+');
    });
});

// TRAINER ROUTES
Route::middleware(['auth', 'verified', 'check.role:trainer'])->prefix('trainer')->name('trainer.')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('trainer.dashboard', ['user' => auth()->user()]);
    })->name('dashboard');

    // Profile Settings
    Route::get('/profile', function () {
        return view('trainer.profile', ['user' => auth()->user()]);
    })->name('profile.edit');
    
    // Profile Update (untuk form submit)
    Route::put('/profile', function (Request $request) {
        $user = auth()->user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048'
        ]);

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->profile_photo_path) {
                Storage::delete('public/' . $user->profile_photo_path);
            }
            
            // Store new photo
            $path = $request->file('photo')->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if (isset($validated['phone'])) {
            $user->phone = $validated['phone'];
        }
        $user->save();

        return redirect()->route('trainer.profile.edit')->with('success', 'Profile updated successfully!');
    })->name('profile.update');
});

// Webhook route (outside auth middleware)
Route::post('/webhook/payment', [CustomerPaymentController::class, 'webhook'])->name('payment.webhook');

// Essential membership checking route  
Route::middleware('auth')->group(function () {
    // Membership force check (for payment page compatibility)
    Route::get('/membership/force-check', [App\Http\Controllers\Customer\MembershipCheckController::class, 'forceCheck'])
        ->name('membership.force-check');
});

// PROFILE ROUTES (Shared)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
