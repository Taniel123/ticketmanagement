<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>TMS - Ticket Management System</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">

  <!-- Navbar -->
  <nav class="bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
      <div class="text-2xl font-bold text-indigo-600">
        TMS<span class="text-green-500">.</span>
      </div>
      <div class="space-x-6 text-sm font-medium">
        <a href="#features" class="text-gray-700 hover:text-indigo-600">Features</a>
        <a href="login" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md transition">
          Sign In <i class="fas fa-sign-in-alt ml-2"></i>
        </a>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="flex items-center justify-center px-6 pt-20 pb-32">
    <div class="bg-white p-10 rounded-xl shadow-lg max-w-2xl w-full text-center">
      <h1 class="text-5xl font-extrabold text-gray-900 mb-4">
        Welcome to <span class="text-indigo-600">TMS</span><span class="text-green-500">.</span>
      </h1>
      <p class="text-gray-600 mb-8 text-sm">
        A simplified, efficient, and intuitive ticket management system to help you stay on top of support and workflow.
      </p>
      <a href="login" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white text-sm font-medium rounded-md shadow hover:bg-indigo-700 transition">
        Get Started <i class="fas fa-arrow-right ml-2"></i>
      </a>
    </div>
  </section>

<!-- Optional Features Section -->
<section id="features" class="bg-white py-16 border-t">
  <div class="max-w-6xl mx-auto px-6">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-12">Why Choose Our TMS?</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 text-center hover:shadow-md transition-all duration-300">
        <div class="inline-flex items-center justify-center h-14 w-14 rounded-full bg-indigo-100 mb-4">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
          </svg>
        </div>
        <h3 class="text-lg font-semibold mb-2">Secure Role-Based Access</h3>
        <p class="text-sm text-gray-600">Dedicated interfaces for users, support staff, and administrators with middleware-protected routes and CSRF security.</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 text-center hover:shadow-md transition-all duration-300">
        <div class="inline-flex items-center justify-center h-14 w-14 rounded-full bg-indigo-100 mb-4">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
          </svg>
        </div>
        <h3 class="text-lg font-semibold mb-2">Email Notifications</h3>
        <p class="text-sm text-gray-600">Automatic email alerts for ticket creation, updates, and status changes with verified user accounts for enhanced security.</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 text-center hover:shadow-md transition-all duration-300">
        <div class="inline-flex items-center justify-center h-14 w-14 rounded-full bg-indigo-100 mb-4">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
          </svg>
        </div>
        <h3 class="text-lg font-semibold mb-2">Intuitive Ticket Management</h3>
        <p class="text-sm text-gray-600">Create, manage, and track support tickets with a clean Tailwind CSS interface and powerful Eloquent-driven backend.</p>
      </div>
    </div>
  </div>
</section>

</body>
</html>