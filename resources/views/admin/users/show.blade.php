@extends('admin.layout')

@section('title', 'User Detail')
@section('page-title', 'User Detail')

@section('topbar-actions')
    <a href="{{ route('admin.users.index') }}" class="btn-outline-sm">← Back to users</a>
@endsection

@section('content')
<div class="detail-two-col">

    {{-- Left column --}}
    <div style="display: flex; flex-direction: column; gap: 14px;">

        {{-- Profile card --}}
        <div class="detail-card">
            <div style="display:flex; align-items:center; gap:14px; margin-bottom:18px;">
                <div class="sb-avatar" style="width:52px; height:52px; font-size:18px; background:#185FA5;">
                    AL
                </div>
                <div>
                    <div style="font-size:16px; font-weight:700; color:#111827;">Alice Tan</div>
                    <div style="font-size:13px; color:#6b7280;">alice@gmail.com</div>
                    <span class="badge badge-role-user" style="margin-top:4px;">User</span>
                </div>
            </div>

            <div class="detail-row">
                <span class="detail-label">Account status</span>
                <span class="badge badge-status-active">Active</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Joined</span>
                <span class="detail-val">10 Jan 2026</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Phone</span>
                <span class="detail-val">012-3456789</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Password</span>
                <span class="detail-val">••••••••</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Last login</span>
                <span class="detail-val">14 Apr 2026, 9:30 PM</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Total bookings</span>
                <span class="detail-val">5</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Total spent</span>
                <span class="detail-val">RM920</span>
            </div>
        </div>

        {{-- Account actions --}}
        <div class="detail-card" style="border-color:#dbeafe;">
            <div class="detail-card-title">Account actions</div>
            <p style="font-size:13px; color:#6b7280; margin-bottom:14px; line-height:1.6;">
                Manage this account here. You can edit account details, reset the password, or block access.
            </p>

            <div style="display:flex; gap:8px; flex-wrap:wrap;">
                <button type="button" class="btn-primary" onclick="openEditModal()">Edit Account</button>
                <button type="button" class="btn-outline-sm" onclick="openResetModal()">Reset Password</button>
                <button type="button" class="btn-danger" onclick="openBanModal()">Ban Account</button>
            </div>
        </div>
    </div>

    {{-- Right column --}}
    <div style="display: flex; flex-direction: column; gap: 14px;">

        {{-- Booking history --}}
        <div class="detail-card">
            <div class="detail-card-title">Booking history</div>

            <div class="recent-row">
                <div class="rec-dot status-upcoming"></div>
                <div class="rec-info">
                    <div class="rec-name">Music Festival 2026</div>
                    <div class="rec-sub">2 ticket(s) · RM376 · 12 Apr 2026</div>
                </div>
                <span class="badge badge-upcoming">Upcoming</span>
            </div>

            <div class="recent-row">
                <div class="rec-dot status-completed"></div>
                <div class="rec-info">
                    <div class="rec-name">Startup Summit</div>
                    <div class="rec-sub">1 ticket(s) · RM80 · 05 Apr 2026</div>
                </div>
                <span class="badge badge-completed">Completed</span>
            </div>

            <div class="recent-row">
                <div class="rec-dot status-cancelled"></div>
                <div class="rec-info">
                    <div class="rec-name">Art Expo</div>
                    <div class="rec-sub">2 ticket(s) · RM240 · 01 Apr 2026</div>
                </div>
                <span class="badge badge-cancelled">Cancelled</span>
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

        <form>
            <div class="form-grid">
                <div class="form-group form-full">
                    <label>Full Name</label>
                    <input class="form-input" type="text" value="Alice Tan">
                </div>

                <div class="form-group form-full">
                    <label>Email Address</label>
                    <input class="form-input" type="email" value="alice@gmail.com">
                </div>

                <div class="form-group">
                    <label>Role</label>
                    <select class="form-input">
                        <option selected>User</option>
                        <option>Admin</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select class="form-input">
                        <option selected>Active</option>
                        <option>Banned</option>
                    </select>
                </div>

                <div class="form-group form-full">
                    <label>Phone</label>
                    <input class="form-input" type="text" value="012-3456789">
                </div>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeModal('edit-modal')">Cancel</button>
                <button type="button" class="btn-primary">Update Account</button>
            </div>
        </form>
    </div>
</div>

{{-- Reset Password Modal --}}
<div class="modal-backdrop" id="reset-modal" style="display:none;">
    <div class="modal modal-sm">
        <div class="modal-header" style="margin-bottom:10px;">
            <div class="modal-title">Reset Password</div>
            <button type="button" class="modal-close" onclick="closeModal('reset-modal')">✕</button>
        </div>

        <div class="modal-sub">
            For security, do not display the old password. Set a new password for this account.
        </div>

        <form>
            <div class="form-group" style="margin-bottom:12px;">
                <label>New Password</label>
                <input class="form-input" type="password" placeholder="Enter new password">
            </div>

            <div class="form-group">
                <label>Confirm Password</label>
                <input class="form-input" type="password" placeholder="Confirm new password">
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeModal('reset-modal')">Cancel</button>
                <button type="button" class="btn-primary">Save Password</button>
            </div>
        </form>
    </div>
</div>

{{-- Ban Account Modal --}}
<div class="modal-backdrop" id="ban-modal" style="display:none;">
    <div class="modal modal-sm">
        <div class="modal-icon-danger">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>
            </svg>
        </div>
        <div class="modal-title text-center">Ban this account?</div>
        <div class="modal-sub text-center">
            This account will no longer be able to log in or use the system. UI only popup.
        </div>
        <div class="modal-actions centered">
            <button type="button" class="btn-cancel" onclick="closeModal('ban-modal')">Cancel</button>
            <button type="button" class="btn-danger">Yes, ban account</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function openEditModal() {
    document.getElementById('edit-modal').style.display = 'flex';
}

function openResetModal() {
    document.getElementById('reset-modal').style.display = 'flex';
}

function openBanModal() {
    document.getElementById('ban-modal').style.display = 'flex';
}

function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}
</script>
@endsection