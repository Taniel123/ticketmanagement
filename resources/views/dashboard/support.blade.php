@extends('layouts.app')

@section('content')
    <h1>Support Dashboard</h1>
    
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Status</th>
                <th>Priority</th>
                <th>User</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tickets as $ticket)
                <tr>
                    <td>{{ $ticket->id }}</td>
                    <td>{{ $ticket->title }}</td>
                    <td>{{ $ticket->description }}</td>
                    <td>{{ $ticket->status }}</td>
                    <td>{{ $ticket->priority }}</td>
                    <td>{{ $ticket->user->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
