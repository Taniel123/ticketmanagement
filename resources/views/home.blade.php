<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Management System</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .bg-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-indigo-900 to-purple-700 bg-pattern">
    <!-- Navigation -->
    <nav class="bg-black/10 backdrop-blur-md border-b border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <span class="text-2xl font-bold text-white">TMS<span class="text-green-400">.</span></span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="#features" class="text-gray-300 hover:text-white px-3 py-2 text-sm font-medium">Features</a>
                    <a href="login" class="ml-4 inline-flex items-center bg-white/10 border border-transparent rounded-md px-4 py-2 text-sm font-medium text-white hover:bg-white/20 transition">
                        Sign In <i class="fas fa-sign-in-alt ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative pt-16 pb-20 flex flex-col items-center">
        <div class="text-center max-w-2xl px-6 mt-16">
            <h1 class="text-5xl md:text-6xl font-extrabold text-white tracking-tight">
                Ticket Management<br><span class="text-green-400">System</span>
            </h1>
            <p class="mt-6 text-xl text-gray-300">
                Track, manage, and resolve customer issues with ease.
            </p>
            <div class="mt-10">
                <a href="login" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-indigo-800 bg-white hover:bg-gray-100 transition">
                    Get Started <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div id="features" class="py-16 bg-black/30">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-extrabold text-white">Key Features</h2>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="feature-card bg-white/10 rounded-xl p-8 border border-white/5 shadow-lg transition duration-300 ease-in-out">
                    <div class="h-12 w-12 rounded-md bg-green-500 flex items-center justify-center">
                        <i class="fas fa-ticket-alt text-white text-xl"></i>
                    </div>
                    <h3 class="mt-4 text-xl font-bold text-white">Ticket Tracking</h3>
                </div>
                
                <!-- Feature 2 -->
                <div class="feature-card bg-white/10 rounded-xl p-8 border border-white/5 shadow-lg transition duration-300 ease-in-out">
                    <div class="h-12 w-12 rounded-md bg-yellow-500 flex items-center justify-center">
                        <i class="fas fa-bell text-white text-xl"></i>
                    </div>
                    <h3 class="mt-4 text-xl font-bold text-white">Notifications</h3>
                </div>
                
                <!-- Feature 3 -->
                <div class="feature-card bg-white/10 rounded-xl p-8 border border-white/5 shadow-lg transition duration-300 ease-in-out">
                    <div class="h-12 w-12 rounded-md bg-red-500 flex items-center justify-center">
                        <i class="fas fa-chart-line text-white text-xl"></i>
                    </div>
                    <h3 class="mt-4 text-xl font-bold text-white">Analytics</h3>
                </div>
                
                <!-- Feature 4 -->
                <div class="feature-card bg-white/10 rounded-xl p-8 border border-white/5 shadow-lg transition duration-300 ease-in-out">
                    <div class="h-12 w-12 rounded-md bg-blue-500 flex items-center justify-center">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                    <h3 class="mt-4 text-xl font-bold text-white">Team Collaboration</h3>
                </div>
                
                <!-- Feature 5 -->
                <div class="feature-card bg-white/10 rounded-xl p-8 border border-white/5 shadow-lg transition duration-300 ease-in-out">
                    <div class="h-12 w-12 rounded-md bg-purple-500 flex items-center justify-center">
                        <i class="fas fa-lock text-white text-xl"></i>
                    </div>
                    <h3 class="mt-4 text-xl font-bold text-white">Secure Access</h3>
                </div>
                
                <!-- Feature 6 -->
                <div class="feature-card bg-white/10 rounded-xl p-8 border border-white/5 shadow-lg transition duration-300 ease-in-out">
                    <div class="h-12 w-12 rounded-md bg-indigo-500 flex items-center justify-center">
                        <i class="fas fa-mobile-alt text-white text-xl"></i>
                    </div>
                    <h3 class="mt-4 text-xl font-bold text-white">Mobile-First</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="py-16">
        <div class="max-w-3xl mx-auto text-center px-6">
            <h2 class="text-3xl font-extrabold text-white">Ready to get started?</h2>
            <div class="mt-8">
                <a href="login" class="inline-flex items-center px-8 py-4 border border-transparent text-base font-medium rounded-md text-indigo-800 bg-white hover:bg-gray-100 transition shadow-lg">
                    Sign In Now <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-black/40 border-t border-white/10">
        <div class="max-w-7xl mx-auto py-8 px-6 flex justify-between items-center">
            <p class="text-base text-gray-400">&copy; 2025 TMS</p>
         
        </div>
    </footer>
</body>
</html>