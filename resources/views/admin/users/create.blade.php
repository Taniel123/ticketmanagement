@extends('layouts.admin_app')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <div class="flex items-center text-sm text-gray-500 mb-4 space-x-2">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition-colors duration-200">Dashboard</a>
            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
            </svg>
            <span>Create User</span>
        </div>

        <!-- Main Content -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h1 class="text-lg font-medium text-gray-800 flex items-center">
                    <span class="w-1 h-5 bg-indigo-600 rounded mr-2"></span>
                    Create New User
                </h1>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('admin.users.store') }}" class="p-6 space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div class="space-y-1">
                        <label for="name" class="block text-sm font-medium text-gray-700">
                            Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" required
                            class="w-full px-3 py-2 border border-gray-300 rounded shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="Enter full name">
                    </div>

                    <!-- Email -->
                    <div class="space-y-1">
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" id="email" required
                            class="w-full px-3 py-2 border border-gray-300 rounded shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="user@example.com">
                    </div>

                    <!-- Password -->
                    <div class="space-y-1">
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password" id="password" required
                            class="w-full px-3 py-2 border border-gray-300 rounded shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="••••••••">
                    </div>

                    <!-- Confirm Password -->
                    <div class="space-y-1">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                            Confirm Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full px-3 py-2 border border-gray-300 rounded shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="••••••••">
                    </div>

                    <!-- Role Selection -->
                    <div class="space-y-1">
                        <label for="role" class="block text-sm font-medium text-gray-700">
                            Role <span class="text-red-500">*</span>
                        </label>
                        <select name="role" id="role" required
                            class="w-full px-3 py-2 border border-gray-300 rounded shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="" disabled selected>Select a role</option>
                            <option value="user">User</option>
                            <option value="support">Support</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="pt-6 border-t border-gray-200 flex justify-end space-x-3">
                    <a href="{{ route('admin.manage-roles') }}"
                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                        Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection