<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Models\User;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['showLogin', 'login', 'showRegister', 'register']);
    }

    // Show login form
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectToDashboard();
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

        if (Auth::attempt($request->only('email', 'password'))) {
            return $this->redirectToDashboard();
        }

        return back()->withErrors(['login' => 'Invalid email or password.']);
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

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        Auth::login($user);

        return redirect()->route('user.dashboard');
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        return redirect('/login');
    }

    // Redirect user based on role
    private function redirectToDashboard()
    {
        $role = Auth::user()->role;
        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'support' => redirect()->route('support.dashboard'),
            default => redirect()->route('user.dashboard'),
        };
    }

    // Show user dashboard
    public function showUserDashboard()
    {
        $user = Auth::user();
        $tickets = Ticket::where('user_id', $user->id)->get();

        return view('dashboard.user', compact('user', 'tickets'));
    }

    // Show admin dashboard
    public function showAdminDashboard()
    {
        $user = Auth::user();
        $pendingUsers = User::where('is_approved', false)->get();
        $users = User::where('is_approved', true)->where('id', '!=', $user->id)->get();
        $tickets = Ticket::all();

        return view('dashboard.admin', compact('user', 'pendingUsers', 'users', 'tickets'));
    }

    // Show support dashboard
    public function showSupportDashboard()
    {
        $user = Auth::user();
        $tickets = Ticket::all();

        return view('dashboard.support', compact('user', 'tickets'));
    }

    // Approve User
    public function approveUser($id)
    {
        $user = User::findOrFail($id);
        $user->is_approved = true;
        $user->save();

        return redirect()->route('admin.dashboard')->with('success', 'User approved successfully.');
    }

    // Delete User
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.dashboard')->with('success', 'User deleted successfully.');
    }

    // Change User Role
    public function changeUserRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:user,support,admin',
        ]);

        $user = User::findOrFail($id);

        if (Auth::id() === $user->id) {
            return redirect()->route('admin.dashboard')->withErrors('You cannot change your own role.');
        }

        $user->role = $request->role;
        $user->save();

        return redirect()->route('admin.dashboard')->with('success', 'User role updated successfully.');
    }
}
