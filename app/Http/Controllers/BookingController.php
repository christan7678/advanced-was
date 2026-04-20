<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    public function __construct()
    {
        // admin or user must be logged in
        $this->middleware('auth:admin,web');
    }

    /**
     * Admin: see all bookings
     * User: see only their own bookings
     */
    public function index()
    {
        if (Gate::allows('isAdmin')) {
            $bookings = Booking::with(['user', 'event'])->latest()->get();
        } else {
            $bookings = Booking::with('event')
                ->where('user_id', auth('web')->id())
                ->latest()
                ->get();
        }

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Show booking detail
     * Admin: any booking / User: only own booking
     */
    public function show(Booking $booking)
    {
        if (!Gate::allows('isAdmin') && $booking->user_id !== auth()->id()) {
            return redirect()->route('bookings.index')->with('error', 'You do not have permission to view this booking.');
        }

        $booking->load(['user', 'event', 'ticket']);
        return view('bookings.show', compact('booking'));
    }

    public function create(Request $request)
    {
        if (!auth('web')->check()) {
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
        if (!auth('web')->check()) {
            return redirect()->route('login')->with('error', 'Only users can make bookings.');
        }

        $request->validate([
            'event_id' => 'required|exists:events,id',
            'number_of_seats' => 'required|integer|min:1',
            'total_amount' => 'required|numeric|min:0',
        ]);

        $userId = auth('web')->id();
        $numberOfSeats = (int) $request->number_of_seats;
        $submittedTotal = (float) $request->total_amount;
        $bookingCode = 'BK' . strtoupper(uniqid());

        DB::transaction(function () use ($request, $userId, $numberOfSeats, $submittedTotal, $bookingCode) {
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
                'total_amount' => $submittedTotal,
                'payment_status' => 'pending',
            ]);

            $qrCodePaths = [
                'storage/qrs/qr1.jpg',
                'storage/qrs/qr2.jpg',
                'storage/qrs/qr3.jpg',
            ];
            $qrCodePath = $qrCodePaths[($booking->id - 1) % count($qrCodePaths)];

            $booking->ticket()->create([
                'user_id' => $userId,
                'event_id' => $event->id,
                'ticket_code' => 'TK' . $booking->id,
                'qr_code_path' => $qrCodePath,
            ]);
            $event->decrement('available_seats', $numberOfSeats);
        });

        return redirect()->route('bookings.index')
            ->with('success', 'Booking confirmed! Your booking code is ' . $bookingCode);
    }

    /**
     * Admin: edit any booking status
     */
    public function edit(Booking $booking)
    {
        Gate::authorize('isAdmin');
        $booking->load(['user', 'event']);
        return view('bookings.edit', compact('booking'));
    }

    /**
     * Admin: update booking status
     */
    public function update(Request $request, Booking $booking)
    {
        Gate::authorize('isAdmin');

        $request->validate([
            'payment_status' => 'required|in:pending,completed,cancelled',
        ]);

        $oldStatus = $booking->payment_status;
        $newStatus = $request->payment_status;
        $booking->update(['payment_status' => $newStatus]);

        if ($oldStatus !== 'cancelled' && $newStatus === 'cancelled') {
            // Restore seats if booking is cancelled
            $booking->event->increment('available_seats', $booking->number_of_seats);
        } elseif ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
            // Decrease seats if booking is reactivated
            if ($booking->number_of_seats > $booking->event->available_seats) {
                return back()->withErrors(['payment_status' => 'Not enough available seats to reactivate this booking.']);
            }
            $booking->event->decrement('available_seats', $booking->number_of_seats);
        }

        return redirect()->route('bookings.index')
            ->with('success', "Booking status updated from $oldStatus to $newStatus.");
    }

    /**
     * User: cancel own booking / Admin: delete any booking
     */
    public function destroy(Booking $booking)
    {
        if ( $booking->user_id !== auth()->id()) {
            return redirect()->route('bookings.index')->with('error', 'You do not have permission to cancel this booking.');
        }

        $booking->loadMissing('event');

        if ($booking->event && $booking->event->date && $booking->event->date->lt(today())) {
            return redirect()->route('bookings.index', ['tab' => 'past'])
                ->with('error', 'Past bookings cannot be cancelled.');
        }

        DB::transaction(function () use ($booking) {
            if ($booking->payment_status !== 'cancelled' && $booking->event) {
                $booking->event->increment('available_seats', $booking->number_of_seats);
            }

            $booking->tickets()->delete();
            $booking->delete();
        });

        return redirect()->route('bookings.index')
            ->with('success', 'Booking cancelled!');
    }
}
