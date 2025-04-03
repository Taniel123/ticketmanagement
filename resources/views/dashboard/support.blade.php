<!-- resources/views/dashboard/support.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="support-dashboard">
        <h2>Support Dashboard</h2>
        <p>Welcome, {{ auth()->user()->name }}! You have support privileges.</p>
        
        <div class="support-functions">
            <ul>
                <li><a href="#">View Support Tickets</a></li>
                <li><a href="#">Manage Users' Requests</a></li>
            </ul>
        </div>
    </div>
@endsection
