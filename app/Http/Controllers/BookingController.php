<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Event;
use App\Models\PromoCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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

        $booking->load(['user', 'event']);
        return view('bookings.show', compact('booking'));
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
        ]);

        $event = Event::findOrFail($request->event_id);

        // Check available seats
        if ($request->number_of_seats > $event->available_seats) {
            return back()->withErrors(['number_of_seats' => 'Not enough available seats.']);
        }

        // Create booking
        Booking::create([
            'user_id' => auth('web')->id(),
            'event_id' => $event->id,
            'number_of_seats' => $request->number_of_seats,
            'booking_status' => 'upcoming', // default status
        ]);

        // Decrease available seats
        $event->decrement('available_seats', $request->number_of_seats);

        return redirect()->route('bookings.index')
            ->with('success', 'Booking confirmed!');
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
            'booking_status' => 'required|in:upcoming,completed,cancelled',
        ]);

        $oldStatus = $booking->booking_status;
        $newStatus = $request->booking_status;
        $booking->update(['booking_status' => $newStatus]);

        if ($oldStatus !== 'cancelled' && $newStatus === 'cancelled') {
            // Restore seats if booking is cancelled
            $booking->event->increment('available_seats', $booking->number_of_seats);
        } elseif ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
            // Decrease seats if booking is reactivated
            if ($booking->number_of_seats > $booking->event->available_seats) {
                return back()->withErrors(['booking_status' => 'Not enough available seats to reactivate this booking.']);
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
        if (!Gate::allows('isAdmin') && $booking->user_id !== auth()->id()) {
            return redirect()->route('bookings.index')->with('error', 'You do not have permission to cancel this booking.');
        }

        // Restore seats if booking was active
        if ($booking->booking_status !== 'cancelled') {
            $booking->event->increment('available_seats', $booking->number_of_seats);
        }

        $booking->delete();

        return redirect()->route('bookings.index')
            ->with('success', 'Booking cancelled!');
    }
}