@extends('admin.layout')

@section('title', 'Booking Detail')
@section('page-title', 'Booking Detail')

@section('topbar-actions')
    <a href="{{ route('admin.bookings.index') }}" class="btn-outline-sm">← Back to bookings</a>
@endsection

@section('content')
<div class="detail-two-col">

    <div style="display: flex; flex-direction: column; gap: 14px;">
        <div class="detail-card">
            <div class="detail-card-title">Booking Info</div>

            <div class="detail-row">
                <span class="detail-label">Booking ID</span>
                <span class="detail-val">#B001</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Status</span>
                <span class="badge badge-pending">Pending</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Tickets</span>
                <span class="detail-val">2</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Total Paid</span>
                <span class="detail-val">RM376</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Booked On</span>
                <span class="detail-val">12 Apr 2026</span>
            </div>
        </div>

        <div class="detail-card">
            <div class="detail-card-title">User</div>

            <div style="display:flex; align-items:center; gap:12px; margin-bottom:14px;">
                <div class="sb-avatar" style="width:40px;height:40px;font-size:14px;">AL</div>
                <div>
                    <div class="td-title">Alice Tan</div>
                    <div class="td-sub">alice@gmail.com</div>
                </div>
            </div>

            <a href="{{ route('admin.users.index') }}" class="btn-outline-sm">
                View user profile
            </a>
        </div>
    </div>

    <div style="display: flex; flex-direction: column; gap: 14px;">
        <div class="detail-card">
            <div class="detail-card-title">Event</div>

            <div class="detail-row">
                <span class="detail-label">Title</span>
                <span class="detail-val">Music Festival 2026</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Artist</span>
                <span class="detail-val">Coldplay</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Date</span>
                <span class="detail-val">20 May 2026</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Venue</span>
                <span class="detail-val">Kuala Lumpur</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Category</span>
                <span class="detail-val">Concert</span>
            </div>
        </div>

        <div class="detail-card" style="border-color:#fca5a5;">
            <div class="detail-card-title">Actions</div>
            <p style="font-size:13px; color:#6b7280; margin-bottom:14px; line-height:1.6;">
                Admin can cancel or remove this booking record here.
            </p>

            <div style="display:flex; gap:8px; flex-wrap:wrap;">
                <button type="button" class="btn-warning" onclick="openCancelModal()">Cancel Booking</button>
                <button type="button" class="btn-danger" onclick="openDeleteModal()">Delete Booking</button>
            </div>
        </div>
    </div>

</div>

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
        <div class="modal-sub text-center">This is UI only popup.</div>
        <div class="modal-actions centered">
            <button type="button" class="btn-cancel" onclick="closeModal('cancel-modal')">Go back</button>
            <button type="button" class="btn-warning">Yes, cancel it</button>
        </div>
    </div>
</div>

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
        <div class="modal-sub text-center">This is UI only popup.</div>
        <div class="modal-actions centered">
            <button type="button" class="btn-cancel" onclick="closeModal('delete-modal')">Cancel</button>
            <button type="button" class="btn-danger">Yes, delete</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openCancelModal() {
        document.getElementById('cancel-modal').style.display = 'flex';
    }

    function openDeleteModal() {
        document.getElementById('delete-modal').style.display = 'flex';
    }

    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }
</script>
@endsection