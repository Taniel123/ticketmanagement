@extends('layouts.app') <!-- Extending the app.blade.php layout -->

@section('content')
    <div class="login-form">
        <h2>Login</h2>

        <!-- Display Validation Errors (if any) -->
        @if ($errors->any())
            <div class="error-messages">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div>
                <label for="email">Email:</label>
                <input type="email" name="email" required placeholder="Enter your email" value="{{ old('email') }}">
            </div>

            <div>
                <label for="password">Password:</label>
                <input type="password" name="password" required placeholder="Enter your password">
            </div>

            <div>
                <button type="submit">Login</button>
            </div>
        </form>

        <div>
            <p>Don't have an account? <a href="{{ route('register') }}">Register here</a></p>
        </div>
    </div>
@endsection