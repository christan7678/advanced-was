@extends('admin.layout')

@section('title', 'Accounts')
@section('page-title', 'Accounts')

@section('topbar-actions')
    <span class="topbar-date">{{ $users->count() }} total accounts</span>

    <div class="topbar-action-wrap">
        <button type="button" class="btn-primary" onclick="openAccountModal('user')">
            + Add User
        </button>
    </div>
@endsection

@section('content')
<form class="toolbar" onsubmit="return false;">
    <input
        id="accountSearch"
        class="toolbar-search"
        type="text"
        placeholder="Search by name or email..."
    >
</form>

{{-- USERS SECTION --}}
<div class="account-section">
    <button type="button" class="account-section-header">
        <div>
            <div class="account-section-title">Users</div>
            <div class="account-section-sub">Customer accounts only</div>
        </div>
        <div class="account-section-right">
            <span class="account-section-count" id="usersCountText">{{ $users->count() }} accounts</span>
        </div>
    </button>

    <div id="usersSection">
        <div class="table">
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Bookings</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody">
                    @forelse($users as $user)
                        <tr data-type="user" data-name="{{ $user->name }}" data-email="{{ $user->email }}">
                            <td>
                                <div class="account-person">
                                    <div class="user-avatar" style="background:#185FA5;">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <span class="td-title">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="td-sub">{{ $user->email }}</td>
                            <td>{{ $user->bookings()->count() }}</td>
                            <td>{{ $user->created_at ? $user->created_at->format('d M Y') : '—' }}</td>
                            <td>
                                <div class="action-group">
                                    <button type="button" class="act-btn" onclick="openEditModal('{{ $user->name }}', '{{ $user->email }}', {{ $user->id }})">Edit</button>
                                    <a href="{{ route('admin.users.show', $user->id) }}" class="act-btn act-view">View</a>
                                    <button type="button" class="act-btn act-del" onclick="confirmDelete('{{ $user->name }}', {{ $user->id }})">Delete</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="noUsersRow">
                            <td colspan="5" class="td-empty">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Add / Edit Account Modal --}}
<div class="modal-backdrop" id="account-modal" style="display:none;">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title" id="account-modal-title">Add Account</div>
            <button type="button" class="modal-close" onclick="closeModal('account-modal')">✕</button>
        </div>

        {{-- Error messages --}}
        <div id="error-messages" style="display:none; background:#fee; border:1px solid #fcc; border-radius:4px; padding:12px; margin-bottom:14px; color:#c33;">
            <ul id="error-list" style="margin:0; padding-left:20px;"></ul>
        </div>

        {{-- Success message --}}
        <div id="success-message" style="display:none; background:#efe; border:1px solid #cfc; border-radius:4px; padding:12px; margin-bottom:14px; color:#3c3;">
            Account created successfully!
        </div>

        <form id="account-form" action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="form-grid">
                <div class="form-group form-full">
                    <label>Full Name</label>
                    <input id="account-name" name="name" class="form-input" type="text" placeholder="Enter full name" required>
                </div>

                <div class="form-group form-full">
                    <label>Email Address</label>
                    <input id="account-email" name="email" class="form-input" type="email" placeholder="Enter email address" required>
                </div>

                <div id="password-section">
                    <div class="form-group form-full">
                        <label>Password <span id="password-required" style="color: red;">*</span></label>
                        <input id="password-field" name="password" class="form-input" type="password" placeholder="Enter password" required>
                    </div>

                    <div class="form-group form-full">
                        <label>Confirm Password <span id="password-confirm-required" style="color: red;">*</span></label>
                        <input id="password-confirmation-field" name="password_confirmation" class="form-input" type="password" placeholder="Confirm password" required>
                    </div>
                </div>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeModal('account-modal')">Cancel</button>
                <button type="submit" class="btn-primary" id="account-submit-btn">Save Account</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('js/admin-users-index.js') }}"></script>
@endsection