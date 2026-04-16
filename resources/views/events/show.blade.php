@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/event-detail.css') }}?v={{ time() }}">
@endsection

@section('content')

<div class="event-detail-page">

    <div class="detail-hero">
        <img src="https://images.unsplash.com/photo-1511578314322-379afb476865?w=1600&h=600&fit=crop" alt="Music Festival 2026">

        <div class="detail-hero-overlay"></div>

        <div class="detail-hero-content">
            <span class="detail-category">Concert</span>
            <h1 class="detail-title">Music Festival 2026</h1>
            <p class="detail-meta">Coldplay · Wed, 29 Apr 2026 · Kuala Lumpur</p>
        </div>
    </div>

    <div class="detail-container">
        <div class="detail-left">

            <div class="detail-section">
                <h3>About this event</h3>
                <p>
                    Experience an unforgettable night with Coldplay at Music Festival 2026.
                    Enjoy live performances, immersive lighting, and an energetic crowd in Kuala Lumpur.
                </p>
            </div>

            <div class="detail-section">
                <h3>Event details</h3>

                <div class="detail-info-list">
                    <div><strong>Date:</strong> 29 April 2026</div>
                    <div><strong>Time:</strong> 8:00 PM</div>
                    <div><strong>Venue:</strong> Kuala Lumpur Stadium</div>
                </div>
            </div>

        </div>

        <div class="detail-right">
            <div class="detail-ticket-card">
                <div class="detail-ticket-price">RM288 – RM888</div>

                <form action="{{ route('bookings.store') }}" method="POST">
                    @csrf

                    <label for="quantity">Quantity</label>
                    <input id="quantity" type="number" value="1" min="1" class="detail-ticket-input" name="quantity">

                    <button type="submit" class="detail-btn-book">Book Now</button>
                </form>

                <div class="detail-ticket-note">Secure your seat before it sells out.</div>
            </div>
        </div>
    </div>

</div>

@endsection