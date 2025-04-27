@extends('layouts.support_app')

@section('content')
<div class="py-6 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-8 lg:px-12">
        <!-- Breadcrumb -->
        <div class="flex items-center text-sm text-gray-500 mb-6 space-x-2">
            <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard') }}" 
                class="hover:text-indigo-600 transition-colors duration-200">Dashboard</a>
            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
            </svg>
            <span>Ticket Details</span>
        </div>

        <!-- Header Section -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                    <span class="w-1.5 h-6 bg-indigo-600 rounded mr-3"></span>
                    Ticket
                </h1>
                <p class="text-sm text-gray-600 mt-1">Created {{ $ticket->created_at->format('M d, Y h:i A') }}</p>
            </div>

            <!-- Ticket Status Badge -->
            <div class="mt-4 md:mt-0 flex items-center space-x-4">
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium
                    {{ $ticket->status === 'open' ? 'bg-green-100 text-green-800' : 
                       ($ticket->status === 'ongoing' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                    <span class="w-1.5 h-1.5 rounded-full 
                        {{ $ticket->status === 'open' ? 'bg-green-600' : 
                           ($ticket->status === 'ongoing' ? 'bg-yellow-600' : 'bg-red-600') }} mr-2"></span>
                    {{ ucfirst($ticket->status) }}
                </span>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Ticket Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Ticket Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-lg font-medium text-gray-800">{{ $ticket->title }}</h2>
                    </div>
                    <div class="p-6">
                        <div class="prose max-w-none">
                            <p class="text-gray-700 whitespace-pre-wrap">{{ $ticket->description }}</p>
                        </div>
                    </div>
                </div>

                <!-- Feedback History -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-medium text-gray-800">Feedback History</h3>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @forelse ($ticket->feedbacks()->with('user')->latest()->get() as $feedback)
                            <div class="p-6 hover:bg-gray-50 transition-colors duration-200">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                            <span class="text-indigo-600 font-medium text-sm">
                                                {{ strtoupper(substr($feedback->user->name, 0, 2)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $feedback->user->name }}
                                            </p>
                                            <span class="text-xs text-gray-500">
                                                {{ $feedback->created_at->format('M d, Y h:i A') }}
                                            </span>
                                        </div>
                                        <p class="mt-1 text-sm text-gray-700">{{ $feedback->comment }}</p>
                                        @if ($feedback->status_change)
                                            <div class="mt-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Status changed to {{ ucfirst($feedback->status_change) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-center">
                                <p class="text-sm text-gray-500">No feedback available for this ticket.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Column - Metadata -->
            <div class="space-y-6">
                <!-- Ticket Information Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-sm font-medium text-gray-800">Ticket Information</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Priority</dt>
                            <dd class="mt-1 flex items-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $ticket->priority === 'high' ? 'bg-red-100 text-red-800' : 
                                       ($ticket->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                    <span class="w-1.5 h-1.5 rounded-full 
                                        {{ $ticket->priority === 'high' ? 'bg-red-600' : 
                                           ($ticket->priority === 'medium' ? 'bg-yellow-600' : 'bg-green-600') }} mr-1.5"></span>
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                            </dd>
                        </div>

                        <div class="pt-4 border-t border-gray-200">
                            <dt class="text-sm font-medium text-gray-500">Created By</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $ticket->user->name }}</dd>
                            <dd class="mt-1 text-xs text-gray-500">{{ $ticket->created_at->format('M d, Y h:i A') }}</dd>
                        </div>

                        <div class="pt-4 border-t border-gray-200">
                            <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $ticket->updatedBy->name ?? 'Unknown' }}</dd>
                            <dd class="mt-1 text-xs text-gray-500">{{ $ticket->updated_at->format('M d, Y h:i A') }}</dd>
                        </div>
                    </div>
                </div>

           <!-- Replace the existing Actions Card with this -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
        <h3 class="text-sm font-medium text-gray-800">Actions</h3>
    </div>
    <div class="p-6 space-y-3">
        <!-- Back to Dashboard button -->
        @if(auth()->check())
            <a href="{{ auth()->user()->role === 'admin' 
                        ? route('admin.manage-tickets') 
                        : (auth()->user()->role === 'support' 
                            ? route('support.dashboard') 
                            : route('user.dashboard')) }}"
                class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Dashboard
            </a>
        @endif
    </div>
</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection