<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectOwnerController;
use App\Http\Controllers\SalaryDecisionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Grup Rute yang Harus Login Terlebih Dahulu
Route::middleware(['auth', 'verified'])->group(function () {
    
    // 1. Rute Khusus Admin
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', [ProjectOwnerController::class, 'index'])->name('admin.dashboard');
    });

    // 2. Rute Khusus Manager
    Route::middleware(['role:manager'])->group(function () {
        Route::get('/manager/dashboard', [DashboardController::class, 'manager'])->name('manager.dashboard');
    });

    // 3. Rute Khusus Developer
    Route::middleware(['role:developer'])->group(function () {
        Route::get('/developer/dashboard', [DashboardController::class, 'developer'])->name('developer.dashboard');
    });

});

// Rute bawaan profil dari Breeze (biarkan tetap ada)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';