<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\CustomerProgressController;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/dashboard', function () {
    $user = auth()->user();
    
    if ($user->role === 'admin') return redirect('/admin/dashboard');
    if ($user->role === 'trainer') return redirect('/trainer/dashboard');
    return redirect('/customer/dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Customer Routes
Route::middleware(['auth', 'verified', 'check.role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    
    // User Progress Routes
    Route::get('/progress', [CustomerProgressController::class, 'index'])->name('progress.index');
    Route::get('/progress/create', [CustomerProgressController::class, 'create'])->name('progress.create');
    Route::post('/progress', [CustomerProgressController::class, 'store'])->name('progress.store');
    Route::get('/progress/{progress}/edit', [CustomerProgressController::class, 'edit'])->name('progress.edit');
    Route::put('/progress/{progress}', [CustomerProgressController::class, 'update'])->name('progress.update');
    Route::delete('/progress/{progress}', [CustomerProgressController::class, 'destroy'])->name('progress.destroy');
});
// routes/web.php
Route::middleware(['auth', 'verified', 'check.role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/users', [DashboardController::class, 'users'])->name('users_management');
    Route::get('/dashboard/trainer', [DashboardController::class, 'trainers'])->name('trainers_management');
    Route::get('/dashboard/booking', [DashboardController::class, 'bookings'])->name('bookings_management');
    Route::get('/dashboard/class', [DashboardController::class, 'classes'])->name('classes_management');
    Route::get('/dashboard/equipment', [DashboardController::class, 'equipments'])->name('equipments_management');
    Route::get('/dashboard/transaction', [DashboardController::class, 'transactions'])->name('transactions_management');
});
// Trainer Routes  
Route::middleware(['auth', 'verified', 'check.role:trainer'])->prefix('trainer')->name('trainer.')->group(function () {
    Route::get('/dashboard', function () {
        return view('trainer.dashboard', ['user' => auth()->user()]);
    })->name('dashboard');
});

require __DIR__.'/auth.php';