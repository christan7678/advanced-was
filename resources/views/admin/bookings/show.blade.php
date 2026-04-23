@extends('admin.layout')

@section('title', 'Booking Detail')
@section('page-title', 'Booking Detail')

@section('topbar-actions')
    <a href="{{ route('admin.bookings.index') }}" class="btn-outline-sm">← Back</a>
@endsection

@section('content')
<div data-admin-bookings-base="{{ rtrim(url('/admin/bookings'), '/') }}" style="display:none;"></div>
<div class="detail-two-col">

    <div style="display: flex; flex-direction: column; gap: 14px;">
        <div class="detail-card">
            <div class="detail-card-title">Booking Info</div>

            <div class="detail-row">
                <span class="detail-label">Booking ID</span>
                <span class="detail-val">#{{ $booking->id }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Booking Code</span>
                <span class="detail-val">{{ $booking->booking_code }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Status</span>
                @php $statusVal = $booking->booking_status ?: 'pending'; @endphp
                <span class="badge badge-{{ $statusVal }}">{{ ucfirst($statusVal) }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Tickets</span>
                <span class="detail-val">{{ $booking->number_of_seats }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Total Paid</span>
                @php
                    $price = $booking->event ? (float) $booking->event->price : 0.0;
                    $total = $price * (int) $booking->number_of_seats;
                @endphp
                <span class="detail-val">RM {{ number_format($total, 2) }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Booked On</span>
                <span class="detail-val">{{ $booking->created_at ? $booking->created_at->format('d M Y') : '—' }}</span>
            </div>
        </div>
        <div class="detail-card">
            <div class="detail-card-title">Payment</div>

            <div class="detail-row">
                <span class="detail-label">Payment Code</span>
                <span class="detail-val">{{ $booking->payment->payment_code ?? '—' }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Payment Method</span>
                <span class="detail-val">{{ $booking->payment->payment_method ?? '—' }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Payment Status</span>
                <span class="detail-val">
                    @php
                        $paymentStatus = $booking->payment_status ?? 'unknown';
                    @endphp
                    <span class="badge badge-{{ $paymentStatus }}">
                        {{ ucfirst($paymentStatus) }}
                    </span>
                </span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Amount</span>
                <span class="detail-val">
                    RM {{ number_format((float) ($booking->payment->amount ?? $booking->total_amount ?? 0), 2) }}
                </span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Paid At</span>
                <span class="detail-val">
                    {{ optional(optional($booking->payment)->paid_at)->format('d M Y H:i') ?? '—' }}
                </span>
            </div>
        </div>

        <div class="detail-card">
            <div class="detail-card-title">User</div>

            <div style="display:flex; align-items:center; gap:12px; margin-bottom:14px;">
                @php
                    $name = (string) ($booking->user->name ?? '—');
                    $initials = strtoupper(substr($name, 0, 1) . substr($name, 1, 1));
                    if (trim($initials) === '') $initials = 'US';
                @endphp
                <div class="sb-avatar" style="width:40px;height:40px;font-size:14px;">{{ $initials }}</div>
                <div>
                    <div class="td-title">{{ $booking->user->name ?? '—' }}</div>
                    <div class="td-sub">{{ $booking->user->email ?? '—' }}</div>
                </div>
            </div>

            <a  href="{{ route('admin.users.show',  $booking->user->id) }}" class="btn-outline-sm">
                View user profile
            </a>
        </div>
    </div>

    <div style="display: flex; flex-direction: column; gap: 14px;">
        <div class="detail-card">
            <div class="detail-card-title">Event</div>

            <div class="detail-row">
                <span class="detail-label">Title</span>
                <span class="detail-val">{{ $booking->event->name ?? '—' }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Artist</span>
                <span class="detail-val">{{ $booking->event->organizer ?? '—' }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Date</span>
                <span class="detail-val">
                    {{ $booking->event && $booking->event->date ? $booking->event->date->format('d M Y') : '—' }}
                    @if($booking->event && $booking->event->time)
                        · {{ substr($booking->event->time, 0, 5) }}
                    @endif
                </span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Venue</span>
                <span class="detail-val">{{ $booking->event->venue ?? '—' }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Category</span>
                <span class="detail-val">{{ $booking->event && $booking->event->category ? $booking->event->category->name : '—' }}</span>
            </div>
        </div>

        <div class="detail-card" style="border-color:#fca5a5;">
            <div class="detail-card-title">Actions</div>
            <p style="font-size:13px; color:#6b7280; margin-bottom:14px; line-height:1.6;">
                Admin can cancel or remove this booking record here.
            </p>

            <div style="display:flex; gap:8px; flex-wrap:wrap;">
            @if(($booking->booking_status ?? 'pending') !== 'cancelled')
                <button type="button"
                    class="btn-warning"
                    onclick='openCancelModal({{ $booking->id }}, @json($booking->user->name ?? ""))'>
                    Cancel Booking
                </button>
            @endif

            <button type="button"
                class="btn-danger"
                onclick='openDeleteModal({{ $booking->id }}, @json($booking->user->name ?? ""))'>
                Delete Booking
            </button>

        </div>
    </div>

</div>

{{-- Cancel Modal --}}
<div class="modal-backdrop" id="cancel-modal" style="display:none;">
    <div class="modal modal-sm">
        <div class="modal-icon-warning">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
        </div>
        <div class="modal-title text-center">Cancel this booking?</div>
        <div class="modal-sub text-center" id="cancel-modal-sub">This action cannot be undone.</div>
        <div class="modal-actions centered">
            <button type="button" class="btn-cancel" onclick="closeModal('cancel-modal')">Go back</button>
            <form id="cancel-form" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn-warning">Yes, cancel it</button>
            </form>
        </div>
    </div>
</div>

{{-- Delete Modal --}}
<div class="modal-backdrop" id="delete-modal" style="display:none;">
    <div class="modal modal-sm">
        <div class="modal-icon-danger">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2">
                <polyline points="3 6 5 6 21 6"/>
                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                <path d="M10 11v6M14 11v6M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
            </svg>
        </div>
        <div class="modal-title text-center">Delete booking?</div>
        <div class="modal-sub text-center" id="delete-modal-sub">This action cannot be undone.</div>
        <div class="modal-actions centered">
            <button type="button" class="btn-cancel" onclick="closeModal('delete-modal')">Cancel</button>
            <form id="delete-form" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">Yes, delete</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/admin-bookings.js') }}"></script>
@endsection