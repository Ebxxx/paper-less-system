<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\SuperadminController;
use App\Http\Middleware\CheckMaintenanceMode;

Route::get('/maintenance', function () {
    return view('maintenance');
})->name('maintenance');

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('user.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [MessageController::class, 'chat'])->name('messages.chat');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
});

Route::middleware('guest')->group(function () {
    Route::get('admin/login', [AdminAuthController::class, 'showLoginForm'])
        ->name('admin.login');
    Route::post('admin/login', [AdminAuthController::class, 'login']);
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::post('/logout', [AdminAuthController::class, 'logout']) ->name('admin.logout');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.AdminDashboard');
    
    // User management routes
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users.index');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');

    Route::get('/admin/mail/messages', function () {
        return view('admin.mail.message'); })->name('admin.mail.message');
});

    // Superadmin Routes
    Route::prefix('superadmin')->name('superadmin.')->group(function () {
        // Authentication Routes
        Route::get('/login', [SuperadminController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [SuperadminController::class, 'login'])->name('login.submit');
        Route::post('/logout', [SuperadminController::class, 'logout'])->name('logout');

        // Protected Superadmin Routes
        Route::middleware(['auth:superadmin'])->group(function () {
            Route::get('/dashboard', [SuperadminController::class, 'dashboard'])->name('dashboard');
            Route::get('/create-admin', [SuperadminController::class, 'createAdmin'])->name('create-admin');
            Route::post('/store-admin', [SuperadminController::class, 'storeAdmin'])->name('store-admin');

            Route::get('/superadmin/user-statistics', [SuperadminController::class, 'getUserStatistics'])->middleware('auth:superadmin');
            Route::post('/maintenance/toggle', [SuperadminController::class, 'toggleMaintenance'])
                ->name('maintenance.toggle');
            Route::get('/superadmin/maintenance/switch', [SuperAdminController::class, 'maintenanceSwitch'])
                ->name('superadmin.maintenance.switch');
        });
});

Route::middleware([CheckMaintenanceMode::class])->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });

    Route::get('/dashboard', function () {
        return view('user.dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');
});

require __DIR__.'/auth.php';