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
        $this->middleware('auth:admin');
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
            $query->where('payment_status', $status);
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
        
        $totalBookings = $statsQuery->count();
        $completed = $statsQuery->where('payment_status', 'completed')->count();
        $pending = $statsQuery->where('payment_status', 'pending')->count();
        $cancelled = $statsQuery->where('payment_status', 'cancelled')->count();

        if ($event_id) {
            $event = \App\Models\Event::find($event_id);
            $current = [
                'title' => $event ? $event->name : 'Event',
                'name' => $event ? $event->category->name : 'Category',
                'organiser' => $event ? $event->organizer : 'Organizer',
                'venue' => $event ? $event->venue : 'Venue',
                'date' => $event ? $event->date->format('d M Y') : 'Date',
                'time' => $event ? substr($event->time, 0, 5) : 'Time',
                'tickets' => $statsQuery->sum('number_of_seats'),
                'total_bookings' => $totalBookings,
                'completed' => $completed,
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
                'tickets' => Booking::sum('number_of_seats'),
                'total_bookings' => $totalBookings,
                'completed' => $completed,
                'pending' => $pending,
                'cancelled' => $cancelled,
            ];
        }

        return view('admin.bookings.index', compact('bookings', 'q', 'status', 'current'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'event.category']);

        return view('admin.bookings.show', compact('booking'));
    }

    public function cancel(Booking $booking)
    {
        if ($booking->payment_status !== 'cancelled') {
            DB::transaction(function () use ($booking) {
                $booking->loadMissing('event');

                if ($booking->event) {
                    $booking->event->increment('available_seats', (int) $booking->number_of_seats);
                }

                $booking->tickets()->delete();
                $booking->update(['payment_status' => 'cancelled']);
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

            if ($booking->payment_status !== 'cancelled' && $booking->event) {
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

