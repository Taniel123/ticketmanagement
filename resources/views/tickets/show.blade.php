@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">{{ $ticket->title }}</h1>
                    <div class="mt-2 flex items-center space-x-4">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $ticket->status == 'open' ? 'bg-green-100 text-green-800' : 
                               ($ticket->status == 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ ucfirst($ticket->status) }}
                        </span>
                        <span class="text-sm text-gray-500">Created {{ $ticket->created_at->diffForHumans() }}</span>
                    </div>
                </div>

                <div class="prose max-w-none">
                    <p class="text-gray-700">{{ $ticket->description }}</p>
                </div>

                <div class="mt-8 border-t pt-6">
                    <h2 class="text-lg font-semibold text-gray-900">Ticket Details</h2>
                    <dl class="mt-4 grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Priority</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($ticket->priority) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($ticket->status) }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Only show status update form for support/admin -->
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'support')
                    <div class="mt-6 border-t pt-6">
                        <h3 class="text-lg font-medium text-gray-900">Update Status</h3>
                        <form action="{{ route('tickets.update-status', $ticket) }}" method="POST" class="mt-4">
                            @csrf
                            @method('PATCH')
                            <div class="flex items-center space-x-4">
                                <select name="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                    Update Status
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                <div class="mt-6">
                    <a href="{{ route('user.dashboard') }}" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        ← Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection