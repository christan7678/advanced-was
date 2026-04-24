@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/user/bookings.css') }}?v={{ time() }}">
@endsection

@section('content')
<div class="booking-review-page">
    <div class="booking-review-card">
        
        <h1>Booking Created Successfully</h1>
        <p>Your booking has been saved successfully. Please complete payment within 15 minutes.</p>

        <div class="booking-review-summary">
            <div>
                <span>Booking Code</span>
                <strong>{{ $booking->booking_code ?? 'N/A' }}</strong>
            </div>

            <div>
                <span>Event</span>
                <strong>{{ $booking->event->name ?? 'Event Unavailable' }}</strong>
            </div>

            <div>
                <span>Seats</span>
                <strong>{{ $booking->number_of_seats }}</strong>
            </div>

            <div>
                <span>Total Amount</span>
                <strong class="booking-review-price">
                    RM{{ number_format((float) $booking->total_amount, 2) }}
                </strong>
            </div>

            <div>
                <span>Payment Status</span>
                <strong class="booking-review-status">
                    {{ ucfirst($booking->payment_status) }}
                </strong>
            </div>
        </div>

        <button type="button" onclick="openPaymentModal()" class="booking-primary-btn">
            Continue
        </button>
    </div>
</div>

<div id="paymentChoiceModal" class="payment-choice-modal">
    <div class="payment-choice-card">
        <div class="payment-choice-label">Next Step</div>
        <h3>Proceed to Payment?</h3>
        <p>Would you like to make payment now or pay later?</p>

        <div class="payment-choice-actions">
            <a href="{{ route('bookings.index') }}" class="booking-secondary-btn">
                Pay Later
            </a>

            <a href="{{ route('payment.show', $booking) }}" class="booking-primary-link">
                Pay Now
            </a>
        </div>
    </div>
</div>

<script>
    function openPaymentModal() {
        document.getElementById('paymentChoiceModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('paymentChoiceModal').style.display = 'none';
    }

    window.onclick = function(event) {
        const modal = document.getElementById('paymentChoiceModal');
        if (event.target === modal) {
            modal.style.display = "none";
        }
    }
</script>
@endsection