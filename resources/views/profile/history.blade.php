@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/user/profile/profile-detail.css') }}">
@endsection

@section('content')
@php
    $query = $user->bookings()->with('event');

    $status = request('status');
    if(request('status')) {
        $query->where('payment_status', request('status'));
    }
    $bookings = $query->orderByDesc('created_at')->get();

    $purchaseCount = $user->bookings()->where('payment_status', 'completed')->count();
    $cancelCount = $user->bookings()->where('payment_status', 'cancelled')->count();
    $refundCount = $user->bookings()->where('payment_status', 'refunded')->count();

    if($status == 'completed'){
        $mainTitle = 'Total Purchased';
        $mainAmount = $bookings->sum('total_amount');
    }
    elseif($status == 'refunded'){
        $mainTitle = 'Total Refunded';
        $mainAmount = $bookings->sum('total_amount');
    }
    elseif($status == 'cancelled'){
        $mainTitle = 'Total Cancelled';
        $mainAmount = $bookings->sum('total_amount');
    }
    elseif($status == 'pending'){
        $mainTitle = 'Total Pending';
        $mainAmount = $bookings->sum('total_amount');
    }
    else {
        $mainTitle = 'Total Spent';
        $mainAmount = $bookings->where('payment_status', 'completed')->sum('total_amount');
    }
@endphp

<div class="profile-detail-page">
    <section class="profile-detail-section">

        <div class="profile-detail-header">
            <div>
                <div class="profile-detail-label">History</div>
                <h1>Purchase History</h1>
                <p>Review your booking and payment records.</p>
            </div>

            <a href="{{ route('bookings.show', ['booking' => $booking, 'from' => url()->current() }}" class="profile-detail-back">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                     fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path d="M15 18l-6-6 6-6"/>
                </svg>
            </a>
        </div>

        <div class="history-stats">
            <div class="history-stat-card">
                <span>Purchase</span>
                <strong class="success">{{ $purchaseCount }}</strong>
            </div>

            <div class="history-stat-card">
                <span>Cancelled</span>
                <strong class="danger">{{ $cancelCount }}</strong>
            </div>

            <div class="history-stat-card">
                <span>Refunded</span>
                <strong class="primary">{{ $refundCount }}</strong>
            </div>

            <div class="history-stat-card">
                <span>{{ $mainTitle }}</span>
                <strong class="purple">RM{{ number_format($mainAmount, 2) }}</strong>
            </div>
        </div>

        <div class="history-toolbar">
            <h2>All Purchases</h2>

            <form method="GET">
                <select name="status" onchange="this.form.submit()" class="history-select">
                    <option value="">All Status</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                </select>
            </form>
        </div>

        @if($bookings->count() > 0)
            <div class="history-list">
                @foreach($bookings as $booking)
                    @php
                        $event = $booking->event;

                        if ($booking->payment_status === 'completed') {
                            $statusLabel = 'Purchased';
                            $statusClass = 'completed';
                        } elseif ($booking->payment_status === 'refunded') {
                            $statusLabel = 'Refunded';
                            $statusClass = 'refunded';
                        } elseif ($booking->payment_status === 'cancelled') {
                            $statusLabel = 'Cancelled';
                            $statusClass = 'cancelled';
                        } else {
                            $statusLabel = ucfirst($booking->payment_status);
                            $statusClass = 'pending';
                        }
                    @endphp

                    <div class="history-card">
                        <div class="history-main">
                            <div class="history-event-name">
                                {{ $event->name ?? 'Event Unavailable' }}
                            </div>

                            <div class="history-meta">
                                {{ $event->date?->format('d M Y') ?? 'N/A' }}
                                · {{ $event->venue ?? 'N/A' }}
                            </div>

                            <div class="history-code">
                                Booking ID: #{{ $booking->id }}
                            </div>
                        </div>

                        <div class="history-details">
                            <div>
                                <span>Purchase Date</span>
                                <strong>{{ $booking->created_at->format('d M Y, H:i') }}</strong>
                            </div>

                            <div>
                                <span>Seats</span>
                                <strong>{{ $booking->number_of_seats ?? 1 }}</strong>
                            </div>

                            <div>
                                <span>Status</span>
                                <strong class="history-status {{ $statusClass }}">{{ $statusLabel }}</strong>
                            </div>
                        </div>

                        <div class="history-action">
                            <div class="history-amount">
                                RM{{ number_format($booking->total_amount ?? 0, 2) }}
                            </div>

                            <a href="{{ route('bookings.show', $booking) }}" class="history-view-btn">
                                View Booking
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="history-empty">
                <div>No Purchase History</div>
                <p>Start exploring and book your favorite events.</p>
            </div>
        @endif

    </section>
</div>
@endsection