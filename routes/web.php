<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return view('welcome');
});

// Grup Rute yang Harus Login Terlebih Dahulu
Route::middleware(['auth', 'verified'])->group(function () {
    
    // 1. Rute Khusus Admin
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
    });

    // 2. Rute Khusus Manager
    Route::middleware(['role:manager'])->group(function () {
        Route::get('/manager/dashboard', [DashboardController::class, 'manager'])->name('manager.dashboard');
    });

    // 3. Rute Khusus Developer
    Route::middleware(['role:developer'])->group(function () {
        Route::get('/developer/dashboard', [DashboardController::class, 'developer'])->name('developer.dashboard');
        
        // Task Routes
        Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
        Route::post('/tasks/{task}/start', [TaskController::class, 'start'])->name('tasks.start');
        Route::post('/tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');
        Route::post('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
    });

    // Generic dashboard route — redirect sesuai role ke dashboard masing-masing
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if (!$user) return redirect('/');

        if (method_exists($user, 'hasRole')) {
            if ($user->hasRole('admin')) return redirect()->route('admin.dashboard');
            if ($user->hasRole('manager')) return redirect()->route('manager.dashboard');
            if ($user->hasRole('developer')) return redirect()->route('developer.dashboard');
        } else {
            $role = $user->role ?? null;
            if ($role === 'admin') return redirect()->route('admin.dashboard');
            if ($role === 'manager') return redirect()->route('manager.dashboard');
            if ($role === 'developer') return redirect()->route('developer.dashboard');
        }

        return redirect('/');
    })->name('dashboard');

});

// Rute bawaan profil dari Breeze (biarkan tetap ada)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';