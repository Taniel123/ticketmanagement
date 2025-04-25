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


// Authentication routes || handles login and register form submission
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);


// Password Reset Routes    Request reset link
Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
    ->middleware('guest')
    ->name('password.request');

Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->middleware('guest')
    ->name('password.email');

    //Reset password via token
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
Route::middleware(['auth', 'verified', 'approved'])->group(function () {
    // Common routes for all authenticated users
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware(['auth', 'verified'])->group(function () {
        // User dashboard route
        Route::get('/dashboard/user', [AuthController::class, 'showUserDashboard'])
            ->name('user.dashboard')
            ->middleware('role:user');

        // User ticket routes (working)
        Route::prefix('user/tickets')->middleware('role:user')->group(function () {
            Route::patch('/{ticket}', [TicketController::class, 'update'])->name('user.tickets.update');
            Route::get('/', [TicketController::class, 'index'])->name('user.tickets.index');
            Route::get('/create', [TicketController::class, 'create'])->name('user.tickets.create');
            Route::post('/', [TicketController::class, 'store'])->name('user.tickets.store');
            Route::get('/{ticket}', [TicketController::class, 'show'])->name('user.tickets.show');
            Route::get('/{ticket}/edit', [TicketController::class, 'edit'])->name('user.tickets.edit');
        });
    });

    // Admin routes (needs fixing)
    Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
        // Admin dashboard route
        Route::get('/dashboard', [AuthController::class, 'showAdminDashboard'])->name('admin.dashboard');

        // Admin user management routes
        Route::prefix('users')->group(function () {
            Route::get('/create', [AuthController::class, 'showCreateUser'])->name('admin.users.create');
            Route::post('/', [AuthController::class, 'storeUser'])->name('admin.users.store');
            Route::get('/{user}', [AuthController::class, 'showUser'])->name('admin.users.show'); // Add this line
            Route::get('/{user}/edit', [AuthController::class, 'editUser'])->name('admin.users.edit');
            Route::patch('/users/{user}', [AuthController::class, 'updateUser'])->name('admin.users.update');
            Route::patch('/{id}/approve', [AuthController::class, 'approveUser'])->name('admin.users.approve');
            Route::patch('/{id}/role', [AuthController::class, 'changeUserRole'])->name('admin.users.change-role');
        });

        // Admin ticket routes
        Route::prefix('tickets')->group(function () {
            Route::get('/create', [TicketController::class, 'adminCreate'])->name('admin.tickets.create');
            Route::post('/', [TicketController::class, 'adminStore'])->name('admin.tickets.store');
            Route::patch('/{ticket}', [TicketController::class, 'adminUpdate'])->name('admin.tickets.update');
            Route::get('/', [TicketController::class, 'adminIndex'])->name('admin.tickets.index');
            Route::get('/{ticket}', [TicketController::class, 'adminShow'])->name('admin.tickets.show');
            Route::get('/{ticket}/edit', [TicketController::class, 'adminEdit'])->name('admin.tickets.edit');
            Route::patch('/{ticket}/status', [TicketController::class, 'updateStatus'])->name('admin.tickets.update-status');
        });
    });

    Route::put('/users/{id}/unarchive', [AuthController::class, 'unarchiveUser'])->name('users.unarchive');

    // Admin and Support ticket archive routes
    // Route::group(['middleware' => ['auth', 'role:admin,support']], function () {
    //     Route::post('/tickets/{id}/archive', [TicketController::class, 'archiveTicket'])->name('tickets.archive');
    //     Route::post('/tickets/{id}/unarchive', [TicketController::class, 'unarchiveTicket'])->name('tickets.unarchive');
    // });
});

// Support routes should be in their own group
Route::prefix('support')->middleware(['auth', 'verified', 'approved', 'role:support'])->group(function () {
    // Support dashboard
    Route::get('/dashboard', [AuthController::class, 'showSupportDashboard'])->name('support.dashboard');
    
    // Support ticket routes
    Route::prefix('tickets')->group(function () {
        Route::patch('/{ticket}', [TicketController::class, 'supportUpdate'])->name('support.tickets.update');
        Route::get('/{ticket}', [TicketController::class, 'supportShow'])->name('support.tickets.show');
        Route::get('/{ticket}/edit', [TicketController::class, 'supportEdit'])->name('support.tickets.edit');
    });
});