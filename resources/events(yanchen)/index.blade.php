@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Events</h2>
            @can('isAdmin')
                <a href="{{ route('events.create') }}" class="btn btn-primary">+ New Event</a>
            @endcan
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Venue</th>
                    <th>Price</th>
                    <th>Seats</th>
                    <th>Category</th>
                    <th>Actions</th>
                    @can('isAdmin')
                        <th>Admin Actions</th>
                    @endcan
                </tr>
            </thead>
            <tbody>
                @forelse($events as $event)
                    <tr>
                        <td>{{ $event->id }}</td>
                        <td>{{ $event->name }}</td>
                        <td>{{ $event->date }}</td>
                        <td>{{ $event->venue }}</td>
                        <td>RM {{ number_format($event->price, 2) }}</td>
                        <td>{{ $event->available_seats }} / {{ $event->total_seats }}</td>
                        <td>{{ $event->category->name ?? '—' }}</td>
                        <td>
                            <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-info">View</a>
                        </td>
                        @can('isAdmin')
                            <td>
                                <a href="{{ route('events.edit', $event) }}" class="btn btn-sm btn-warning">Edit</a>

                                <form action="{{ route('events.destroy', $event) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Delete this event?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        @endcan
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">No events found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection