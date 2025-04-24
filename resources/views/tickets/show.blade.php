@extends('layouts.user_app')

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
                {{ $ticket->status == 'open'
                    ? 'bg-green-100 text-green-800'
                    : ($ticket->status == 'in_progress'
                        ? 'bg-blue-100 text-blue-800'
                        : 'bg-gray-100 text-gray-800') }}">
                        <span class="h-1.5 w-1.5 mr-1.5 rounded-full 
                    {{ $ticket->status == 'open'
                        ? 'bg-green-600'
                        : ($ticket->status == 'in_progress'
                            ? 'bg-blue-600'
                            : 'bg-gray-600') }}"></span>
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
                            <span
                                class="inline-block h-2 w-2 rounded-full mr-2
                            {{ $ticket->priority == 'high'
                                ? 'bg-red-500'
                                : ($ticket->priority == 'medium'
                                    ? 'bg-yellow-500'
                                    : 'bg-green-500') }}"></span>
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

        <!-- Feedback History -->
        <div class="mt-6">
            <h3 class="text-lg font-medium text-gray-900">Feedback History</h3>
            <div class="mt-2 space-y-4">
                @foreach ($ticket->feedbacks()->with('user')->latest()->get() as $feedback)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium text-indigo-600">
                                    {{ $feedback->user->name }}
                                    <span class="text-gray-500 font-normal">added feedback</span>
                                </p>
                                <p class="mt-1 text-sm text-gray-700">{{ $feedback->comment }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-xs text-gray-500">
                                    {{ $feedback->created_at->diffForHumans() }}
                                </span>
                                @if ($feedback->status_change)
                                    <p class="text-xs text-gray-500 mt-1">
                                        Changed status to <span
                                            class="font-medium">{{ ucfirst($feedback->status_change) }}</span>
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Ticket Update History -->
        <div class="mt-6">
            <h3 class="text-lg font-medium text-gray-900">Update History</h3>
            <div class="mt-2">
                <p class="text-sm text-gray-500">
                    Last updated by {{ $ticket->updatedBy->name ?? 'Unknown' }}
                    {{ $ticket->updated_at->diffForHumans() }}
                </p>
                <p class="text-sm text-gray-500">
                    Created by {{ $ticket->user->name }}
                    {{ $ticket->created_at->diffForHumans() }}
                </p>
            </div>
        </div>

        <!-- Action Button -->
        <div class="flex">
            <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded shadow-sm text-sm font-medium transition-colors duration-200 inline-flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Dashboard
            </a>
        </div>
    </div>
@endsection
