@extends('layouts.admin_app')

@section('content')
    <!-- Breadcrumb -->
    <div class="flex items-center text-sm text-gray-500 mb-4 mt-4">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition-colors duration-200">Dashboard</a>
        <svg class="h-5 w-5 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
        </svg>
        <span>Manage Tickets</span>
    </div>

    

   <!-- Tickets Management Section -->
<div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-300">
    <!-- Header Section -->
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
        <!-- Title and Create Button Row -->
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-xl font-semibold text-gray-800 flex items-center">
                <span class="w-1.5 h-6 bg-indigo-600 rounded mr-3"></span>
                Manage Tickets
            </h1>

            <a href="{{ route('admin.tickets.create') }}" 
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Create Ticket
            </a>
        </div>

        <!-- Filters and Search Row -->
        <form id="filterForm" method="GET" action="{{ route('admin.manage-tickets') }}"
            class="flex flex-wrap items-center gap-4">
            <div class="flex items-center space-x-4 flex-1">
                <!-- Status Dropdown -->
                <div class="min-w-[150px]">
                    <label for="status" class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                    <select id="status" name="status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Status</option>
                        <option value="Open" {{ request('status') == 'Open' ? 'selected' : '' }}>Open</option>
                        <option value="Ongoing" {{ request('status') == 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                        <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>

                <!-- Priority Dropdown -->
                <div class="min-w-[150px]">
                    <label for="priority" class="block text-xs font-medium text-gray-500 mb-1">Priority</label>
                    <select id="priority" name="priority"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Priorities</option>
                        <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    </select>
                </div>
            </div>
        
            <!-- Search Box -->
            <div class="relative flex-1 max-w-md">
                <label for="search" class="block text-xs font-medium text-gray-500 mb-1">Search</label>
                <div class="relative">
                    <input type="text" id="search" name="search" value="{{ request('search') }}" 
                        placeholder="Search by ticket title or description..." 
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </form>
    </div>

        <!-- Table Section -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($tickets as $ticket)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $ticket->title }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                        <span class="text-xs font-medium text-indigo-600">
                                            {{ strtoupper(substr($ticket->user->name, 0, 2)) }}
                                        </span>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $ticket->user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $ticket->created_at->format('M d, Y') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $ticket->status === 'Open' ? 'bg-green-100 text-green-800' : 
                                       ($ticket->status === 'Ongoing' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    <span class="h-1.5 w-1.5 mr-1.5 rounded-full 
                                        {{ $ticket->status === 'Open' ? 'bg-green-600' : 
                                           ($ticket->status === 'Ongoing' ? 'bg-yellow-600' : 'bg-red-600') }}"></span>
                                    {{ $ticket->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $ticket->priority === 'High' ? 'bg-red-100 text-red-800' : 
                                       ($ticket->priority === 'Medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                    <span class="h-1.5 w-1.5 mr-1.5 rounded-full 
                                        {{ $ticket->priority === 'high' ? 'bg-red-600' : 
                                           ($ticket->priority === 'medium' ? 'bg-yellow-600' : 'bg-green-600') }}"></span>
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-3">
                                <a href="{{ route('tickets.show', $ticket->id) }}"
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        View
                                    </a>
                                    <a href="{{ route('tickets.edit', $ticket->id) }}"
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <h3 class="text-sm font-medium text-gray-900">No tickets found</h3>
                                    <p class="mt-1 text-sm text-gray-500">Get started by creating a new ticket.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

      <!-- Table Footer/Pagination -->
      <div class="px-6 py-5 bg-gray-50 border-t border-gray-200 text-xs text-gray-600 flex justify-between items-center">
            <div>
                Showing {{ $tickets->firstItem() ?? 0 }}–{{ $tickets->lastItem() ?? 0 }} of {{ $tickets->total() }} ticket(s)
            </div>
            <div class="text-right">
                <div class="inline-flex items-center space-x-1">
                    @if ($tickets->onFirstPage())
                        <span
                            class="px-3 py-1 text-xs text-gray-400 bg-gray-100 border border-gray-300 rounded-md cursor-not-allowed">Previous</span>
                    @else
                        <a href="{{ $tickets->previousPageUrl() }}"
                            class="px-3 py-1 text-xs text-indigo-600 border border-indigo-600 rounded-md hover:bg-indigo-50">Previous</a>
                    @endif

                    @foreach ($tickets->getUrlRange(1, $tickets->lastPage()) as $page => $url)
                        @if ($page == $tickets->currentPage())
                            <span
                                class="px-3 py-1 text-xs text-white bg-indigo-600 border border-indigo-600 rounded-md">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}"
                                class="px-3 py-1 text-xs text-indigo-600 border border-indigo-600 rounded-md hover:bg-indigo-50">{{ $page }}</a>
                        @endif
                    @endforeach

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

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('filterForm');

            // Auto-submit on dropdown change
            form.querySelectorAll('select').forEach(select => {
                select.addEventListener('change', () => {
                    form.submit();
                });
            });

            // Auto-submit on Enter key in search input
            const searchInput = form.querySelector('input[name="search"]');
            if (searchInput) {
                searchInput.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter') {
                        form.submit();
                    }
                });
            }
        });

        function handleTicketUpdate(event) {
            event.preventDefault();
            const form = event.target;
            
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    status: document.getElementById('ticketStatus').value,
                    priority: document.getElementById('ticketPriority').value,
                    _method: 'PATCH'
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Redirect to admin tickets page on success
                window.location.href = '{{ route("admin.manage-tickets") }}';
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to update ticket. Please try again.');
            });

            return false;
        }

        function openTicketModal(ticketId) {
            const form = document.getElementById('ticketForm');
            form.action = `/admin/tickets/${ticketId}/update`;
            
            // Show modal
            document.getElementById('ticketModal').classList.remove('hidden');
            document.getElementById('ticketModal').classList.add('flex');
        }

        function closeTicketModal() {
            const modal = document.getElementById('openTicketModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
@endsection

@endsection