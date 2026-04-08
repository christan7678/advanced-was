@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/bookings.css') }}?v={{ time() }}">
@endsection

@section('content')
<div class="bookings-page">

    <div class="bookings-header">
        <h2 class="section-title">My Bookings</h2>
        <p class="section-subtitle">Manage your event tickets and reservations</p>
    </div>

    @if(isset($bookingMessage) && $bookingMessage)
        <div class="booking-alert">
            Session Message: {{ $bookingMessage }}
        </div>
    @endif

    <div class="booking-stats">
        <div class="stat-card">
            <div class="stat-label">Total bookings</div>
            <div class="stat-value">5</div>
            <div class="stat-note">All time</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Upcoming</div>
            <div class="stat-value accent">2</div>
            <div class="stat-note">Next event: 29 Apr</div>
        </div>
    </div>

    <div class="booking-tabs">
        <a href="{{ route('bookings.index') }}" class="booking-tab {{ request('status') == null ? 'active' : '' }}">All</a>

        <a href="{{ route('bookings.index', ['status' => 'upcoming']) }}"
        class="booking-tab {{ request('status') == 'upcoming' ? 'active' : '' }}">
            Upcoming
        </a>

        <a href="{{ route('bookings.index', ['status' => 'completed']) }}"
        class="booking-tab {{ request('status') == 'completed' ? 'active' : '' }}">
            Completed
        </a>

        <a href="{{ route('bookings.index', ['status' => 'cancelled']) }}"
        class="booking-tab {{ request('status') == 'cancelled' ? 'active' : '' }}">
            Cancelled
        </a>
    </div>

    @php
        $status = request('status');
    @endphp

    <div class="booking-list">

        {{-- UPCOMING --}}
        @if(!$status || $status == 'upcoming')
        <div class="booking-card">
            <div class="booking-thumb thumb-blue"></div>

            <div class="booking-main">
                <div class="booking-title">Coldplay - Music Festival 2026</div>
                <div class="booking-meta">Wed, 29 Apr 2026 · 8:00 PM · Kuala Lumpur</div>

                <div class="booking-tags">
                    <span class="booking-badge badge-category">Concert</span>
                    <span class="booking-badge badge-upcoming">Upcoming</span>
                    <span class="ticket-count">2 tickets</span>
                </div>

                <div class="booking-actions">
                    <a href="#" class="booking-btn">View Ticket</a>
                    <a href="#" class="booking-btn">Refund</a>
                </div>
            </div>

            <div class="booking-price">RM576</div>
        </div>
        @endif


        {{-- COMPLETED --}}
        @if(!$status || $status == 'completed')
        <div class="booking-card">
            <div class="booking-thumb thumb-green"></div>

            <div class="booking-main">
                <div class="booking-title">Food Carnival — Street Food Fair</div>
                <div class="booking-meta">Fri, 2 May 2026 · 5:00 PM · Johor Bahru</div>

                <div class="booking-tags">
                    <span class="booking-badge badge-category">Food & Drink</span>
                    <span class="booking-badge badge-completed">Completed</span>
                    <span class="ticket-count">3 tickets</span>
                </div>

                <div class="booking-actions">
                    <a href="#" class="booking-btn">View Receipt</a>
                </div>
            </div>

            <div class="booking-price">RM210</div>
        </div>
        @endif


        {{-- CANCELLED --}}
        @if(!$status || $status == 'cancelled')
        <div class="booking-card cancelled">
            <div class="booking-thumb thumb-gray"></div>

            <div class="booking-main">
                <div class="booking-title">One Ok Rock 2026 Concert in Malaysia</div>
                <div class="booking-meta">Wed, 29 Apr 2026 · 8:00 PM · Kuala Lumpur</div>

                <div class="booking-tags">
                    <span class="booking-badge badge-category">Concert</span>
                    <span class="booking-badge badge-cancelled">Cancelled</span>
                    <span class="ticket-count">2 tickets</span>
                </div>

                <div class="booking-actions">
                    <a href="#" class="booking-btn">View Details</a>
                </div>
            </div>

            <div class="booking-price old-price">RM350</div>
        </div>
        @endif

    </div>

</div>
@endsection