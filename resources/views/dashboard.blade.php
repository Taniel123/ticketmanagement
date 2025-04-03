@extends('layouts.app') <!-- Extending the app.blade.php layout -->

@section('content')
    <div class="dashboard-container">
        <h1>Welcome to the Dashboard</h1>

        <p>Here you can manage your tickets, view reports, and much more.</p>

        <!-- Display the user's name as a welcome message -->
        <p>Welcome, {{ session('user_id') ? \App\Models\User::find(session('user_id'))->name : 'Guest' }}!</p>

        <p><a href="{{ route('logout') }}">Logout</a></p>
    </div>
@endsection
