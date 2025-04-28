@extends('layouts.support_app')

@section('content')
<!-- Breadcrumb -->
<div class="flex items-center text-sm text-gray-500 mb-4 mt-4">
    <a href="{{ route('support.dashboard') }}" class="hover:text-indigo-600 transition-colors duration-200">Dashboard</a>
    <svg class="h-5 w-5 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
    </svg>
    <span>Support Tickets</span>
</div>

<!-- Stats Section -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <!-- Open Tickets -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 mr-4">
                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">Open Tickets</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $tickets->where('status', 'Open')->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Ongoing Tickets -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 mr-4">
                <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">Ongoing Tickets</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $tickets->where('status', 'Ongoing')->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Closed Tickets -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-gray-100 mr-4">
                <svg class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">Closed Tickets</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $tickets->where('status', 'Closed')->count() }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <h1 class="text-lg font-medium text-gray-800 flex items-center">
                <span class="w-1.5 h-6 bg-indigo-600 rounded mr-3"></span>
                Support Tickets
            </h1>

            <!-- Enhanced Filter Form -->
            <form id="filterForm" method="GET" action="{{ route('support.dashboard') }}"
                class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                <div class="flex items-center space-x-3">
                    <select name="status"
                        class="px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 min-w-[120px]">
                        <option value="">All Status</option>
                        <option value="Open" {{ request('status') == 'Open' ? 'selected' : '' }}>Open</option>
                        <option value="Ongoing" {{ request('status') == 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                        <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
                    </select>

                    <select name="priority"
                        class="px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 min-w-[120px]">
                        <option value="">All Priorities</option>
                        <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    </select>

                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="Search tickets..." 
                            class="pl-10 pr-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 w-full sm:w-64">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Section -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">#{{ $ticket->id }}</td>
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
                                   ($ticket->status === 'Ongoing' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                <span class="h-1.5 w-1.5 mr-1.5 rounded-full 
                                    {{ $ticket->status === 'Open' ? 'bg-green-600' : 
                                       ($ticket->status === 'Ongoing' ? 'bg-blue-600' : 'bg-gray-600') }}"></span>
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
                                    <button onclick="handleEditClick({{ json_encode($ticket) }})"
    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
    <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
    </svg>
    Edit
</button>
                                </div>
                            </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <h3 class="text-sm font-medium text-gray-900">No tickets found</h3>
                                <p class="mt-1 text-sm text-gray-500">No tickets match your current filters.</p>
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

    
 <!-- Replace the existing modal content -->
<div id="openTicketModal" class="fixed inset-0 bg-black/30 z-50 hidden items-center justify-center backdrop-blur-sm">
    <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-xl">
        <!-- Modal Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                <span class="w-1.5 h-6 bg-indigo-600 mr-3 rounded"></span>
                Update Ticket Status
            </h2>
            <button type="button" onclick="closeTicketModal()" class="text-gray-400 hover:text-gray-500">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Ticket Details (Read-only) -->
        <div class="mb-6 space-y-4">
            <div>
            
                <p id="modalTitle" class="mt-1 text-sm text-gray-900"></p>
            </div>
            
            <div>
               
                <p id="modalDescription" class="mt-1 text-sm text-gray-900 whitespace-pre-line"></p>
            </div>
            
            <div>
                
                <p id="modalPriority" class="mt-1 text-sm text-gray-900"></p>
            </div>
        </div>

        <!-- Form for Status Update -->
        <form id="ticketForm" method="POST" class="space-y-6">
            @csrf
            @method('PATCH')
            <input type="hidden" name="ticket_id" id="modalTicketId">

            <!-- Status -->
            <div class="space-y-1">
                <label for="status" class="block text-sm font-medium text-gray-700">
                    Status <span class="text-red-500">*</span>
                </label>
                <select 
                    name="status" 
                    id="modalStatus" 
                    required
                    onchange="toggleFeedback(this.value)"
                    class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm px-3 py-2 bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="open">Open</option>
                    <option value="ongoing">Ongoing</option>
                    <option value="closed">Closed</option>
                </select>
            </div>

            <!-- Feedback -->
            <div id="feedbackSection" class="space-y-1">
                <label for="feedback" class="block text-sm font-medium text-gray-700">
                    Feedback <span id="feedbackRequired" class="text-red-500 hidden">*</span>
                </label>
                <textarea 
                    name="feedback" 
                    id="modalFeedback" 
                    rows="3"
                    class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm px-3 py-2 bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    placeholder="Add your feedback here..."></textarea>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                <button type="button" 
                    onclick="closeTicketModal()"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                    Update Status
                </button>
            </div>
        </form>
    </div>
</div>
    <!-- Keep the feedback modal include if needed -->
    @foreach ($tickets as $ticket)
        @include('components.feedback-modal', ['ticket' => $ticket])
    @endforeach
@endsection

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

     // Add this to your existing scripts section
function openTicketModal(ticket) {
    // Populate form fields
    document.getElementById('modalTitle').value = ticket.title;
    document.getElementById('modalDescription').value = ticket.description;
    document.getElementById('modalPriority').value = ticket.priority;
    document.getElementById('modalStatus').value = ticket.status;
    
    // Update form action URL
    const form = document.getElementById('ticketForm');
    form.action = `/support/tickets/${ticket.id}/update`;
    
    // Show modal
    const modal = document.getElementById('openTicketModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    // Initialize feedback visibility
    toggleFeedback(ticket.status);
}

function toggleFeedback(status) {
    const feedbackSection = document.getElementById('feedbackSection');
    const feedbackRequired = document.getElementById('feedbackRequired');
    const feedbackInput = document.getElementById('modalFeedback');
    
    if (status === 'ongoing' || status === 'closed') {
        feedbackRequired.classList.remove('hidden');
        feedbackInput.required = true;
    } else {
        feedbackRequired.classList.add('hidden');
        feedbackInput.required = false;
    }
}

function handleEditClick(ticket) {
    const modal = document.getElementById('openTicketModal');
    const form = document.getElementById('ticketForm');
    
    // Set the correct form action
    form.action = `/support/tickets/${ticket.id}/status`;
    
    // Set form fields
    document.getElementById('modalTicketId').value = ticket.id;
    document.getElementById('modalTitle').value = ticket.title;
    document.getElementById('modalDescription').value = ticket.description;
    document.getElementById('modalPriority').value = ticket.priority;
    document.getElementById('modalStatus').value = ticket.status.toLowerCase();
    
    // Show modal
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Initialize feedback visibility
    toggleFeedback(ticket.status.toLowerCase());
}

function closeTicketModal() {
    const modal = document.getElementById('openTicketModal');
    const form = document.getElementById('ticketForm');
    
    // Hide modal
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    
    // Reset form
    form.reset();
}
    </script>
@endsection