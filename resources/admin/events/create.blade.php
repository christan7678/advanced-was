@extends('layouts.app')

@section('content')
<div class="card form-card p-4">
    <h2 class="text-center mb-4">Admin - Create Event</h2>

    <form action="#" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Event Title</label>
            <input type="text" name="title" class="form-control" placeholder="Enter event title">
        </div>

        <div class="mb-3">
            <label class="form-label">Date</label>
            <input type="date" name="date" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Time</label>
            <input type="time" name="time" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Venue</label>
            <input type="text" name="venue" class="form-control" placeholder="Enter venue">
        </div>

        <div class="mb-3">
            <label class="form-label">Price</label>
            <input type="number" name="price" class="form-control" placeholder="Enter price">
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" rows="4" class="form-control" placeholder="Enter event description"></textarea>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-dark">Create Event</button>
        </div>
    </form>
</div>
@endsection