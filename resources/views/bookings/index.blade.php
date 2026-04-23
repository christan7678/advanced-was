@extends('layouts.app')

@section('content')
    @php
        $activeTab = request('tab', 'upcoming');

        $cancelledBookings = $bookings
            ->filter(fn($booking) =>
                $booking->booking_status === 'cancelled' ||
                in_array($booking->payment_status, ['cancelled', 'refunded'])
            )
            ->values();

        $pastBookings = $bookings
            ->filter(fn($booking) =>
                $booking->event &&
                $booking->event->date &&
                $booking->event->date->lt(today()) &&
                !($booking->booking_status === 'cancelled' || in_array($booking->payment_status, ['cancelled', 'refunded']))
            )
            ->sortByDesc(fn($booking) => $booking->event->date)
            ->values();

        $upcomingBookings = $bookings
            ->filter(fn($booking) =>
                $booking->event &&
                $booking->event->date &&
                $booking->event->date->gte(today()) &&
                !($booking->booking_status === 'cancelled' || in_array($booking->payment_status, ['cancelled', 'refunded']))
            )
            ->sortBy(fn($booking) => $booking->event->date)
            ->values();

        $pendingBookings = $upcomingBookings
            ->filter(fn($booking) => $booking->payment_status === 'pending')
            ->sortBy(fn($booking) => $booking->event->date)
            ->values();

        $confirmedBookings = $upcomingBookings
            ->filter(fn($booking) => in_array($booking->payment_status, ['completed', 'confirmed', 'paid']))
            ->sortBy(fn($booking) => $booking->event->date)
            ->values();

        if ($activeTab === 'past') {
            $visibleBookings = $pastBookings;
        } elseif ($activeTab === 'cancelled') {
            $visibleBookings = $cancelledBookings;
        } else {
            $visibleBookings = $upcomingBookings;
        }
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
                    style="display: inline-block; padding: 10px 16px; border-radius: 999px; text-decoration: none; font-weight: 600; background: {{ $activeTab === 'upcoming' ? '#2563eb' : '#e5e7eb' }}; color: {{ $activeTab === 'upcoming' ? 'white' : '#374151' }};">
                    Upcoming
                </a>

                <a href="{{ route('bookings.index', ['tab' => 'past']) }}"
                    style="display: inline-block; padding: 10px 16px; border-radius: 999px; text-decoration: none; font-weight: 600; background: {{ $activeTab === 'past' ? '#2563eb' : '#e5e7eb' }}; color: {{ $activeTab === 'past' ? 'white' : '#374151' }};">
                    Past
                </a>

                <a href="{{ route('bookings.index', ['tab' => 'cancelled']) }}"
                    style="display: inline-block; padding: 10px 16px; border-radius: 999px; text-decoration: none; font-weight: 600; background: {{ $activeTab === 'cancelled' ? '#2563eb' : '#e5e7eb' }}; color: {{ $activeTab === 'cancelled' ? 'white' : '#374151' }};">
                    Cancelled
                </a>
            </div>

            @if($activeTab === 'upcoming')

                {{-- Pending Payment Section --}}
                <div style="margin-bottom: 22px;">
                    <h4 style="margin-bottom: 12px; color: #92400e;">Pending Payment</h4>

                    @if($pendingBookings->count() > 0)
                        <div style="display: grid; gap: 12px;">
                            @foreach($pendingBookings as $booking)
                                @php $event = $booking->event; @endphp

                                <div
                                    style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 15px; display: grid; grid-template-columns: 1fr auto; align-items: center; gap: 15px;">
                                    <div>
                                        <div style="font-weight: 600; color: #1f2937; margin-bottom: 5px;">
                                            {{ $event->name ?? 'Event Unavailable' }}
                                        </div>
                                        <div style="font-size: 13px; color: #6b7280; margin-bottom: 3px;">
                                            {{ $event && $event->date ? $event->date->format('d M Y') : 'Date Unavailable' }}
                                            @if($event && $event->time)
                                                at {{ $event->time }}
                                            @endif
                                        </div>
                                        <div style="font-size: 13px; color: #6b7280;">
                                            {{ $event->venue ?? 'Venue Unavailable' }}
                                        </div>
                                        <div style="font-size: 12px; color: #6b7280; margin-top: 5px;">
                                            Booking Code: {{ $booking->booking_code ?? 'N/A' }} |
                                            Seats: {{ $booking->number_of_seats }} |
                                            Amount: RM{{ number_format((float) $booking->total_amount, 2) }} |
                                            Payment:
                                            <span style="color: #92400e; font-weight: 500;">Pending</span>
                                        </div>
                                    </div>

                                    <div style="display: flex; gap: 10px; justify-content: flex-end; align-items: center; flex-wrap: wrap;">
                                        <a href="{{ route('payment.show', $booking) }}"
                                            style="background: #16a34a; color: white; text-decoration: none; padding: 6px 12px; border-radius: 4px; font-size: 12px;">
                                            Pay Now
                                        </a>

                                        <form action="{{ route('bookings.destroy', $booking) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                style="background: #ef4444; color: white; border: none; cursor: pointer; padding: 6px 12px; border-radius: 4px; font-size: 12px;"
                                                onclick="return confirm('Cancel this booking?')">
                                                Cancel
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div
                            style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; padding: 16px; color: #92400e;">
                            No pending bookings.
                        </div>
                    @endif
                </div>

                {{-- Confirmed Section --}}
                <div>
                    <h4 style="margin-bottom: 12px; color: #065f46;">Confirmed Bookings</h4>

                    @if($confirmedBookings->count() > 0)
                        <div style="display: grid; gap: 12px;">
                            @foreach($confirmedBookings as $booking)
                                @php $event = $booking->event; @endphp

                                <div
                                    style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 15px; display: grid; grid-template-columns: 1fr auto; align-items: center; gap: 15px;">
                                    <div>
                                        <div style="font-weight: 600; color: #1f2937; margin-bottom: 5px;">
                                            {{ $event->name ?? 'Event Unavailable' }}
                                        </div>
                                        <div style="font-size: 13px; color: #6b7280; margin-bottom: 3px;">
                                            {{ $event && $event->date ? $event->date->format('d M Y') : 'Date Unavailable' }}
                                            @if($event && $event->time)
                                                at {{ $event->time }}
                                            @endif
                                        </div>
                                        <div style="font-size: 13px; color: #6b7280;">
                                            {{ $event->venue ?? 'Venue Unavailable' }}
                                        </div>
                                        <div style="font-size: 12px; color: #6b7280; margin-top: 5px;">
                                            Booking Code: {{ $booking->booking_code ?? 'N/A' }} |
                                            Seats: {{ $booking->number_of_seats }} |
                                            Amount: RM{{ number_format((float) $booking->total_amount, 2) }} |
                                            Payment:
                                            <span style="color: #059669; font-weight: 500;">
                                                {{ ucfirst($booking->payment_status) }}
                                            </span>
                                        </div>
                                    </div>

                                    <div style="display: flex; gap: 10px; justify-content: flex-end; align-items: center; flex-wrap: wrap;">
                                            <a href="{{ route('bookings.show', ['booking' => $booking->id, 'from' => url()->current()]) }}" 
                                            style="background:#2563eb; color:white; border:none; padding:6px 12px; border-radius:4px; cursor:pointer; font-size:12px;">
                                                View Booking
                                            </a>

                                        <form action="{{ route('bookings.destroy', $booking) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                style="background: #ef4444; color: white; border: none; cursor: pointer; padding: 6px 12px; border-radius: 4px; font-size: 12px;"
                                                onclick="return confirm('Cancel this booking?')">
                                                Cancel
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div
                            style="background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 8px; padding: 16px; color: #065f46;">
                            No confirmed bookings.
                        </div>
                    @endif
                </div>

            @else
                @if($visibleBookings->count() > 0)
                    <div style="display: grid; gap: 12px;">
                        @foreach($visibleBookings as $booking)
                            @php $event = $booking->event; @endphp

                            <div
                                style="background: {{ $activeTab === 'past' || $activeTab === 'cancelled' ? '#f9fafb' : 'white' }}; border: 1px solid #e5e7eb; border-radius: 8px; padding: 15px; display: grid; grid-template-columns: 1fr auto; align-items: center; gap: 15px; opacity: {{ $activeTab === 'past' || $activeTab === 'cancelled' ? '0.9' : '1' }};">
                                <div>
                                    <div style="font-weight: 600; color: #1f2937; margin-bottom: 5px;">
                                        {{ $event->name ?? 'Event Unavailable' }}
                                    </div>
                                    <div style="font-size: 13px; color: #6b7280; margin-bottom: 3px;">
                                        {{ $event && $event->date ? $event->date->format('d M Y') : 'Date Unavailable' }}
                                        @if($event && $event->time)
                                            at {{ $event->time }}
                                        @endif
                                    </div>
                                    <div style="font-size: 13px; color: #6b7280;">
                                        {{ $event->venue ?? 'Venue Unavailable' }}
                                    </div>
                                    <div style="font-size: 12px; color: #6b7280; margin-top: 5px;">
                                        Booking Code: {{ $booking->booking_code ?? 'N/A' }} |
                                        Seats: {{ $booking->number_of_seats }} |
                                        Amount: RM{{ number_format((float) $booking->total_amount, 2) }} |
                                        Payment:
                                        <span
                                            style="color:
                                                {{ $booking->payment_status === 'cancelled' || $booking->payment_status === 'refunded'
                                                    ? '#b91c1c'
                                                    : ($booking->payment_status === 'pending' ? '#92400e' : '#059669') }};
                                                font-weight: 500;">
                                            {{ ucfirst($booking->payment_status ?? 'unknown') }}
                                        </span>
                                    </div>
                                </div>

                                <div style="display: flex; gap: 10px; justify-content: flex-end; align-items: center; flex-wrap: wrap;">
                                    <form action="{{ route('bookings.show', $booking) }}" method="GET" style="display:inline;">
                                        <button
                                            style="background:#2563eb; color:white; border:none; padding:6px 12px; border-radius:4px; cursor:pointer; font-size:12px;">
                                            View Booking
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div
                        style="background:
                            {{ $activeTab === 'past' ? '#f3f4f6' : '#fef2f2' }};
                            border: 1px solid
                            {{ $activeTab === 'past' ? '#d1d5db' : '#fecaca' }};
                            border-radius: 8px; padding: 20px; text-align: center; color:
                            {{ $activeTab === 'past' ? '#6b7280' : '#b91c1c' }};">
                        @if($activeTab === 'past')
                            No past bookings to show.
                        @else
                            No cancelled bookings to show.
                        @endif
                    </div>
                @endif
            @endif
        </section>
    </div>
@endsection