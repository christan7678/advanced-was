@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/user/bookings.css') }}?v={{ time() }}">
@endsection

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
            !($booking->booking_status === 'cancelled' || in_array($booking->payment_status, ['cancelled', 'refunded'])) &&
            !($booking->payment_status === 'pending' && $booking->expires_at && $booking->expires_at->lte(now()))
        )
        ->sortBy(fn($booking) => $booking->event->date)
        ->values();

    $pendingBookings = $upcomingBookings
        ->filter(fn($booking) =>
               
            $booking->payment_status === 'pending' &&
            $booking->expires_at &&
            $booking->expires_at->gt(now())
         &&
                $booking->expires_at &&
                $booking->expires_at->gt(now())
            )
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

<div class="bookings-page">
    <section class="bookings-section">

        <div class="bookings-header">
            <div>
                <div class="bookings-label">My Bookings</div>
                <h1>Booking History</h1>
                <p>Manage your upcoming, past, and cancelled bookings.</p>
            </div>

            <a href="{{ route('bookings.create') }}" class="bookings-primary-btn">
                Book Now
            </a>
        </div>

        <div class="bookings-tabs">
            <a href="{{ route('bookings.index', ['tab' => 'upcoming']) }}"
               class="bookings-tab {{ $activeTab === 'upcoming' ? 'active' : '' }}">
                Upcoming
            </a>

            <a href="{{ route('bookings.index', ['tab' => 'past']) }}"
               class="bookings-tab {{ $activeTab === 'past' ? 'active' : '' }}">
                Past
            </a>

            <a href="{{ route('bookings.index', ['tab' => 'cancelled']) }}"
               class="bookings-tab {{ $activeTab === 'cancelled' ? 'active' : '' }}">
                Cancelled
            </a>
        </div>

        @if($activeTab === 'upcoming')

            <div class="bookings-group">
                <div class="bookings-group-header">
                    <div>
                        <span class="bookings-group-label warning">Action Required</span>
                        <h2>Pending Payment</h2>
                    </div>
                    <strong>{{ $pendingBookings->count() }}</strong>
                </div>

                @if($pendingBookings->count() > 0)
                    <div class="bookings-list">
                        @foreach($pendingBookings as $booking)
                            @php $event = $booking->event; @endphp

                            <div class="bookings-card">
                                <div class="bookings-card-main">
                                    <h3>{{ $event->name ?? 'Event Unavailable' }}</h3>

                                    <p>
                                        {{ $event && $event->date ? $event->date->format('d M Y') : 'Date Unavailable' }}
                                        @if($event && $event->time)
                                            at {{ $event->time }}
                                        @endif
                                        · {{ $event->venue ?? 'Venue Unavailable' }}
                                    </p>

                                    <div class="bookings-info-row">
                                        <span>Code: {{ $booking->booking_code ?? 'N/A' }}</span>
                                        <span>Seats: {{ $booking->number_of_seats }}</span>
                                        <span>RM{{ number_format((float) $booking->total_amount, 2) }}</span>
                                    </div>

                                    <span class="bookings-status pending">Pending</span>
                                </div>

                                <div class="bookings-actions">
                                    <a href="{{ route('payment.show', $booking) }}" class="bookings-btn primary">
                                        Pay Now
                                    </a>

                                    <form action="{{ route('bookings.destroy', $booking) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="bookings-btn danger"
                                                onclick="return confirm('Cancel this booking?')">
                                            Cancel
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bookings-empty warning">No pending bookings.</div>
                @endif
            </div>

            <div class="bookings-group">
                <div class="bookings-group-header">
                    <div>
                        <span class="bookings-group-label success">Ready to Attend</span>
                        <h2>Confirmed Bookings</h2>
                    </div>
                    <strong>{{ $confirmedBookings->count() }}</strong>
                </div>

                @if($confirmedBookings->count() > 0)
                    <div class="bookings-list">
                        @foreach($confirmedBookings as $booking)
                            @php $event = $booking->event; @endphp

                            <div class="bookings-card">
                                <div class="bookings-card-main">
                                    <h3>{{ $event->name ?? 'Event Unavailable' }}</h3>

                                    <p>
                                        {{ $event && $event->date ? $event->date->format('d M Y') : 'Date Unavailable' }}
                                        @if($event && $event->time)
                                            at {{ $event->time }}
                                        @endif
                                        · {{ $event->venue ?? 'Venue Unavailable' }}
                                    </p>

                                    <div class="bookings-info-row">
                                        <span>Code: {{ $booking->booking_code ?? 'N/A' }}</span>
                                        <span>Seats: {{ $booking->number_of_seats }}</span>
                                        <span>RM{{ number_format((float) $booking->total_amount, 2) }}</span>
                                    </div>

                                    <span class="bookings-status completed">
                                        {{ ucfirst($booking->payment_status) }}
                                    </span>
                                </div>

                                <div class="bookings-actions">
                                    <a href="{{ route('bookings.show', ['booking' => $booking->id, 'from' => url()->current()]) }}"
                                       class="bookings-btn primary">
                                        View Booking
                                    </a>

                                    <form action="{{ route('bookings.destroy', $booking) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="bookings-btn danger"
                                                onclick="return confirm('Cancel this booking?')">
                                            Cancel
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bookings-empty success">No confirmed bookings.</div>
                @endif
            </div>

        @else

            @if($visibleBookings->count() > 0)
                <div class="bookings-list">
                    @foreach($visibleBookings as $booking)
                        @php $event = $booking->event; @endphp

                        <div class="bookings-card {{ $activeTab === 'past' || $activeTab === 'cancelled' ? 'muted' : '' }}">
                            <div class="bookings-card-main">
                                <h3>{{ $event->name ?? 'Event Unavailable' }}</h3>

                                <p>
                                    {{ $event && $event->date ? $event->date->format('d M Y') : 'Date Unavailable' }}
                                    @if($event && $event->time)
                                        at {{ $event->time }}
                                    @endif
                                    · {{ $event->venue ?? 'Venue Unavailable' }}
                                </p>

                                <div class="bookings-info-row">
                                    <span>Code: {{ $booking->booking_code ?? 'N/A' }}</span>
                                    <span>Seats: {{ $booking->number_of_seats }}</span>
                                    <span>RM{{ number_format((float) $booking->total_amount, 2) }}</span>
                                </div>

                                <span class="bookings-status 
                                    {{ $booking->payment_status === 'cancelled' || $booking->payment_status === 'refunded'
                                        ? 'cancelled'
                                        : ($booking->payment_status === 'pending' ? 'pending' : 'completed') }}">
                                    {{ ucfirst($booking->payment_status ?? 'unknown') }}
                                </span>
                            </div>

                            <div class="bookings-actions">
                                <form action="{{ route('bookings.show', $booking) }}" method="GET">
                                    <button class="bookings-btn primary">
                                        View Booking
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bookings-empty {{ $activeTab === 'past' ? 'neutral' : 'danger' }}">
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

