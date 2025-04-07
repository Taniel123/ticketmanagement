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
// Authentication routes - remove guest middleware
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

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

    // User dashboard route - accessible by all authenticated users
    Route::get('/dashboard/user', [AuthController::class, 'showUserDashboard'])
        ->name('user.dashboard');

    // User ticket routes
    Route::resource('tickets', TicketController::class)
        ->except(['edit', 'update', 'destroy']);

    // Support routes with prefix
    Route::group(['prefix' => 'support', 'middleware' => ['auth', 'verified', 'role:support,admin']], function () {
        Route::get('/dashboard', [AuthController::class, 'showSupportDashboard'])
            ->name('support.dashboard');
        Route::patch('/tickets/{ticket}/status', [TicketController::class, 'updateStatus'])
            ->name('tickets.update-status');
    });

    // Admin routes with prefix
    Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'verified', 'role:admin']], function () {
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