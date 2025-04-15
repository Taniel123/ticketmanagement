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
use App\Notifications\RoleChangeNotification;

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

        // Check if user is archived
        if ($user->is_archived) {
            return back()->withErrors([
                'email' => 'This account has been archived. Please contact support.',
            ])->onlyInput('email');
        }
        
        // Check credentials without logging in the user yet
        if (Auth::validate($credentials)) {
            // Email verification check not needed if already verified
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
            
            Auth::login($user, $remember);
            return redirect()->route($user->role . '.dashboard');
        }

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
        $tickets = $user->tickets()->latest()->paginate(3);
        return view('dashboard.user', compact('tickets'));
    }

    // Show admin dashboard
    public function showAdminDashboard()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }
    
        // Daily tickets: last 7 days
        $dailyTickets = Ticket::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    
        // Weekly tickets: last 8 weeks
        $weeklyTickets = Ticket::selectRaw('YEARWEEK(created_at, 1) as yearweek, COUNT(*) as count')
            ->where('created_at', '>=', now()->subWeeks(8))
            ->groupBy('yearweek')
            ->orderBy('yearweek')
            ->get();
    
        // Monthly tickets: last 12 months
        $monthlyTickets = Ticket::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    
        // Ticket counts
        $totalTickets = Ticket::count();
        $openTickets = Ticket::where('status', 'open')->count();
        $ongoingTickets = Ticket::where('status', 'ongoing')->count();
        $closedTickets = Ticket::where('status', 'closed')->count();
    
        // User data
        $pendingUsers = User::where('is_approved', false)
            ->where('is_archived', false)
            ->paginate(3, ['*'], 'pending_page');
    
        $users = User::where('is_approved', true)
            ->where('is_archived', false)
            ->paginate(3, ['*'], 'users_page');
    
        $archivedUsers = User::where('is_archived', true)
            ->paginate(3, ['*'], 'archived_page');
    
        $tickets = Ticket::with('user')->latest()->paginate(3, ['*'], 'tickets_page');
    
        // Consolidated return statement
        return view('dashboard.admin', compact(
            'dailyTickets',
            'weeklyTickets',
            'monthlyTickets',
            'pendingUsers',
            'users',
            'archivedUsers',
            'tickets',
            'totalTickets',
            'openTickets',
            'ongoingTickets',
            'closedTickets'
        ));
    }

    // Show support dashboard
    public function showSupportDashboard()
    {
        if (auth()->user()->role !== 'support') {
            return redirect()->route(auth()->user()->role . '.dashboard');
        }
        
        $tickets = Ticket::whereIn('status', ['open', 'ongoing'])->latest()->paginate(3);
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
        try {
            $user = User::findOrFail($id);
            $oldRole = $user->role;
            
            // Update the role
            $user->update(['role' => $request->role]);
            
            // Send notification using the new RoleChangeNotification
            $user->notify(new RoleChangeNotification($request->role, auth()->user()));
            
            return back()->with('success', 'User role updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update user role: ' . $e->getMessage());
        }
    }

    public function archiveUser($id)
    {
        try {
            $user = User::findOrFail($id);
            
            if ($user->role === 'admin') {
                return redirect()->back()->with('error', 'Cannot archive an admin user');
            }
            
            $result = $user->update([
                'is_archived' => true
            ]);

            if ($result) {
                return redirect()->back()->with('success', 'User has been archived successfully');
            }
            
            return redirect()->back()->with('error', 'Failed to archive user');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to archive user: ' . $e->getMessage());
        }
    }

    public function unarchiveUser($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->update(['is_archived' => false]);
            return redirect()->back()->with('success', 'User unarchived successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to unarchive user');
        }
    }
}