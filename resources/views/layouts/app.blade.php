<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticketing Management</title>
    <!-- Add any CSS files here -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>

    <div class="container">
        <!-- Navigation -->
        <nav>
            <ul>
                <!-- Conditional navigation based on authentication -->
                @if(Session::has('user_id')) <!-- Check if the user is logged in -->
                    <li><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit">Logout</button>
                        </form>
                    </li>
                @else
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('register') }}">Register</a></li>
                @endif
            </ul>
        </nav>

        <!-- Main Content -->
        @yield('content') <!-- This is where specific page content like login will be injected -->
    </div>

    <!-- Add any JavaScript files here -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
