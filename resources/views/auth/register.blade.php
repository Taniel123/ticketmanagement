@extends('layouts.app') <!-- Extending the app.blade.php layout -->

@section('content')
    <div class="login-form">
        <h2>Register</h2>

        <form action="{{ route('register') }}" method="POST">
            @csrf

            <div>
                <label for="name">Name:</label>
                <input type="text" name="name" required placeholder="Enter your name">
            </div>

            <div>
                <label for="email">Email:</label>
                <input type="email" name="email" required placeholder="Enter your email">
            </div>

            <div>
                <label for="password">Password:</label>
                <input type="password" name="password" required placeholder="Enter your password">
            </div>

            <div>
                <label for="password_confirmation">Confirm Password:</label>
                <input type="password" name="password_confirmation" required placeholder="Confirm your password">
            </div>

            <div>
                <button type="submit">Register</button>
            </div>
        </form>

        <div>
            <p>Already have an account? <a href="{{ route('login') }}">Login here</a></p>
        </div>
    </div>
@endsection
