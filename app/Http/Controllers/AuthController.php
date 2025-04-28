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
// use App\Notifications\UserArchivedNotification;
// use App\Notifications\UserUnarchiveNotification;

class AuthController extends Controller
{
    // Show login form
    public function showLogin()
    {
        // Check if the user is already logged in
        if (session()->has('user_id')) {
            // if the user is login ddrirect siya sa kung ano yung role niya.
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

            // para sa remember token 
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

            // First check if the user exists
        $user = User::where('email', $request->email)->first();
            
        // check if may match na credentials
        if (!$user) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

        // check if may naka archive yung account
        if ($user->is_archived) {
            return back()->withErrors([
                'email' => 'This account has been archived. Please contact support.',
            ])->onlyInput('email');
        }

        if ($user->status !== 1) { // Only allow if status is 1 (active)
            return back()->withErrors([
                'email' => 'Your account is inactive. Please contact support.',
            ])->onlyInput('email');
        }

        // Check credentials without logging in the user yet
        if (Auth::validate($credentials)) {
            // check if the account email is verified
            if (!$user->hasVerifiedEmail()) {
                return back()->withErrors([
                    'email' => 'You need to verify your email address first. Please check your email for the verification link.',
                ])->onlyInput('email');
            }
            
            // Check if user is approved by admin
            if (!$user->is_approved) {
                return back()->withErrors([
                    'email' => 'Your account is pending admin approval. You will be notified via email once approved.',
                ])->onlyInput('email');
            }

            //redirect the account base on the role
            Auth::login($user, $remember);
            return redirect()->route($user->role . '.dashboard');
        }
            //Redirects the user back to the previous page
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // Show registration form / registration route
    public function showRegister()
    {
        return view('auth.register');
    }

    // Handle registration logic
  
    public function register(Request $request)
    {
        
    //input validation - Checks name, email, and password validity.
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);
            
              //Transaction start- initializes the transaction by starting a transactional block.
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

            //operations are successful.
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
        $request->session()->regenerateToken(); // Regenerates the CSRF token
        //redirect sa login
        return redirect()->route('login');
    }

    // Show user dashboard
    //user dashboard route
    public function showUserDashboard(Request $request)
    {
        $user = Auth::user();
        $status = $request->get('status', 'open');
        
        // Start with base query
        $query = $user->tickets();
        
        // Apply status filter if not 'all'
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        // Get filtered tickets
        $tickets = $query->latest()->paginate(6);
        
        // Get counts for each status
        $allCount = $user->tickets()->count();
        $openCount = $user->tickets()->where('status', 'open')->count();
        $ongoingCount = $user->tickets()->where('status', 'ongoing')->count();
        $closedCount = $user->tickets()->where('status', 'closed')->count();
        
        return view('dashboard.user', compact(
            'tickets',
            'status',
            'allCount',
            'openCount',
            'ongoingCount',
            'closedCount'
        ));
    }

    // Show admin dashboard
    public function showAdminDashboard(Request $request)
    {   
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        // Get all the counts first
        $pendingUsersCount = User::where('is_approved', false)
            ->where('is_archived', false)
            ->count();
        
        $totalUsersCount = User::where('is_archived', false)->count();
        $archivedUsersCount = User::where('is_archived', true)->count();

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

        // User data with pagination
        $pendingUsers = User::where('is_approved', false)
            ->where('is_archived', false)
            ->paginate(3, ['*'], 'pending_page');

        $users = User::where('is_approved', true)
            ->where('is_archived', false)
            ->paginate(3, ['*'], 'users_page');

        $archivedUsers = User::where('is_archived', true)
            ->paginate(3, ['*'], 'archived_page');

        $tickets = Ticket::with('user')->latest()->paginate(3, ['*'], 'tickets_page');

        return view('dashboard.admin', compact(
            'pendingUsersCount',
            'totalUsersCount',
            'archivedUsersCount',
            'dailyTickets',
            'weeklyTickets',
            'monthlyTickets',
            'pendingUsers',
            'users',
            'archivedUsers',
            'tickets'
        ));
    }

public function showSupportDashboard(Request $request)
{
    if (auth()->user()->role !== 'support') {
        return redirect()->route(auth()->user()->role . '.dashboard');
    }

    // Start with base query - remove the whereIn condition to show all tickets
    $query = Ticket::latest();

    // Apply search filter if there's a search term
    if ($request->has('search') && $request->search != '') {
        $query->where('title', 'like', '%' . $request->search . '%');
    }

    // Apply status filter if selected
    if ($request->has('status') && $request->status != '') {
        $query->where('status', $request->status);
    }

    // Apply priority filter if selected
    if ($request->has('priority') && $request->priority != '') {
        $query->where('priority', $request->priority);
    }

    // Paginate results
    $tickets = $query->paginate(10);

    // Get counts for each status
    $openCount = Ticket::where('status', 'open')->count();
    $ongoingCount = Ticket::where('status', 'ongoing')->count();
    $closedCount = Ticket::where('status', 'closed')->count();

    return view('dashboard.support', compact(
        'tickets',
        'openCount',
        'ongoingCount',
        'closedCount'
    ));
}

    // Approve User
    public function approveUser($id)
    {
        $user = User::findOrFail($id); //find user via id
        $user->update(['is_approved' => true]); //update if approved
        
        // Send approval notification
        $user->notify(new UserApprovedNotification()); // email notificatiion
        
        return back()->with('success', 'User approved successfully. An email notification has been sent.');
    }

        // for delete user
    // public function deleteUser($id)
    // {
    //     $user = User::findOrFail($id);

    //     $user->delete();

    //     return redirect()->route('admin.dashboard')->with('success', 'User deleted successfully.');
    // }


        //accept incoming request sa new role
    public function changeUserRole(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $oldRole = $user->role;

            // Update the role
            $user->update([
                'role' => $request->role,
                'status' => (int)$request->status,
            ]);

            // Send notification using the new RoleChangeNotification
            $user->notify(new RoleChangeNotification($request->role, auth()->user()));

            return back()->with('success', 'User role updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update user role: ' . $e->getMessage());
        }
    }

    // public function archiveUser($id)
    // {
    //     try {
    //         $user = User::findOrFail($id);
            
    //         if ($user->role === 'admin') {
    //             return redirect()->back()->with('error', 'Cannot archive an admin user');
    //         }
            
    //         $result = $user->update([
    //             'is_archived' => true
    //         ]);

    //         if ($result) {
    //             // Send notification to admin 
    //             auth()->user()->notify(new UserArchivedNotification($user));
    //             return redirect()->back()->with('success', 'User has been archived successfully');
    //         }
            
    //         return redirect()->back()->with('error', 'Failed to archive user');
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with('error', 'Failed to archive user: ' . $e->getMessage());
    //     }
    // }

    // public function unarchiveUser($id)
    // {
    //     try {
    //         $user = User::findOrFail($id);
    //         $result = $user->update([
    //             'is_archived' => false
    //         ]);

    //         if ($result) {
    //             // Send notification to admin instead of user
    //             auth()->user()->notify(new UserUnarchiveNotification($user));
    //             return redirect()->back()->with('success', 'User unarchived successfully');
    //         }

    //         return redirect()->back()->with('error', 'Failed to unarchive user');
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with('error', 'Failed to unarchive user: ' . $e->getMessage());
    //     }
    // }

    public function showCreateUser()
    {
        return view('admin.users.create');
    }public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,support,user'
        ]);
    
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_approved' => true,
            // Remove email_verified_at to require verification
        ]);
    
        // Trigger verification email
        event(new Registered($user));
    
        // Redirect to manage roles page instead of dashboard
        return redirect()->route('admin.manage-roles')
            ->with('success', 'User created successfully');
    }
    public function updateUserStatus(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            if ($user->role === 'admin') {
                return back()->with('error', 'Cannot change status of an admin user.');
            }

            $request->validate([
                'status' => 'required|in:active,inactive',
            ]);

            $user->update([
                'status' => $request->status, // should be 'active' or 'inactive'
            ]);

            return back()->with('success', 'User status updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update user status: ' . $e->getMessage());
        }
    }

    public function updateRole(Request $request, $userId)
    {
        try {
            $user = User::findOrFail($userId);
            
            $validated = $request->validate([
                'role' => 'required|in:admin,support,user'
            ]);

            // Store old role for notification
            $oldRole = $user->role;

            $user->update([
                'role' => $validated['role']
            ]);

            // Optionally send notification
            try {
                $user->notify(new RoleChangeNotification($oldRole, $validated['role']));
            } catch (\Exception $e) {
                \Log::error('Failed to send role change notification: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Role updated successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Role update failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update role: ' . $e->getMessage()
            ], 500);
        }
    }
}

