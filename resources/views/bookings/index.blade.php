@extends('layouts.app')

@section('content')
    @php
        $activeTab = request('tab', 'upcoming');

        $upcomingBookings = $bookings
            ->filter(fn($booking) => $booking->event && $booking->event->date && $booking->event->date->gte(today()))
            ->sortBy(fn($booking) => $booking->event->date)
            ->values();

        $pastBookings = $bookings
            ->filter(fn($booking) => $booking->event && $booking->event->date && $booking->event->date->lt(today()))
            ->sortByDesc(fn($booking) => $booking->event->date)
            ->values();

        $cancelledBookings = $bookings->where('payment_status', 'cancelled');
        $visibleBookings = $activeTab === 'past' ? $pastBookings : $upcomingBookings;
    @endphp

    <div class="account-page">
        <section class="account-section">
            <div style="display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap;">
                <div class="account-section-label" style="margin-bottom: 8px;">My Bookings</div>
                <a href="{{ route('bookings.create') }}"
                    style="display: inline-flex; align-items: center; justify-content: center; padding: 12px 18px; border-radius: 12px; background: #2563eb; color: white; text-decoration: none; font-weight: 600;">
                    Book Now
                </a>
            </div>

            <div style="display: flex; gap: 10px; margin-bottom: 18px; flex-wrap: wrap;">
                <a href="{{ route('bookings.index', ['tab' => 'upcoming']) }}"
                    style="display: inline-block; padding: 10px 16px; border-radius: 999px; text-decoration: none; font-weight: 600; background: {{ $activeTab !== 'past' ? '#2563eb' : '#e5e7eb' }}; color: {{ $activeTab !== 'past' ? 'white' : '#374151' }};">
                    Upcoming
                </a>
                <a href="{{ route('bookings.index', ['tab' => 'past']) }}"
                    style="display: inline-block; padding: 10px 16px; border-radius: 999px; text-decoration: none; font-weight: 600; background: {{ $activeTab === 'past' ? '#2563eb' : '#e5e7eb' }}; color: {{ $activeTab === 'past' ? 'white' : '#374151' }};">
                    Past
                </a>
            </div>

            @if($visibleBookings->count() > 0)
                <div style="display: grid; gap: 12px;">
                    @foreach($visibleBookings as $booking)
                        @php
                            $event = $booking->event;
                            $isPastBooking = $event && $event->date && $event->date->lt(today());
                        @endphp

                        <div
                            style="background: {{ $activeTab === 'past' ? '#f9fafb' : 'white' }}; border: 1px solid #e5e7eb; border-radius: 8px; padding: 15px; display: grid; grid-template-columns: 1fr auto; align-items: center; gap: 15px; opacity: {{ $activeTab === 'past' ? '0.9' : '1' }};">
                            <div>
                                <div style="font-weight: 600; color: #1f2937; margin-bottom: 5px;">{{ $event->name }}</div>
                                <div style="font-size: 13px; color: #6b7280; margin-bottom: 3px;">
                                    {{ $event->date->format('d M Y') }} at {{ $event->time }}
                                </div>
                                <div style="font-size: 13px; color: #6b7280;">{{ $event->venue }}</div>
                                <div style="font-size: 12px; color: #6b7280; margin-top: 5px;">
                                    Booking Code: {{ $booking->booking_code ?? 'N/A' }} | Seats: {{ $booking->number_of_seats }}
                                    | Amount: RM{{ number_format((float) $booking->total_amount, 2) }} | Payment:
                                    <span
                                        style="color: {{ $booking->payment_status === 'cancelled' ? '#b91c1c' : ($booking->payment_status === 'pending' ? '#92400e' : '#059669') }}; font-weight: 500;">
                                        {{ ucfirst($booking->payment_status ?? 'unknown') }}
                                    </span>
                                </div>
                            </div>

                            <div style="display: flex; gap: 10px; justify-content: flex-end; align-items: center; flex-wrap: wrap;">
                                <a href="{{ route('bookings.show', $booking) }}" class="act-btn act-view">
                                    View Booking
                                </a>

                                @if(!$isPastBooking && $booking->payment_status !== 'cancelled')
                                    <form action="{{ route('bookings.destroy', $booking) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            style="background: #ef4444; color: white; border: none; cursor: pointer; padding: 6px 12px; border-radius: 4px; font-size: 12px;"
                                            onclick="return confirm('Cancel this booking?')">Cancel</button>
                                    </form>
                                @elseif($isPastBooking)
                                    <span
                                        style="display: inline-block; background: #e5e7eb; color: #6b7280; padding: 6px 12px; border-radius: 4px; font-size: 12px;">
                                        Past Booking
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div
                    style="background: {{ $activeTab === 'past' ? '#f3f4f6' : '#eff6ff' }}; border: 1px solid {{ $activeTab === 'past' ? '#d1d5db' : '#bfdbfe' }}; border-radius: 8px; padding: 20px; text-align: center; color: {{ $activeTab === 'past' ? '#6b7280' : '#1e40af' }};">
                    {{ $activeTab === 'past' ? 'No past bookings to show.' : 'No upcoming bookings yet. Start exploring and reserve your next event!' }}
                </div>
            @endif
        </section>
    </div>
@endsection