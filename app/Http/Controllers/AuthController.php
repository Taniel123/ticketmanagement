<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

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
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        // Assign a default role during registration
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',  // Default role
        ]);

        session(['user_id' => $user->id, 'user_role' => $user->role]);

        return redirect()->route('user.dashboard');
    }

    // Handle logout
    public function logout()
    {
        session()->forget(['user_id', 'user_role']);
        return redirect()->route('login');
    }

    // Show user dashboard
    public function showUserDashboard()
    {
        if (!auth()->check()) {
            return redirect()->route('login'); // Redirect if not authenticated
        }
    
        $user = auth()->user(); // Safe to access user now
        return view('dashboard.user', compact('user'));
    }

    // Show admin dashboard
    public function showAdminDashboard()
    {
        if (!auth()->check()) {
            return redirect()->route('login'); // Redirect if not authenticated
        }
    
        $user = auth()->user(); // Safe to access user now

        $pendingUsers = User::where('is_approved', false)->get();

        // Pass the list of pending users to the view
        return view('dashboard.admin', compact('user', 'pendingUsers'));
    }

    // Show support dashboard
    public function showSupportDashboard()
    {
        if (!auth()->check()) {
            return redirect()->route('login'); // Redirect if not authenticated
        }
    
        $user = auth()->user(); // Safe to access user now
        return view('dashboard.support', compact('user'));
    }

    // Approve User
public function approveUser($id)
{
    // Find the user by ID
    $user = User::findOrFail($id);

    // Update the is_approved column to true (approve the user)
    $user->is_approved = true;
    $user->save();

    // Redirect back to admin dashboard with success message
    return redirect()->route('admin.dashboard')->with('success', 'User approved successfully.');
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

}
