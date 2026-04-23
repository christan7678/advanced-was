@extends('admin.layout')

@section('title', $admin->name . ' — Admin Detail')
@section('page-title', $admin->name)

@section('topbar-actions')
    <a href="{{ route('admin.admins.index') }}" class="btn-outline-sm">← Back to admins</a>
@endsection

@section('content')
<div class="detail-two-col" data-admin-id="{{ $admin->id }}">

    {{-- Left column --}}
    <div style="display: flex; flex-direction: column; gap: 14px;">

        {{-- Profile card --}}
        <div class="detail-card">
            <div style="display:flex; align-items:center; gap:14px; margin-bottom:18px;">
                <div class="sb-avatar" style="width:52px; height:52px; font-size:18px; background:#185FA5;">
                    {{ strtoupper(substr($admin->name, 0, 2)) }}
                </div>
                <div>
                    <div style="font-size:16px; font-weight:700; color:#111827;">{{ $admin->name }}</div>
                    <div style="font-size:13px; color:#6b7280;">{{ $admin->email }}</div>
                    @if($admin->role === 'admin')
                        <span class="badge badge-role-admin" style="margin-top:4px;">Admin</span>
                    @elseif($admin->role === 'super_admin')
                        <span class="badge badge-role-admin" style="margin-top:4px;">Super Admin</span>
                    @endif
                </div>
            </div>

            <div class="detail-row">
                <span class="detail-label">Joined</span>
                <span class="detail-val">{{ $admin->created_at ? $admin->created_at->format('d M Y') : '—' }}</span>
            </div>
        </div>

        {{-- Account actions --}}
        <div class="detail-card" style="border-color:#dbeafe;">
            <div class="detail-card-title">Account actions</div>
            <p style="font-size:13px; color:#6b7280; margin-bottom:14px; line-height:1.6;">
                Manage this account here. You can edit account details or delete the account.
            </p>

            <div style="display:flex; gap:8px; flex-wrap:wrap;">
                <button type="button" class="btn-primary" onclick="openChangePasswordModal()">Change Password </button>
                <button type="button" class="btn-primary" onclick="openEditModal()">Edit Account</button>
                <button type="button" class="btn-danger" onclick="confirmDelete('{{ $admin->name }}', {{ $admin->id }})">Delete Account</button>
                
            </div>
        </div>
    </div>
</div>

{{-- ChangePassword Modal --}}
<div class="modal-backdrop" id="changePassword-modal" style="display:none;">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">Change Password</div>
            <button type="button" class="modal-close" onclick="closeModal('changePassword-modal')">✕</button>
        </div> 

        {{-- Error Message --}}
        <div id="password-error-messages" style="display:none; background:#fee; border:1px solid #fcc; border-radius:4px; padding:12px; margin-bottom:14px; color:#c33;">
            <ul id="password-error-list" style="margin:0; padding-left:20px;"></ul>
        </div>

         {{-- Success Message --}}
        <div id="password-success-message" style="display:none; background:#efe; border:1px solid #cfc; border-radius:4px; padding:12px; margin-bottom:14px; color:#3c3;">
            Password updated successfully!
        </div>

        <form id="change-password-form" action="{{ route('admin.admins.update', $admin->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group form-full">
                    <label>New Password</label>
                    <input id="new-password" name="password" class="form-input" type="password" placeholder="Enter new password" required>
                </div>

                <div class="form-group form-full">
                    <label>Confirm New Password</label>
                    <input id="new-password-confirmation" name="password_confirmation" class="form-input" type="password" placeholder="Confirm new password" required>
                </div>
            </div>

        
            <!-- Security Tips -->
            <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 15px; margin-top: 20px; color: #1e40af; font-size: 13px;">
                <strong>🛡️ Security Tips:</strong>
                    <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                        <li>Use a strong password with letters, numbers, and symbols</li>
                        <li>Never share your password with anyone</li>
                        <li>Change your password regularly for security</li>
                        <li>If you suspect unauthorized access, change immediately</li>
                    </ul>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeModal('changePassword-modal')">Cancel</button>
                <button type="submit" class="btn-primary" id="password-submit-btn">Update Password</button>
            </div>
        </form>
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

        <form id="edit-form" action="{{ route('admin.admins.update', $admin->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <div class="form-group form-full">
                    <label>Full Name</label>
                    <input id="edit-name" class="form-input" type="text" name="name" value="{{ $admin->name }}" required>
                </div>

                <div class="form-group form-full">
                    <label>Email Address</label>
                    <input id="edit-email" class="form-input" type="email" name="email" value="{{ $admin->email }}" required>
                </div>

                <div class="form-group form-full">
                    <label>Phone Number</label>
                    <input id="admin-phone" name="phone_number" class="form-input" type="text" placeholder="Exp: 60123285123" value="{{ $admin->phone_number }}" required>
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
<script src="{{ asset('js/admin-admins-show.js') }}"></script>
@endsection
