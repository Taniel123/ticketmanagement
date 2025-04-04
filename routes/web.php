<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\CheckRole;

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

// Email verification routes
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])
        ->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->name('verification.verify');
    Route::post('/email/resend', [EmailVerificationController::class, 'resend'])
        ->name('verification.resend');
});

// Protected routes requiring authentication and email verification
Route::middleware(['auth', 'verified'])->group(function () {
    // Common routes for all authenticated users
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

});

Route::get('/dashboard/user', [AuthController::class, 'showUserDashboard'])->name('user.dashboard');
Route::get('/dashboard/admin', [AuthController::class, 'showAdminDashboard'])->name('admin.dashboard');
Route::post('/admin/approve/{id}', [AuthController::class, 'approveUser'])->name('admin.approve');
Route::delete('/admin/delete/{id}', [AuthController::class, 'deleteUser'])->name('admin.delete');
Route::post('/admin/change-role/{id}', [AuthController::class, 'changeUserRole'])->name('admin.changeRole');
Route::get('/dashboard/support', [AuthController::class, 'showSupportDashboard'])->name('support.dashboard');
Route::patch('/tickets/{id}/status', [TicketController::class, 'updateStatus'])->name('tickets.updateStatus');


