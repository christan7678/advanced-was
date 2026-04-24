@extends('admin.layout')

@section('title', 'Bookings')
@section('page-title', 'Bookings')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/admin-dashboard.css') }}">
@endpush
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/admin-bookings.css') }}">
@endpush

@section('topbar-actions')
    @if(request()->has('event_id'))
        <a href="{{ url()->previous() }}" class="btn-outline-sm">← Back</a>
    @endif

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

<div class="bookings-stat-grid">
    <div class="stat-card">
        <div class="stat-label">Total Bookings</div>
        <div class="stat-value">{{ $current['total_bookings'] }}</div>
        <div class="stat-note nothings">All booking records</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Confirmed</div>
        <div class="stat-value">{{ $current['confirmed'] }}</div>
        <div class="stat-note positive">Confirmed bookings</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Pending</div>
        <div class="stat-value">{{ $current['pending'] }}</div>
        <div class="stat-note muted">Awaiting confirmation</div>
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
        <option value="confirmed" {{ $status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
        <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
    </select>
</form>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Booking Code</th>
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
                    $statusVal = $booking->booking_status ?: 'pending';
                @endphp

                <tr>
                    <td>{{ $booking->id }}</td>
                    <td>{{ $booking->booking_code }}</td>
                    <td><div class="td-title">{{ $user->name ?? '—' }}</div></td>
                    <td class="td-sub">{{ $user->email ?? '—' }}</td>
                    <td>{{ $booking->number_of_seats }}</td>
                    <td>RM {{ number_format($booking->total_amount ?? 0, 2) }}</td>
                    <td>{{ $booking->created_at ? $booking->created_at->format('d M Y') : '—' }}</td>
                    <td>
                        <span class="badge badge-{{ $statusVal }}">{{ ucfirst($statusVal) }}</span>
                    </td>
                    <td>
                        <a href="{{ route('admin.bookings.show', $booking->id) }}" class="act-btn act-view">View</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="td-empty">No bookings found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-row">
    <div class="page-info">
        Showing {{ $bookings->firstItem() }} to {{ $bookings->lastItem() }} of {{ $bookings->total() }} account(s)
    </div>

    <div class="pagination-controls">
        {{-- Previous --}}
        <a href="{{ $bookings->previousPageUrl() }}"
           class="pagination-btn {{ $bookings->onFirstPage() ? 'disabled' : '' }}">
            Prev
        </a>

        {{-- Page Numbers --}}
        @for ($i = 1; $i <= $bookings->lastPage(); $i++)
            @if ($i == 1 || $i == $bookings->lastPage() || abs($i - $bookings->currentPage()) <= 1)
                <a href="{{ $bookings->url($i) }}"
                   class="pagination-btn {{ $bookings->currentPage() == $i ? 'active' : '' }}">
                    {{ $i }}
                </a>
            @elseif ($i == 2 || $i == $bookings->lastPage() - 1)
                <span class="pagination-ellipsis">...</span>
            @endif
        @endfor

        {{-- Next --}}
        <a href="{{ $bookings->nextPageUrl() }}"
           class="pagination-btn {{ !$bookings->hasMorePages() ? 'disabled' : '' }}">
            Next
        </a>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('js/admin-bookings.js') }}"></script>
<script src="{{ asset('js/admin-bookings-toolbar.js') }}"></script>
@endsection