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
            <a href="{{ route('admin.manage-tickets') }}" class="hover:text-indigo-600 transition-colors duration-200">Manage Tickets</a>
            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
            </svg>
            <span>Create Ticket</span>
        </div>

        <!-- Main Content -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h1 class="text-lg font-medium text-gray-800 flex items-center">
                    <span class="w-1 h-5 bg-indigo-600 rounded mr-2"></span>
                    Create New Ticket
                </h1>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('admin.tickets.store') }}" class="p-6 space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Assign To User -->
                    <div class="space-y-1">
                        <label for="user_id" class="block text-sm font-medium text-gray-700">
                            Assign To User <span class="text-red-500">*</span>
                        </label>
                        <select name="user_id" id="user_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Select a user</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Priority -->
                    <div class="space-y-1">
                        <label for="priority" class="block text-sm font-medium text-gray-700">
                            Priority <span class="text-red-500">*</span>
                        </label>
                        <select name="priority" id="priority" required
                            class="w-full px-3 py-2 border border-gray-300 rounded shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>

                    <!-- Title -->
                    <div class="space-y-1 md:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700">
                            Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="title" required
                            class="w-full px-3 py-2 border border-gray-300 rounded shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="Enter ticket title">
                    </div>

                    <!-- Description -->
                    <div class="space-y-1 md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700">
                            Description <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description" id="description" rows="4" required
                            class="w-full px-3 py-2 border border-gray-300 rounded shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="Enter ticket description"></textarea>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="pt-6 border-t border-gray-200 flex justify-end space-x-3">
                    <a href="{{ route('admin.manage-tickets') }}"
                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                        Create Ticket
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection