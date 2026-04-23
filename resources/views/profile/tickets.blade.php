@extends('layouts.app')

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
                return $timeB <=> $timeA; // latest past first
            }

            return $timeA <=> $timeB; // nearest upcoming first
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

    <div class="account-page">
        <section class="account-section">
            <div class="account-back">
                <a href="{{ route('profile.index') }}" class="back-btn">
                    Back
                </a>
            </div>

            <div class="account-section-label">My Tickets</div>

            <div class="account-menu" style="padding: 20px;">
                <div style="max-width: 900px;">
                    <div
                        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; margin-bottom: 24px;">
                        <div style="background: #f3f4f6; padding: 15px; border-radius: 8px; text-align: center;">
                            <div style="font-size: 24px; font-weight: bold; color: #2563eb;">
                                {{ $upcomingBookings->count() }}
                            </div>
                            <div style="font-size: 12px; color: #6b7280;">Upcoming Events</div>
                        </div>
                        <div style="background: #f3f4f6; padding: 15px; border-radius: 8px; text-align: center;">
                            <div style="font-size: 24px; font-weight: bold; color: #7c3aed;">
                                {{ $pastBookings->count() }}
                            </div>
                            <div style="font-size: 12px; color: #6b7280;">Past Events</div>
                        </div>
                    </div>

                    <div style="display: flex; gap: 10px; margin-bottom: 18px; flex-wrap: wrap;">
                        <a href="{{ route('profile.tickets', ['tab' => 'upcoming', 'status' => $statusFilter]) }}"
                            style="display: inline-block; padding: 10px 16px; border-radius: 999px; text-decoration: none; font-weight: 600; background: {{ $activeTab !== 'past' ? '#2563eb' : '#e5e7eb' }}; color: {{ $activeTab !== 'past' ? 'white' : '#374151' }};">
                            Upcoming
                        </a>
                        <a href="{{ route('profile.tickets', ['tab' => 'past', 'status' => $statusFilter]) }}"
                            style="display: inline-block; padding: 10px 16px; border-radius: 999px; text-decoration: none; font-weight: 600; background: {{ $activeTab === 'past' ? '#2563eb' : '#e5e7eb' }}; color: {{ $activeTab === 'past' ? 'white' : '#374151' }};">
                            Past
                        </a>
                    </div>

                    <div style="display: flex; gap: 10px; margin-bottom: 18px; flex-wrap: wrap;">
                        <a href="{{ route('profile.tickets', ['tab' => $activeTab]) }}"
                            style="display: inline-block; padding: 8px 14px; border-radius: 999px; text-decoration: none; font-weight: 600; background: {{ $statusFilter === '' ? '#111827' : '#e5e7eb' }}; color: {{ $statusFilter === '' ? 'white' : '#374151' }};">
                            All
                        </a>
                        <a href="{{ route('profile.tickets', ['tab' => $activeTab, 'status' => 'confirmed']) }}"
                            style="display: inline-block; padding: 8px 14px; border-radius: 999px; text-decoration: none; font-weight: 600; background: {{ $statusFilter === 'confirmed' ? '#111827' : '#e5e7eb' }}; color: {{ $statusFilter === 'confirmed' ? 'white' : '#374151' }};">
                            Confirmed
                        </a>
                        <a href="{{ route('profile.tickets', ['tab' => $activeTab, 'status' => 'pending']) }}"
                            style="display: inline-block; padding: 8px 14px; border-radius: 999px; text-decoration: none; font-weight: 600; background: {{ $statusFilter === 'pending' ? '#111827' : '#e5e7eb' }}; color: {{ $statusFilter === 'pending' ? 'white' : '#374151' }};">
                            Pending
                        </a>
                        <a href="{{ route('profile.tickets', ['tab' => $activeTab, 'status' => 'cancelled']) }}"
                            style="display: inline-block; padding: 8px 14px; border-radius: 999px; text-decoration: none; font-weight: 600; background: {{ $statusFilter === 'cancelled' ? '#111827' : '#e5e7eb' }}; color: {{ $statusFilter === 'cancelled' ? 'white' : '#374151' }};">
                            Cancelled
                        </a>
                    </div>

                    <div style="margin-bottom: 12px;">
                        <h3 style="font-size: 18px; font-weight: 600; color: #1f2937; margin: 0;">
                            {{ $activeTab === 'past' ? 'Past Events' : 'Upcoming Events' }}
                        </h3>
                    </div>

                    @if($visibleBookings->count() > 0)
                        <div style="display: grid; gap: 12px;">
                            @foreach($visibleBookings as $booking)
                                @php
                                    $event = $booking->event;
                                    $ticket = $booking->ticket;
                                @endphp

                                <div
                                    style="background: {{ $activeTab === 'past' ? '#f9fafb' : 'white' }}; border: 1px solid #e5e7eb; border-radius: 8px; padding: 15px; display: grid; grid-template-columns: 1fr auto; align-items: center; gap: 15px; opacity: {{ $activeTab === 'past' ? '0.9' : '1' }};">
                                    <div>
                                        <div style="font-weight: 600; color: #1f2937; margin-bottom: 5px;">
                                            {{ $event->name }}
                                        </div>
                                        <div style="font-size: 13px; color: #6b7280; margin-bottom: 3px;">
                                            {{ $event->date->format('d M Y') }} at {{ $event->time }}
                                        </div>
                                        <div style="font-size: 13px; color: #6b7280;">
                                            {{ $event->venue }}
                                        </div>
                                        <div style="font-size: 12px; color: #6b7280; margin-top: 5px;">
                                            Booking ID: #{{ $booking->id }} | Ticket:
                                            {{ ($ticket && $booking->payment_status === 'completed') ? $ticket->ticket_code : '-' }} | Seats:
                                            {{ $booking->number_of_seats }} | Payment:
                                            <span
                                                style="color:
                                                    {{ in_array($booking->payment_status, ['cancelled', 'refunded']) ? '#b91c1c' : ($booking->payment_status === 'pending' ? '#92400e' : '#059669') }};
                                                    font-weight: 500;">
                                                {{ ucfirst($booking->payment_status ?? 'unknown') }}
                                            </span>
                                        </div>
                                    </div>

                                    <div style="display: flex; gap: 10px; justify-content: flex-end; align-items: center; flex-wrap: wrap;">
                                        <a href="{{ route('bookings.show', ['booking' => $booking->id, 'from' => url()->current()]) }}">
                                            View Booking
                                        </a>

                                        @if($ticket && $booking->payment_status === 'completed')
                                            <a href="{{ route('tickets.qr', $ticket) }}" class="act-btn"
                                                style="background: #0f766e; color: white; text-decoration: none; padding: 6px 12px; border-radius: 4px; font-size: 12px;">
                                                View QR
                                            </a>
                                        @elseif(in_array($booking->payment_status, ['cancelled', 'refunded']))
                                            <span
                                                style="display: inline-block; background: #fee2e2; color: #991b1b; padding: 6px 12px; border-radius: 4px; font-size: 12px;">
                                                QR Unavailable
                                            </span>
                                        @else
                                            <span
                                                style="display: inline-block; background: #e5e7eb; color: #6b7280; padding: 6px 12px; border-radius: 4px; font-size: 12px;">
                                                No QR
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div
                            style="background: {{ $activeTab === 'past' ? '#f3f4f6' : '#eff6ff' }}; border: 1px solid {{ $activeTab === 'past' ? '#d1d5db' : '#bfdbfe' }}; border-radius: 8px; padding: 20px; text-align: center; color: {{ $activeTab === 'past' ? '#6b7280' : '#1e40af' }};">
                            {{ $activeTab === 'past' ? 'No past events to show.' : 'No upcoming events. Start exploring and book your next experience!' }}
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </div>
@endsection