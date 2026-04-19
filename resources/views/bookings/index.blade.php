@extends('layouts.app')

@section('content')
    <div class="container">
        <div style="display: grid; grid-template-columns: 1fr auto; align-items: center; margin-bottom: 20px; gap: 15px;">
            <h2 class="mb-0" style="font-size: 28px; font-weight: 700;">
                My Bookings
            </h2>
            <a href="{{ route('bookings.create') }}" class="btn btn-primary" style="padding: 10px 20px; font-weight: 600;">
                + Book Now
            </a>
        </div>

        @if(session('success'))
            <div
                style="background: #d1fae5; border: 1px solid #6ee7b7; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; color: #065f46;">
                ✓ {{ session('success') }}
            </div>
        @endif

        @if($bookings->count() > 0)
            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="background: #f3f4f6;">
                        <tr>
                            <th
                                style="padding: 12px 16px; text-align: left; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">
                                #</th>
                            @can('isAdmin')
                                <th
                                    style="padding: 12px 16px; text-align: left; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">
                                    User</th>
                            @endcan
                            <th
                                style="padding: 12px 16px; text-align: left; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">
                                Event</th>
                            <th
                                style="padding: 12px 16px; text-align: left; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">
                                Booking Code</th>
                            <th
                                style="padding: 12px 16px; text-align: left; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">
                                Seats</th>
                            <th
                                style="padding: 12px 16px; text-align: left; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">
                                Amount</th>
                            <th
                                style="padding: 12px 16px; text-align: left; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">
                                Status</th>
                            <th
                                style="padding: 12px 16px; text-align: left; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">
                                Date</th>
                            <th
                                style="padding: 12px 16px; text-align: left; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                            <tr style="border-bottom: 1px solid #e5e7eb; hover: background #f9fafb;">
                                <td style="padding: 12px 16px;">{{ $booking->id }}</td>
                                <td style="padding: 12px 16px; font-weight: 500;">{{ $booking->event->name }}</td>
                                <td style="padding: 12px 16px; font-family: monospace; color: #6b7280;">
                                    {{ $booking->booking_code ?? 'N/A' }}</td>
                                <td style="padding: 12px 16px;">{{ $booking->number_of_seats }}</td>
                                <td style="padding: 12px 16px; font-weight: 600;">
                                    {{ $booking->total_amount ? 'RM' . number_format($booking->total_amount, 2) : 'N/A' }}</td>
                                <td style="padding: 12px 16px;">
                                    <span
                                        style="display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; background: {{ $booking->payment_status === 'completed' ? '#d1fae5' : ($booking->payment_status === 'pending' ? '#fef3c7' : '#fee2e2') }}; color: {{ $booking->payment_status === 'completed' ? '#065f46' : ($booking->payment_status === 'pending' ? '#92400e' : '#7f1d1d') }};">
                                        {{ ucfirst($booking->payment_status) }}
                                    </span>
                                </td>
                                <td style="padding: 12px 16px; color: #6b7280;">{{ $booking->created_at->format('d M Y') }}</td>
                                <td style="padding: 12px 16px;">
                                    <a href="{{ route('bookings.show', $booking) }}"
                                        style="color: #2563eb; text-decoration: none; font-weight: 500; margin-right: 8px;">View</a>
                                    @if($booking->payment_status !== 'cancelled')
                                        <form action="{{ route('bookings.destroy', $booking) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                style="color: #dc2626; background: none; border: none; cursor: pointer; font-weight: 500; text-decoration: none;"
                                                onclick="return confirm('Cancel this booking?')">Cancel</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div
                style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 30px; text-align: center; color: #1e40af;">
                <div style="font-size: 40px; margin-bottom: 10px;">🎫</div>
                <div style="font-weight: 600; margin-bottom: 5px;">No Bookings Yet</div>
                <div style="font-size: 14px; margin-bottom: 15px;">You haven't booked any events. Start exploring!</div>
                <a href="{{ route('home') }}"
                    style="display: inline-block; background: #2563eb; color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: 600;">
                    Browse Events
                </a>
            </div>
        @endif
    </div>
@endsection