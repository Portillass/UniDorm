<?php

use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WaitingApprovalController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Waiting Approval Route
    Route::get('/waiting-approval', [WaitingApprovalController::class, 'index'])
        ->name('waiting-approval')
        ->middleware('role:admin');

    // Tenant Management Routes
    Route::middleware(['role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/tenants', [TenantController::class, 'index'])->name('tenants.index');
        Route::post('/tenants/{user}/approve', [TenantController::class, 'approve'])->name('tenants.approve');
        Route::put('/tenants/{user}/deactivate', [TenantController::class, 'deactivate'])->name('tenants.deactivate');
    });

    // Admin Dashboard Routes
    Route::middleware(['role:admin', 'admin.approved'])->prefix('admin-dashboard')->name('admin-dashboard.')->group(function () {
        // Add your admin dashboard routes here
    });

    // Admin approval routes
    Route::middleware(['auth', 'role:super_admin'])->group(function () {
        Route::post('/admin/approve/{user}', [App\Http\Controllers\Admin\AdminController::class, 'approve'])->name('admin.approve');
    });
});

require __DIR__.'/auth.php';
