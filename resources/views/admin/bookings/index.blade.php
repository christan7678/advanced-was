@extends('admin.layout')

@section('title', 'Bookings')
@section('page-title', 'Bookings')

@section('topbar-actions')
    <span class="topbar-date">{{ $bookings->total() }} total bookings</span>
@endsection

@section('content')
<div data-admin-bookings-base="{{ rtrim(url('/admin/bookings'), '/') }}" style="display:none;"></div>
@if(session('success'))
    <div class="alert alert-success" style="background:#d1fae5;color:#065f46;padding:10px 14px;border-radius:8px;margin-bottom:14px;font-size:13px;">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="alert alert-error" style="background:#fee2e2;color:#991b1b;padding:10px 14px;border-radius:8px;margin-bottom:14px;font-size:13px;">
        {{ session('error') }}
    </div>
@endif

<div class="detail-card" style="margin-bottom:16px;">
    <div class="detail-card-title">{{ $current['title'] }}</div>

    <div class="detail-two-col" style="grid-template-columns: 1fr 1fr; gap: 12px;">
        <div>
            <div class="detail-row">
                <span class="detail-label">Category</span>
                <span class="detail-val">{{ $current['name'] }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Artist / Organiser</span>
                <span class="detail-val">{{ $current['organiser'] }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Venue</span>
                <span class="detail-val">{{ $current['venue'] }}</span>
            </div>
        </div>

        <div>
            <div class="detail-row">
                <span class="detail-label">Date</span>
                <span class="detail-val">{{ $current['date'] }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Time</span>
                <span class="detail-val">{{ $current['time'] }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Tickets Sold</span>
                <span class="detail-val">{{ $current['tickets'] }}</span>
            </div>
        </div>
    </div>
</div>

<div class="stat-grid" style="margin-bottom:18px;">
    <div class="stat-card">
        <div class="stat-label">Total Bookings</div>
        <div class="stat-value">{{ $current['total_bookings'] }}</div>
        <div class="stat-note nothings">All booking records</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Completed</div>
        <div class="stat-value">{{ $current['completed'] }}</div>
        <div class="stat-note positive">Successful payments</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Pending</div>
        <div class="stat-value">{{ $current['pending'] }}</div>
        <div class="stat-note muted">Awaiting payment</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Cancelled</div>
        <div class="stat-value">{{ $current['cancelled'] }}</div>
        <div class="stat-note negative" >Cancelled bookings</div>
    </div>
</div>

<form class="toolbar" method="GET" action="{{ route('admin.bookings.index') }}">
    <input class="toolbar-search" type="text" name="q" value="{{ $q }}" placeholder="Search by booking ID, username, or email...">

    <select class="toolbar-select" name="status">
        <option value="" {{ $status === '' ? 'selected' : '' }}>All status</option>
        <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
        <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
        <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
    </select>
</form>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>User</th>
                <th>Email</th>
                <th>Tickets</th>
                <th>Total Paid</th>
                <th>Booked On</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            @forelse($bookings as $booking)
                @php
                    $event = $booking->event;
                    $user = $booking->user;
                    $statusVal = $booking->payment_status ?: 'pending';
                    $price = $event ? (float) $event->price : 0.0;
                    $total = $price * (int) $booking->number_of_seats;
                @endphp
                <tr>
                    <td>#{{ $booking->id }}</td>
                    <td><div class="td-title">{{ $user->name ?? '—' }}</div></td>
                    <td class="td-sub">{{ $user->email ?? '—' }}</td>
                    <td>{{ $booking->number_of_seats }}</td>
                    <td>RM {{ number_format($total, 2) }}</td>
                    <td>{{ $booking->created_at ? $booking->created_at->format('d M Y') : '—' }}</td>
                    <td>
                        <span class="badge badge-{{ $statusVal }}">{{ ucfirst($statusVal) }}</span>
                    </td>
                    <td>
                        <a href="{{ route('admin.bookings.show', $booking) }}" class="act-btn act-view">View</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="td-empty">No bookings found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top:14px;">
    {{ $bookings->links() }}
</div>

<style>
    .w-5{display: none}
</style>

@endsection

@section('scripts')
<script src="{{ asset('js/admin-bookings.js') }}"></script>
<script src="{{ asset('js/admin-bookings-toolbar.js') }}"></script>
@endsection