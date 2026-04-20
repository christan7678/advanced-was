@extends('layouts.app')

@section('content')
    @php
        $isPastBooking = $booking->event && $booking->event->date && $booking->event->date->lt(today());
    @endphp

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Booking #{{ $booking->id }}</h2>
            <div class="d-flex gap-2">
                @if($booking->ticket && $booking->payment_status !== 'cancelled')
                    <a href="{{ route('tickets.qr', $booking->ticket) }}" class="btn btn-primary">View QR</a>
                @endif
                <a href="{{ route('bookings.index') }}" class="btn btn-secondary">Back</a>
            </div>
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
                <th>Booking Code</th>
                <td>{{ $booking->booking_code ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Ticket Code</th>
                <td>{{ optional($booking->ticket)->ticket_code ?? 'Not available' }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    <span class="badge bg-{{ match ($booking->payment_status) {
        'pending' => 'warning',
        'completed' => 'success',
        'cancelled' => 'danger',
        default => 'secondary'
    } }}">
                        {{ ucfirst($booking->payment_status ?? 'unknown') }}
                    </span>
                </td>
            </tr>
            <tr>
                <th>Booked On</th>
                <td>{{ $booking->created_at->format('d M Y, g:i A') }}</td>
            </tr>
        </table>

        @if(!$isPastBooking && $booking->payment_status !== 'cancelled')
            <form action="{{ route('bookings.destroy', $booking) }}" method="POST" class="d-inline"
                onsubmit="return confirm('Cancel this booking?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger">Cancel Booking</button>
            </form>
        @elseif($isPastBooking)
            <span class="badge bg-secondary">Past bookings cannot be cancelled</span>
        @endif
    </div>
@endsection