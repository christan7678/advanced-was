<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);

        $booking->expirePaymentIfNeeded();

        if ($booking->payment_status === 'cancelled') {
            return redirect()->route('bookings.index', ['tab' => 'cancelled'])
                ->with('error', 'Payment session expired. This booking has been cancelled.');
        }

        if ($booking->payment_status !== 'pending') {
            return redirect()->route('bookings.index')
                ->with('error', 'This booking is no longer payable.');
        }

        return view('payment.show', compact('booking'));
    }

    public function process(Request $request, Booking $booking)
    {
        $this->authorize('pay', $booking);

        $booking->expirePaymentIfNeeded();

        if ($booking->payment_status === 'cancelled') {
            return redirect()->route('bookings.index', ['tab' => 'cancelled'])
                ->with('error', 'Payment session expired. This booking has been cancelled.');
        }

        if ($booking->payment_status !== 'pending') {
            return back()->with('error', 'This booking is no longer payable.');
        }

        $request->validate([
            'payment_method' => 'required|in:card,fpx,ewallet',
        ]);

        if ($request->payment_method === 'card') {
            $request->validate([
                'card_number' => ['required', 'digits_between:13,19'],
                'expiry' => ['required'],
                'cvv' => ['required', 'digits_between:3,4'],
                'card_name' => ['required', 'string', 'max:255'],
            ]);
        }

        DB::transaction(function () use ($request, $booking) {
            Payment::create([
                'booking_id' => $booking->id,
                'user_id' => auth()->id(),
                'payment_code' => 'PAY' . strtoupper(uniqid()),
                'payment_method' => $request->payment_method,
                'card_last_four' => $request->filled('card_number')
                    ? substr($request->card_number, -4)
                    : null,
                'card_name' => $request->card_name,
                'amount' => $booking->total_amount,
                'status' => 'completed',
                'paid_at' => now(),
            ]);

            $booking->update([
                'payment_status' => 'completed',
                'booking_status' => 'confirmed',
                'paid_at' => now(),
            ]);
        });

        return redirect()->route('bookings.index')
            ->with('success', 'Payment successful!');
    }
}