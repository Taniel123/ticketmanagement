@extends('layouts.admin_app')

@section('content')

            <!-- Dashboard Header -->
            <div class="bg-white shadow-md rounded-xl p-6 border border-gray-200">
                <!-- Add this near the top of your dashboard content -->
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-semibold text-gray-800">Admin Dashboard</h1>
                    <a href="{{ route('admin.users.create') }}"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                        Create New User
                    </a>
                </div>


    <!-- Main Content Area -->
    <div class="flex-1">
        <div class="py-6 min-h-screen">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">

                <!-- Dashboard Header -->
                <div class="bg-white shadow-md rounded-xl p-4 sm:p-6 md:p-8 border border-gray-200">
                    <h1 class="text-2xl sm:text-3xl font-semibold text-gray-800">Admin Dashboard</h1>

                    @if(session('success'))
                        <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

         <!-- Stats Summary -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mt-6">
    <!-- Pending Users -->
    <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base sm:text-lg font-medium text-gray-800">Pending Users</h2>
            <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Today</span>
        </div>
        <div class="flex items-center">
            <div class="bg-indigo-100 p-3 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div class="ml-4">
                <span class="text-xl sm:text-2xl font-bold text-gray-900">{{ $pendingUsers->total() }}</span>
                <p class="text-xs text-gray-500">Awaiting approval</p>
            </div>
        </div>
    </div>

    <!-- Total Users -->
    <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base sm:text-lg font-medium text-gray-800">Total Users</h2>
            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Today</span>
        </div>
        <div class="flex items-center">
            <div class="bg-green-100 p-3 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div class="ml-4">
                <span class="text-xl sm:text-2xl font-bold text-gray-900">{{ $users->total() }}</span>
                <p class="text-xs text-gray-500">Active accounts</p>
            </div>
        </div>
    </div>

    <!-- Admins -->
    <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base sm:text-lg font-medium text-gray-800">Admins</h2>
            <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Today</span>
        </div>
        <div class="flex items-center">
            <div class="bg-purple-100 p-3 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <div class="ml-4">
                <span class="text-xl sm:text-2xl font-bold text-gray-900">{{ \App\Models\User::where('role', 'admin')->count() }}</span>
                <p class="text-xs text-gray-500">System administrators</p>
            </div>
        </div>
    </div>

    <!-- Archived Users -->
    <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base sm:text-lg font-medium text-gray-800">Archived Users</h2>
            <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Today</span>
        </div>
        <div class="flex items-center">
            <div class="bg-gray-100 p-3 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2a4 4 0 014-4h1V7a2 2 0 012-2h2a2 2 0 012 2v2h-2a2 2 0 00-2 2v1h-1a4 4 0 00-4 4v2H9z" />
                </svg>
            </div>
            <div class="ml-4">
                <span class="text-xl sm:text-2xl font-bold text-gray-900">{{ $archivedUsers->total() }}</span>
                <p class="text-xs text-gray-500">Archived accounts</p>
            </div>
        </div>
    </div>
</div>

                                <!-- Ticket Analytics -->
<!-- Ticket Analytics -->
<div class="bg-white rounded-lg shadow-lg mt-10 px-5 py-4 border border-gray-200">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Ticket Creation Analytics</h2>
            <p class="text-sm text-gray-500 mt-1">Tickets created over time</p>
        </div>
        
        <!-- View Selector Dropdown -->
        <div class="relative">
            <select id="viewSelector" class="px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="daily">Daily</option>
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
            </select>
        </div>
    </div>

    <div class="relative h-96">
        <canvas id="ticketAnalyticsChart" class="rounded-lg"></canvas>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('ticketAnalyticsChart').getContext('2d');
        const viewSelector = document.getElementById('viewSelector');
        let ticketAnalyticsChart;
        
        // Dataset definitions
        const datasets = {
            daily: {
                label: 'Daily',
                data: [
                    @foreach($dailyTickets as $d)
                        {{ $d->count }},
                    @endforeach
                ],
                borderColor: 'rgba(79, 70, 229, 1)',
                backgroundColor: 'rgba(79, 70, 229, 0.2)',
                fill: true,
                tension: 0.4
            },
            weekly: {
                label: 'Weekly',
                data: [
                    @foreach($weeklyTickets as $w)
                        {{ $w->count }},
                    @endforeach
                ],
                borderColor: 'rgba(16, 185, 129, 1)',
                backgroundColor: 'rgba(16, 185, 129, 0.2)',
                fill: true,
                tension: 0.4
            },
            monthly: {
                label: 'Monthly',
                data: [
                    @foreach($monthlyTickets as $m)
                        {{ $m->count }},
                    @endforeach
                ],
                borderColor: 'rgba(234, 179, 8, 1)',
                backgroundColor: 'rgba(234, 179, 8, 0.2)',
                fill: true,
                tension: 0.4
            }
        };
        
        // Labels for each view
        const labels = {
            daily: [
                @foreach($dailyTickets as $d)
                    '{{ \Carbon\Carbon::parse($d->date)->format('M d') }}',
                @endforeach
            ],
            weekly: [
                @foreach($weeklyTickets as $w)
                    '{{ \Carbon\Carbon::parse($w->date)->format('M d') }}',
                @endforeach
            ],
            monthly: [
                @foreach($monthlyTickets as $m)
                    '{{ \Carbon\Carbon::parse($m->date)->format('M Y') }}',
                @endforeach
            ]
        };
        
        // Function to create or update chart
        function updateChart(viewMode) {
            // Destroy existing chart if it exists
            if (ticketAnalyticsChart) {
                ticketAnalyticsChart.destroy();
            }
            
            // Create new chart with selected dataset
            ticketAnalyticsChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels[viewMode],
                    datasets: [datasets[viewMode]]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false,
                            position: 'bottom',
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        },
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    },
                    scales: {
                        x: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Date'
                            }
                        },
                        y: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Tickets'
                            },
                            beginAtZero: true
                        }
                    },
                    animation: {
                        duration: 500,
                        easing: 'easeOutQuart'
                    }
                }
            });
        }
        
        // Initialize with daily view
        updateChart('daily');
        
        // Update chart when selector changes
        viewSelector.addEventListener('change', function() {
            updateChart(this.value);
        });
    });
</script>

        
                </div>



            </div>
        </div>
    </div>
@endsection
