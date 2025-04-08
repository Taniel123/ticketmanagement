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
      <h2 class="text-2xl font-bold text-center text-gray-800 mb-12">Why Choose TMS?</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-gray-50 p-6 rounded-lg shadow text-center hover:shadow-md transition">
          <i class="fas fa-ticket-alt text-indigo-500 text-3xl mb-4"></i>
          <h3 class="text-lg font-semibold mb-2">Easy Ticketing</h3>
          <p class="text-sm text-gray-600">Create, track, and manage tickets with an intuitive interface.</p>
        </div>
        <div class="bg-gray-50 p-6 rounded-lg shadow text-center hover:shadow-md transition">
          <i class="fas fa-user-shield text-indigo-500 text-3xl mb-4"></i>
          <h3 class="text-lg font-semibold mb-2">Role-based Access</h3>
          <p class="text-sm text-gray-600">Admins, support staff, and users all get tailored experiences.</p>
        </div>
        <div class="bg-gray-50 p-6 rounded-lg shadow text-center hover:shadow-md transition">
          <i class="fas fa-chart-line text-indigo-500 text-3xl mb-4"></i>
          <h3 class="text-lg font-semibold mb-2">Performance Insights</h3>
          <p class="text-sm text-gray-600">Gain visibility into ticket statuses and response times.</p>
        </div>
      </div>
    </div>
  </section>

</body>
</html>
