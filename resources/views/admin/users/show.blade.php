@extends('admin.layout')

@section('title', $user->name . ' — User Detail')
@section('page-title', $user->name)

@section('topbar-actions')
    <a href="{{ route('admin.users.index') }}" class="btn-outline-sm">← Back to users</a>
@endsection

@section('content')
<div class="detail-two-col" data-user-id="{{ $user->id }}">

    {{-- Left column --}}
    <div style="display: flex; flex-direction: column; gap: 14px;">

        {{-- Profile card --}}
        <div class="detail-card">
            <div style="display:flex; align-items:center; gap:14px; margin-bottom:18px;">
                <div class="sb-avatar" style="width:52px; height:52px; font-size:18px; background:#185FA5;">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <div>
                    <div style="font-size:16px; font-weight:700; color:#111827;">{{ $user->name }}</div>
                    <div style="font-size:13px; color:#6b7280;">{{ $user->email }}</div>
                    <span class="badge badge-role-user" style="margin-top:4px;">User</span>
                </div>
            </div>

            <div class="detail-row">
                <span class="detail-label">Joined</span>
                <span class="detail-val">{{ $user->created_at ? $user->created_at->format('d M Y') : '—' }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Total bookings</span>
                <span class="detail-val" id="booking-count">{{ $user->bookings->count() }}</span>
            </div>
        </div>

        {{-- Account actions --}}
        <div class="detail-card" style="border-color:#dbeafe;">
            <div class="detail-card-title">Account actions</div>
            <p style="font-size:13px; color:#6b7280; margin-bottom:14px; line-height:1.6;">
                Manage this account here. You can edit account details or delete the account.
            </p>

            <div style="display:flex; gap:8px; flex-wrap:wrap;">
                <button type="button" class="btn-primary" onclick="openEditModal()">Edit Account</button>
                <button type="button" class="btn-danger" onclick="confirmDelete('{{ $user->name }}', {{ $user->id }})">Delete Account</button>
            </div>
        </div>
    </div>

    {{-- Right column --}}
    <div style="display: flex; flex-direction: column; gap: 14px;">

        {{-- Booking history --}}
        <div class="detail-card">
            <div class="detail-card-title">Booking history</div>
            <div id="booking-list">
                @forelse($user->bookings as $booking)
                    <div class="recent-row">
                        <div class="rec-dot status-upcoming"></div>
                        <div class="rec-info">
                            <div class="rec-name">{{ $booking->event->name ?? '—' }}</div>
                            <div class="rec-sub">{{ $booking->number_of_seats ?? 1 }} ticket(s) · {{ $booking->event->category->name ?? '—' }} · {{ $booking->created_at ? $booking->created_at->format('d M Y') : '—' }}</div>
                        </div>
                        <span class="badge badge-upcoming">{{ ucfirst($booking->payment_status) }}</span>
                    </div>
                @empty
                    <div style="text-align:center; padding:20px; color:#9ca3af;">
                        No bookings found for this user.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</div>

{{-- Edit Account Modal --}}
<div class="modal-backdrop" id="edit-modal" style="display:none;">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">Edit Account</div>
            <button type="button" class="modal-close" onclick="closeModal('edit-modal')">✕</button>
        </div>

        {{-- Error messages --}}
        <div id="edit-error-messages" style="display:none; background:#fee; border:1px solid #fcc; border-radius:4px; padding:12px; margin-bottom:14px; color:#c33;">
            <ul id="edit-error-list" style="margin:0; padding-left:20px;"></ul>
        </div>

        {{-- Success message --}}
        <div id="edit-success-message" style="display:none; background:#efe; border:1px solid #cfc; border-radius:4px; padding:12px; margin-bottom:14px; color:#3c3;">
            Account updated successfully!
        </div>

        <form id="edit-form" action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <div class="form-group form-full">
                    <label>Full Name</label>
                    <input id="edit-name" class="form-input" type="text" name="name" value="{{ $user->name }}" required>
                </div>

                <div class="form-group form-full">
                    <label>Email Address</label>
                    <input id="edit-email" class="form-input" type="email" name="email" value="{{ $user->email }}" required>
                </div>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeModal('edit-modal')">Cancel</button>
                <button type="submit" class="btn-primary" id="edit-submit-btn">Update Account</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('js/admin-users-show.js') }}"></script>
@endsection
