function safeGet(id) {
    return document.getElementById(id);
}

let currentUsersPage = 1;
const usersPerPage = 15;
let currentUsersData = [];

function escapeHtml(value) {
    return String(value || '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function escapeJsString(value) {
    return String(value || '').replace(/\\/g, '\\\\').replace(/'/g, "\\'");
}

function renderUsersTable(users) {
    const tableBody = safeGet('usersTableBody');
    const usersCountText = safeGet('usersCountText');
    const pageInfo = safeGet('usersPageInfo');
    const paginationControls = safeGet('usersPaginationControls');

    if (!tableBody) return;

    tableBody.innerHTML = '';

    const totalItems = users.length;
    const totalPages = Math.ceil(totalItems / usersPerPage);

    if (currentUsersPage > totalPages && totalPages > 0) {
        currentUsersPage = totalPages;
    }

    if (totalItems === 0) {
        tableBody.innerHTML = `
            <tr id="noUsersRow">
                <td colspan="5" class="td-empty">No users found.</td>
            </tr>
        `;

        if (usersCountText) usersCountText.textContent = '0 accounts';
        if (pageInfo) pageInfo.textContent = 'Showing 0 account(s)';
        if (paginationControls) paginationControls.innerHTML = '';
        return;
    }

    const startIndex = (currentUsersPage - 1) * usersPerPage;
    const endIndex = startIndex + usersPerPage;
    const pageUsers = users.slice(startIndex, endIndex);

    pageUsers.forEach(user => {
        const tr = document.createElement('tr');
        tr.setAttribute('data-type', 'user');
        tr.setAttribute('data-name', user.name || '');
        tr.setAttribute('data-email', user.email || '');

        const safeName = escapeHtml(user.name);
        const safeEmail = escapeHtml(user.email);
        const safePhone = escapeJsString(user.phone_number || '');
        const safeNameJs = escapeJsString(user.name || '');
        const safeEmailJs = escapeJsString(user.email || '');
        const initials = String(user.name || '').substring(0, 2).toUpperCase();

        tr.innerHTML = `
            <td>
                <div class="account-person">
                    <div class="user-avatar" style="background:#185FA5;">
                        ${escapeHtml(initials)}
                    </div>
                    <span class="td-title">${safeName}</span>
                </div>
            </td>
            <td class="td-sub email-cell">${safeEmail}</td>
            <td>${user.bookings_count ?? 0}</td>
            <td>${escapeHtml(user.created_at || '—')}</td>
            <td class="actions-cell">
                <div class="action-group action-group-tight">
                    <button type="button" class="act-btn"
                        onclick="openEditModal('${safeNameJs}', '${safeEmailJs}', '${safePhone}', ${user.id})">
                        Edit
                    </button>
                    <a href="/admin/users/${user.id}" class="act-btn act-view">View</a>
                    <button type="button" class="act-btn act-del"
                        onclick="confirmDelete('${safeNameJs}', ${user.id})">
                        Delete
                    </button>
                </div>
            </td>
        `;

        tableBody.appendChild(tr);
    });

    if (usersCountText) {
        usersCountText.textContent = `${totalItems} accounts`;
    }

    if (pageInfo) {
        const start = totalItems === 0 ? 0 : startIndex + 1;
        const end = Math.min(endIndex, totalItems);
        pageInfo.textContent = `Showing ${start} to ${end} of ${totalItems} account(s)`;
    }

    renderUsersPagination(totalPages);
}

function renderUsersPagination(totalPages) {
    const paginationControls = safeGet('usersPaginationControls');
    if (!paginationControls) return;

    paginationControls.innerHTML = '';

    if (totalPages <= 1) return;

    function createButton(label, page, disabled, isActive) {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.textContent = label;
        btn.className = 'pagination-btn' + (isActive ? ' active' : '');
        btn.disabled = !!disabled;

        if (!disabled && page !== null) {
            btn.addEventListener('click', function () {
                currentUsersPage = page;
                renderUsersTable(currentUsersData);
            });
        }

        return btn;
    }

    function createEllipsis() {
        const span = document.createElement('span');
        span.className = 'pagination-ellipsis';
        span.textContent = '...';
        return span;
    }

    paginationControls.appendChild(
        createButton('Prev', currentUsersPage - 1, currentUsersPage === 1, false)
    );

    const pages = [];

    if (totalPages <= 7) {
        for (let i = 1; i <= totalPages; i++) {
            pages.push(i);
        }
    } else {
        pages.push(1);

        if (currentUsersPage <= 4) {
            pages.push(2, 3, 4, 5, '...');
        } else if (currentUsersPage >= totalPages - 3) {
            pages.push('...');
            pages.push(totalPages - 4, totalPages - 3, totalPages - 2, totalPages - 1);
        } else {
            pages.push('...');
            pages.push(currentUsersPage - 1, currentUsersPage, currentUsersPage + 1);
            pages.push('...');
        }

        pages.push(totalPages);
    }

    pages.forEach(function (item) {
        if (item === '...') {
            paginationControls.appendChild(createEllipsis());
        } else {
            paginationControls.appendChild(
                createButton(String(item), item, false, item === currentUsersPage)
            );
        }
    });

    paginationControls.appendChild(
        createButton('Next', currentUsersPage + 1, currentUsersPage === totalPages, false)
    );
}

// FORM SUBMIT
const accountForm = safeGet('account-form');

if (accountForm) {
    accountForm.addEventListener('submit', async function (e) {
        e.preventDefault();

        const form = this;
        const errorDiv = safeGet('error-messages');
        const errorList = safeGet('error-list');
        const successDiv = safeGet('success-message');

        if (errorDiv) errorDiv.style.display = 'none';
        if (successDiv) successDiv.style.display = 'none';
        if (errorList) errorList.innerHTML = '';

        const formData = new FormData(form);
        const csrfToken = formData.get('_token');

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: formData,
            });

            const data = await response.json();

            if (response.ok) {
                if (successDiv) successDiv.style.display = 'block';
                setTimeout(() => location.reload(), 1500);
            } else {
                if (data.errors && errorList) {
                    Object.values(data.errors).forEach(messages => {
                        messages.forEach(msg => {
                            const li = document.createElement('li');
                            li.textContent = msg;
                            errorList.appendChild(li);
                        });
                    });
                } else if (data.message && errorList) {
                    const li = document.createElement('li');
                    li.textContent = data.message;
                    errorList.appendChild(li);
                }

                if (errorDiv) errorDiv.style.display = 'block';
            }
        } catch (error) {
            console.error('Form submit error:', error);
        }
    });
}

// MODAL FUNCTIONS
function openAccountModal() {
    safeGet('account-modal').style.display = 'flex';

    const form = safeGet('account-form');
    const nameInput = safeGet('account-name');
    const emailInput = safeGet('account-email');
    const phoneInput = safeGet('account-phone');
    const passwordField = safeGet('password-field');
    const passwordConfirmField = safeGet('password-confirmation-field');
    const passwordSection = safeGet('password-section');

    safeGet('error-messages').style.display = 'none';
    safeGet('success-message').style.display = 'none';

    nameInput.value = '';
    emailInput.value = '';
    phoneInput.value = '';
    passwordField.value = '';
    passwordConfirmField.value = '';

    form.action = '/admin/users';
    form.method = 'POST';

    const oldMethodField = form.querySelector('input[name="_method"]');
    if (oldMethodField) oldMethodField.remove();

    passwordField.required = true;
    passwordConfirmField.required = true;
    passwordSection.style.display = 'block';

    safeGet('account-modal-title').textContent = 'Add User';
    safeGet('account-submit-btn').textContent = 'Save User';
}

function openEditModal(name, email, phone_number, userId) {
    safeGet('account-modal').style.display = 'flex';

    const form = safeGet('account-form');
    const nameInput = safeGet('account-name');
    const emailInput = safeGet('account-email');
    const phoneInput = safeGet('account-phone');
    const passwordField = safeGet('password-field');
    const passwordConfirmField = safeGet('password-confirmation-field');
    const passwordSection = safeGet('password-section');

    safeGet('error-messages').style.display = 'none';
    safeGet('success-message').style.display = 'none';

    form.action = `/admin/users/${userId}`;
    form.method = 'POST';

    let methodField = form.querySelector('input[name="_method"]');
    if (methodField) methodField.remove();

    methodField = document.createElement('input');
    methodField.type = 'hidden';
    methodField.name = '_method';
    methodField.value = 'PUT';

    const csrfField = form.querySelector('input[name="_token"]');
    csrfField.parentNode.insertBefore(methodField, csrfField.nextSibling);

    nameInput.value = name;
    emailInput.value = email;
    phoneInput.value = phone_number || '';
    passwordField.value = '';
    passwordConfirmField.value = '';

    passwordField.required = false;
    passwordConfirmField.required = false;
    passwordSection.style.display = 'none';

    safeGet('account-modal-title').textContent = 'Edit Account';
    safeGet('account-submit-btn').textContent = 'Update Account';
}

function closeModal(id) {
    const el = safeGet(id);
    if (el) el.style.display = 'none';
}

// DELETE USER
function confirmDelete(name, userId) {
    if (confirm(`Are you sure you want to delete ${name}?`)) {
        deleteUser(userId);
    }
}

function deleteUser(userId) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) return alert('Missing CSRF token');

    fetch(`/admin/users/${userId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken.content,
            'Accept': 'application/json',
        },
    })
        .then(res => res.json())
        .then(data => {
            if (data) {
                alert('Deleted successfully');
                location.reload();
            }
        })
        .catch(err => console.error(err));
}

// SEARCH + PAGINATION
function filterAccounts() {
    const input = safeGet('accountSearch');
    if (!input) return;

    const search = input.value.toLowerCase().trim();

    fetch(`/admin/users/search?q=${encodeURIComponent(search)}`)
        .then(res => res.json())
        .then(data => {
            currentUsersPage = 1;
            currentUsersData = Array.isArray(data.users) ? data.users : [];
            renderUsersTable(currentUsersData);
        })
        .catch(err => console.error(err));
}

function loadInitialUsersFromTable() {
    const rows = document.querySelectorAll('#usersTableBody tr[data-type="user"]');
    currentUsersData = Array.from(rows).map(function (row) {
        const cells = row.querySelectorAll('td');

        return {
            id: extractUserIdFromRow(row),
            name: row.getAttribute('data-name') || '',
            email: row.getAttribute('data-email') || '',
            phone_number: extractPhoneFromEditButton(row),
            bookings_count: cells[2] ? cells[2].textContent.trim() : '0',
            created_at: cells[3] ? cells[3].textContent.trim() : '—',
        };
    });

    renderUsersTable(currentUsersData);
}

function extractUserIdFromRow(row) {
    const viewLink = row.querySelector('a.act-view');
    if (!viewLink) return 0;

    const match = viewLink.getAttribute('href').match(/\/admin\/users\/(\d+)/);
    return match ? parseInt(match[1], 10) : 0;
}

function extractPhoneFromEditButton(row) {
    const editBtn = row.querySelector('button.act-btn');
    if (!editBtn) return '';

    const onclickValue = editBtn.getAttribute('onclick') || '';
    const match = onclickValue.match(/openEditModal\('.*?',\s*'.*?',\s*'(.*?)',\s*\d+\)/);

    return match ? match[1] : '';
}

// INIT
const searchInput = safeGet('accountSearch');
if (searchInput) {
    searchInput.addEventListener('input', filterAccounts);
}

document.addEventListener('DOMContentLoaded', function () {
    loadInitialUsersFromTable();
});