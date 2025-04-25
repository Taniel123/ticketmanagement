<div id="feedback-modal-{{ $ticket->id }}"
    class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Add Feedback</h3>
                <button type="button" onclick="closeModal({{ $ticket->id }})" class="text-gray-400 hover:text-gray-500">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form
                action="{{ auth()->user()->role === 'admin'
                    ? route('admin.tickets.update', $ticket)
                    : (auth()->user()->role === 'support'
                        ? route('support.tickets.update', $ticket)
                        : route('user.tickets.update', $ticket)) }}"
                method="POST">
                @csrf
                @method('PATCH')

                <input type="hidden" name="status" id="selected-status-{{ $ticket->id }}"
                    value="{{ $ticket->status }}">

                <!-- Feedback Text Area -->
                <div class="mt-2">
                    <label for="feedback" class="block text-sm font-medium text-gray-700">
                        Your Feedback
                        <span class="text-red-500">*</span>
                    </label>
                    <textarea name="feedback" id="feedback-{{ $ticket->id }}" rows="4"
                        class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                        placeholder="Enter your feedback here..." required></textarea>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3 mt-4">
                    <button type="button" onclick="closeModal({{ $ticket->id }})"
                        class="bg-white px-4 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 border border-gray-300">
                        Cancel
                    </button>
                    <button type="submit"
                        class="bg-indigo-600 px-4 py-2 rounded-md text-sm font-medium text-white hover:bg-indigo-700">
                        Submit Feedback
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
