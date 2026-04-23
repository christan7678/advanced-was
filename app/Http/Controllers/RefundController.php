<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Refund;
use Illuminate\Http\Request;

class RefundController extends Controller
{
    public function refund(Payment $payment)
    {
        if ($payment->status !== 'completed') {
            return back()->with('error', 'Only completed payments can be refunded.');
        }
        
        if ($payment->status === 'refunded') {
            return back()->with('error', 'Already refunded.');
        }

        $refund = Refund::create([
            'payment_id' => $payment->id,
            'booking_id' => $payment->booking_id,
            'refund_code' => 'RF' . strtoupper(uniqid()),
            'refund_amount' => $payment->amount,
            'refund_reason' => 'User cancelled booking',
            'status' => 'completed',
            'refunded_at' => now(),
            'processed_by' => auth()->id(),
        ]);

        // update payment
        $payment->update([
            'status' => 'refunded',
        ]);

        // update booking
        $payment->booking->update([
            'booking_status' => 'cancelled',
            'payment_status' => 'refunded',
        ]);

        // restore seats
        $payment->booking->event->increment(
            'available_seats',
            $payment->booking->number_of_seats
        );

        return back()->with('success', 'Refund processed.');
    }
}