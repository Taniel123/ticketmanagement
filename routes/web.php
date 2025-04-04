<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;  // Import TicketController

// Make the login page the landing page (so when users visit '/', it will show the login page)
Route::get('/', function () {
    return view('home'); // View for the landing page
})->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');  // Ensure login page works
Route::post('/login', [AuthController::class, 'login']);  // Handles POST request for login

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');  // Registration page
Route::post('/register', [AuthController::class, 'register']);  // Handles POST request for registration

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');  // Handles logout

Route::middleware(['auth'])->group(function () {
    // Your protected routes
    Route::get('/user/dashboard', [AuthController::class, 'showUserDashboard'])->name('user.dashboard');
    Route::get('/admin/dashboard', [AuthController::class, 'showAdminDashboard'])->name('admin.dashboard');
    Route::get('/support/dashboard', [AuthController::class, 'showSupportDashboard'])->name('support.dashboard');
});

// Route::get('/dashboard/user', [AuthController::class, 'showUserDashboard'])->name('user.dashboard');
// Route::get('/dashboard/admin', [AuthController::class, 'showAdminDashboard'])->name('admin.dashboard');
Route::post('/admin/approve/{id}', [AuthController::class, 'approveUser'])->name('admin.approve');
Route::delete('/admin/delete/{id}', [AuthController::class, 'deleteUser'])->name('admin.delete');
Route::post('/admin/change-role/{id}', [AuthController::class, 'changeUserRole'])->name('admin.changeRole');
// Route::get('/dashboard/support', [AuthController::class, 'showSupportDashboard'])->name('support.dashboard');
Route::patch('/tickets/{id}/status', [TicketController::class, 'updateStatus'])->name('tickets.updateStatus');


Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');  // Ticket creation page
Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');  // Store ticket
