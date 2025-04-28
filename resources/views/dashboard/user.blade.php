<!-- resources/views/dashboard/user.blade.php -->

@extends('layouts.user_app')

@section('content')
<div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Dashboard Header Section -->
    <div class="mb-8 flex flex-col space-y-4 md:flex-row md:items-center md:justify-between md:space-y-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                <div class="h-8 w-1.5 bg-indigo-600 rounded-full mr-3"></div>
                Welcome, {{ Auth::user()->name }}
            </h1>
            <p class="mt-1 text-sm text-gray-500">Manage and track all your support tickets</p>
        </div>

        <div class="flex flex-col sm:flex-row gap-4">
            <a href="{{ route('tickets.create') }}" 
               class="bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-md shadow-sm text-sm font-medium transition-colors duration-200 inline-flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create New Ticket
            </a>
        </div>
    </div>

    <!-- Status Filter Tabs -->
    <div class="mb-6">
        <div class="border-b border-gray-200">
            <nav class="flex flex-wrap -mb-px space-x-1 sm:space-x-6" aria-label="Tabs">
                <a href="{{ route('user.dashboard', ['status' => 'all']) }}"
                   class="w-full sm:w-auto flex-1 sm:flex-initial inline-flex items-center justify-center px-3 py-2.5 font-medium text-sm border-b-2 
                   {{ $status === 'all' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <span>All Tickets</span>
                    <span class="ml-2 px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-700">
                        {{ $allCount }}
                    </span>
                </a>

                <a href="{{ route('user.dashboard', ['status' => 'open']) }}"
                   class="w-full sm:w-auto flex-1 sm:flex-initial inline-flex items-center justify-center px-3 py-2.5 font-medium text-sm border-b-2 
                   {{ $status === 'open' ? 'border-green-600 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <span>Open</span>
                    <span class="ml-2 px-2 py-0.5 text-xs rounded-full bg-green-100 text-green-700">
                        {{ $openCount }}
                    </span>
                </a>

                <a href="{{ route('user.dashboard', ['status' => 'ongoing']) }}"
                   class="w-full sm:w-auto flex-1 sm:flex-initial inline-flex items-center justify-center px-3 py-2.5 font-medium text-sm border-b-2 
                   {{ $status === 'ongoing' ? 'border-yellow-600 text-yellow-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <span>Ongoing</span>
                    <span class="ml-2 px-2 py-0.5 text-xs rounded-full bg-yellow-100 text-yellow-700">
                        {{ $ongoingCount }}
                    </span>
                </a>

                <a href="{{ route('user.dashboard', ['status' => 'closed']) }}"
                   class="w-full sm:w-auto flex-1 sm:flex-initial inline-flex items-center justify-center px-3 py-2.5 font-medium text-sm border-b-2 
                   {{ $status === 'closed' ? 'border-red-600 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <span>Closed</span>
                    <span class="ml-2 px-2 py-0.5 text-xs rounded-full bg-red-100 text-red-700">
                        {{ $closedCount }}
                    </span>
                </a>
            </nav>
        </div>
    </div>

    <!-- Tickets List -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h2 class="text-lg font-medium text-gray-800 flex items-center">
                <span class="w-1 h-5 bg-indigo-600 rounded-full mr-2"></span>
                Your Tickets
            </h2>
            <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-1 rounded-full inline-flex items-center">
                <span class="h-1.5 w-1.5 bg-indigo-600 rounded-full mr-1.5"></span>
                {{ $tickets->count() }} ticket(s)
            </span>
        </div>

        <div class="p-6">
            @if ($tickets->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($tickets as $ticket)
                        <div class="group bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">
                            <div class="h-2 
                                {{ $ticket->status == 'open' ? 'bg-green-500' : 
                                  ($ticket->status == 'in_progress' ? 'bg-blue-500' : 'bg-gray-400') }}">
                            </div>
                            <div class="p-5">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="text-lg font-medium text-gray-900 group-hover:text-indigo-600 transition-colors duration-200">
                                        {{ $ticket->title }}
                                    </h3>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $ticket->status == 'open' ? 'bg-green-100 text-green-800' : 
                                          ($ticket->status == 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                        <span class="h-1.5 w-1.5 mr-1.5 rounded-full 
                                            {{ $ticket->status == 'open' ? 'bg-green-600' : 
                                              ($ticket->status == 'in_progress' ? 'bg-blue-600' : 'bg-gray-600') }}">
                                        </span>
                                        {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                    </span>
                                </div>
                                
                                <p class="text-sm text-gray-500 mb-4">{{ Str::limit($ticket->description, 120) }}</p>
    
    <!-- Time and Feedback Status -->
    <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ $ticket->created_at->diffForHumans() }}
        </div>
        
        @if($ticket->feedbacks->count() > 0)
            <div class="flex items-center text-indigo-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                </svg>
                <span>{{ $ticket->feedbacks->count() }} {{ Str::plural('comment', $ticket->feedbacks->count()) }}</span>
            </div>
        @endif
    </div>

    <a href="{{ route('tickets.show', $ticket) }}" 
       class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-sm font-medium rounded-md transition-colors duration-200">
        View Details
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
    </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-10">
                    <div class="flex justify-center">
                        <div class="inline-flex items-center overflow-hidden rounded-md border border-gray-200">
                            {{-- Previous Page Link --}}
                            @if ($tickets->onFirstPage())
                                <span class="px-4 py-2 text-sm text-gray-400 bg-gray-50 cursor-not-allowed">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </span>
                            @else
                                <a href="{{ $tickets->previousPageUrl() }}" class="inline-flex items-center px-4 py-2 text-sm text-indigo-600 hover:bg-indigo-50 hover:text-indigo-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </a>
                            @endif

                            {{-- Pagination Links --}}
                            @foreach ($tickets->getUrlRange(1, $tickets->lastPage()) as $page => $url)
                                @if ($page == $tickets->currentPage())
                                    <span class="inline-flex items-center px-4 py-2 text-sm bg-indigo-600 text-white font-medium">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="inline-flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            @if ($tickets->hasMorePages())
                                <a href="{{ $tickets->nextPageUrl() }}" class="inline-flex items-center px-4 py-2 text-sm text-indigo-600 hover:bg-indigo-50 hover:text-indigo-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            @else
                                <span class="px-4 py-2 text-sm text-gray-400 bg-gray-50 cursor-not-allowed">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="py-12 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900">No tickets found</h3>
                    <p class="mt-1 text-sm text-gray-500">Create your first ticket to get started</p>
                    <div class="mt-6">
                        <a href="{{ route('tickets.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md shadow-sm transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Create New Ticket
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection