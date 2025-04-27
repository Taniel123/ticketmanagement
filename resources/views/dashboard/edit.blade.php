@extends('layouts.admin_app')

@section('content')
    <div class="py-6 bg-gray-50">
    <div class="w-full px-4 sm:px-8 lg:px-16 mx-auto">

            <!-- Header -->
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                    <span class="w-1.5 h-6 bg-indigo-600 mr-3 rounded"></span>
                    Edit Ticket #{{ $ticket->id }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Update the information below to modify this support ticket</p>
            </div>

            <!-- Card -->
            <div class="bg-white rounded-lg shadow-md">
                <!-- Card Title -->
                <div class="border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <span class="w-1.5 h-5 bg-indigo-600 mr-2 rounded"></span>
                        Ticket Details
                    </h3>
                </div>

                <form id="ticketForm" method="POST" action="{{ auth()->user()->role === 'support' ? route('support.tickets.update', $ticket) : route('tickets.update', $ticket) }}" class="p-6 space-y-6">
                    @csrf
                    @method('PATCH')

                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Ticket Title</label>
                        <input type="text" name="title" id="title" value="{{ $ticket->title }}" required
                            class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm px-3 py-2 bg-gray-50 focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm">
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Ticket Description</label>
                        <textarea name="description" id="description" rows="4" required
                            class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm px-3 py-2 bg-gray-50 focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm">{{ $ticket->description }}</textarea>
                    </div>

                    <!-- Priority & Status -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700">Priority Level</label>
                            <select name="priority" id="priority" required
                                class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm px-3 py-2 bg-gray-50 focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm">
                                <option value="low" {{ $ticket->priority === 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ $ticket->priority === 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ $ticket->priority === 'high' ? 'selected' : '' }}>High</option>
                            </select>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status" required onchange="toggleFeedback(this.value)"
                                class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm px-3 py-2 bg-gray-50 focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm">
                                <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Open</option>
                                <option value="ongoing" {{ $ticket->status === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>
                    </div>

                    <!-- Feedback -->
                    <div id="feedbackSection" class="pt-2">
                        <label for="feedback" class="block text-sm font-medium text-gray-700">
                            Feedback
                            <span id="feedbackRequired" class="text-red-500 text-xs ml-1 hidden">Required</span>
                        </label>
                        <textarea name="feedback" id="feedback" rows="3"
                            class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm px-3 py-2 bg-gray-50 focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm"
                            placeholder="Add your feedback here...">{{ $ticket->feedback ?? '' }}</textarea>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.dashboard') }}"
                            class="text-sm text-gray-600 hover:underline flex items-center">
                            ← Back to Dashboard
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            + Update Ticket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleFeedback(status) {
            const feedbackSection = document.getElementById('feedbackSection');
            const feedbackInput = document.getElementById('feedback');
            const feedbackRequired = document.getElementById('feedbackRequired');

            if (status === 'ongoing' || status === 'closed') {
                feedbackInput.required = true;
                feedbackRequired.classList.remove('hidden');
            } else {
                feedbackInput.required = false;
                feedbackRequired.classList.add('hidden');
            }   
        }

        document.addEventListener('DOMContentLoaded', function () {
            toggleFeedback(document.getElementById('status').value);
        });

        document.getElementById('ticketForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the ticket');
            });
        });
    </script>
@endsection
