@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6 flex justify-between items-center">
                        <h2 class="text-2xl font-bold text-gray-800">Edit Ticket</h2>
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-900">
                            Back to Dashboard
                        </a>
                    </div>

                    <form action="{{ route('admin.tickets.update', $ticket) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $ticket->title) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                required>
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                required>{{ old('description', $ticket->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                onchange="toggleFeedback(this.value, '{{ $ticket->status }}')" required>
                                <option value="open" {{ old('status', $ticket->status) === 'open' ? 'selected' : '' }}>
                                    Open</option>
                                <option value="ongoing"
                                    {{ old('status', $ticket->status) === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="closed" {{ old('status', $ticket->status) === 'closed' ? 'selected' : '' }}>
                                    Closed</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Priority -->
                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700">Priority</label>
                            <select name="priority" id="priority"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                required>
                                <option value="low"
                                    {{ old('priority', $ticket->priority) === 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium"
                                    {{ old('priority', $ticket->priority) === 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high"
                                    {{ old('priority', $ticket->priority) === 'high' ? 'selected' : '' }}>High</option>
                            </select>
                            @error('priority')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Feedback -->
                        <div id="feedbackSection" class="{{ old('status', $ticket->status) === 'open' ? 'hidden' : '' }}">
                            <label for="feedback" class="block text-sm font-medium text-gray-700">
                                Feedback <span class="text-red-500">*</span>
                            </label>
                            <textarea name="feedback" id="feedback" rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                {{ old('status', $ticket->status) !== 'open' ? 'required' : '' }}>{{ old('feedback') }}</textarea>
                            @error('feedback')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('admin.dashboard') }}"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                Cancel
                            </a>
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700">
                                Update Ticket
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleFeedback(newStatus, originalStatus) {
            const feedbackSection = document.getElementById('feedbackSection');
            const feedbackInput = document.getElementById('feedback');

            if (newStatus !== 'open' && newStatus !== originalStatus) {
                feedbackSection.classList.remove('hidden');
                feedbackInput.required = true;
            } else {
                feedbackSection.classList.add('hidden');
                feedbackInput.required = false;
            }
        }
    </script>
@endsection
