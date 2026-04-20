// SAFE EVENT BINDING
function safeGet(id) {
    return document.getElementById(id);
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

        // Clear messages
        if (errorDiv) errorDiv.style.display = 'none';
        if (successDiv) successDiv.style.display = 'none';
        if (errorList) errorList.innerHTML = '';

        const formData = new FormData(form);
        const csrfToken = formData.get('_token');

        try {
            const response = await fetch(form.action, {
                method: form.method,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
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
    const passwordField = safeGet('password-field');
    const passwordConfirmField = safeGet('password-confirmation-field');
    const passwordSection = safeGet('password-section');

    safeGet('error-messages').style.display = 'none';
    safeGet('success-message').style.display = 'none';

    nameInput.value = '';
    emailInput.value = '';
    passwordField.value = '';
    passwordConfirmField.value = '';

    form.action = '/admin/users';
    form.method = 'POST';

    passwordField.required = true;
    passwordConfirmField.required = true;
    passwordSection.style.display = 'block';

    safeGet('account-modal-title').textContent = 'Add User';
    safeGet('account-submit-btn').textContent = 'Save User';
}

function openEditModal(name, email, userId) {
    safeGet('account-modal').style.display = 'flex';

    const form = safeGet('account-form');
    const nameInput = safeGet('account-name');
    const emailInput = safeGet('account-email');
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

// SEARCH
function filterAccounts() {
    const input = safeGet('accountSearch');
    if (!input) return;

    const search = input.value.toLowerCase().trim();

    fetch(`/admin/users/search?q=${encodeURIComponent(search)}`)
        .then(res => res.json())
        .then(data => {
            const tableBody = safeGet('usersTableBody');
            const noUsersRow = safeGet('noUsersRow');
            const usersCountText = safeGet('usersCountText');

            if (!tableBody) return;

            tableBody.querySelectorAll('tr[data-type]').forEach(r => r.remove());

            if (data.users.length === 0) {
                if (noUsersRow) noUsersRow.style.display = '';
                if (usersCountText) usersCountText.textContent = '0 accounts';
                return;
            }

            if (noUsersRow) noUsersRow.style.display = 'none';

            data.users.forEach(user => {
                const tr = document.createElement('tr');
                tr.setAttribute('data-type', 'user');

                tr.innerHTML = `
                    <td>
                        <div class="account-person">
                            <div class="user-avatar" style="background:#185FA5;">
                                ${user.name.substring(0, 2).toUpperCase()}
                            </div>
                            <span class="td-title">${user.name}</span>
                        </div>
                    </td>
                    <td class="td-sub">${user.email}</td>
                    <td>${user.bookings_count}</td>
                    <td>${user.created_at}</td>
                    <td>
                        <div class="action-group">
                            <button type="button" class="act-btn" onclick="openEditModal('${user.name.replace(/'/g, "\\'")}', '${user.email.replace(/'/g, "\\'")}', ${user.id})">
                                Edit
                            </button>
                            <a href="/admin/users/${user.id}" class="act-btn act-view">View</a>
                            <button type="button" class="act-btn act-del" onclick="confirmDelete('${user.name.replace(/'/g, "\\'")}', ${user.id})">
                                Delete
                            </button>
                        </div>
                    </td>
                `;

                tableBody.appendChild(tr);
            });

            if (usersCountText) {
                usersCountText.textContent = `${data.users.length} accounts`;
            }
        })
        .catch(err => console.error(err));
}

// INIT EVENTS
const searchInput = safeGet('accountSearch');
if (searchInput) {
    searchInput.addEventListener('input', filterAccounts);
}