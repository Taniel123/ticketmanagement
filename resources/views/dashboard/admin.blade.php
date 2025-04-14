@extends('layouts.admin')

@section('content')



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

<!-- Ticket Statistics Dashboard -->
<div class="bg-white rounded-lg shadow-lg mt-5 px-5 py-3 border border-gray-200">
  <!-- Header Section with Overview -->
  <div class="mb-6">
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between pb-4 border-b border-gray-200">
      <div>
        <h2 class="text-xl font-bold text-gray-800">TICKET STATISTICS</h2>
        <p class="text-sm text-gray-500 mt-1">Overview of your support ticket activity</p>
      </div>
      <div class="mt-4 md:mt-0 bg-gray-50 p-4 rounded-lg">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Tickets</p>
        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $openTickets + $ongoingTickets + $closedTickets }}</p>
      </div>
    </div>
  </div>



  <!-- Chart Container -->
  <div class="bg-white rounded-lg p-4 border border-gray-100 shadow-sm">
    <div class="flex justify-between items-center mb-4">
      <h3 class="text-md font-semibold text-gray-700">Ticket Distribution</h3>
      <div class="text-xs text-gray-500">Last updated: {{ date('M d, Y') }}</div>
    </div>
    <div class="relative h-80">
      <canvas id="ticketStatisticsChart" class="rounded-lg"></canvas>
    </div>
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
        'rgba(79, 70, 229, 0.75)', // Open Tickets (Indigo)
        'rgba(16, 185, 129, 0.75)', // Ongoing Tickets (Green)
        'rgba(107, 114, 128, 0.75)' // Closed Tickets (Gray)
      ],
      hoverBackgroundColor: [
        'rgba(79, 70, 229, 0.9)', // Open Tickets (Indigo)
        'rgba(16, 185, 129, 0.9)', // Ongoing Tickets (Green)
        'rgba(107, 114, 128, 0.9)' // Closed Tickets (Gray)
      ],
      borderColor: [
        'rgba(79, 70, 229, 1)', // Open Tickets (Indigo)
        'rgba(16, 185, 129, 1)', // Ongoing Tickets (Green)
        'rgba(107, 114, 128, 1)' // Closed Tickets (Gray)
      ],
      borderWidth: 1,
      borderRadius: 8,
      barPercentage: 0.6
    }]
  };

  // Configuration for the chart
  const config = {
    type: 'bar',
    data: ticketData,
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: true,
          position: 'bottom',
          labels: {
            padding: 20,
            boxWidth: 12,
            usePointStyle: true,
            font: {
              size: 12,
              family: "'Inter', sans-serif",
              weight: '500'
            },
            color: '#374151'
          }
        },
        tooltip: {
          backgroundColor: 'rgba(17, 24, 39, 0.85)',
          titleFont: { size: 13, weight: '600', family: "'Inter', sans-serif" },
          bodyFont: { size: 12, family: "'Inter', sans-serif" },
          padding: 12,
          cornerRadius: 6,
          displayColors: true,
          callbacks: {
            label: function(context) {
              const percentage = Math.round((context.raw / ({{ $openTickets + $ongoingTickets + $closedTickets }}) * 100));
              return `${context.label}: ${context.raw} (${percentage}%)`;
            }
          }
        }
      },
      scales: {
        x: {
          grid: {
            display: false
          },
          ticks: {
            font: {
              size: 12,
              family: "'Inter', sans-serif"
            },
            color: '#4B5563'
          }
        },
        y: {
          beginAtZero: true,
          grid: {
            color: 'rgba(243, 244, 246, 1)',
            drawBorder: false
          },
          ticks: {
            font: {
              size: 12,
              family: "'Inter', sans-serif"
            },
            color: '#4B5563',
            precision: 0
          }
        }
      }
    }
  };

  // Render the chart
  document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('ticketStatisticsChart').getContext('2d');
    new Chart(ctx, config);
  });
</script>
</div>

        </div>
    </div>
    </div>
@endsection