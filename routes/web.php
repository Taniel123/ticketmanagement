<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Make the login page the landing page (so when users visit '/', it will show the login page)
Route::get('/', [AuthController::class, 'showLogin'])->name('login');  // Directs to the login page

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');  // Ensure login page works
Route::post('/login', [AuthController::class, 'login']);  // Handles POST request for login

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');  // Registration page
Route::post('/register', [AuthController::class, 'register']);  // Handles POST request for registration

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');  // Handles logout

