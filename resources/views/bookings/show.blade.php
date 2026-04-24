@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/user/booking-action.css') }}?v={{ time() }}">
@endsection

@section('content')
@php
    $isPastBooking = $booking->event && $booking->event->date && $booking->event->date->lt(today());
@endphp

<div class="booking-action-page">
    <div class="booking-action-card">

        <div class="booking-action-header">
            <div>
                <div class="booking-action-label">Booking Details</div>
                <h1>Booking #{{ $booking->id }}</h1>
                <p>Review your booking information and ticket status.</p>
            </div>

            <a href="{{ $from ?: route('bookings.index') }}" class="booking-action-back">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                     fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path d="M15 18l-6-6 6-6"/>
                </svg>
            </a>
        </div>

        <div class="booking-detail-panel">
            @can('isAdmin')
                <div class="booking-detail-row">
                    <span>User</span>
                    <strong>{{ $booking->user->name }} ({{ $booking->user->email }})</strong>
                </div>
            @endcan

            <div class="booking-detail-row">
                <span>Event</span>
                <strong>{{ $booking->event->name }}</strong>
            </div>

            <div class="booking-detail-row">
                <span>Date</span>
                <strong>{{ $booking->event->date->format('Y-m-d') }} {{ $booking->event->time }}</strong>
            </div>

            <div class="booking-detail-row">
                <span>Venue</span>
                <strong>{{ $booking->event->venue }}</strong>
            </div>

            <div class="booking-detail-row">
                <span>Seats</span>
                <strong>{{ $booking->number_of_seats }}</strong>
            </div>

            <div class="booking-detail-row">
                <span>Total</span>
                <strong class="booking-green">RM {{ number_format($booking->event->price * $booking->number_of_seats, 2) }}</strong>
            </div>

            <div class="booking-detail-row">
                <span>Booking Code</span>
                <strong>{{ $booking->booking_code ?? 'N/A' }}</strong>
            </div>

            <div class="booking-detail-row">
                <span>Ticket Code</span>
                <strong>{{ optional($booking->ticket)->ticket_code ?? 'Not available' }}</strong>
            </div>

            <div class="booking-detail-row">
                <span>Status</span>
                <strong class="booking-status {{ $booking->payment_status }}">
                    {{ ucfirst($booking->payment_status ?? 'unknown') }}
                </strong>
            </div>

            <div class="booking-detail-row">
                <span>Booked On</span>
                <strong>{{ $booking->created_at->format('d M Y, g:i A') }}</strong>
            </div>
        </div>

        <div class="booking-detail-actions">
            @if($booking->ticket && $booking->payment_status !== 'cancelled' && $booking->payment_status !== 'refunded')
                <a href="{{ route('tickets.qr', ['ticket' => $booking->ticket->id, 'from' => $from ?: url()->current()]) }}" class="booking-submit-link">
                    View QR
                </a>
            @endif

            @if($booking->payment_status === 'pending')
                <a href="{{ route('payment.show', $booking) }}" class="booking-submit-link">
                    Pay Now
                </a>
            @endif

            @if(!$isPastBooking && $booking->payment_status !== 'cancelled' && $booking->payment_status !== 'refunded')
                <form action="{{ route('bookings.destroy', $booking) }}" method="POST" onsubmit="return confirm('Cancel this booking?')">
                    @csrf
                    @method('DELETE')
                    <button class="booking-danger-btn">Cancel Booking</button>
                </form>
            @elseif($isPastBooking)
                <span class="booking-muted-pill">Past bookings cannot be cancelled</span>
            @endif
        </div>

    </div>
</div>
@endsection