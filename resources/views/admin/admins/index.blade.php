@extends('admin.layout')

@section('title', 'Admin Accounts')
@section('page-title', 'Admin Accounts')

@section('topbar-actions')
    <span class="topbar-date">total {{ $admins->count() }} admins </span>

    <div class="topbar-action-wrap">
        <button type="button" class="btn-primary" onclick="openAdminModal()">
            + Add Admin
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

{{-- ADMINS SECTION --}}
<div class="account-section">
    <button type="button" class="account-section-header">
        <div>
            <div class="account-section-title">Admins</div>
            <div class="account-section-sub">Administrative accounts only</div>
        </div>
        <div class="account-section-right">
            <span class="account-section-count" id="adminsCountText">{{ $admins->count() }} accounts</span>
        </div>
    </button>

    <div id="adminsSection">
        <div class="table">
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
                        <th>Admin</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Joined</th>
                        <th class="actions-col">Actions</th>
                    </tr>
                </thead>
                <tbody id="adminsTableBody">
                    @forelse($admins as $admin)
                        <tr data-type="admin" data-name="{{ $admin->name }}" data-email="{{ $admin->email }}">
                            <td>
                                <div class="account-person">
                                    <div class="user-avatar" style="background:#185FA5;">
                                        {{ strtoupper(substr($admin->name, 0, 2)) }}
                                    </div>
                                    <span class="td-title">{{ $admin->name }}</span>
                                </div>
                            </td>
                            <td class="td-sub">{{ $admin->email }}</td>
                            <td>{{ $admin->phone_number }}</td>
                            <td>{{ $admin->created_at ? $admin->created_at->format('d M Y') : '—' }}</td>
                            <td class="actions-cell">
                                <div class="action-group action-group-tight">
                                    <button type="button" class="act-btn" onclick="openEditModal('{{ $admin->name }}', '{{ $admin->email }}', '{{ $admin->phone_number }}','{{ $admin->id }}')">Edit</button>
                                    <a href="{{ route('admin.admins.show', $admin->id) }}" class="act-btn act-view">View</a>
                                    <button type="button" class="act-btn act-del" onclick="confirmDelete('{{ $admin->name }}', '{{ $admin->id }}')">Delete </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="noAdminsRow">
                            <td colspan="5" class="td-empty">No admins found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="pagination-row" style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-top:14px;">
        <div class="page-info" id="adminsPageInfo">Showing {{ $admins->count() }} account(s)</div>

        <div id="adminsPaginationControls" class="pagination-controls"
            style="display:flex;gap:6px;align-items:center;flex-wrap:wrap;">
        </div>
    </div>
</div>

{{-- add/ edit admin account modal --}}
<div class="modal-backdrop" id="admin-modal" style="display:none;">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">Add Admin</div>
            <button type="button" class="modal-close" onclick="closeAdminModal()">✕</button>
        </div>

        <div id="admin-error-messages" style="display:none; background:#fee; border:1px solid #fcc; border-radius:4px; padding:12px; margin-bottom:14px; color:#c33;">
            <ul id="admin-error-list" style="margin:0; padding-left:20px;"></ul>
        </div>

        <div id="admin-success-message" style="display:none; background:#efe; border:1px solid #cfc; border-radius:4px; padding:12px; margin-bottom:14px; color:#3c3;">
            Admin account created successfully!
        </div>

        <form id="admin-form">
            @csrf
            <div class="form-grid">
                <div class="form-group form-full">
                    <label>Full Name</label>
                    <input id="admin-name" name="name" class="form-input" type="text" placeholder="Enter full name" required>
                </div>

                <div class="form-group form-full">
                    <label>Email Address</label>
                    <input id="admin-email" name="email" class="form-input" type="email" placeholder="Enter email address" required>
                </div>

                <div class="form-group form-full">
                    <label>Phone Number</label>
                    <input id="admin-phone" name="phone_number" class="form-input" type="text" placeholder="Exp: 60123285123" required>
                </div>

                <div class="form-group form-full">
                    <label>Password</label>
                    <input id="admin-password" name="password" class="form-input" type="password" placeholder="Enter password" required>
                </div>

                <div class="form-group form-full">
                    <label>Confirm Password</label>
                    <input id="admin-password-confirmation" name="password_confirmation" class="form-input" type="password" placeholder="Confirm password" required>
                </div>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeAdminModal()">Cancel</button>
                <button type="submit" class="btn-primary" id="admin-submit-btn">Save Admin</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/admin-admins-index.js') }}"></script>
@endsection


@section('styles')
<style>
    .actions-col {
        text-align: center;
    }

    .actions-cell {
        text-align: right;
    }

    .action-group-tight {
        display: inline-flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        white-space: nowrap;
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