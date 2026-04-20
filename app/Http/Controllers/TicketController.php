<?php

namespace App\Http\Controllers;

use App\Models\Ticket;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showQR(Ticket $ticket)
    {
        if ($ticket->user_id !== auth()->id()) {
            return redirect()->route('profile.tickets')
                ->with('error', 'You do not have permission to view this ticket QR.');
        }

        $ticket->loadMissing(['booking.event', 'user']);

        if (($ticket->booking->payment_status ?? null) === 'cancelled') {
            return redirect()->route('profile.tickets')
                ->with('error', 'Cancelled bookings cannot view QR.');
        }

        return view('tickets.qr', compact('ticket'));
    }
}
