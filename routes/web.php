<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectManagerController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\SalaryDecisionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Grup Rute yang Harus Login Terlebih Dahulu
Route::middleware(['auth', 'verified'])->group(function () {

    // ========== LEADERBOARD (Accessible by all authenticated users) ==========
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');
    Route::get('/leaderboard/developer/{developer}', [LeaderboardController::class, 'show'])->name('leaderboard.show');
    Route::get('/leaderboard/statistics', [LeaderboardController::class, 'statistics'])->name('leaderboard.statistics');
    Route::get('/api/leaderboard', [LeaderboardController::class, 'api'])->name('api.leaderboard');

    // ========== ADMIN ROUTES ==========
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard',                      [DashboardController::class, 'admin'])->name('admin.dashboard');
        Route::get('/admin/statistics',                     [StatisticsController::class, 'adminStats'])->name('admin.statistics');
        Route::get('/admin/rewards',                        [RewardController::class, 'index'])->name('admin.rewards.index');
        Route::get('/admin/rewards/statistics',             [RewardController::class, 'statistics'])->name('admin.rewards.statistics');
        Route::get('/admin/rewards/{reward}',               [RewardController::class, 'show'])->name('admin.rewards.show');
        
        // PERBAIKAN: Mengubah parameter {reward} menjadi {recommendation} agar sesuai dengan Route Model Binding di SalaryDecisionController
        Route::post('/admin/rewards/{recommendation}/approve',   [SalaryDecisionController::class, 'approve'])->name('admin.rewards.approve');
        Route::post('/admin/rewards/{recommendation}/reject',    [SalaryDecisionController::class, 'reject'])->name('admin.rewards.reject');
    });

    // ========== MANAGER ROUTES ==========
    Route::middleware(['role:manager'])->group(function () {
        Route::get('/manager/dashboard',                    [ProjectManagerController::class, 'index'])->name('manager.dashboard');
        Route::post('/manager/project/store',               [ProjectManagerController::class, 'storeProject'])->name('manager.project.store');
        Route::get('/manager/project/{project}/edit',       [ProjectManagerController::class, 'editProject'])->name('manager.project.edit');
        Route::put('/manager/project/{project}',            [ProjectManagerController::class, 'updateProject'])->name('manager.project.update');
        Route::delete('/manager/project/{project}',         [ProjectManagerController::class, 'destroyProject'])->name('manager.project.destroy');
        Route::get('/manager/task/{task}/edit',             [ProjectManagerController::class, 'editTask'])->name('manager.task.edit');
        Route::put('/manager/task/{task}',                  [ProjectManagerController::class, 'updateTask'])->name('manager.task.update');
        Route::post('/manager/task/{id}/approve',           [ProjectManagerController::class, 'approveTask'])->name('manager.task.approve');
        Route::get('/manager/statistics',                   [StatisticsController::class, 'managerStats'])->name('manager.statistics');
        Route::get('/manager/tasks',                        [TaskController::class, 'index'])->name('manager.tasks.index');
        Route::get('/manager/rewards',                      [RewardController::class, 'index'])->name('manager.rewards.index');
        Route::get('/manager/rewards/create',               [RewardController::class, 'create'])->name('manager.rewards.create');
        Route::post('/manager/rewards',                     [RewardController::class, 'store'])->name('manager.rewards.store');
        Route::get('/manager/rewards/statistics',           [RewardController::class, 'statistics'])->name('manager.rewards.statistics');
        Route::get('/manager/rewards/{reward}',             [RewardController::class, 'show'])->name('manager.rewards.show');
    });

    // ========== DEVELOPER ROUTES ==========
    Route::middleware(['role:developer'])->group(function () {
        Route::get('/developer/dashboard',                  [DashboardController::class, 'developer'])->name('developer.dashboard');
        Route::get('/developer/statistics',                 [StatisticsController::class, 'developerStats'])->name('developer.statistics');
        Route::get('/tasks',                                [TaskController::class, 'index'])->name('tasks.index');
        Route::get('/tasks/{task}',                         [TaskController::class, 'show'])->name('tasks.show');
        Route::post('/tasks/{task}/start',                  [TaskController::class, 'start'])->name('tasks.start');
        Route::post('/tasks/{task}/complete',               [TaskController::class, 'complete'])->name('tasks.complete');
        Route::post('/tasks/{task}/status',                 [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
        Route::get('/developer/rewards',                    [RewardController::class, 'index'])->name('developer.rewards.index');
        Route::get('/developer/rewards/{reward}',           [RewardController::class, 'show'])->name('developer.rewards.show');
    });

    // Generic dashboard — redirect sesuai role
    Route::get('/dashboard', function () {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        if (!$user) return redirect('/');

        $role = $user->role ?? null;
        if ($role === 'admin')     return redirect()->route('admin.dashboard');
        if ($role === 'manager')   return redirect()->route('manager.dashboard');
        if ($role === 'developer') return redirect()->route('developer.dashboard');

        return redirect('/');
    })->name('dashboard');

});

// Rute bawaan profil dari Breeze
Route::middleware('auth')->group(function () {
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';