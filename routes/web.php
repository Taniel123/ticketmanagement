<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Make the login page the landing page (so when users visit '/', it will show the login page)
Route::get('/', function () {
    return view('home'); // View for the landing page
})->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');  // Ensure login page works
Route::post('/login', [AuthController::class, 'login']);  // Handles POST request for login

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');  // Registration page
Route::post('/register', [AuthController::class, 'register']);  // Handles POST request for registration

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');  // Handles logout

Route::get('/dashboard/user', [AuthController::class, 'showUserDashboard'])->name('user.dashboard');
Route::get('/dashboard/admin', [AuthController::class, 'showAdminDashboard'])->name('admin.dashboard');
Route::post('/admin/approve/{id}', [AuthController::class, 'approveUser'])->name('admin.approve');
Route::delete('/admin/delete/{id}', [AuthController::class, 'deleteUser'])->name('admin.delete');
Route::get('/dashboard/support', [AuthController::class, 'showSupportDashboard'])->name('support.dashboard');
