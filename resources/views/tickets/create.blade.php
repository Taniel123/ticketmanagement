<!-- resources/views/tickets/create.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Create a New Ticket</h2>

        <!-- Ticket creation form -->
        <form action="{{ route('tickets.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="title">Ticket Title</label>
        <input type="text" name="title" id="title" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="description">Ticket Description</label>
        <textarea name="description" id="description" class="form-control" required></textarea>
    </div>

    <!-- Priority Dropdown -->
    <div class="form-group">
        <label for="priority">Priority</label>
        <select name="priority" id="priority" class="form-control" required>
            <option value="low">Low</option>
            <option value="medium">Medium</option>
            <option value="high">High</option>
        </select>
    </div>

    <!-- Status Dropdown -->
    <div class="form-group">
        <label for="status">Status</label>
        <select name="status" id="status" class="form-control" required>
            <option value="open">Open</option>
            <option value="ongoing">Ongoing</option>
            <option value="closed">Closed</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Create Ticket</button>
</form>
    </div>
@endsection
