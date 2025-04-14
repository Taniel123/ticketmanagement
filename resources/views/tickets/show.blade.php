@extends('layouts.app')

@section('content')
<div class="py-6">
    <!-- Header Section -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                <div class="h-8 w-2 bg-indigo-600 rounded-full mr-3"></div>
                Ticket Details
            </h1>
        </div>
        
        <!-- Quick Status -->
        <!-- <div class="mt-4 md:mt-0">
            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium
                {{ $ticket->status == 'open' ? 'bg-green-100 text-green-800' : 
                   ($ticket->status == 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                <span class="h-1.5 w-1.5 mr-1.5 rounded-full 
                    {{ $ticket->status == 'open' ? 'bg-green-600' : 
                       ($ticket->status == 'in_progress' ? 'bg-blue-600' : 'bg-gray-600') }}"></span>
                {{ ucfirst($ticket->status) }}
            </span>
        </div> -->
    </div>

    <!-- Ticket Information -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-all duration-300 mb-6">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h2 class="text-lg font-bold text-gray-800 flex items-center">
                <!-- <span class="w-1 h-5 bg-indigo-600 rounded mr-2"></span> -->
                {{ $ticket->title }}
            </h2>
            <span class="text-sm text-gray-500">Created {{ $ticket->created_at->diffForHumans() }}</span>
        </div>
        
        <div class="p-6">
    <div class="prose max-w-none break-words">
        <dt class="text-sm font-medium text-gray-500">Description</dt>
        <dd class="mt-1 text-sm text-gray-900">{{ $ticket->description }}</dd>
    </div>
</div>
    </div>

    <!-- Ticket Metadata -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-all duration-300 mb-6">
        <div class="p-6">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Priority</dt>
                    <dd class="mt-1 text-sm text-gray-900 flex items-center">
                        <span class="inline-block h-2 w-2 rounded-full mr-2
                            {{ $ticket->priority == 'high' ? 'bg-red-500' : 
                              ($ticket->priority == 'medium' ? 'bg-yellow-500' : 'bg-green-500') }}"></span>
                        {{ ucfirst($ticket->priority) }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($ticket->status) }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Status Update Form (Admin/Support Only) -->
    <!-- @if(auth()->user()->role === 'admin' || auth()->user()->role === 'support')
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-all duration-300 mb-6">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-medium text-gray-800 flex items-center">
                    <span class="w-1 h-5 bg-indigo-600 rounded mr-2"></span>
                    Update Status
                </h2>
            </div>
            
            <div class="p-6">
                <form action="{{ route('tickets.update-status', $ticket) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="flex items-center space-x-4">
                        <select name="status" class="block w-full bg-gray-50 border border-gray-300 text-gray-700 py-2 px-3 rounded leading-tight focus:outline-none focus:bg-white focus:border-indigo-500 text-sm">
                            <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Open</option>
                            <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded shadow-sm text-sm font-medium transition-colors duration-200 inline-flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Update Status
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif -->

    <!-- Action Button -->
    <div class="flex">
        <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard') }}" 
            class="bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded shadow-sm text-sm font-medium transition-colors duration-200 inline-flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Dashboard
        </a>
    </div>
</div>
@endsection