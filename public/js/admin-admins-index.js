function safeGet(id) {
    return document.getElementById(id);
}

let currentAdminsPage = 1;
const adminsPerPage = 5;
let currentAdminsData = [];

// --------------------
// FORM SUBMIT
// --------------------
const adminForm = safeGet('admin-form');

if (adminForm) {
    adminForm.addEventListener('submit', async function (e) {
        e.preventDefault();

        const form = this;
        const errorDiv = safeGet('admin-error-messages');
        const errorList = safeGet('admin-error-list');
        const successDiv = safeGet('admin-success-message');
        const submitBtn = safeGet('admin-submit-btn');

        if (errorDiv) errorDiv.style.display = 'none';
        if (successDiv) successDiv.style.display = 'none';
        if (errorList) errorList.innerHTML = '';

        submitBtn.disabled = true;
        submitBtn.textContent = form.querySelector('input[name="_method"]') ? 'Updating...' : 'Saving...';

        const formData = new FormData(form);
        const csrfToken = formData.get('_token');

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: formData,
            });

            const data = await response.json();

            if (response.ok) {
                if (successDiv) {
                    successDiv.textContent = data.message || 'Admin account saved successfully.';
                    successDiv.style.display = 'block';
                }

                setTimeout(() => {
                    location.reload();
                }, 900);
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
            console.error('Admin form submit error:', error);

            if (errorList) {
                const li = document.createElement('li');
                li.textContent = 'Something went wrong. Please try again.';
                errorList.appendChild(li);
            }

            if (errorDiv) errorDiv.style.display = 'block';
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = form.querySelector('input[name="_method"]') ? 'Update Admin' : 'Save Admin';
        }
    });
}

// --------------------
// MODAL FUNCTIONS
// --------------------
function openAdminModal() {
    safeGet('admin-modal').style.display = 'flex';

    const form = safeGet('admin-form');
    const nameInput = safeGet('admin-name');
    const emailInput = safeGet('admin-email');
    const phoneInput = safeGet('admin-phone');
    const passwordField = safeGet('admin-password');
    const passwordConfirmField = safeGet('admin-password-confirmation');

    safeGet('admin-error-messages').style.display = 'none';
    safeGet('admin-success-message').style.display = 'none';
    safeGet('admin-error-list').innerHTML = '';

    if (form) {
        form.reset();
        form.action = '/admin/admins';
        form.method = 'POST';
    }

    let methodField = form.querySelector('input[name="_method"]');
    if (methodField) methodField.remove();

    nameInput.value = '';
    emailInput.value = '';
    phoneInput.value = '';
    passwordField.value = '';
    passwordConfirmField.value = '';

    passwordField.required = true;
    passwordConfirmField.required = true;

    safeGet('admin-modal-title').textContent = 'Add Admin';
    safeGet('admin-submit-btn').textContent = 'Save Admin';
}

function openEditModal(name, email, phoneNumber, adminId) {
    safeGet('admin-modal').style.display = 'flex';

    const form = safeGet('admin-form');
    const nameInput = safeGet('admin-name');
    const emailInput = safeGet('admin-email');
    const phoneInput = safeGet('admin-phone');
    const passwordField = safeGet('admin-password');
    const passwordConfirmField = safeGet('admin-password-confirmation');
    const passwordSection = safeGet('password-section');

    safeGet('admin-error-messages').style.display = 'none';
    safeGet('admin-success-message').style.display = 'none';
    safeGet('admin-error-list').innerHTML = '';

    form.action = `/admin/admins/${adminId}`;
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
    phoneInput.value = phoneNumber || '';
    passwordField.value = '';
    passwordConfirmField.value = '';

    if (passwordSection) {
        passwordSection.style.display = 'none';
    } else {
        passwordField.closest('.form-group').style.display = 'none';
        passwordConfirmField.closest('.form-group').style.display = 'none';
    }

    passwordField.required = false;
    passwordConfirmField.required = false;

    safeGet('admin-modal-title').textContent = 'Edit Admin';
    safeGet('admin-submit-btn').textContent = 'Update Admin';
}

function closeAdminModal() {
    const modal = safeGet('admin-modal');
    if (modal) modal.style.display = 'none';

    const form = safeGet('admin-form');
    if (form) form.reset();

    const passwordSection = safeGet('password-section');
    const passwordField = safeGet('admin-password');
    const passwordConfirmField = safeGet('admin-password-confirmation');

    if (passwordSection) {
        passwordSection.style.display = 'block';
    } else {
        if (passwordField) passwordField.closest('.form-group').style.display = '';
        if (passwordConfirmField) passwordConfirmField.closest('.form-group').style.display = '';
    }

    if (passwordField) passwordField.required = true;
    if (passwordConfirmField) passwordConfirmField.required = true;

    const errorDiv = safeGet('admin-error-messages');
    const errorList = safeGet('admin-error-list');
    const successDiv = safeGet('admin-success-message');

    if (errorDiv) errorDiv.style.display = 'none';
    if (successDiv) successDiv.style.display = 'none';
    if (errorList) errorList.innerHTML = '';
}

// --------------------
// DELETE ADMIN
// --------------------
function confirmDelete(name, adminId) {
    if (confirm(`Are you sure you want to delete ${name}?`)) {
        deleteAdmin(adminId);
    }
}

function deleteAdmin(adminId) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) return alert('Missing CSRF token');

    fetch(`/admin/admins/${adminId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken.content,
            'Accept': 'application/json',
        },
    })
        .then(async res => {
            const data = await res.json();
            if (!res.ok) {
                alert(data.message || 'Failed to delete admin');
                return;
            }

            alert(data.message || 'Deleted successfully');
            location.reload();
        })
        .catch(err => {
            console.error(err);
            alert('Something went wrong while deleting');
        });
}

// --------------------
// PAGINATION
// --------------------
function renderAdminsPagination(totalPages) {
    const paginationControls = safeGet('adminsPaginationControls');
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
                currentAdminsPage = page;
                renderAdminsTable(currentAdminsData);
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
        createButton('Prev', currentAdminsPage - 1, currentAdminsPage === 1, false)
    );

    const pages = [];

    if (totalPages <= 7) {
        for (let i = 1; i <= totalPages; i++) {
            pages.push(i);
        }
    } else {
        pages.push(1);

        if (currentAdminsPage <= 4) {
            pages.push(2, 3, 4, 5, '...');
        } else if (currentAdminsPage >= totalPages - 3) {
            pages.push('...');
            pages.push(totalPages - 4, totalPages - 3, totalPages - 2, totalPages - 1);
        } else {
            pages.push('...');
            pages.push(currentAdminsPage - 1, currentAdminsPage, currentAdminsPage + 1);
            pages.push('...');
        }

        pages.push(totalPages);
    }

    pages.forEach(function (item) {
        if (item === '...') {
            paginationControls.appendChild(createEllipsis());
        } else {
            paginationControls.appendChild(
                createButton(String(item), item, false, item === currentAdminsPage)
            );
        }
    });

    paginationControls.appendChild(
        createButton('Next', currentAdminsPage + 1, currentAdminsPage === totalPages, false)
    );
}

function renderAdminsTable(admins) {
    const tableBody = safeGet('adminsTableBody');
    const adminsCountText = safeGet('adminsCountText');
    const pageInfo = safeGet('adminsPageInfo');

    if (!tableBody) return;

    tableBody.innerHTML = '';

    const totalItems = admins.length;
    const totalPages = Math.ceil(totalItems / adminsPerPage);

    if (currentAdminsPage > totalPages && totalPages > 0) {
        currentAdminsPage = totalPages;
    }

    if (totalItems === 0) {
        tableBody.innerHTML = `
            <tr id="noAdminsRow">
                <td colspan="5" class="td-empty">No admins found.</td>
            </tr>
        `;

        if (adminsCountText) adminsCountText.textContent = '0 accounts';
        if (pageInfo) pageInfo.textContent = 'Showing 0 account(s)';
        renderAdminsPagination(0);
        return;
    }

    const startIndex = (currentAdminsPage - 1) * adminsPerPage;
    const endIndex = startIndex + adminsPerPage;
    const pageAdmins = admins.slice(startIndex, endIndex);

    pageAdmins.forEach(admin => {
        const tr = document.createElement('tr');
        tr.setAttribute('data-type', 'admin');
        tr.setAttribute('data-name', admin.name || '');
        tr.setAttribute('data-email', admin.email || '');

        const initials = String(admin.name || '').substring(0, 2).toUpperCase();
        const safeName = String(admin.name || '').replace(/'/g, "\\'");
        const safeEmail = String(admin.email || '').replace(/'/g, "\\'");
        const safePhone = String(admin.phone_number || '').replace(/'/g, "\\'");

        tr.innerHTML = `
            <td>
                <div class="account-person">
                    <div class="user-avatar" style="background:#185FA5;">
                        ${initials}
                    </div>
                    <span class="td-title">${admin.name || ''}</span>
                </div>
            </td>
            <td class="td-sub">${admin.email || ''}</td>
            <td>${admin.phone_number || '—'}</td>
            <td>${admin.created_at || '—'}</td>
            <td class="actions-cell">
                <div class="action-group action-group-tight">
                    <button type="button" class="act-btn" onclick="openEditModal('${safeName}', '${safeEmail}', '${safePhone}', '${admin.id}')">Edit</button>
                    <a href="/admin/admins/${admin.id}" class="act-btn act-view">View</a>
                    <button type="button" class="act-btn act-del" onclick="confirmDelete('${safeName}', '${admin.id}')">Delete</button>
                </div>
            </td>
        `;

        tableBody.appendChild(tr);
    });

    if (adminsCountText) {
        adminsCountText.textContent = `${totalItems} accounts`;
    }

    if (pageInfo) {
        const start = startIndex + 1;
        const end = Math.min(endIndex, totalItems);
        pageInfo.textContent = `Showing ${start} to ${end} of ${totalItems} account(s)`;
    }

    renderAdminsPagination(totalPages);
}

// --------------------
// SEARCH ADMINS
// --------------------
function filterAdmins() {
    const input = safeGet('accountSearch');
    if (!input) return;

    const search = input.value.toLowerCase().trim();

    const filtered = currentAdminsData.filter(admin => {
        const name = String(admin.name || '').toLowerCase();
        const email = String(admin.email || '').toLowerCase();
        return name.includes(search) || email.includes(search);
    });

    currentAdminsPage = 1;
    renderAdminsTable(filtered);
}

function loadInitialAdminsFromTable() {
    const rows = document.querySelectorAll('#adminsTableBody tr[data-type="admin"]');

    currentAdminsData = Array.from(rows).map(function (row) {
        const cells = row.querySelectorAll('td');
        const editBtn = row.querySelector('button.act-btn');
        const onclickValue = editBtn ? editBtn.getAttribute('onclick') || '' : '';

        let phone = '';
        const phoneMatch = onclickValue.match(/openEditModal\('.*?',\s*'.*?',\s*'(.*?)',\s*'.*?'\)/);
        if (phoneMatch) {
            phone = phoneMatch[1];
        }

        const viewLink = row.querySelector('a.act-view');
        let adminId = '';
        if (viewLink) {
            const match = viewLink.getAttribute('href').match(/\/admin\/admins\/(\d+)/);
            if (match) adminId = match[1];
        }

        return {
            id: adminId,
            name: row.getAttribute('data-name') || '',
            email: row.getAttribute('data-email') || '',
            phone_number: phone,
            created_at: cells[3] ? cells[3].textContent.trim() : '—',
        };
    });

    renderAdminsTable(currentAdminsData);
}

// --------------------
// INIT
// --------------------
const searchInput = safeGet('accountSearch');
if (searchInput) {
    searchInput.addEventListener('input', filterAdmins);
}

document.addEventListener('DOMContentLoaded', function () {
    loadInitialAdminsFromTable();
});