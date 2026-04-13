@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-3">
            @can('isAdmin')
                All Bookings
            @else
                My Bookings
            @endcan
        </h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    @can('isAdmin')
                    <th>User</th> @endcan
                    <th>Event</th>
                    <th>Seats</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                    <tr>
                        <td>{{ $booking->id }}</td>
                        @can('isAdmin')
                            <td>{{ $booking->user->name }}</td>
                        @endcan
                        <td>{{ $booking->event->name }}</td>
                        <td>{{ $booking->number_of_seats }}</td>
                        <td>
                            <span class="badge bg-{{ match($booking->booking_status) {
                                'upcoming' => 'primary',
                                'completed' => 'success',
                                'cancelled' => 'danger',
                                default => 'secondary'
                            } }}">
                                {{ ucfirst($booking->booking_status) }}
                            </span>
                        </td>
                        <td>{{ $booking->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('bookings.show', $booking) }}" class="btn btn-sm btn-info">View</a>
                            
                            @can('isAdmin')
                                <a href="{{ route('bookings.edit', $booking) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('bookings.destroy', $booking) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Delete this booking?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            @else
                                @if($booking->booking_status === 'cancelled')
                                    <form action="{{ route('bookings.destroy', $booking) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Delete this booking?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                @endif
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No bookings found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection