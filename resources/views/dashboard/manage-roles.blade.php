@extends('layouts.admin_app')


@section('content')
    <!-- Alert Messages -->
    <div class="space-y-4 mb-6">
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm animate-fade-in flex items-center" role="alert">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm animate-fade-in flex items-center" role="alert">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif
    </div>

    <!-- Page Header with Action Button -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-4 sm:mb-0">User Management</h1>
        <a href="{{ route('admin.users.create') }}"
            class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded flex items-center transition-colors duration-200 shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Create New User
        </a>
    </div>

    <!-- Pending Users Table -->
    <div class="bg-white shadow rounded-lg border border-gray-200 mb-8 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
            <h2 class="text-lg font-medium text-gray-800 flex items-center">
                <span class="w-1.5 h-6 bg-amber-500 rounded mr-3"></span>
                Pending Users
            </h2>
            <span class="bg-amber-100 text-amber-800 px-3 py-1.5 rounded-full text-xs font-medium flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ $pendingUsers->total() }} awaiting approval
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registered</th>
                        <th class="px-6 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($pendingUsers as $user)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-800">{{ $user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                {{ $user->created_at->format('M d, Y h:i A') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form action="{{ route('admin.approve', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded text-xs font-medium transition-colors duration-200 shadow-sm flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Approve
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach

                    @if(count($pendingUsers) === 0)
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="h-12 w-12 mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="text-sm font-medium">No pending users found</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        
        <!-- Table Footer/Pagination -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 text-xs text-gray-600 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div>
                Showing {{ $pendingUsers->firstItem() ? $pendingUsers->firstItem() : '0' }}–{{ $pendingUsers->lastItem() ? $pendingUsers->lastItem() : '0' }} of {{ $pendingUsers->total() }}
                pending user(s)
            </div>
            <div class="pagination-container">
                <div class="inline-flex items-center rounded-md shadow-sm">
                    {{-- Previous Page Link --}}
                    @if ($pendingUsers->onFirstPage())
                        <span class="px-3 py-2 text-xs text-gray-400 bg-gray-100 border border-gray-300 rounded-l-md cursor-not-allowed">Previous</span>
                    @else
                        <a href="{{ $pendingUsers->previousPageUrl() }}"
                            class="px-3 py-2 text-xs text-indigo-600 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50">Previous</a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($pendingUsers->getUrlRange(1, $pendingUsers->lastPage()) as $page => $url)
                        @if ($page == $pendingUsers->currentPage())
                            <span class="px-3 py-2 text-xs text-white bg-indigo-600 border border-indigo-600">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="px-3 py-2 text-xs text-indigo-600 bg-white border border-gray-300 hover:bg-gray-50">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($pendingUsers->hasMorePages())
                        <a href="{{ $pendingUsers->nextPageUrl() }}"
                            class="px-3 py-2 text-xs text-indigo-600 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50">Next</a>
                    @else
                        <span class="px-3 py-2 text-xs text-gray-400 bg-gray-100 border border-gray-300 rounded-r-md cursor-not-allowed">Next</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Manage Users Section -->
    <div class="bg-white shadow rounded-lg border border-gray-200 mt-8 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 w-full">
                <!-- Left Title -->
                <h2 class="text-lg font-medium text-gray-800 flex items-center">
                    <span class="w-1.5 h-6 bg-indigo-500 rounded mr-3"></span>
                    Manage Users
                </h2>

                <!-- Filters and Search -->
                <form id="filterForm" method="GET" action="{{ route('admin.manage-roles') }}"
                    class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                    <!-- Role Dropdown -->
                    <div class="relative">
                        <select name="role" onchange="this.form.submit()"
                            class="pl-10 pr-4 py-2 border border-gray-300 rounded shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500 appearance-none">
                            <option value="">All Roles</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="support" {{ request('role') == 'support' ? 'selected' : '' }}>Support</option>
                            <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                        </select>
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>

                    <!-- Search Input -->
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name..."
                            class="w-full sm:w-64 pl-10 pr-4 py-2 border border-gray-300 rounded shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                    
                    <button type="submit" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded text-sm font-medium transition-colors duration-200 shadow-sm">
                        Filter
                    </button>
                </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-800">{{ $user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                    {{ $user->role == 'admin' ? 'bg-purple-100 text-purple-800' :
                                    ($user->role == 'support' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                    <span class="h-2 w-2 mr-1.5 rounded-full
                                        {{ $user->role == 'admin' ? 'bg-purple-600' :
                                        ($user->role == 'support' ? 'bg-blue-600' : 'bg-gray-600') }}"></span>
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
    <div class="flex items-center space-x-2">
        <!-- View Details Button -->
        <button type="button"
            onclick="openUserDetailsModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', '{{ $user->role }}', '{{ $user->created_at->format('M d, Y h:i A') }}', {{ $user->is_approved ? 'true' : 'false' }})"
            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded text-xs font-medium transition-colors duration-200 shadow-sm flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
            View
        </button>

        <!-- Existing Edit Button -->
        <button type="button"
            onclick="openRoleModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', '{{ $user->role }}')"
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded text-xs font-medium transition-colors duration-200 shadow-sm flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Edit
        </button>
    </div>
</td>
                        </tr>
                    @endforeach

                    @if(count($users) === 0)
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="h-12 w-12 mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    <p class="text-sm font-medium">No users found</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Table Footer/Pagination -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 text-xs text-gray-600 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div>
                Showing {{ $users->firstItem() ? $users->firstItem() : '0' }}–{{ $users->lastItem() ? $users->lastItem() : '0' }} of {{ $users->total() }} user(s)
            </div>
            <div class="pagination-container">
                <div class="inline-flex items-center rounded-md shadow-sm">
                    {{-- Previous Page Link --}}
                    @if ($users->onFirstPage())
                        <span class="px-3 py-2 text-xs text-gray-400 bg-gray-100 border border-gray-300 rounded-l-md cursor-not-allowed">Previous</span>
                    @else
                        <a href="{{ $users->previousPageUrl() }}"
                            class="px-3 py-2 text-xs text-indigo-600 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50">Previous</a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                        @if ($page == $users->currentPage())
                            <span class="px-3 py-2 text-xs text-white bg-indigo-600 border border-indigo-600">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="px-3 py-2 text-xs text-indigo-600 bg-white border border-gray-300 hover:bg-gray-50">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($users->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}"
                            class="px-3 py-2 text-xs text-indigo-600 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50">Next</a>
                    @else
                        <span class="px-3 py-2 text-xs text-gray-400 bg-gray-100 border border-gray-300 rounded-r-md cursor-not-allowed">Next</span>
                    @endif
                </div>
            </div>
        </div>
    </div>


<!-- Modal Background -->
<div id="roleModal" class="fixed inset-0 bg-black/30 z-50 hidden items-center justify-center backdrop-blur-xs">
    <div class="bg-white rounded-lg shadow-xl p-8 w-full max-w-md">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Update User Role</h2>

        <form id="roleForm" method="POST" onsubmit="return handleRoleSubmit(event)">
            @csrf
            @method('PATCH')  <!-- Change this to PATCH instead of POST -->

            <!-- Name Field -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Name:</label>
                <p id="modalName" class="text-gray-900 mt-1 text-sm font-medium"></p>
            </div>

            <!-- Email Field -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Email:</label>
                <p id="modalEmail" class="text-gray-900 mt-1 text-sm font-medium"></p>
            </div>

            <!-- Role Dropdown -->
            <div class="mb-6">
                <label for="modalRole" class="block text-sm font-medium text-gray-700">Role:</label>
                <select name="role" id="modalRole"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="user">User</option>
                    <option value="support">Support</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end gap-4">
                <button type="button" onclick="closeRoleModal()"
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 px-6 py-2 rounded-md text-sm font-medium focus:outline-none focus:ring-2 focus:ring-gray-400 transition">
                    Cancel
                </button>
                <button type="submit"
                    class="bg-indigo-600 text-white hover:bg-indigo-700 px-6 py-2 rounded-md text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

<!-- User Details Modal -->
<div id="userDetailsModal" class="fixed inset-0 bg-black/30 z-50 hidden items-center justify-center backdrop-blur-xs">
    <div class="bg-white rounded-lg shadow-xl p-8 w-full max-w-md">
        <div class="flex justify-between items-start mb-6">
            <h2 class="text-xl font-semibold text-gray-800">User Details</h2>
            <button type="button" onclick="closeUserDetailsModal()" class="text-gray-400 hover:text-gray-500">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="space-y-4">
            <!-- User Avatar and Basic Info -->
            <div class="flex items-center space-x-4 pb-4 border-b border-gray-200">
                <div class="bg-indigo-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div>
                    <h3 id="detailsName" class="text-lg font-medium text-gray-900"></h3>
                    <p id="detailsEmail" class="text-sm text-gray-500"></p>
                </div>
            </div>

            <!-- User Details List -->
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-500">Role</span>
                    <span id="detailsRole" class="text-sm text-gray-900"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-500">Joined Date</span>
                    <span id="detailsJoined" class="text-sm text-gray-900"></span>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="mt-8 text-right">
            <button type="button" onclick="closeUserDetailsModal()"
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                Close
            </button>
        </div>
    </div>
</div>


@section('scripts')
<script>
    function openRoleModal(userId, name, email, role) {
        // Set the modal content
        document.getElementById('modalName').textContent = name;
        document.getElementById('modalEmail').textContent = email;
        document.getElementById('modalRole').value = role;

        // Set the correct form action URL
        const form = document.getElementById('roleForm');
        form.action = `/admin/users/${userId}/update-role`; // Update this to match your route

        // Show modal
        document.getElementById('roleModal').classList.remove('hidden');
        document.getElementById('roleModal').classList.add('flex');
    }

    function handleRoleSubmit(event) {
    event.preventDefault();
    const form = event.target;
    
    const formData = new FormData();
    formData.append('role', document.getElementById('modalRole').value);
    formData.append('_method', 'PATCH');
    
    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            window.location.reload(); // Changed to reload instead of redirect
        } else {
            throw new Error(data.message || 'Failed to update role');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to update role. Please try again.');
    });

    return false;
}

function openRoleModal(userId, name, email, role) {
    document.getElementById('modalName').textContent = name;
    document.getElementById('modalEmail').textContent = email;
    document.getElementById('modalRole').value = role;

    // Update form action using the route helper
    const form = document.getElementById('roleForm');
    form.action = `/admin/users/${userId}/update-role`;

    const modal = document.getElementById('roleModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

// ... existing script code ...

function openUserDetailsModal(userId, name, email, role, joinedDate, isApproved) {
    // Set modal content
    document.getElementById('detailsName').textContent = name;
    document.getElementById('detailsEmail').textContent = email;
    document.getElementById('detailsRole').textContent = role.charAt(0).toUpperCase() + role.slice(1);
    document.getElementById('detailsJoined').textContent = joinedDate;
    

    // Show modal
    const modal = document.getElementById('userDetailsModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeUserDetailsModal() {
    const modal = document.getElementById('userDetailsModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}


function closeRoleModal() {
    document.getElementById('roleModal').classList.add('hidden');
}
</script>
@endsection


@endsection