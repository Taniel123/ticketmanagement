@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-3">
     <!-- All Tickets Section -->
     <div
                class="bg-white shadow-sm rounded-lg border border-gray-200 mt-6 overflow-hidden hover:shadow-md transition-shadow duration-300">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                    <h1 class="text-xl font-medium text-gray-800 flex items-center">
                    <span class="w-1 h-8 bg-blue-500 rounded mr-2"></span>
                        Manage Tickets
                    </h1>
                    <span
                    class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-1 rounded-full inline-flex items-center">
                    <span class="h-1.5 w-1.5 bg-indigo-600 rounded-full mr-1.5"></span>
                    {{ $tickets->total() }} ticket(s)
                </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Created By</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Priority</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($tickets ?? [] as $ticket)
                                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-800">
                                                        {{ $ticket->title }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                                        {{ $ticket->user->name }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                                {{ $ticket->status === 'open' ? 'bg-green-100 text-green-800' :
                                ($ticket->status === 'ongoing' ? 'bg-blue-100 text-blue-800' :
                                    'bg-gray-100 text-gray-800') }}">
                                                            <span class="h-1.5 w-1.5 mr-1.5 rounded-full 
                                                                    {{ $ticket->status === 'open' ? 'bg-green-600' :
                                ($ticket->status === 'ongoing' ? 'bg-blue-600' :
                                    'bg-gray-600') }}"></span>
                                                            {{ ucfirst($ticket->status) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                                {{ $ticket->priority === 'High' ? 'bg-red-100 text-red-800' :
                                ($ticket->priority === 'Medium' ? 'bg-yellow-100 text-yellow-800' :
                                    'bg-green-100 text-green-800') }}">
                                                            <span class="h-1.5 w-1.5 mr-1.5 rounded-full 
                                                                    {{ $ticket->priority === 'High' ? 'bg-red-600' :
                                ($ticket->priority === 'Medium' ? 'bg-yellow-600' :
                                    'bg-green-600') }}"></span>
                                                            {{ ucfirst($ticket->priority) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap space-x-2">
                                                        <div class="flex items-center space-x-3">
                                                            <a href="{{ route('tickets.show', $ticket) }}"
                                                                class="text-indigo-600 hover:text-indigo-900">View Details</a>

                                                            <form action="{{ route('admin.tickets.update-status', $ticket) }}" method="POST"
                                                                class="inline-flex items-center space-x-2">
                                                                @csrf
                                                                @method('PATCH')
                                                                <select name="status"
                                                                    class="w-32 bg-white border border-gray-300 text-gray-700 py-1.5 px-2 rounded focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm shadow-sm">
                                                                    <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Open
                                                                    </option>
                                                                    <option value="ongoing" {{ $ticket->status === 'ongoing' ? 'selected' : '' }}>
                                                                        Ongoing</option>
                                                                    <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>
                                                                        Closed</option>
                                                                </select>
                                                                <button type="submit"
                                                                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded text-xs font-medium transition-colors duration-200 shadow-sm">
                                                                    Update
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                            @endforeach

                            @if(empty($tickets) || count($tickets) === 0)
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        <div class="flex flex-col items-center">
                                            <svg class="h-8 w-8 mb-2 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <p class="text-sm font-medium">No tickets found</p>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div
                class="px-6 py-5 bg-gray-50 border-t border-gray-200 text-xs text-gray-600 flex justify-between items-center">
                <div>
                Showing {{ $tickets->firstItem() }}–{{ $tickets->lastItem() }} of {{ $tickets->total() }} ticket(s)
                </div>
                <div class="text-right">
                    <div class="inline-flex items-center space-x-1">
                        {{-- Previous Page Link --}}
                        @if ($tickets->onFirstPage())
                            <span
                                class="px-3 py-1 text-xs text-gray-400 bg-gray-100 border border-gray-300 rounded-md cursor-not-allowed">Previous</span>
                        @else
                            <a href="{{ $tickets->previousPageUrl() }}"
                                class="px-3 py-1 text-xs text-indigo-600 border border-indigo-600 rounded-md hover:bg-indigo-50">Previous</a>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($tickets->getUrlRange(1, $tickets->lastPage()) as $page => $url)
                            @if ($page == $tickets->currentPage())
                                <span
                                    class="px-3 py-1 text-xs text-white bg-indigo-600 border border-indigo-600 rounded-md">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}"
                                    class="px-3 py-1 text-xs text-indigo-600 border border-indigo-600 rounded-md hover:bg-indigo-50">{{ $page }}</a>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($tickets->hasMorePages())
                            <a href="{{ $tickets->nextPageUrl() }}"
                                class="px-3 py-1 text-xs text-indigo-600 border border-indigo-600 rounded-md hover:bg-indigo-50">Next</a>
                        @else
                            <span
                                class="px-3 py-1 text-xs text-gray-400 bg-gray-100 border border-gray-300 rounded-md cursor-not-allowed">Next</span>
                        @endif
                    </div>
                </div>
            </div>
            </div>

</div>
@endsection