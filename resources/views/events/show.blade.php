@extends('layouts.app')

@section('content')
    <div class="event-detail-page">

        {{-- Hero Section --}}
        <div class="detail-hero">
            <img src="{{ $event->image ? asset('storage/' . $event->image) : 'https://images.unsplash.com/photo-1511578314322-379afb476865?w=1600&h=600&fit=crop' }}"
                alt="{{ $event->name }}">
            <div class="detail-hero-overlay"></div>
            <div class="detail-hero-content">
                <span class="detail-category">{{ $event->category->name ?? 'Event' }}</span>
                <h1 class="detail-title">{{ $event->name }}</h1>
                <p class="detail-meta">
                    {{ $event->organizer ?? '' }}
                    @if($event->organizer) · @endif
                    {{ \Carbon\Carbon::parse($event->date)->format('D, d M Y') }} ·
                    {{ $event->venue }}
                </p>
            </div>
        </div>

        <div class="detail-container">

            {{-- Left: About + Details --}}
            <div class="detail-left">

                <div class="detail-section">
                    <h3>About this event</h3>
                    <p>{{ $event->description ?? 'No description provided.' }}</p>
                </div>

                <div class="detail-section">
                    <h3>Event details</h3>
                    <div class="detail-info-list">
                        <div><strong>Date:</strong> {{ \Carbon\Carbon::parse($event->date)->format('d F Y') }}</div>
                        <div><strong>Time:</strong> {{ \Carbon\Carbon::parse($event->time)->format('g:i A') }}</div>
                        <div><strong>Venue:</strong> {{ $event->venue }}</div>
                        <div><strong>Category:</strong> {{ $event->category->name ?? '—' }}</div>
                        <div><strong>Organizer:</strong> {{ $event->organizer ?? '—' }}</div>
                        <div><strong>Available Seats:</strong> {{ $event->available_seats }} / {{ $event->total_seats }}
                        </div>
                    </div>
                </div>

            </div>

            {{-- Right: Booking Card --}}
            <div class="detail-right">
                <div class="detail-ticket-card">
                    <div class="detail-ticket-price">RM <span id="total-price">{{ number_format($event->price, 2) }}</span>
                    </div>

                    @auth
                        @if($event->available_seats > 0)
                            <form action="{{ route('bookings.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="event_id" value="{{ $event->id }}">

                                <label for="number_of_seats">Number of Seats</label>
                                <input id="number_of_seats" type="number" name="number_of_seats" value="1" min="1"
                                    max="{{ $event->available_seats }}" class="detail-ticket-input">

                                <button type="submit" class="detail-btn-book">Book Now</button>
                            </form>
                        @else
                            <button class="detail-btn-book" disabled>Sold Out</button>
                        @endif
                        <div class="detail-ticket-note">Secure your seat before it sells out.</div>
                    @else
                        <a href="{{ route('login') }}" class="detail-btn-book">Login to Book</a>
                        <div class="detail-ticket-note">You need to login before booking.</div>
                    @endauth

                    @can('isAdmin')
                        <hr>
                        <a href="{{ route('events.edit', $event) }}" class="btn btn-warning w-100 mt-2">Edit Event</a>
                    @endcan
                </div>
            </div>

            <script>
                const price = {{ $event->price }};
                const number_of_seatsInput = document.getElementById('number_of_seats');
                const totalPrice = document.getElementById('total-price');

                number_of_seatsInput.addEventListener('input', function () {
                    const number_of_seats = parseInt(this.value) || 1;
                    const total = price * number_of_seats;
                    totalPrice.textContent = total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                });
            </script>

        </div>
    </div>
@endsection