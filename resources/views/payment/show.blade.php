@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/user/payment.css') }}?v={{ time() }}">
@endsection

@section('content')
<div class="payment-page">
  <div class="payment-container">

    <div class="payment-header">
        <div>
            <div class="payment-label">Secure Checkout</div>
            <h1>Payment Details</h1>
            <p>Complete your payment within 15 minutes to confirm your booking.</p>
        </div>

        <a href="{{ route('bookings.index') }}" class="payment-back-btn-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                <path d="M15 18l-6-6 6-6"/>
            </svg>
        </a>
    </div>

    @if(session('error'))
        <div class="payment-alert error">{{ session('error') }}</div>
    @endif

    @if($booking->expires_at && $booking->expires_at->lt(now()))
        <div class="payment-alert error">
            This booking has expired. Please book again.
        </div>
    @endif
    @if ($errors->any())
        <div class="payment-alert error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="payment-layout">

        <div class="payment-card">
            <h2>Choose Payment Method</h2>

            <div class="payment-timer">
                Time left to pay:
                <span id="countdown">Loading...</span>
            </div>

            @if(!$booking->expires_at || $booking->expires_at->gt(now()))
                <form method="POST" action="{{ route('payment.process', $booking) }}">
                    @csrf

                    <div class="payment-field">
                        <label>Payment Method</label>
                        <select name="payment_method" id="paymentMethod" required onchange="toggleMethod()">
                            <option value="">Select payment method</option>
                            <option value="card">Credit / Debit Card</option>
                            <option value="fpx">Online Banking (FPX)</option>
                            <option value="ewallet">E-Wallet</option>
                        </select>
                    </div>

                    <div id="cardSection" style="display:none;">
                        <div class="payment-field">
                            <label>Card Number</label>
                            <input type="text" id="cardNumber" name="card_number" placeholder="1234 5678 9012 3456">
                            <div id="cardNumberError" class="invalid-feedback"></div>
                        </div>

                        <div class="payment-row">
                            <div class="payment-field">
                                <label>Expiry</label>
                                <input type="text" id="expiryInput" name="expiry" placeholder="MM/YY">
                                <div id="expiryError" class="invalid-feedback"></div>
                            </div>

                            <div class="payment-field">
                                <label>CVV</label>
                                <input type="password" id="cvv" name="cvv" placeholder="123">
                                <div id="cvvError" class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="payment-field">
                            <label>Cardholder Name</label>
                            <input type="text" id="cardName" name="card_name">
                            <div id="cardNameError" class="invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="payment-actions">
                        <button type="submit" class="payment-pay-btn">Pay Now</button>
                        <a href="{{ route('bookings.index') }}" class="payment-cancel-btn">Pay Later</a>
                    </div>
                </form>
            @endif
        </div>

        <div class="payment-summary">
            <h2>Order Summary</h2>

            <div class="summary-row">
                <span>Event</span>
                <strong>{{ $booking->event->name }}</strong>
            </div>

            <div class="summary-row">
                <span>Seats</span>
                <strong>{{ $booking->number_of_seats }}</strong>
            </div>

            <div class="summary-row total">
                <span>Total</span>
                <strong>RM{{ number_format($booking->total_amount, 2) }}</strong>
            </div>

            <div class="summary-note">
                Secure checkout · Payment is encrypted
            </div>
        </div>

    </div>
  </div>
</div>
@endsection

<script>
    window.bookingData = {
        expiry: "{{ $booking->expires_at->toIso8601String() }}"
    };
</script>

<script src="{{ asset('js/payment.js') }}"></script>