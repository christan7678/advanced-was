@extends('admin.layout')

@section('title', 'Accounts')
@section('page-title', 'Accounts')

@section('topbar-actions')
    <span class="topbar-date">860 total accounts</span>

    <div class="topbar-action-wrap">
        <button type="button" class="btn-primary" onclick="toggleAddAccountMenu()">
            + Add Account
        </button>

        <div class="topbar-action-menu" id="addAccountMenu" style="display:none;">
            <button type="button" class="topbar-action-item" onclick="openAccountModal('user')">
                Add User
            </button>
            <button type="button" class="topbar-action-item" onclick="openAccountModal('admin')">
                Add Admin
            </button>
        </div>
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

    <select id="statusFilter" class="toolbar-select">
        <option value="">All status</option>
        <option value="active">Active</option>
        <option value="banned">Banned</option>
    </select>

    <button type="button" class="btn-outline-sm" onclick="filterAccounts()">Filter</button>
    <button type="button" class="btn-outline-sm" onclick="resetFilters()">Reset</button>
</form>

{{-- ADMINS SECTION --}}
<div class="account-section" style="margin-top:18px;">
    <button type="button" class="account-section-header" onclick="toggleSection('adminsSection', 'adminsArrow')">
        <div>
            <div class="account-section-title">Admins</div>
            <div class="account-section-sub">System management accounts</div>
        </div>
        <div class="account-section-right">
            <span class="account-section-count" id="adminsCountText">2 accounts</span>
            <span class="account-section-arrow" id="adminsArrow">⌄</span>
        </div>
    </button>

    <div id="adminsSection" style="display:none;">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Admin</th>
                        <th>Email</th>
                        <th>Permission</th>
                        <th>Joined</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody id="adminsTableBody">





                    <tr data-type="admin" data-name="Bryan Lee" data-email="bryan@gmail.com" data-status="active">
                        <td>
                            <div class="account-person">
                                <div class="user-avatar" style="background:#185FA5;">BR</div>
                                <span class="td-title">Bryan Lee</span>
                            </div>
                        </td>
                        <td class="td-sub">bryan@gmail.com</td>
                        <td><span class="badge badge-role-admin">Admin</span></td>
                        <td>02 Jan 2026</td>
                        <td><span class="badge badge-status-active">Active</span></td>
                        <td>
                            <div class="action-group">
                                <button type="button" class="act-btn">Edit</button>
                                <a href="{{ route('admin.users.show') }}" class="act-btn act-view">View</a>
                                <button type="button" class="act-btn act-del">Ban</button>
                            </div>
                        </td>
                    </tr>



                    <tr data-type="admin" data-name="Admin Jane" data-email="jane.admin@gmail.com" data-status="active">
                        <td>
                            <div class="account-person">
                                <div class="user-avatar" style="background:#185FA5;">JA</div>
                                <span class="td-title">Admin Jane</span>
                            </div>
                        </td>
                        <td class="td-sub">jane.admin@gmail.com</td>
                        <td><span class="badge badge-role-admin">Super Admin</span></td>
                        <td>18 Jan 2026</td>
                        <td><span class="badge badge-status-active">Active</span></td>
                        <td>
                            <div class="action-group">
                                <button type="button" class="act-btn">Edit</button>
                                <a href="{{ route('admin.users.show') }}" class="act-btn act-view">View</a>
                                <button type="button" class="act-btn act-del">Ban</button>
                            </div>
                        </td>
                    </tr>





                    <tr id="noAdminsRow" style="display:none;">
                        <td colspan="6" class="td-empty">No matching admins found.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- USERS SECTION --}}
<div class="account-section">
    <button type="button" class="account-section-header" onclick="toggleSection('usersSection', 'usersArrow')">
        <div>
            <div class="account-section-title">Users</div>
            <div class="account-section-sub">Customer accounts only</div>
        </div>
        <div class="account-section-right">
            <span class="account-section-count" id="usersCountText">3 accounts</span>
            <span class="account-section-arrow" id="usersArrow">⌄</span>
        </div>
    </button>

    <div id="usersSection">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Bookings</th>
                        <th>Joined</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>




                <tbody id="usersTableBody">



                    <tr data-type="user" data-name="Alice Tan" data-email="alice@gmail.com" data-status="active">
                        <td>
                            <div class="account-person">
                                <div class="user-avatar" style="background:#185FA5;">AL</div>
                                <span class="td-title">Alice Tan</span>
                            </div>
                        </td>
                        <td class="td-sub">alice@gmail.com</td>
                        <td>5</td>
                        <td>10 Jan 2026</td>
                        <td><span class="badge badge-status-active">Active</span></td>
                        <td>
                            <div class="action-group">
                                <button type="button" class="act-btn">Edit</button>
                                <a href="{{ route('admin.users.show') }}" class="act-btn act-view">View</a>
                                <button type="button" class="act-btn act-del">Ban</button>
                            </div>
                        </td>
                    </tr>




                    <tr data-type="user" data-name="Chloe Lim" data-email="chloe@gmail.com" data-status="banned">
                        <td>
                            <div class="account-person">
                                <div class="user-avatar" style="background:#185FA5;">CL</div>
                                <span class="td-title">Chloe Lim</span>
                            </div>
                        </td>
                        <td class="td-sub">chloe@gmail.com</td>
                        <td>2</td>
                        <td>15 Feb 2026</td>
                        <td><span class="badge badge-status-banned">Banned</span></td>
                        <td>
                            <div class="action-group">
                                <button type="button" class="act-btn">Edit</button>
                                <a href="{{ route('admin.users.show') }}" class="act-btn act-view">View</a>
                                <button type="button" class="act-btn act-del">Ban</button>
                            </div>
                        </td>
                    </tr>




                    <tr data-type="user" data-name="Daniel Wong" data-email="daniel@gmail.com" data-status="active">
                        <td>
                            <div class="account-person">
                                <div class="user-avatar" style="background:#185FA5;">DW</div>
                                <span class="td-title">Daniel Wong</span>
                            </div>
                        </td>
                        <td class="td-sub">daniel@gmail.com</td>
                        <td>8</td>
                        <td>20 Mar 2026</td>
                        <td><span class="badge badge-status-active">Active</span></td>
                        <td>
                            <div class="action-group">
                                <button type="button" class="act-btn">Edit</button>
                                <a href="{{ route('admin.users.show') }}" class="act-btn act-view">View</a>
                                <button type="button" class="act-btn act-del">Ban</button>
                            </div>
                        </td>
                    </tr>

                    <tr id="noUsersRow" style="display:none;">
                        <td colspan="6" class="td-empty">No matching users found.</td>
                    </tr>
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

        <form>
            <div class="form-grid">
                <div class="form-group form-full">
                    <label>Full Name</label>
                    <input id="account-name" class="form-input" type="text" placeholder="Enter full name">
                </div>

                <div class="form-group form-full">
                    <label>Email Address</label>
                    <input id="account-email" class="form-input" type="email" placeholder="Enter email address">
                </div>

                <div class="form-group">
                    <label>Account Type</label>
                    <select class="form-input" id="account-role">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select class="form-input">
                        <option>Active</option>
                        <option>Banned</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input class="form-input" type="password" placeholder="Enter password">
                </div>

                <div class="form-group">
                    <label>Confirm Password</label>
                    <input class="form-input" type="password" placeholder="Confirm password">
                </div>

                <div class="form-group form-full">
                    <label>Notes</label>
                    <textarea class="form-input form-textarea" placeholder="Optional note for this account..."></textarea>
                </div>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeModal('account-modal')">Cancel</button>
                <button type="button" class="btn-primary" id="account-submit-btn">Save Account</button>
            </div>
        </form>
    </div>
</div>

{{-- Ban Modal --}}
<div class="modal-backdrop" id="ban-modal" style="display:none;">
    <div class="modal modal-sm">
        <div class="modal-icon-danger">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>
            </svg>
        </div>

        <div class="modal-title text-center" id="ban-modal-title">Ban account?</div>
        <div class="modal-sub text-center" id="ban-modal-sub">
            This account will lose access. UI only popup.
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
    function toggleAddAccountMenu() {
        const menu = document.getElementById('addAccountMenu');
        menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
    }

    function openAccountModal(role) {
        document.getElementById('addAccountMenu').style.display = 'none';
        document.getElementById('account-modal').style.display = 'flex';

        const title = document.getElementById('account-modal-title');
        const roleSelect = document.getElementById('account-role');
        const submitBtn = document.getElementById('account-submit-btn');
        const nameInput = document.getElementById('account-name');
        const emailInput = document.getElementById('account-email');

        nameInput.value = '';
        emailInput.value = '';

        if (role === 'admin') {
            title.textContent = 'Add Admin';
            roleSelect.value = 'admin';
            submitBtn.textContent = 'Save Admin';
        } else {
            title.textContent = 'Add User';
            roleSelect.value = 'user';
            submitBtn.textContent = 'Save User';
        }
    }

    function openEditModal(name, email, role) {
        document.getElementById('account-modal').style.display = 'flex';
        document.getElementById('account-modal-title').textContent = 'Edit Account';
        document.getElementById('account-submit-btn').textContent = 'Update Account';
        document.getElementById('account-name').value = name;
        document.getElementById('account-email').value = email;
        document.getElementById('account-role').value = role;
    }

    function openBanModal(name, role) {
        document.getElementById('ban-modal').style.display = 'flex';
        document.getElementById('ban-modal-title').textContent = 'Ban ' + role + '?';
        document.getElementById('ban-modal-sub').textContent =
            name + ' will no longer be able to log in or use the system. UI only popup.';
    }

    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }

    function toggleSection(sectionId, arrowId) {
        const section = document.getElementById(sectionId);
        const arrow = document.getElementById(arrowId);

        if (section.style.display === 'none') {
            section.style.display = '';
            arrow.textContent = '⌄';
        } else {
            section.style.display = 'none';
            arrow.textContent = '›';
        }
    }

    function filterAccounts() {
        const search = document.getElementById('accountSearch').value.toLowerCase().trim();
        const status = document.getElementById('statusFilter').value.toLowerCase();

        const userRows = document.querySelectorAll('#usersTableBody tr[data-type]');
        const adminRows = document.querySelectorAll('#adminsTableBody tr[data-type]');

        let visibleUsers = 0;
        let visibleAdmins = 0;

        userRows.forEach(row => {
            const name = row.dataset.name.toLowerCase();
            const email = row.dataset.email.toLowerCase();
            const rowStatus = row.dataset.status.toLowerCase();

            const matchSearch = search === '' || name.includes(search) || email.includes(search);
            const matchStatus = status === '' || rowStatus === status;

            if (matchSearch && matchStatus) {
                row.style.display = '';
                visibleUsers++;
            } else {
                row.style.display = 'none';
            }
        });

        adminRows.forEach(row => {
            const name = row.dataset.name.toLowerCase();
            const email = row.dataset.email.toLowerCase();
            const rowStatus = row.dataset.status.toLowerCase();

            const matchSearch = search === '' || name.includes(search) || email.includes(search);
            const matchStatus = status === '' || rowStatus === status;

            if (matchSearch && matchStatus) {
                row.style.display = '';
                visibleAdmins++;
            } else {
                row.style.display = 'none';
            }
        });

        document.getElementById('noUsersRow').style.display = visibleUsers === 0 ? '' : 'none';
        document.getElementById('noAdminsRow').style.display = visibleAdmins === 0 ? '' : 'none';

        document.getElementById('usersCountText').textContent = visibleUsers + (visibleUsers === 1 ? ' account' : ' accounts');
        document.getElementById('adminsCountText').textContent = visibleAdmins + (visibleAdmins === 1 ? ' account' : ' accounts');
    }

    function resetFilters() {
        document.getElementById('accountSearch').value = '';
        document.getElementById('statusFilter').value = '';
        filterAccounts();
    }

    document.addEventListener('click', function(e) {
        const wrap = document.querySelector('.topbar-action-wrap');
        const menu = document.getElementById('addAccountMenu');

        if (wrap && !wrap.contains(e.target)) {
            menu.style.display = 'none';
        }
    });

    document.getElementById('accountSearch').addEventListener('input', filterAccounts);
    document.getElementById('statusFilter').addEventListener('change', filterAccounts);
</script>
@endsection