@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Edit Booking #{{ $booking->id }}</h2>

        <form action="{{ route('bookings.update', $booking) }}" method="POST">
            @csrf
            @method('PUT')

            <table class="table table-bordered mb-3">
                <tr>
                    <th>User</th>
                    <td>{{ $booking->user->name }}</td>
                </tr>
                <tr>
                    <th>Event</th>
                    <td>{{ $booking->event->name }}</td>
                </tr>
                <tr>
                    <th>Seats</th>
                    <td>{{ $booking->number_of_seats }}</td>
                </tr>
            </table>

            <div class="mb-3">
                <label class="form-label">Booking Status</label>
                <select name="booking_status" class="form-select @error('booking_status') is-invalid @enderror">
                    <option value="upcoming" {{ $booking->booking_status === 'upcoming' ? 'selected' : '' }}>Upcoming
                    </option>
                    <option value="completed" {{ $booking->booking_status === 'completed' ? 'selected' : '' }}>Completed
                    </option>
                    <option value="cancelled" {{ $booking->booking_status === 'cancelled' ? 'selected' : '' }}>Cancelled
                    </option>
                </select>
                @error('booking_status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-warning">Update Booking</button>
            <a href="{{ route('bookings.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection