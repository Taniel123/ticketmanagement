@extends('layouts.admin')

@section('content')



    <!-- Main Content Area -->
    <div class="flex-1">
    <div class="py-10 min-h-screen">
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
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mt-6">
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
    </div>
</div>

<!-- Ticket Statistics -->
<div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-semibold text-gray-800">Ticket Statistics</h2>
        <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-3 py-1 rounded-full">Overview</span>
    </div>
    <div class="relative">
        <canvas id="ticketStatisticsChart" width="400" height="200"></canvas>
    </div>
</div>

<script>
    // Data for the chart
    const ticketData = {
        labels: ['Open Tickets', 'Ongoing Tickets', 'Closed Tickets'],
        datasets: [{
            label: 'Tickets',
            data: [{{ $openTickets }}, {{ $ongoingTickets }}, {{ $closedTickets }}],
            backgroundColor: [
                'rgba(99, 102, 241, 0.2)', // Open Tickets (Indigo)
                'rgba(16, 185, 129, 0.2)', // Ongoing Tickets (Green)
                'rgba(229, 231, 235, 0.2)' // Closed Tickets (Gray)
            ],
            borderColor: [
                'rgba(99, 102, 241, 1)', // Open Tickets (Indigo)
                'rgba(16, 185, 129, 1)', // Ongoing Tickets (Green)
                'rgba(107, 114, 128, 1)' // Closed Tickets (Gray)
            ],
            borderWidth: 2,
            borderRadius: 5, // Rounded bars
            barPercentage: 0.6 // Adjust bar width
        }]
    };

    // Configuration for the chart
    const config = {
        type: 'bar', // Change to 'pie' or 'doughnut' for other chart types
        data: ticketData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false // Hide legend for a cleaner look
                },
                tooltip: {
                    backgroundColor: 'rgba(31, 41, 55, 0.9)', // Dark tooltip background
                    titleFont: { size: 14, weight: 'bold', family: 'Inter, sans-serif' },
                    bodyFont: { size: 12, family: 'Inter, sans-serif' },
                    callbacks: {
                        label: function(context) {
                            return `${context.label}: ${context.raw}`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false // Hide vertical grid lines
                    },
                    ticks: {
                        font: {
                            size: 12,
                            family: 'Inter, sans-serif'
                        },
                        color: '#4B5563' // Gray text
                    }
                },
                y: {
                    grid: {
                        color: '#E5E7EB' // Light gray grid lines
                    },
                    ticks: {
                        beginAtZero: true,
                        font: {
                            size: 12,
                            family: 'Inter, sans-serif'
                        },
                        color: '#4B5563' // Gray text
                    }
                }
            }
        }
    };

    // Render the chart
    const ctx = document.getElementById('ticketStatisticsChart').getContext('2d');
    new Chart(ctx, config);
</script>



        </div>
    </div>
    </div>
@endsection
