<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\CustomerProgressController;
// === TAMBAHKAN CONTROLLER BOOKING ===
use App\Http\Controllers\Customer\CustomerBookingController;
// ===================================
use Illuminate\Support\Facades\Route;
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
    Route::get('/dashboard/class', [AdminDashboardController::class, 'classes'])->name('classes_management');
    Route::get('/dashboard/equipment', [AdminDashboardController::class, 'equipments'])->name('equipments_management');
    Route::get('/dashboard/transaction', [AdminDashboardController::class, 'transactions'])->name('transactions_management');
});


// CUSTOMER ROUTES
Route::middleware(['auth', 'verified', 'check.role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');

    // EQUIPMENT ROUTE
    Route::get('/equipment', [CustomerDashboardController::class, 'equipments'])->name('equipments.index');

    // TRAINER ROUTE
    Route::get('/trainers', [CustomerDashboardController::class, 'trainers'])->name('trainers.index');

    // Progress Tracking Routes
    Route::resource('progress', CustomerProgressController::class);

    // === PASTIKAN BOOKING RESOURCE ADA ===
    // Ini akan otomatis membuat rute: index, create, store, show, edit, update, destroy
    Route::resource('bookings', CustomerBookingController::class);
    // =====================================

});


// TRAINER ROUTES
Route::middleware(['auth', 'verified', 'check.role:trainer'])->prefix('trainer')->name('trainer.')->group(function () {
    Route::get('/dashboard', function () {
        return view('trainer.dashboard', ['user' => auth()->user()]);
    })->name('dashboard');
});


// PROFILE ROUTES (Shared)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';