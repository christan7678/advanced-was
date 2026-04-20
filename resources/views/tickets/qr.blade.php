@extends('layouts.app')

@section('content')
    <div style="max-width: 720px; margin: 40px auto; padding: 20px;">
        <div
            style="background: white; border: 1px solid #e5e7eb; border-radius: 16px; padding: 28px; box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);">
            <div style="display: flex; justify-content: space-between; gap: 12px; align-items: center; margin-bottom: 24px;">
                <div>
                    <div style="font-size: 28px; font-weight: 700; color: #111827;">Ticket QR</div>
                    <div style="font-size: 14px; color: #6b7280; margin-top: 6px;">
                        {{ $ticket->booking->event->name ?? 'Event' }}
                    </div>
                </div>

                <div style="display: flex; gap: 10px;">
                    <a href="{{ route('bookings.show', $ticket->booking) }}"
                        style="display: inline-block; padding: 10px 14px; background: #eff6ff; color: #1d4ed8; text-decoration: none; border-radius: 8px; font-weight: 600;">
                        Booking Detail
                    </a>
                    <a href="{{ route('profile.tickets') }}"
                        style="display: inline-block; padding: 10px 14px; background: #f3f4f6; color: #374151; text-decoration: none; border-radius: 8px; font-weight: 600;">
                        Back
                    </a>
                </div>
            </div>

            <div
                style="display: grid; grid-template-columns: minmax(240px, 280px) 1fr; gap: 24px; align-items: center;">
                <div
                    style="background: #f8fafc; border: 1px solid #e5e7eb; border-radius: 16px; padding: 18px; text-align: center;">
                    <img src="{{ asset($ticket->qr_code_path) }}" alt="Ticket QR code"
                        style="width: 100%; max-width: 240px; aspect-ratio: 1 / 1; object-fit: cover; border-radius: 12px;">
                </div>

                <div style="display: grid; gap: 12px;">
                    <div style="font-size: 14px; color: #6b7280;">Ticket Code</div>
                    <div style="font-size: 20px; font-weight: 700; color: #111827;">{{ $ticket->ticket_code }}</div>

                    <div style="font-size: 14px; color: #6b7280; margin-top: 8px;">Booking Code</div>
                    <div style="font-size: 16px; font-weight: 600; color: #1f2937;">
                        {{ $ticket->booking->booking_code ?? 'N/A' }}
                    </div>

                    <div style="font-size: 14px; color: #6b7280; margin-top: 8px;">Event</div>
                    <div style="font-size: 16px; font-weight: 600; color: #1f2937;">
                        {{ $ticket->booking->event->name ?? 'N/A' }}
                    </div>

                    <div style="font-size: 14px; color: #6b7280; margin-top: 8px;">Seats</div>
                    <div style="font-size: 16px; font-weight: 600; color: #1f2937;">
                        {{ $ticket->booking->number_of_seats ?? 1 }}
                    </div>

                    <div style="font-size: 14px; color: #6b7280; margin-top: 8px;">Status</div>
                    <div style="font-size: 16px; font-weight: 600; color: #1f2937;">
                        {{ ucfirst($ticket->booking->payment_status ?? 'unknown') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
