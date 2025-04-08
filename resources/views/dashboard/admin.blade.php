@extends('layouts.app')

@section('content')
<div class="py-10 bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">

        <!-- Dashboard Header -->
        <div class="bg-white shadow-md rounded-xl p-6 border border-gray-200">
            <h1 class="text-3xl font-semibold text-gray-800">Admin Dashboard</h1>
            <p class="text-sm text-gray-500 mt-1">Manage users and permissions</p>

            <!-- Stats Summary -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                <!-- Pending Users -->
                <div class="bg-indigo-100 p-4 rounded-xl border border-indigo-200 hover:shadow-md transition">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-indigo-200 rounded-full">
                            <svg class="w-6 h-6 text-indigo-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-indigo-700">Pending Users</p>
                            <p class="text-2xl font-bold text-gray-800">{{ count($pendingUsers) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Users -->
                <div class="bg-green-100 p-4 rounded-xl border border-green-200 hover:shadow-md transition">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-green-200 rounded-full">
                            <svg class="w-6 h-6 text-green-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-green-700">Total Users</p>
                            <p class="text-2xl font-bold text-gray-800">{{ count($users) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Admins -->
                <div class="bg-purple-100 p-4 rounded-xl border border-purple-200 hover:shadow-md transition">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-purple-200 rounded-full">
                            <svg class="w-6 h-6 text-purple-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-purple-700">Admins</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $users->where('role', 'admin')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Users Table -->
        <div class="bg-white shadow-md rounded-xl border border-gray-200">
            <div class="px-6 py-5 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">Pending Users</h2>
                <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-medium">
                    {{ count($pendingUsers) }} awaiting approval
                </span>
            </div>
            <div class="overflow-x-auto p-6">
                <table class="min-w-full divide-y divide-gray-200 text-sm text-left">
                    <thead class="bg-gray-50 text-gray-500 uppercase font-medium">
                        <tr>
                            <th class="px-6 py-3">Name</th>
                            <th class="px-6 py-3">Email</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($pendingUsers as $user)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-medium text-gray-800">{{ $user->name }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                        {{ $user->is_approved ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $user->is_approved ? 'Approved' : 'Pending' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 space-x-2">
                                    <form action="{{ route('admin.approve', $user->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded text-xs font-semibold transition">
                                            Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.delete', $user->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded text-xs font-semibold transition">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                        @if(count($pendingUsers) === 0)
                            <tr>
                                <td colspan="4" class="px-6 py-6 text-center text-gray-500 bg-gray-50">
                                    <div class="flex flex-col items-center">
                                        <svg class="h-6 w-6 mb-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        No pending users found
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Change User Roles Section -->
        <div class="bg-white shadow-md rounded-xl border border-gray-200">
            <div class="px-6 py-5 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Change User Roles</h2>
            </div>
            <div class="overflow-x-auto p-6">
                <table class="min-w-full divide-y divide-gray-200 text-sm text-left">
                    <thead class="bg-gray-50 text-gray-500 uppercase font-medium">
                        <tr>
                            <th class="px-6 py-3">Name</th>
                            <th class="px-6 py-3">Email</th>
                            <th class="px-6 py-3">Role</th>
                            <th class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($users as $user)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-medium text-gray-800">{{ $user->name }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                        {{ $user->role == 'admin' ? 'bg-purple-100 text-purple-800' : ($user->role == 'support' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <form action="{{ route('admin.changeRole', $user->id) }}" method="POST" class="flex items-center space-x-2">
                                        @csrf
                                        <select name="role" required class="w-32 bg-white border border-gray-300 text-gray-700 py-1.5 px-3 rounded focus:ring-1 focus:ring-indigo-500 text-sm">
                                            <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                                            <option value="support" {{ $user->role == 'support' ? 'selected' : '' }}>Support</option>
                                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                        </select>
                                        <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 text-white px-3 py-1.5 rounded text-xs font-semibold transition">
                                            Update
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                        @if(count($users) === 0)
                            <tr>
                                <td colspan="4" class="px-6 py-6 text-center text-gray-500 bg-gray-50">
                                    <div class="flex flex-col items-center">
                                        <svg class="h-6 w-6 mb-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        No users found
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
