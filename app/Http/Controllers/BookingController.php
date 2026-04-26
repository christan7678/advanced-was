<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Ticket;
use App\Models\Event;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Admin: see all bookings
     * User: see only their own bookings
     */
    public function index()
    {
        if (Gate::allows('administration')) {
            $bookings = Booking::with(['user', 'event'])
                ->leftJoin('events', 'bookings.event_id', '=', 'events.id')
                ->orderByRaw("
                    CASE 
                        WHEN bookings.payment_status = 'completed' THEN 0
                        WHEN bookings.payment_status = 'pending' THEN 1
                        WHEN bookings.payment_status = 'refunded' THEN 2
                        WHEN bookings.payment_status = 'cancelled' THEN 3
                        ELSE 4
                    END
                ")
                ->orderBy('events.date', 'asc')
                ->select('bookings.*')
                ->get();
        } else {
            $bookings = Booking::with('event')
                ->where('user_id', auth()->id())
                ->leftJoin('events', 'bookings.event_id', '=', 'events.id')
                ->orderByRaw("
                    CASE 
                        WHEN bookings.payment_status = 'completed' THEN 0
                        WHEN bookings.payment_status = 'pending' THEN 1
                        WHEN bookings.payment_status = 'refunded' THEN 2
                        WHEN bookings.payment_status = 'cancelled' THEN 3
                        ELSE 4
                    END
                ")
                ->orderBy('events.date', 'asc')
                ->select('bookings.*')
                ->get();
        }

        $bookings = Booking::with(['event', 'payment'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        foreach ($bookings as $booking) {
            $booking->expirePaymentIfNeeded();
        }

        $bookings = Booking::with(['event', 'payment'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Show booking detail
     */
    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);
        $booking->loadMissing(['event', 'payment']);
         if (
            $booking->payment_status === 'pending' &&
            $booking->expires_at &&
            $booking->expires_at->lt(now())
        ) {
            DB::transaction(function () use ($booking) {
                $booking->update([
                    'payment_status' => 'cancelled',
                    'booking_status' => 'cancelled',
                ]);

                if ($booking->payment) {
                    $booking->payment->update([
                        'status' => 'cancelled',
                    ]);
                }

                if ($booking->event) {
                    $booking->event->increment('available_seats', $booking->number_of_seats);
                }
            });
        }

        $booking->load(['user', 'event', 'ticket']);

        $from = request()->query('from');
        return view('bookings.show', compact('booking','from'));
    }

    public function create(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to make a booking.');
        }

        $eventId = $request->query('event_id');
        $event = null;

        if ($eventId) {
            $event = Event::findOrFail($eventId);
        }

        return view('bookings.create', compact('event'));
    }

    /**
     * Store a new booking (user only)
     */
    public function store(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 
            'Only users can make bookings.');
        }

        $request->validate([
            'event_id' => 'required|exists:events,id',
            'number_of_seats' => 'required|integer|min:1',
            'total_amount' => 'required|numeric|min:0',
        ]);

        $userId = auth()->id();
        $numberOfSeats = (int) $request->number_of_seats;
        $submittedTotal = (float) $request->total_amount;
        $bookingCode = 'BK' . strtoupper(uniqid());

        $booking = DB::transaction(function () use ($request, $userId, $numberOfSeats, $submittedTotal, $bookingCode) {
            $event = Event::whereKey($request->event_id)->lockForUpdate()->firstOrFail();

            if ($numberOfSeats > $event->available_seats) {
                throw ValidationException::withMessages([
                    'number_of_seats' => 'Not enough available seats.',
                ]);
            }

            if ($event->date && $event->date->lt(today())) {
                throw ValidationException::withMessages([
                    'event_id' => 'Past events cannot be booked.',
                ]);
            }

            $expectedTotal = (float) $event->price * $numberOfSeats;

            if (abs($expectedTotal - $submittedTotal) > 0.01) {
                throw ValidationException::withMessages([
                    'total_amount' => 'Invalid total amount. Please try again.',
                ]);
            }

            $booking = Booking::create([
                'booking_code' => $bookingCode,
                'user_id' => $userId,
                'event_id' => $event->id,
                'number_of_seats' => $numberOfSeats,
                'total_amount' => $expectedTotal,
                'payment_status' => 'pending',
                'booking_status' => 'pending',
                'booked_at' => now(),
                'expires_at' => now()->addMinutes(1),
            ]);

            Payment::create([
                'booking_id' => $booking->id,
                'user_id' => $userId,
                'payment_code' => 'PAY' . strtoupper(uniqid()),
                'payment_method' => null,
                'card_last_four' => null,
                'card_name' => null,
                'amount' => $expectedTotal,
                'status' => 'pending',
                'paid_at' => null,
            ]);

            $qrPaths = [
                'storage/qrs/qr1.jpg',
                'storage/qrs/qr2.jpg',
                'storage/qrs/qr3.jpg',
            ];

            $lastTicket = Ticket::orderBy('id', 'desc')->first();

            if ($lastTicket && $lastTicket->ticket_code) {
                $lastNumber = (int) preg_replace('/[^0-9]/', '', $lastTicket->ticket_code);
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }

            Ticket::create([
                'booking_id' => $booking->id,
                'user_id' => auth()->id(),
                'event_id' => $request->event_id,
                'ticket_code' => 'TK' . $nextNumber ,
                'qr_code_path' => $qrPaths[array_rand($qrPaths)],
                'status' => 'active',
            ]);

            $event->decrement('available_seats', $numberOfSeats);
            $event->refresh();

            // If no seats left, update event status to sold_out
            if ($event->available_seats <= 0) {
                $event->update([
                    'available_seats' => 0,
                    'status' => 'sold_out',
                ]);
            } else {
                $event->update([
                    'status' => 'active',
                ]);
            }

            return $booking;
        });

        return redirect()->route('bookings.after', $booking)
            ->with('success', 'Booking created. Please complete payment within 15 minutes.');
    }

    public function destroy(Booking $booking)
    {
        $this->authorize('delete', $booking);

        $booking->loadMissing('event');

        if ($booking->event && $booking->event->date && $booking->event->date->lt(today())) {
            return redirect()->route('bookings.index', ['tab' => 'past'])
                ->with('error', 'Past bookings cannot be cancelled.');
        }

        DB::transaction(function () use ($booking) {
            if ($booking->booking_status === 'cancelled') {
                return;
            }

            $booking->loadMissing(['event', 'payment']);

            if ($booking->event) {
                $event = $booking->event;

                if (!in_array($booking->payment_status, ['cancelled', 'refunded'])) {
                    $event->increment('available_seats', $booking->number_of_seats);
                    $event->refresh();
                }

                if ($event->available_seats > 0 && $event->date && $event->date->gte(today())) {
                    $event->status = 'active';
                    $event->save();
                }
            }

            $newPaymentStatus = $booking->payment_status === 'completed'
                ? 'refunded'
                : 'cancelled';

            $booking->update([
                'booking_status' => 'cancelled',
                'payment_status' => $newPaymentStatus,
            ]);


            if ($booking->payment) {
                $booking->payment->update([
                    'status' => $newPaymentStatus,
                ]);
            }
        });

        $message = $booking->payment_status === 'completed'
            ? 'Booking cancelled successfully. Refund will be processed within 7 working days.'
            : 'Booking cancelled successfully!';

        return redirect()->route('bookings.index', ['tab' => 'cancelled'])
            ->with('success', $message);
    }

    public function afterBooking(Booking $booking)
    {
        $this->authorize('view', $booking);

        $booking->loadMissing('event');

        return view('bookings.afterBooking', compact('booking'));
    }

    /**
     * Admin-only helper pages if you still use them
     */
    public function adminIndex()
    {
        Gate::authorize('administration');

        $bookings = Booking::with(['user', 'event'])->latest()->get();

        return view('admin.bookings.index', compact('bookings'));
    }

    public function adminShow(Booking $booking)
    {
        Gate::authorize('administration');

        $booking->load(['user', 'event', 'ticket']);

        return view('admin.bookings.show', compact('booking'));
    }
}