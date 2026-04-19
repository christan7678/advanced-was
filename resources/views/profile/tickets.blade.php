@extends('layouts.app')

@section('content')
    <div class="account-page">
        <section class="account-section">
            <div class="account-back">
                <a href="/profile" onclick="if(history.length > 1){ history.back(); return false; }" class="back-btn">
                    🡰 Back
                </a>
            </div>

            <div class="account-section-label">My Tickets</div>

            <div class="account-menu" style="padding: 20px;">
                <div style="max-width: 900px;">
                    <!-- Summary Stats -->
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; margin-bottom: 30px;">
                        <div style="background: #f3f4f6; padding: 15px; border-radius: 8px; text-align: center;">
                            <div style="font-size: 24px; font-weight: bold; color: #059669;">{{ $user->bookings()->where('payment_status', 'completed')->count() }}</div>
                            <div style="font-size: 12px; color: #6b7280;">Confirmed Bookings</div>
                        </div>
                        <div style="background: #f3f4f6; padding: 15px; border-radius: 8px; text-align: center;">
                            <div style="font-size: 24px; font-weight: bold; color: #2563eb;">{{ $user->bookings()->where('payment_status', 'pending')->count() }}</div>
                            <div style="font-size: 12px; color: #6b7280;">Pending</div>
                        </div>
                        <div style="background: #f3f4f6; padding: 15px; border-radius: 8px; text-align: center;">
                            <div style="font-size: 24px; font-weight: bold; color: #7c3aed;">{{ $user->bookings()->count() }}</div>
                            <div style="font-size: 12px; color: #6b7280;">Total Bookings</div>
                        </div>
                    </div>

                    <!-- Upcoming Events -->
                    <div style="margin-bottom: 40px;">
                        <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 15px; color: #1f2937;">
                            📅 Upcoming Events
                        </h3>
                        
                        @php
                            $upcomingBookings = $user->bookings()
                                ->with('event')
                                ->get()
                                ->filter(fn($b) => $b->event->date && $b->event->date->isFuture())
                                ->sortBy(fn($b) => $b->event->date);
                        @endphp

                        @if($upcomingBookings->count() > 0)
                            <div style="display: grid; gap: 12px;">
                                @foreach($upcomingBookings as $booking)
                                    @php $event = $booking->event; @endphp
                                    <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 15px; display: grid; grid-template-columns: 1fr auto; align-items: center; gap: 15px;">
                                        <div>
                                            <div style="font-weight: 600; color: #1f2937; margin-bottom: 5px;">{{ $event->name }}</div>
                                            <div style="font-size: 13px; color: #6b7280; margin-bottom: 3px;">
                                                📅 {{ $event->date->format('d M Y') }} at {{ $event->time }}
                                            </div>
                                            <div style="font-size: 13px; color: #6b7280;">
                                                📍 {{ $event->venue }}
                                            </div>
                                            <div style="font-size: 12px; color: #6b7280; margin-top: 5px;">
                                                Booking ID: #{{ $booking->id }} • Payment: 
                                                <span style="color: #059669; font-weight: 500;">{{ ucfirst($booking->payment_status) }}</span>
                                            </div>
                                        </div>
                                        <div style="text-align: right;">
                                            <a href="{{ route('events.show', $event) }}" class="act-btn act-view" style="display: inline-block; margin-bottom: 8px;">View Event</a>
                                            @if($booking->payment_status === 'completed')
                                                <div>
                                                    <button onclick="cancelBooking({{ $booking->id }})" class="act-btn" style="background: #ef4444; color: white; border: none; cursor: pointer; padding: 6px 12px; border-radius: 4px; font-size: 12px;">Cancel</button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 20px; text-align: center; color: #1e40af;">
                                ✨ No upcoming events. Start exploring and book your next experience!
                            </div>
                        @endif
                    </div>

                    <!-- Past Events -->
                    <div>
                        <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 15px; color: #1f2937;">
                            🎬 Past Events
                        </h3>
                        
                        @php
                            $pastBookings = $user->bookings()
                                ->with('event')
                                ->get()
                                ->filter(fn($b) => $b->event->date && $b->event->date->isPast())
                                ->sortByDesc(fn($b) => $b->event->date);
                        @endphp

                        @if($pastBookings->count() > 0)
                            <div style="display: grid; gap: 12px;">
                                @foreach($pastBookings as $booking)
                                    @php $event = $booking->event; @endphp
                                    <div style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 15px; opacity: 0.85;">
                                        <div style="display: grid; grid-template-columns: 1fr auto; gap: 15px; align-items: center;">
                                            <div>
                                                <div style="font-weight: 600; color: #4b5563; margin-bottom: 5px;">{{ $event->name }}</div>
                                                <div style="font-size: 13px; color: #9ca3af; margin-bottom: 3px;">
                                                    📅 {{ $event->date->format('d M Y') }} at {{ $event->time }}
                                                </div>
                                                <div style="font-size: 13px; color: #9ca3af;">
                                                    📍 {{ $event->venue }}
                                                </div>
                                            </div>
                                            <div style="text-align: right;">
                                                <span style="display: inline-block; background: #e5e7eb; color: #6b7280; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                                                    Completed
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div style="background: #f3f4f6; border: 1px solid #d1d5db; border-radius: 8px; padding: 20px; text-align: center; color: #6b7280;">
                                No past events to show.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        function cancelBooking(bookingId) {
            if (!confirm('Are you sure you want to cancel this booking?')) {
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            fetch(`/bookings/${bookingId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Booking cancelled successfully!');
                    location.reload();
                } else {
                    alert('Failed to cancel booking.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        }
    </script>
@endsection