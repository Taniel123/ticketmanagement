<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Notifications\RoleChangeNotification;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.dashboard');
    }

    /**
     * Show user details
     */
    public function show(User $user)
    {
        // Get the user's tickets with pagination
        $tickets = $user->tickets()
                       ->with('user')
                       ->latest()
                       ->paginate(10);

        // Get ticket statistics
        $ticketStats = [
            'total' => $user->tickets()->count(),
            'open' => $user->tickets()->where('status', 'open')->count(),
            'ongoing' => $user->tickets()->where('status', 'ongoing')->count(),
            'closed' => $user->tickets()->where('status', 'closed')->count(),
        ];

        return view('admin.users.show', compact('user', 'tickets', 'ticketStats'));
    }

    /**
     * Show edit form for user
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update user details
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:user,support,admin'
        ]);

        try {
            $user->update($validated);

            return redirect()->route('admin.users.show', $user)
                ->with('success', 'User updated successfully');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to update user: ' . $e->getMessage());
        }
    }

    public function deleteUser(User $user)
    {
        try {
            if ($user->role === 'admin') {
                return back()->with('error', 'Cannot delete an admin user');
            }

            \DB::beginTransaction();
            
            // Delete user's tickets first
            $user->tickets()->delete();
            
            // Delete the user
            $user->delete();
            
            \DB::commit();

            return redirect()->route('admin.dashboard')
                ->with('success', 'User deleted successfully');
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }

    public function updatePassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            return redirect()->route('admin.users.show', $user)
                ->with('success', 'Password updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update password: ' . $e->getMessage());
        }
    }

    public function toggleStatus(User $user)
    {
        try {
            $wasApproved = $user->is_approved;
            $user->is_approved = !$wasApproved;
            
            // If being approved for the first time, set approved_at
            if (!$wasApproved && $user->is_approved && $user->approved_at === null) {
                $user->approved_at = now();
            }
            
            $user->save();

            $status = $user->is_approved ? 'activated' : 'deactivated';
            return back()->with('success', "User account {$status} successfully");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to toggle user status: ' . $e->getMessage());
        }
    }

    public function showAdminDashboard(Request $request)
    {
        $tab = $request->get('tab', 'active');
        $status = $request->get('status', 'all');

        // Get pending users query
        $pendingUsers = User::where('is_approved', false)
                           ->whereNotNull('email_verified_at')
                           ->whereNull('approved_at')
                           ->where('is_archived', false)
                           ->latest()
                           ->paginate(10);

        // Initialize users query
        $usersQuery = User::query();

        // Filter users based on tab
        switch ($tab) {
            case 'inactive':
                $usersQuery->where('is_approved', false)
                          ->whereNotNull('email_verified_at')
                          ->whereNotNull('approved_at');
                break;
                
            case 'pending':
                $usersQuery->where('is_approved', false)
                          ->whereNotNull('email_verified_at')
                          ->whereNull('approved_at')
                          ->where('is_archived', false);
                break;

            default: // 'active' tab
                $usersQuery->where('is_approved', true)
                          ->whereNotNull('email_verified_at')
                          ->where('is_archived', false);
                break;
        }

        // Get counts
        $counts = [
            'active' => User::where('is_approved', true)
                           ->whereNotNull('email_verified_at')
                           ->where('is_archived', false)
                           ->count(),
            'inactive' => User::where('is_approved', false)
                             ->whereNotNull('email_verified_at')
                             ->whereNotNull('approved_at')
                             ->count(),
            'pending' => User::where('is_approved', false)
                            ->whereNotNull('email_verified_at')
                            ->whereNull('approved_at')
                            ->where('is_archived', false)
                            ->count()
        ];

        // Paginate users
        $users = $usersQuery->latest()->paginate(10);

        // Get tickets with status filtering
        $ticketsQuery = Ticket::with('user');
        if ($status !== 'all') {
            $ticketsQuery->where('status', $status);
        }

        // Get ticket counts
        $openCount = Ticket::where('status', 'open')->count();
        $ongoingCount = Ticket::where('status', 'ongoing')->count();
        $closedCount = Ticket::where('status', 'closed')->count();

        // Paginate tickets
        $tickets = $ticketsQuery->latest()->paginate(10);

        return view('dashboard.admin', compact(
            'users',
            'pendingUsers',
            'tickets',
            'tab',
            'status',
            'counts',
            'openCount',
            'ongoingCount',
            'closedCount'
        ));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,support,admin'
        ]);

        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'is_approved' => true,
                'approved_at' => now(),
                'email_verified_at' => now()
            ]);

            return redirect()->route('admin.users.show', $user)
                ->with('success', 'User created successfully');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to create user: ' . $e->getMessage());
        }
    }

    public function approve(User $user)
    {
        try {
            $user->update([
                'is_approved' => true,
                'approved_at' => now()
            ]);

            // Send notification to user
            $user->notify(new UserApprovedNotification());

            return back()->with('success', 'User approved successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to approve user: ' . $e->getMessage());
        }
    }
}