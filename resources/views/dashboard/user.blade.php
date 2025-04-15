<!-- resources/views/dashboard/user.blade.php -->

@extends('layouts.user_app')

@section('content')
    <div class="py-6">
        <!-- Header Section -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
    <div class="h-8 w-2 bg-indigo-600 rounded-full mr-3"></div>
    Welcome, {{ Auth::user()->name }}
</h1>
            </div>


            <div class="p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <a href="{{ route('tickets.create') }}"
                    class="mt-4 sm:mt-0 bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded shadow-sm text-sm font-medium transition-colors duration-200 inline-flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Create New Ticket
                </a>
            </div>

        </div>





        <!-- Tickets List -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-all duration-300">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h2 class="text-lg font-medium text-gray-800 flex items-center">
                    <span class="w-1 h-5 bg-indigo-600 rounded mr-2"></span>
                    Your Tickets
                </h2>
                <span
                    class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-1 rounded-full inline-flex items-center">
                    <span class="h-1.5 w-1.5 bg-indigo-600 rounded-full mr-1.5"></span>
                    {{ $tickets->count() }} ticket(s)
                </span>
            </div>

            <div class="px-6 pt-6">
                @if($tickets->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($tickets as $ticket)
                                <div
                                    class="bg-gray border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">
                                    <div class="border-l-4 
                                                {{ $ticket->status == 'open' ? 'border-green-500' :
                            ($ticket->status == 'in_progress' ? 'border-blue-500' : 'border-gray-400') }}">
                                        <div class="p-5">
                                            <h3 class="text-lg font-medium text-gray-900">{{ $ticket->title }}</h3>
                                            <p class="mt-2 text-sm text-gray-500">{{ Str::limit($ticket->description, 80) }}</p>

                                            <div class="mt-4 flex items-center justify-between">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                            {{ $ticket->status == 'open' ? 'bg-green-100 text-green-800' :
                            ($ticket->status == 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                                    <span
                                                        class="h-1.5 w-1.5 mr-1.5 rounded-full 
                                                                {{ $ticket->status == 'open' ? 'bg-green-600' :
                            ($ticket->status == 'in_progress' ? 'bg-blue-600' : 'bg-gray-600') }}"></span>
                                                    {{ ucfirst($ticket->status) }}
                                                </span>

                                                <div class="text-xs text-gray-500">
                                                    {{ $ticket->created_at->diffForHumans() }}
                                                </div>
                                            </div>

                                            <div class="mt-4 pt-4 border-t border-gray-100">
                                                <a href="{{ route('tickets.show', $ticket) }}"
                                                    class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-indigo-600 text-sm font-medium rounded transition-colors duration-200">
                                                    View Details
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M9 5l7 7-7 7" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        @endforeach
                    </div>

              
                @else
                    <div class="py-10 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-3" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <p class="text-gray-500 text-lg font-medium">No tickets found</p>
                        <p class="text-gray-400 mt-1">Create your first ticket to get started</p>
                    </div>
                @endif
            </div>

            <div class="mt-5 p-5 bg-gray-50 border-t border-gray-200">
    <div class="flex justify-between items-center">
        <div class="text-sm text-gray-500">
            Showing {{ $tickets->firstItem() }}–{{ $tickets->lastItem() }} of {{ $tickets->total() }} ticket(s)
        </div>
        <div class="inline-flex items-center space-x-1">
            {{-- Previous Page Link --}}
            @if ($tickets->onFirstPage())
                <span
                    class="px-3 py-1 text-xs text-gray-500 cursor-not-allowed bg-gray-100 rounded-md border border-gray-300">Previous</span>
            @else
                <a href="{{ $tickets->previousPageUrl() }}"
                    class="px-3 py-1 text-xs text-indigo-600 hover:bg-indigo-100 border border-indigo-600 rounded-md">Previous</a>
            @endif

            {{-- Pagination Links --}}
            @foreach ($tickets->getUrlRange(1, $tickets->lastPage()) as $page => $url)
                @if ($page == $tickets->currentPage())
                    <span
                        class="px-3 py-1 text-xs text-white bg-indigo-600 border border-indigo-600 rounded-md">{{ $page }}</span>
                @else
                    <a href="{{ $url }}"
                        class="px-3 py-1 text-xs text-indigo-600 hover:bg-indigo-100 border border-indigo-600 rounded-md">{{ $page }}</a>
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($tickets->hasMorePages())
                <a href="{{ $tickets->nextPageUrl() }}"
                    class="px-3 py-1 text-xs text-indigo-600 hover:bg-indigo-100 border border-indigo-600 rounded-md">Next</a>
            @else
                <span
                    class="px-3 py-1 text-xs text-gray-500 cursor-not-allowed bg-gray-100 border border-gray-300 rounded-md">Next</span>
            @endif
        </div>
    </div>
</div>
            
        </div>
    </div>
@endsection