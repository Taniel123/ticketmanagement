@extends('layouts.app') <!-- Extends the main app layout -->

@section('content') <!-- Defines the content section that will be inserted into the @yield('content') of app.blade.php -->
    <div style="text-align: center; margin-top: 100px;">
        <h1>Welcome to Our Website</h1>
        <p>Click the button below to go to the login page</p>

        <a href="{{ route('login') }}">
            <button style="padding: 10px 20px; font-size: 16px;">Login</button>
        </a>
    </div>
@endsection