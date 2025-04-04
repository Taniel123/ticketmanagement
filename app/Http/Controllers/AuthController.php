<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ticket;
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

        $tickets = Ticket::where('user_id', $user->id)->get();

        return view('dashboard.user', compact('user', 'tickets'));
    }

    // Show admin dashboard
    public function showAdminDashboard()
    {
        if (!auth()->check()) {
            return redirect()->route('login'); // Redirect if not authenticated
        }
    
        $user = auth()->user(); // Safe to access user now

        $pendingUsers = User::where('is_approved', false)->get();

        $users = User::where('is_approved', true)
        ->where('id', '!=', $user->id) // Exclude the current admin user
        ->get();

        $tickets = Ticket::all();

        // Pass the list of pending users to the view
        return view('dashboard.admin', compact('user', 'pendingUsers', 'users', 'tickets'));
    }

    // Show support dashboard
    public function showSupportDashboard()
    {
        if (!auth()->check()) {
            return redirect()->route('login'); // Redirect if not authenticated
        }
    
        $user = auth()->user(); // Safe to access user now

        $tickets = Ticket::all();

        return view('dashboard.support', compact('user', 'tickets'));
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

public function changeUserRole(Request $request, $id)
{
    // Validate the incoming role change request
    $request->validate([
        'role' => 'required|in:user,support,admin', // Only allow these roles
    ]);

    // Find the user by ID
    $user = User::findOrFail($id);

    // Prevent changing the admin's role
    if (auth()->user()->id === $user->id) {
        return redirect()->route('admin.dashboard')->withErrors('You cannot change your own role.');
    }

    // Update the user's role
    $user->role = $request->role;
    $user->save();

    return redirect()->route('admin.dashboard')->with('success', 'User role updated successfully.');
}


}
