<!-- resources/views/dashboard/user.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="user-dashboard">
        <h2>User Dashboard</h2>
        <p>Welcome, {{ auth()->user()->name }}! You have user privileges.</p>
        
        <div class="user-functions">
            <ul>
                <li><a href="#">View Your Profile</a></li>
                <li><a href="#">View Your Orders</a></li>
            </ul>
        </div>
    </div>
@endsection
