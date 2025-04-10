<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Events\Registered;
use App\Notifications\TicketNotification;
use App\Notifications\UserApprovedNotification;

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

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        // First check if the user exists
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }
        
        // Check credentials without logging in the user yet
        if (Auth::validate($credentials)) {
            // Check email verification using hasVerifiedEmail() method
            if (!$user->hasVerifiedEmail()) {
                return back()->withErrors([
                    'email' => 'You need to verify your email address first. Please check your email for the verification link.',
                ])->onlyInput('email');
            }
            
            // Check if user is approved
            if (!$user->is_approved) {
                return back()->withErrors([
                    'email' => 'Your account is pending admin approval. You will be notified via email once approved.',
                ])->onlyInput('email');
            }
            
            // All checks passed, now actually log in the user
            Auth::login($user, $remember);
            $request->session()->regenerate();
            
            return redirect()->route($user->role . '.dashboard');
        }
        
        // Invalid password
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // Show registration form
    public function showRegister()
    {
        return view('auth.register');
    }

    // Handle registration logic
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);

            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'user',
                'is_approved' => false,
                'email_verified_at' => null
            ]);

            if (!$user) {
                throw new \Exception('Failed to create user');
            }

            // Send verification email
            event(new Registered($user));

            DB::commit();

            // Redirect to login with success message
            return redirect()->route('login')
                ->with('success', 'Registration successful! Please check your email to verify your account.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors([
                'error' => 'Registration failed. Please try again. ' . $e->getMessage()
            ])->withInput();
        }
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
        if (auth()->user()->role !== 'user') {
            return redirect()->route(auth()->user()->role . '.dashboard');
        }
        
        $user = Auth::user();
        $tickets = $user->tickets()->latest()->get();
        return view('dashboard.user', compact('tickets'));
    }

    // Show admin dashboard
    public function showAdminDashboard()
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route(auth()->user()->role . '.dashboard');
        }
        
        $pendingUsers = User::where('is_approved', false)->get();
        $users = User::where('is_approved', true)->get();
        return view('dashboard.admin', compact('pendingUsers', 'users'));
    }

    // Show support dashboard
    public function showSupportDashboard()
    {
        if (auth()->user()->role !== 'support') {
            return redirect()->route(auth()->user()->role . '.dashboard');
        }
        
        $tickets = Ticket::whereIn('status', ['open', 'ongoing'])->latest()->get();
        return view('dashboard.support', compact('tickets'));
    }

    // Approve User
    public function approveUser($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_approved' => true]);
        
        // Send approval notification
        $user->notify(new UserApprovedNotification());
        
        return back()->with('success', 'User approved successfully. An email notification has been sent.');
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
        $oldRole = $user->role;
        $user->update(['role' => $request->role]);
        
        // Send notification to user
        $user->notify(new TicketNotification(null, 'user_role_changed', auth()->user()));
        
        return back()->with('success', 'User role updated successfully.');
    }
}
