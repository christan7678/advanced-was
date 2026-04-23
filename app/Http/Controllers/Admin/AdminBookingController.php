<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminBookingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,super_admin']);
    }

    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $status = (string) $request->query('status', '');
        $event_id = $request->query('event_id');

        $query = Booking::query()
            ->with(['user', 'event.category'])
            ->latest();

        if ($status !== '') {
            $query->where('booking_status', $status);
        }

        if ($event_id) {
            $query->where('event_id', $event_id);
        }

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->whereHas('user', function ($u) use ($q) {
                    $u->where('name', 'like', '%' . $q . '%')
                        ->orWhere('email', 'like', '%' . $q . '%');
                })->orWhereHas('event', function ($e) use ($q) {
                    $e->where('name', 'like', '%' . $q . '%')
                        ->orWhere('venue', 'like', '%' . $q . '%');
                });
            });
        }

        $bookings = $query->paginate(12)->withQueryString();

        // Get booking statistics
        $statsQuery = Booking::query();
        if ($event_id) {
            $statsQuery->where('event_id', $event_id);
        }
        
        $totalBookings = (clone $statsQuery)->count();
        $confirmed = (clone $statsQuery)->where('booking_status', 'confirmed')->count();
        $pending = (clone $statsQuery)->where('booking_status', 'pending')->count();
        $cancelled = (clone $statsQuery)->where('booking_status', 'cancelled')->count();
        $tickets = (clone $statsQuery)->sum('number_of_seats');

        if ($event_id) {
            $event = \App\Models\Event::with('category')->find($event_id);

            $current = [
                'title' => $event ? $event->name : 'Event',
                'name' => $event && $event->category ? $event->category->name : 'Category',
                'organiser' => $event ? $event->organizer : 'Organizer',
                'venue' => $event ? $event->venue : 'Venue',
                'date' => $event && $event->date ? $event->date->format('d M Y') : 'Date',
                'time' => $event && $event->time ? substr($event->time, 0, 5) : 'Time',
                'tickets' => $tickets,
                'total_bookings' => $totalBookings,
                'confirmed' => $confirmed,
                'pending' => $pending,
                'cancelled' => $cancelled,
            ];
        } else {
            $current = [
                'title' => 'All Bookings',
                'name' => 'All Categories',
                'organiser' => 'All Organizers',
                'venue' => 'All Venues',
                'date' => 'All Time',
                'time' => 'All Times',
                'tickets' => $tickets,
                'total_bookings' => $totalBookings,
                'confirmed' => $confirmed,
                'pending' => $pending,
                'cancelled' => $cancelled,
            ];
        }

        return view('admin.bookings.index', compact('bookings', 'q', 'status', 'current'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'event.category','payment']);

        return view('admin.bookings.show', compact('booking'));
    }

   public function cancel(Booking $booking)
    {
        if ($booking->booking_status !== 'cancelled') {
            DB::transaction(function () use ($booking) {
                $booking->loadMissing('event');

                if ($booking->event) {
                    $booking->event->increment('available_seats', (int) $booking->number_of_seats);
                }

                $booking->tickets()->delete();

                $booking->update([
                    'booking_status' => 'cancelled',
                    'payment_status' => 'cancelled',
                ]);
            });
        }

        return redirect()
            ->route('admin.bookings.show', $booking)
            ->with('success', 'Booking cancelled.');
    }

    public function destroy(Booking $booking)
    {
        DB::transaction(function () use ($booking) {
            $booking->loadMissing('event');

            if ($booking->booking_status !== 'cancelled' && $booking->event) {
                $booking->event->increment('available_seats', (int) $booking->number_of_seats);
            }

            $booking->tickets()->delete();
            $booking->delete();
        });

        return redirect()
            ->route('admin.bookings.index')
            ->with('success', 'Booking deleted.');
    }
}

