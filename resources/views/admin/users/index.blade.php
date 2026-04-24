@extends('admin.layout')

@section('title', 'Accounts')
@section('page-title', 'Accounts')

@section('topbar-actions')
    <span class="topbar-date">total {{ $users->count() }} users</span>

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
        <div class="table accounts-table-wrap">
            <table class="accounts-table">
                <colgroup>
                    <col style="width: 25%;">
                    <col style="width: 22%;">
                    <col style="width: 11%;">
                    <col style="width: 12%;">
                    <col style="width: 30%;">
                </colgroup>

                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Bookings</th>
                        <th>Joined</th>
                        <th class="actions-col">Actions</th>
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
                            <td class="td-sub email-cell">{{ $user->email }}</td>
                            <td>{{ $user->bookings()->count() }}</td>
                            <td>{{ $user->created_at ? $user->created_at->format('d M Y') : '—' }}</td>
                            <td class="actions-cell">
                                <div class="action-group action-group-tight">
                                    <button type="button" class="act-btn" onclick="openEditModal('{{ $user->name }}', '{{ $user->email }}', '{{ $user->phone_number }}',{{ $user->id }})">Edit</button>
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
    <div class="pagination-row" style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-top:14px;">
        <div class="page-info" id="usersPageInfo">Showing {{ $users->count() }} account(s)</div>

        <div id="usersPaginationControls" class="pagination-controls" style="display:flex;gap:6px;align-items:center;flex-wrap:wrap;">
        </div>
    </div>
</div>

{{-- Add / Edit User Account Modal --}}
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
            User account created successfully!
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

                <div class="form-group form-full">
                    <label>phone number</label>
                    <input id="account-phone" name="phone_number" class="form-input" type="text" placeholder="Exp: 60123251236" required>
                </div>
                

                <div id="password-section">
                    <div class="form-group form-full">
                        <label>Password <span id="password-required" style="color: red;">*</span></label>
                        <input id="password-field" name="password" class="form-input" type="password" placeholder="Enter password" required>
                    </div>

                    <div class="form-group form-full" >
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

@section('styles')
<style>
    .accounts-table {
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
    }

    .accounts-table th,
    .accounts-table td {
        vertical-align: middle;
    }

    .accounts-table th {
        text-align: left;
        white-space: nowrap;
    }

    .accounts-table
    .accounts-table .actions-cell {
        text-align: right;
    }

    .accounts-table .email-cell {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .account-person {
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 0;
    }

    .td-title {
        display: inline-block;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .action-group-tight {
        display: inline-flex;
        justify-content: flex-end;
        align-items: center;
        gap: 12px;
        width: auto;
        white-space: nowrap;
    }

    .action-group-tight .act-btn {
        min-width: 96px;
        text-align: center;
    }

    .actions-col {
        text-align: center;
    }

    .pagination-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 18px;
    }

    .pagination-controls {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
        flex-wrap: wrap;
    }

    .pagination-btn {
        min-width: 46px;
        height: 42px;
        padding: 0 14px;
        border: 1px solid #d1d5db;
        background: #ffffff;
        color: #374151;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .pagination-btn:hover:not(:disabled) {
        background: #f9fafb;
        border-color: #9ca3af;
    }

    .pagination-btn.active {
        background: #0f172a;
        color: #ffffff;
        border-color: #0f172a;
    }

    .pagination-btn:disabled {
        opacity: 0.45;
        cursor: not-allowed;
    }

    .pagination-ellipsis {
        padding: 0 2px;
        color: #6b7280;
        font-weight: 700;
        user-select: none;
    }
</style>
@endsection