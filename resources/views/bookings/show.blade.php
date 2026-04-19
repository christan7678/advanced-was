@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Booking #{{ $booking->id }}</h2>
            <a href="{{ route('bookings.index') }}" class="btn btn-secondary">Back</a>
        </div>

        <table class="table table-bordered">
            @can('isAdmin')
                <tr>
                    <th>User</th>
                    <td>{{ $booking->user->name }} ({{ $booking->user->email }})</td>
                </tr>
            @endcan
            <tr>
                <th>Event</th>
                <td>{{ $booking->event->name }}</td>
            </tr>
            <tr>
                <th>Date</th>
                <td>{{ $booking->event->date->format('Y-m-d') }} {{ $booking->event->time }}</td>
            </tr>
            <tr>
                <th>Venue</th>
                <td>{{ $booking->event->venue }}</td>
            </tr>
            <tr>
                <th>Seats</th>
                <td>{{ $booking->number_of_seats }}</td>
            </tr>
            <tr>
                <th>Total</th>
                <td>RM {{ number_format($booking->event->price * $booking->number_of_seats, 2) }}</td>
            </tr>
            <tr>
                <th>Status</th>
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
            </tr>
            <tr>
                <th>Booked On</th>
                <td>{{ $booking->created_at->format('d M Y, g:i A') }}</td>
            </tr>
        </table>

            @can('isAdmin')
                <a href="{{ route('bookings.edit', $booking) }}" class="btn btn-warning">Edit Status</a>
            @else
                <form action="{{ route('bookings.destroy', $booking) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Delete this booking?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">Delete Booking</button>
                </form>
            @endcan
    </div>
@endsection