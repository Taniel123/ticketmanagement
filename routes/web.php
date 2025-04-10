<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\CheckRole;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

// Public routes
Route::get('/', function () {
    return view('home');
})->name('home');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Password Reset Routes
Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
    ->middleware('guest')
    ->name('password.request');

Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->middleware('guest')
    ->name('password.email');

Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('reset-password', [ResetPasswordController::class, 'reset'])
    ->middleware('guest')
    ->name('password.update');

   //DAPAT NASA LABAS TO NG MIDDLEWARE PARA GUMANA YUNG VERIFICATION EMAIL
Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->middleware(['signed'])
    ->name('verification.verify');

Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])
        ->name('verification.notice');
    
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])
        ->middleware(['throttle:6,1'])
        ->name('verification.send');
});

// Protected routes requiring authentication and email verification
Route::middleware(['auth', 'verified'])->group(function () {
    // Common routes for all authenticated users
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // User dashboard route - accessible by users only
    Route::get('/dashboard/user', [AuthController::class, 'showUserDashboard'])
        ->name('user.dashboard')
        ->middleware('role:user');

    // User ticket routes
    Route::resource('tickets', TicketController::class)
        ->except(['edit', 'destroy']);

    // Support routes
    Route::prefix('support')->middleware('role:support')->group(function () {
        Route::get('/dashboard', [AuthController::class, 'showSupportDashboard'])
            ->name('support.dashboard');
        Route::patch('/tickets/{ticket}/status', [TicketController::class, 'updateStatus'])
            ->name('tickets.update-status');
    });

    // Admin routes
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [AuthController::class, 'showAdminDashboard'])
            ->name('admin.dashboard');
        Route::get('/', [AdminController::class, 'index'])
            ->name('admin.index');
        Route::post('/approve/{id}', [AuthController::class, 'approveUser'])
            ->name('admin.approve');
        Route::delete('/delete/{id}', [AuthController::class, 'deleteUser'])
            ->name('admin.delete');
        Route::post('/change-role/{id}', [AuthController::class, 'changeUserRole'])
            ->name('admin.changeRole');
    });
});
