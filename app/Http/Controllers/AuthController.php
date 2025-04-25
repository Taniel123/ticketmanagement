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
        if (auth()->user()->role !== 'user') {
            abort(403, 'Unauthorized action.');
        }

        $status = $request->get('status', 'all');
        $query = Ticket::where('user_id', auth()->id());

        // Apply status filter
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Get counts for each status
        $openCount = Ticket::where('user_id', auth()->id())->where('status', 'open')->count();
        $ongoingCount = Ticket::where('user_id', auth()->id())->where('status', 'ongoing')->count();
        $closedCount = Ticket::where('user_id', auth()->id())->where('status', 'closed')->count();
        $allCount = Ticket::where('user_id', auth()->id())->count();

        $tickets = $query->latest()->paginate(10);

        return view('dashboard.user', compact(
            'tickets',
            'status',
            'openCount',
            'ongoingCount',
            'closedCount',
            'allCount'
        ));
    }

    // Show admin dashboard
    public function showAdminDashboard(Request $request)
    {   
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        // Get the status filter
        $status = $request->get('status', 'open');
        
        // Get pending users
        $pendingUsers = User::where('is_approved', false)
                        ->paginate(3, ['*'], 'pending_page');
        
        // Get approved users
        $users = User::where('is_approved', true)
                    ->paginate(3, ['*'], 'users_page');
        
        // Start with base query
        $query = Ticket::with('user');
        
        // Apply status filter if not 'all'
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        // Get filtered tickets
        $tickets = $query->latest()->paginate(3, ['*'], 'tickets_page');
        
        // Get counts for each status
        $openCount = Ticket::where('status', 'open')->count();
        $ongoingCount = Ticket::where('status', 'ongoing')->count();
        $closedCount = Ticket::where('status', 'closed')->count();
        
        return view('dashboard.admin', compact(
            'pendingUsers',
            'users',
            'tickets',
            'status',
            'openCount',
            'ongoingCount',
            'closedCount'
        ));
    }

    // Show support dashboard
    public function showSupportDashboard(Request $request)
    {
        if (auth()->user()->role !== 'support') {
            return redirect()->route(auth()->user()->role . '.dashboard');
        }
        
        // Get the filter from request, default to open tickets
        $status = $request->get('status', 'open');
        
        // Start with base query
        $query = Ticket::query()->with('user')->latest();
        
        // Apply status filter if not 'all'
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        // Get tickets based on filter
        $tickets = $query->paginate(10)->withQueryString();
        
        // Get counts for each status
        $openCount = Ticket::where('status', 'open')->count();
        $ongoingCount = Ticket::where('status', 'ongoing')->count();
        $closedCount = Ticket::where('status', 'closed')->count();
        
        return view('dashboard.support', compact(
            'tickets', 
            'status',
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
            $user->update(['role' => $request->role]);
            
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
    }

    public function storeUser(Request $request)
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
            'email_verified_at' => now(), // Auto verify email for admin-created users
        ]);

        return redirect()->route('admin.dashboard')
            ->with('success', 'User created successfully');
    }

    public function showUser(User $user)
    {
        $tickets = $user->tickets()->latest()->paginate(10);
        return view('admin.users.show', compact('user', 'tickets'));
    }

 
    public function editUser(User $user)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        return view('admin.users.edit', compact('user'));
    }

  
    public function updateUser(Request $request, User $user)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:user,support,admin',
        ]);

        $user->update($validated);

        return redirect()->route('admin.dashboard')
            ->with('success', 'User updated successfully');
    }
}