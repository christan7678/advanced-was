@extends('layouts.app')

@section('content')
<div class="account-page">
    <section class="account-section">
        <div style="max-width: 700px; margin: 40px auto; text-align: center;">
            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 12px; padding: 30px;">
                <h2 style="margin-bottom: 10px; color: #111827;">Booking Created Successfully</h2>
                <p style="color: #6b7280; margin-bottom: 20px;">
                    Your booking has been saved successfully.
                </p>

                <div style="margin-bottom: 20px; text-align: left; background: #f9fafb; border-radius: 10px; padding: 16px;">
                    <div><strong>Booking Code:</strong> {{ $booking->booking_code ?? 'N/A' }}</div>
                    <div><strong>Event:</strong> {{ $booking->event->name ?? 'Event Unavailable' }}</div>
                    <div><strong>Seats:</strong> {{ $booking->number_of_seats }}</div>
                    <div><strong>Total Amount:</strong> RM{{ number_format((float) $booking->total_amount, 2) }}</div>
                    <div><strong>Payment Status:</strong> {{ ucfirst($booking->payment_status) }}</div>
                </div>

                <button type="button" onclick="openPaymentModal()"
                    style="background: #2563eb; color: white; border: none; padding: 12px 18px; border-radius: 10px; cursor: pointer; font-weight: 600;">
                    Continue
                </button>
            </div>
        </div>
    </section>
</div>

<div id="paymentChoiceModal" style="
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.45);
    justify-content: center;
    align-items: center;
    z-index: 9999;">
    
    <div style="background: white; width: 100%; max-width: 420px; border-radius: 14px; padding: 24px; box-shadow: 0 20px 50px rgba(0,0,0,0.2);">
       
    <h3 style="margin-bottom: 10px; color: #111827;">Proceed to Payment?</h3>
        <p style="color: #6b7280; margin-bottom: 20px;">
            Would you like to make payment now or pay later?
        </p>

        <div style="display: flex; gap: 12px; justify-content: flex-end; flex-wrap: wrap;">
            <a href="{{ route('bookings.index') }}"
                style="background: #e5e7eb; color: #374151; text-decoration: none; padding: 10px 16px; border-radius: 8px; font-weight: 600;">
                Pay Later
            </a>

            <a href="{{ route('payment.show', $booking) }}"
                style="background: #16a34a; color: white; text-decoration: none; padding: 10px 16px; border-radius: 8px; font-weight: 600;">
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