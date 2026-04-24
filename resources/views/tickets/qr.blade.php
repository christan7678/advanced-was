@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/user/ticket.css') }}?v={{ time() }}">
@endsection

@section('content')
<div class="ticket-page">
    <div class="ticket-card">

        <div class="ticket-header">
            <div>
                <div class="ticket-label">Digital Ticket</div>
                <h1>Ticket QR</h1>
                <p>{{ $ticket->booking->event->name ?? 'Event' }}</p>
            </div>

            <a href="{{ url()->previous() }}" class="ticket-back-btn-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path d="M15 18l-6-6 6-6"/>
                </svg>
            </a>
        </div>

        <div class="ticket-body">
            <div class="ticket-qr-panel">
                <div class="ticket-qr-box">
                    <img src="{{ asset($ticket->qr_code_path ?? 'storage/qrs/qr1.jpg') }}" alt="Ticket QR code">
                </div>

                <div class="ticket-qr-note">
                    Scan this QR code at the event entrance.
                </div>
            </div>

            <div class="ticket-info-panel">
                <div class="ticket-info-item">
                    <span>Ticket Code</span>
                    <strong>{{ $ticket->ticket_code }}</strong>
                </div>

                <div class="ticket-info-item">
                    <span>Booking Code</span>
                    <strong>{{ $ticket->booking->booking_code ?? 'N/A' }}</strong>
                </div>

                <div class="ticket-info-item">
                    <span>Event</span>
                    <strong>{{ $ticket->booking->event->name ?? 'N/A' }}</strong>
                </div>

                <div class="ticket-info-item">
                    <span>Seats</span>
                    <strong>{{ $ticket->booking->number_of_seats ?? 1 }}</strong>
                </div>

                <div class="ticket-info-item">
                    <span>Status</span>
                    <strong class="ticket-status">
                        {{ ucfirst($ticket->booking->payment_status ?? 'unknown') }}
                    </strong>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection