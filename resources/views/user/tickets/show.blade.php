@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Header -->
                    <div class="mb-6 flex justify-between items-center">
                        <h2 class="text-2xl font-bold text-gray-800">Ticket Details</h2>
                        <a href="{{ route('user.dashboard') }}" class="text-gray-600 hover:text-gray-900">
                            Back to Dashboard
                        </a>
                    </div>

                    <!-- Ticket Information -->
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                        <dl class="grid grid-cols-1 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Title</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $ticket->title }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Description</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $ticket->description }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Priority</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($ticket->priority) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="mt-1">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $ticket->status === 'open'
                                        ? 'bg-green-100 text-green-800'
                                        : ($ticket->status === 'ongoing'
                                            ? 'bg-blue-100 text-blue-800'
                                            : 'bg-gray-100 text-gray-800') }}">
                                        {{ ucfirst($ticket->status) }}
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Ticket Feedback Section -->
                    <div class="mt-6">
                        <h3 class="text-lg font-medium text-gray-900">Ticket Updates</h3>
                        @if ($ticket->feedbacks->count() > 0)
                            <div class="mt-4 space-y-4">
                                @foreach ($ticket->feedbacks as $feedback)
                                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-sm font-medium text-gray-600">
                                                {{ $feedback->user->name }} ({{ ucfirst($feedback->user->role) }})
                                            </span>
                                            <span class="text-sm text-gray-500">
                                                {{ $feedback->created_at->format('M d, Y H:i') }}
                                            </span>
                                        </div>
                                        <p class="text-gray-700">{{ $feedback->comment }}</p>
                                        <div class="mt-2">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                Status changed to: {{ ucfirst($feedback->status_change) }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="mt-4 text-gray-500">No updates yet.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
