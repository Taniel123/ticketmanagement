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
Route::middleware(['auth', 'verified'])->group(function () {
    // Common routes for all authenticated users
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // User dashboard route - accessible by users only
    Route::get('/dashboard/user', [AuthController::class, 'showUserDashboard'])
        ->name('user.dashboard')
        ->middleware('role:user');

    // User ticket routes
    Route::group(['prefix' => 'tickets'], function () {
        Route::get('/', [TicketController::class, 'index'])->name('tickets.index');
        Route::get('/create', [TicketController::class, 'create'])->name('tickets.create');
        Route::post('/', [TicketController::class, 'store'])->name('tickets.store');
        Route::get('/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
        Route::get('/{ticket}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
        Route::patch('/{ticket}', [TicketController::class, 'update'])->name('tickets.update');
        Route::delete('/{ticket}', [TicketController::class, 'destroy'])->name('tickets.destroy');
    });

 // Support routes - without approval check
Route::prefix('support')->middleware(['auth', 'verified', 'role:support'])->group(function () {
    Route::get('/dashboard', [AuthController::class, 'showSupportDashboard'])->name('support.dashboard');
    Route::get('/tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('support.tickets.edit');
    Route::patch('/tickets/{ticket}', [TicketController::class, 'update'])->name('support.tickets.update');
    Route::patch('/tickets/{ticket}/status', [TicketController::class, 'updateStatus'])
    ->name('support.tickets.update-status');
});

    // Admin routes
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [AuthController::class, 'showAdminDashboard'])
            ->name('admin.dashboard');
        Route::get('/', [AdminController::class, 'index'])
            ->name('admin.index');
        Route::post('/approve/{id}', [AuthController::class, 'approveUser'])
            ->name('admin.approve');
        Route::post('/change-role/{id}', [AuthController::class, 'changeUserRole'])
            ->name('admin.changeRole');
        Route::patch('/tickets/{ticket}/status', [TicketController::class, 'updateStatus'])
            ->name('admin.tickets.update-status');
        Route::get('/admin/users/pending', [AdminController::class, 'pendingUsers'])->name('admin.pending-users');
        Route::get('/roles/manage', [AdminController::class, 'manageRoles'])->name('admin.manage-roles');
        Route::get('/tickets/manage', [AdminController::class, 'manageTickets'])->name('admin.manage-tickets');
        Route::get('/admin/archive-users', [AdminController::class, 'archiveUsers'])->name('admin.archive-users');
        Route::post('/archive/{id}', [AuthController::class, 'archiveUser'])
            ->name('admin.archive');
        Route::post('/tickets/{id}/archive', [TicketController::class, 'archiveTicket'])
            ->name('admin.tickets.archive');
        Route::post('/tickets/{id}/unarchive', [TicketController::class, 'unarchiveTicket'])
            ->name('admin.tickets.unarchive');
            

          // ...existing admin routes...
    Route::get('/tickets/create', [TicketController::class, 'adminCreate'])->name('admin.tickets.create');
    Route::post('/tickets/store', [TicketController::class, 'adminStore'])->name('admin.tickets.store');

            
        
        // routes for admin create account
        Route::get('/users/create', [AuthController::class, 'showCreateUser'])
            ->name('admin.users.create');
        Route::post('/users', [AuthController::class, 'storeUser'])
            ->name('admin.users.store');
    });

    Route::put('/users/{id}/unarchive', [AuthController::class, 'unarchiveUser'])->name('users.unarchive');
});

// Regular user routes
Route::middleware(['auth'])->group(function () {
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    Route::get('/tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
    Route::patch('/tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');
    Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy'])->name('tickets.destroy');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    // Dashboard
// With this one:
Route::get('/dashboard', [AuthController::class, 'showAdminDashboard'])->name('admin.dashboard');
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    
    // User management
    Route::get('/roles/manage', [AdminController::class, 'manageRoles'])->name('admin.manage-roles');
    Route::patch('/users/{user}/update-role', [AuthController::class, 'updateRole'])->name('admin.users.update-role');
    Route::get('/users/create', [AuthController::class, 'showCreateUser'])->name('admin.users.create');
    Route::post('/users', [AuthController::class, 'storeUser'])->name('admin.users.store');
    Route::post('/approve/{id}', [AuthController::class, 'approveUser'])->name('admin.approve');
    Route::post('/change-role/{id}', [AuthController::class, 'changeUserRole'])->name('admin.changeRole');
    
    
    // Ticket management
    Route::get('/tickets/manage', [AdminController::class, 'manageTickets'])->name('admin.manage-tickets');
    Route::patch('/tickets/{ticket}/status', [TicketController::class, 'updateStatus'])->name('admin.tickets.update-status');
    Route::patch('/tickets/{ticket}/update', [TicketController::class, 'adminUpdate'])->name('admin.tickets.update');
    // Route::post('/tickets/{id}/archive', [TicketController::class, 'archiveTicket'])->name('admin.tickets.archive');
    // Route::post('/tickets/{id}/unarchive', [TicketController::class, 'unarchiveTicket'])->name('admin.tickets.unarchive');
    
    // Archive management
    // Route::get('/archive-users', [AdminController::class, 'archiveUsers'])->name('admin.archive-users');
    // Route::post('/archive/{id}', [AuthController::class, 'archiveUser'])->name('admin.archive');
});