@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/user/booking-action.css') }}?v={{ time() }}">
@endsection

@section('content')
<div class="booking-action-page">
    <div class="booking-action-card">

        <div class="booking-action-header">
            <div>
                <div class="booking-action-label">Booking</div>
                <h1>Book Your Tickets</h1>
                <p>Select ticket quantity and confirm your booking.</p>
            </div>

            <a href="/profile" onclick="if(history.length > 1){ history.back(); return false; }" class="booking-action-back">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                     fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path d="M15 18l-6-6 6-6"/>
                </svg>
            </a>
        </div>

        @if($errors->any())
            <div class="booking-action-alert error">
                <strong>Booking Error:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('bookings.store') }}" class="booking-action-form">
            @csrf

            @if(!$event)
                <div class="booking-field">
                    <label>Select Event *</label>
                    <select name="event_id" required id="eventSelect">
                        <option value="">-- Choose an event --</option>
                        @forelse(\App\Models\Event::whereDate('date', '>=', today())->where('status', '!=', 'sold_out')->orderBy('date')->get() as $evt)
                            <option value="{{ $evt->id }}" data-price="{{ $evt->price }}" data-available="{{ $evt->available_seats }}">
                                {{ $evt->name }} - {{ $evt->date->format('d M Y') }} ({{ $evt->available_seats }} seats available)
                            </option>
                        @empty
                            <option disabled>No available events</option>
                        @endforelse
                    </select>

                    @error('event_id')
                        <div class="booking-error">{{ $message }}</div>
                    @enderror
                </div>
            @else
                <div class="booking-event-box">
                    <div class="booking-event-name">{{ $event->name }}</div>

                    <div class="booking-event-grid">
                        <div><span>Date</span><strong>{{ $event->date->format('d M Y') }} at {{ $event->time }}</strong></div>
                        <div><span>Venue</span><strong>{{ $event->venue }}</strong></div>
                        <div><span>Price</span><strong>RM{{ number_format($event->price, 2) }} per ticket</strong></div>
                        <div><span>Available</span><strong>{{ $event->available_seats }} seats</strong></div>
                    </div>
                </div>

                <input type="hidden" name="event_id" value="{{ $event->id }}">
                <input type="hidden" id="eventData" data-price="{{ $event->price }}" data-available="{{ $event->available_seats }}">
            @endif

            <div id="ticketSection" class="booking-field">
                <label>Number of Tickets *</label>

                <div class="booking-ticket-row">
                    <input type="number" name="number_of_seats" value="{{ old('number_of_seats', 1) }}" min="1" id="seatsInput" required>

                    <div id="seatInfo" class="booking-seat-info">
                        @if($event)
                            Max {{ $event->available_seats }} seats available
                        @endif
                    </div>
                </div>

                @error('number_of_seats')
                    <div class="booking-error">{{ $message }}</div>
                @enderror
            </div>

            <div id="soldOutBox" class="booking-soldout-box">
                SOLD OUT
            </div>

            <div id="priceContainer" class="booking-price-box" style="display: {{ $event ? 'block' : 'none' }};">
                <div class="booking-summary-row">
                    <span>Unit Price</span>
                    <strong id="unitPriceDisplay">RM{{ $event ? number_format($event->price, 2) : '0.00' }}</strong>
                </div>

                <div class="booking-summary-row">
                    <span>Quantity</span>
                    <strong id="quantityDisplay">1</strong>
                </div>

                <div class="booking-summary-row total">
                    <span>Total Amount</span>
                    <strong id="totalPrice">RM{{ $event ? number_format($event->price, 2) : '0.00' }}</strong>
                </div>

                <div class="booking-field amount-field">
                    <label>Amount to Pay</label>
                    <input type="text" id="displayTotalAmount" value="RM{{ $event ? number_format($event->price, 2) : '0.00' }}" readonly>
                </div>
            </div>

            <input type="hidden" id="totalAmountInput" name="total_amount" value="{{ $event ? $event->price : 0 }}">

            <button type="submit" id="submitBtn" class="booking-submit-btn">
                Confirm Booking
            </button>
        </form>

        <div class="booking-secure-note">
            Your booking is secure and your payment information is encrypted.
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/user-bookings.js') }}"></script>
@endsection