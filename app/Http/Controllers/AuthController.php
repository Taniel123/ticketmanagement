<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    // Show login form
    public function showLogin()
    {
        // Check if the user is already logged in
        if (session()->has('user_id')) {
            // Redirect to the appropriate dashboard based on the user's role
            return redirect()->route(session('user_role') . '.dashboard');
        }
        return view('auth.login');
    }

    // Handle login logic
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // Store user in session
            auth()->login($user); // This logs the user in

            // Redirect to respective dashboard
            return redirect()->route($user->role . '.dashboard');
        }

        return back()->withErrors('Invalid email or password.');
    }

    // Show registration form
    public function showRegister()
    {
        return view('auth.register');
    }

    // Handle registration logic
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // Set default role
            'is_approved' => false // Set default approval status
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('verification.notice');
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }

    // Show user dashboard
    public function showUserDashboard()
    {
        $user = Auth::user();
        $tickets = $user->tickets()->latest()->get();
        
        return view('dashboard.user', compact('tickets'));
    }

    // Show admin dashboard
    public function showAdminDashboard()
    {
        $pendingUsers = User::where('is_approved', false)->get();
        $users = User::where('is_approved', true)->get();
        
        return view('dashboard.admin', compact('pendingUsers', 'users'));
    }

    // Show support dashboard
    public function showSupportDashboard()
    {
        $tickets = Ticket::whereIn('status', ['open', 'in_progress'])->latest()->get();
        return view('dashboard.support', compact('tickets'));
    }

    // Approve User
    public function approveUser($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_approved' => true]);
        
        return back()->with('success', 'User approved successfully.');
    }

    // Delete User
    public function deleteUser($id)
    {
        // Find the user by ID
        $user = User::findOrFail($id);

        // Delete the user from the database
        $user->delete();

        // Redirect back to admin dashboard with success message
        return redirect()->route('admin.dashboard')->with('success', 'User deleted successfully.');
    }

    public function changeUserRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update(['role' => $request->role]);
        
        return back()->with('success', 'User role updated successfully.');
    }
}