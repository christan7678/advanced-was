<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Support\Facades\Gate;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showQR(Ticket $ticket)
    {
        if (!Gate::allows('view', $ticket)) {
            return redirect()->route('profile.tickets')
                ->with('error', 'You do not have permission to view this ticket QR.');
        }

        $ticket->loadMissing(['booking.event', 'user']);

        if (!$ticket->booking || $ticket->booking->payment_status !== 'completed') {
            return redirect()->route('profile.tickets')
                ->with('error', 'This ticket is not valid for QR access.');
        }

        return view('tickets.qr', compact('ticket'));
    }
}