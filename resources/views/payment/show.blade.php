@extends('layouts.app')

@section('content')
<div class="container">

    <h3 class="mb-4">Payment Details</h3>

    <div class="card p-4 shadow-sm">

        {{-- Booking Info --}}
        <div class="mb-4 border-bottom pb-3">
            <p><strong>Event:</strong> {{ $booking->event->name }}</p>
            <p><strong>Seats:</strong> {{ $booking->number_of_seats }}</p>
            <p><strong>Total:</strong> 
                <span class="text-success fw-bold">
                    RM{{ number_format($booking->total_amount, 2) }}
                </span>
            </p>

            {{--  Expiry Timer --}}
            <p class="text-danger fw-bold">
                ⏳ Time left to pay: <span id="countdown">Loading...</span>
            </p>
        </div>

        {{-- ERROR --}}
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if($booking->expires_at && $booking->expires_at->lt(now()))
            <div class="alert alert-danger">
                ❌ This booking has expired. Please book again.
            </div>
        @endif

        @if(!$booking->expires_at || $booking->expires_at->gt(now()))
        <form method="POST" action="{{ route('payment.process', $booking) }}">
            @csrf

            {{-- Payment Method --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Payment Method</label>
                <select name="payment_method" id="paymentMethod" class="form-select" required onchange="toggleMethod()">
                    <option value="">-- Select Method --</option>
                    <option value="card">Credit / Debit Card</option>
                    <option value="fpx">Online Banking (FPX)</option>
                    <option value="ewallet">E-Wallet</option>
                </select>
            </div>

            {{-- CARD SECTION --}}
            <div id="cardSection" style="display:none;">
                <div class="mb-3">
                    <label class="form-label">Card Number</label>
                    <input type="text" id="cardNumber" name="card_number" class="form-control"
                        placeholder="1234 5678 9012 3456">
                    <div id="cardNumberError" class="invalid-feedback"></div>
                </div>

                <div class="row">
                    <div class="mb-3">
                        <label>Expiry (MM/YY)</label>
                        <input 
                            type="text" 
                            id="expiryInput" 
                            name="expiry" 
                            class="form-control"
                            placeholder="MM/YY"
                        >
                        <div class="invalid-feedback" id="expiryError"></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">CVV</label>
                        <input type="password" id="cvv" name="cvv" class="form-control"
                            placeholder="123">
                        <div id="cvvError" class="invalid-feedback"></div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Cardholder Name</label>
                    <input type="text" id="cardName" name="card_name" class="form-control">
                    <div id="cardNameError" class="invalid-feedback"></div>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success flex-fill">
                    Pay Now
                </button>

                <a href="{{ route('bookings.index') }}" class="btn btn-secondary flex-fill">
                    Back
                </a>
            </div>

        </form>
        @endif

    </div>

</div>
@endsection

<script>
    window.bookingData = {
        expiry: "{{ $booking->expires_at->toIso8601String() }}"
    };
</script>

<script src="{{ asset('js/payment.js') }}"></script>