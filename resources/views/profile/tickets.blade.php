@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/user/profile/profile-detail.css') }}">
@endsection

@section('content')
@php
    $activeTab = request('tab', 'upcoming');
    $statusFilter = request('status', '');

    $bookings = $user->bookings()
        ->with(['event', 'ticket'])
        ->get();

    $statusPriority = function ($status) {
        switch ($status) {
            case 'completed':
            case 'confirmed':
            case 'paid':
                return 0;
            case 'pending':
                return 1;
            case 'refunded':
                return 2;
            case 'cancelled':
                return 3;
            default:
                return 4;
        }
    };

    $compareBookings = function ($a, $b) use ($activeTab, $statusPriority) {
        $statusA = $statusPriority($a->payment_status);
        $statusB = $statusPriority($b->payment_status);

        if ($statusA !== $statusB) {
            return $statusA <=> $statusB;
        }

        $timeA = ($a->event && $a->event->date) ? $a->event->date->timestamp : 0;
        $timeB = ($b->event && $b->event->date) ? $b->event->date->timestamp : 0;

        if ($activeTab === 'past') {
            return $timeB <=> $timeA;
        }

        return $timeA <=> $timeB;
    };

    $compareByDateOnly = function ($a, $b) use ($activeTab) {
        $timeA = ($a->event && $a->event->date) ? $a->event->date->timestamp : 0;
        $timeB = ($b->event && $b->event->date) ? $b->event->date->timestamp : 0;

        if ($activeTab === 'past') {
            return $timeB <=> $timeA;
        }

        return $timeA <=> $timeB;
    };

    $upcomingBookings = $bookings
        ->filter(function ($booking) {
            return $booking->event &&
                $booking->event->date &&
                $booking->event->date->gte(today()) &&
                $booking->payment_status === 'completed';
        })
        ->sort($compareByDateOnly)
        ->values();

    $pastBookings = $bookings
        ->filter(function ($booking) {
            return $booking->event &&
                $booking->event->date &&
                $booking->event->date->lt(today()) &&
                $booking->payment_status === 'completed';
        })
        ->sort($compareByDateOnly)
        ->values();

    $baseBookings = $bookings->filter(function ($booking) use ($activeTab) {
        return $booking->event &&
            $booking->event->date &&
            (
                ($activeTab === 'past' && $booking->event->date->lt(today())) ||
                ($activeTab !== 'past' && $booking->event->date->gte(today()))
            );
    });

    if ($statusFilter === 'pending') {
        $visibleBookings = $baseBookings
            ->filter(function ($booking) {
                return $booking->payment_status === 'pending';
            })
            ->sort($compareByDateOnly)
            ->values();

    } elseif ($statusFilter === 'cancelled') {
        $visibleBookings = $baseBookings
            ->filter(function ($booking) {
                return in_array($booking->payment_status, ['cancelled', 'refunded']);
            })
            ->sort($compareBookings)
            ->values();

    } elseif ($statusFilter === 'confirmed') {
        $visibleBookings = $baseBookings
            ->filter(function ($booking) {
                return in_array($booking->payment_status, ['completed', 'confirmed', 'paid']);
            })
            ->sort($compareByDateOnly)
            ->values();

    } else {
        $visibleBookings = $baseBookings
            ->sort($compareBookings)
            ->values();
    }
@endphp

<div class="profile-detail-page">
    <section class="profile-detail-section">

        <div class="profile-detail-header">
            <div>
                <div class="profile-detail-label">Tickets</div>
                <h1>My Tickets</h1>
                <p>View your upcoming, past, and payment-related tickets.</p>
            </div>

            <a href="{{ url()->previous() }}" class="profile-detail-back">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                     fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path d="M15 18l-6-6 6-6"/>
                </svg>
            </a>
        </div>

        <div class="ticket-stats">
            <div class="ticket-stat-card">
                <span>Upcoming Events</span>
                <strong>{{ $upcomingBookings->count() }}</strong>
            </div>

            <div class="ticket-stat-card">
                <span>Past Events</span>
                <strong class="purple">{{ $pastBookings->count() }}</strong>
            </div>
        </div>

        <div class="ticket-tabs">
            <a href="{{ route('profile.tickets', ['tab' => 'upcoming', 'status' => $statusFilter]) }}"
               class="ticket-tab {{ $activeTab !== 'past' ? 'active' : '' }}">
                Upcoming
            </a>

            <a href="{{ route('profile.tickets', ['tab' => 'past', 'status' => $statusFilter]) }}"
               class="ticket-tab {{ $activeTab === 'past' ? 'active' : '' }}">
                Past
            </a>
        </div>

        <div class="ticket-filter-tabs">
            <a href="{{ route('profile.tickets', ['tab' => $activeTab]) }}"
               class="ticket-filter {{ $statusFilter === '' ? 'active' : '' }}">
                All
            </a>

            <a href="{{ route('profile.tickets', ['tab' => $activeTab, 'status' => 'confirmed']) }}"
               class="ticket-filter {{ $statusFilter === 'confirmed' ? 'active' : '' }}">
                Confirmed
            </a>

            <a href="{{ route('profile.tickets', ['tab' => $activeTab, 'status' => 'pending']) }}"
               class="ticket-filter {{ $statusFilter === 'pending' ? 'active' : '' }}">
                Pending
            </a>

            <a href="{{ route('profile.tickets', ['tab' => $activeTab, 'status' => 'cancelled']) }}"
               class="ticket-filter {{ $statusFilter === 'cancelled' ? 'active' : '' }}">
                Cancelled
            </a>
        </div>

        <h2 class="ticket-section-title">
            {{ $activeTab === 'past' ? 'Past Events' : 'Upcoming Events' }}
        </h2>

        @if($visibleBookings->count() > 0)
            <div class="ticket-list">
                @foreach($visibleBookings as $booking)
                    @php
                        $event = $booking->event;
                        $ticket = $booking->ticket;
                    @endphp

                    <div class="ticket-item-card {{ $activeTab === 'past' ? 'muted' : '' }}">
                        <div class="ticket-main">
                            <div class="ticket-event-name">{{ $event->name }}</div>
                            <div class="ticket-meta">
                                {{ $event->date->format('d M Y') }} at {{ $event->time }}
                            </div>
                            <div class="ticket-meta">
                                {{ $event->venue }}
                            </div>

                            <div class="ticket-small-info">
                                Booking ID: #{{ $booking->id }} |
                                Ticket: {{ ($ticket && $booking->payment_status === 'completed') ? $ticket->ticket_code : '-' }} |
                                Seats: {{ $booking->number_of_seats }} |
                                Payment:
                                <span class="
                                    {{ in_array($booking->payment_status, ['cancelled', 'refunded']) ? 'status-cancelled' : ($booking->payment_status === 'pending' ? 'status-pending' : 'status-completed') }}">
                                    {{ ucfirst($booking->payment_status ?? 'unknown') }}
                                </span>
                            </div>
                        </div>

                        <div class="ticket-actions">
                            <a href="{{ route('bookings.show', ['booking' => $booking->id, 'from' => url()->current()]) }}"
                               class="ticket-action-btn">
                                View Booking
                            </a>

                            @if($ticket && $booking->payment_status === 'completed')
                                <a href="{{ route('tickets.qr', $ticket) }}" class="ticket-action-btn qr">
                                    View QR
                                </a>
                            @elseif(in_array($booking->payment_status, ['cancelled', 'refunded']))
                                <span class="ticket-status-pill unavailable">
                                    QR Unavailable
                                </span>
                            @else
                                <span class="ticket-status-pill neutral">
                                    No QR
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="ticket-empty">
                {{ $activeTab === 'past' ? 'No past events to show.' : 'No upcoming events. Start exploring and book your next experience!' }}
            </div>
        @endif

    </section>
</div>
@endsection