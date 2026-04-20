@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/user/event-detail.css') }}?v={{ time() }}">
@endsection

@section('content')
    @php
        $isPastEvent = $event->date && $event->date->lt(today());
    @endphp

    <div class="event-detail-page">

        <div class="detail-hero">
            <img src="{{ $event->image ? asset('storage/' . $event->image) : 'https://images.unsplash.com/photo-1511578314322-379afb476865?w=1600&h=600&fit=crop' }}"
                alt="{{ $event->name }}">
            <div class="detail-hero-overlay"></div>
            <div class="detail-hero-content">
                @if($event->category)
                    <span class="detail-category">{{ $event->category->name }}</span>
                @endif
                <h1 class="detail-title">{{ $event->name }}</h1>
                <p class="detail-meta">
                    @if($event->artist)
                        {{ $event->artist }} ·
                    @endif
                    {{ $event->date->format('D, d M Y') }} · {{ $event->venue }}
                </p>
            </div>
        </div>

        <div class="detail-container">

            <div class="detail-left">
                <div class="detail-section">
                    <h3>About this event</h3>
                    <p>
                        {{ $event->description ?: 'Join us for an amazing experience with ' . ($event->artist ?? $event->organizer ?? 'our artists') . '. This is an unforgettable event that brings together passionate fans and world-class entertainment.' }}
                    </p>
                </div>

                <div class="detail-section">
                    <h3>Event Details</h3>
                    <div class="detail-info-list">
                        <div><strong>Date:</strong> {{ $event->date->format('d F Y') }}</div>
                        <div><strong>Time:</strong> {{ $event->time }}</div>
                        <div><strong>Venue:</strong> {{ $event->venue }}</div>
                        @if($event->organizer)
                            <div><strong>Organizer:</strong> {{ $event->organizer }}</div>
                        @endif
                        @if($event->artist)
                            <div><strong>Artist/Performer:</strong> {{ $event->artist }}</div>
                        @endif
                    </div>
                </div>

                <div class="detail-section">
                    <h3>Ticket Information</h3>
                    <div class="detail-info-list">
                        <div><strong>Total Seats:</strong> {{ $event->total_seats }}</div>
                        <div>
                            <strong>Available Seats:</strong>
                            <span
                                style="color: {{ $event->available_seats > 0 ? '#059669' : '#dc2626' }}; font-weight: 600;">
                                {{ $event->available_seats }}
                            </span>
                        </div>
                        <div><strong>Price per Ticket:</strong> <span
                                style="color: #059669; font-weight: 700; font-size: 16px;">RM{{ number_format($event->price, 2) }}</span>
                        </div>
                        @if($isPastEvent)
                            <div style="color: #6b7280; font-weight: 600;">This event has already ended.</div>
                        @elseif($event->status === 'sold_out')
                            <div style="color: #dc2626; font-weight: 600;">This event is SOLD OUT</div>
                        @elseif($event->available_seats < 10)
                            <div style="color: #f59e0b; font-weight: 600;">Only {{ $event->available_seats }} seats remaining!
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="detail-right">
                <div class="detail-ticket-card">
                    <div class="detail-ticket-price">RM{{ number_format($event->price, 2) }} per ticket</div>

                    @if($isPastEvent)
                        <div
                            style="background: #e5e7eb; border: 1px solid #d1d5db; border-radius: 8px; padding: 12px; color: #374151; font-weight: 600; text-align: center;">
                            This event has ended
                        </div>
                    @elseif($event->status === 'sold_out')
                        <div
                            style="background: #fee2e2; border: 1px solid #fca5a5; border-radius: 8px; padding: 12px; color: #991b1b; font-weight: 600; text-align: center;">
                            Sold Out
                        </div>
                    @else
                        <form action="{{ route('bookings.store') }}" method="POST">
                            @csrf

                            <input type="hidden" name="event_id" value="{{ $event->id }}">

                            <div style="margin-bottom: 14px;">
                                <label
                                    style="display: block; font-weight: 600; color: #1f2937; margin-bottom: 6px; font-size: 13px;">
                                    Number of Tickets
                                </label>
                                <input type="number" name="number_of_seats" value="1" min="1"
                                    max="{{ $event->available_seats }}" class="detail-ticket-input" required id="quantityInput"
                                    onchange="updateTotal()" oninput="updateTotal()">
                                <div style="font-size: 12px; color: #6b7280; margin-top: 4px;">
                                    Max {{ $event->available_seats }} tickets available
                                </div>
                            </div>

                            <div
                                style="background: #f0fdf4; border: 1px solid #86efac; border-radius: 8px; padding: 12px; margin-bottom: 14px; text-align: center;">
                                <div style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">Total Amount</div>
                                <div style="font-size: 24px; font-weight: 700; color: #059669;" id="totalDisplay">
                                    RM{{ number_format($event->price, 2) }}
                                </div>
                            </div>

                            <input type="hidden" name="total_amount" value="{{ $event->price }}" id="totalAmountInput">

                            <button type="submit" class="detail-btn-book" @if($event->available_seats <= 0) disabled @endif>
                                Book Now
                            </button>
                        </form>

                        <div class="detail-ticket-note">
                            Secure checkout · Your payment is encrypted
                        </div>
                    @endif
                </div>
            </div>

        </div>

    </div>

    <script>
        const eventPrice = {{ $event->price }};
        const maxSeats = {{ $event->available_seats }};

        function updateTotal() {
            const quantity = parseInt(document.getElementById('quantityInput')?.value) || 1;
            const total = (quantity * eventPrice).toFixed(2);
            const formattedTotal = 'RM' + parseFloat(total).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

            const totalDisplay = document.getElementById('totalDisplay');
            const totalAmountInput = document.getElementById('totalAmountInput');

            if (totalDisplay) {
                totalDisplay.textContent = formattedTotal;
            }

            if (totalAmountInput) {
                totalAmountInput.value = total;
            }
        }
    </script>

@endsection
