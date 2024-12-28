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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [MessageController::class, 'chat'])->name('messages.chat');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');

    // Archive routes should come before the {message} route to avoid conflicts
    Route::get('/mail/archive', [MessageController::class, 'archive'])->name('mail.archive');
    Route::post('/mail/bulk-archive', [MessageController::class, 'bulkArchive'])->name('mail.bulk-archive');
    Route::post('/mail/bulk-unarchive', [MessageController::class, 'bulkUnarchive'])->name('mail.bulk-unarchive');

    // Other mail routes
    Route::get('/mail/inbox', [MessageController::class, 'inbox'])->name('mail.inbox');
    Route::get('/mail/compose', [MessageController::class, 'compose'])->name('mail.compose');
    Route::get('/mail/sent', [MessageController::class, 'sent'])->name('mail.sent');
    Route::get('/mail/archive', [MessageController::class, 'archive'])->name('mail.archive');
    
    // Add the new starred route here
    Route::get('/mail/starred', [MessageController::class, 'starred'])->name('mail.starred');
    
    // Other mail routes
    Route::post('/mail/send', [MessageController::class, 'send'])->name('mail.send');
    Route::get('/mail/{message}', [MessageController::class, 'show'])->name('mail.show');
    Route::post('/mail/{message}/star', [MessageController::class, 'toggleStar'])->name('mail.toggle-star');
    Route::post('/mail/{message}/toggle-archive', [MessageController::class, 'toggleArchive'])->name('mail.toggle-archive');
    Route::post('/mail/{message}/toggle-read', [MessageController::class, 'toggleRead'])->name('mail.toggle-read');
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
            Route::post('/maintenance/toggle', [SuperadminController::class, 'maintenanceToggle'])
                ->name('maintenance.toggle');
            Route::get('/maintenance', [SuperadminController::class, 'maintenanceView'])
                ->name('maintenance');
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

Route::get('/mail/attachment/{attachment}/download', [MessageController::class, 'download'])
    ->name('mail.download');

require __DIR__.'/auth.php';