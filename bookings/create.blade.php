@extends('layouts.app')

@section('content')
<div class="card form-card p-4">
    <h2 class="text-center mb-4">Book Ticket</h2>

    <form action="{{ route('bookings.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Event Name</label>
            <input type="text" name="event_name" class="form-control" value="Music Festival 2026" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="customer_name" class="form-control" placeholder="Enter your name">
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" placeholder="Enter your email">
        </div>

        <div class="mb-3">
            <label class="form-label">Phone Number</label>
            <input type="text" name="phone" class="form-control" placeholder="Enter your phone number">
        </div>

        <div class="mb-3">
            <label class="form-label">Quantity</label>
            <input type="number" name="quantity" class="form-control" min="1" placeholder="Enter ticket quantity">
        </div>

        <div class="mb-3">
            <label class="form-label">Payment Method</label>
            <select name="payment_method" class="form-select">
                <option value="">Select payment method</option>
                <option value="Online Banking">Online Banking</option>
                <option value="Credit Card">Credit Card</option>
                <option value="E-Wallet">E-Wallet</option>
            </select>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Confirm Booking</button>
        </div>
    </form>
</div>
@endsection