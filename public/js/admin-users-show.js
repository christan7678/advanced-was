// SAFE EVENT BINDING
function safeGet(id) {
    return document.getElementById(id);
}

function openEditModal() {
    const modal = safeGet('edit-modal');
    if (modal) modal.style.display = 'flex';
}

function closeModal(id) {
    const el = safeGet(id);
    if (el) el.style.display = 'none';
}

// EDIT FORM SUBMIT
const editForm = safeGet('edit-form');

if (editForm) {
    editForm.addEventListener('submit', async function (e) {
        e.preventDefault();

        const form = this;
        const errorDiv = safeGet('edit-error-messages');
        const errorList = safeGet('edit-error-list');
        const successDiv = safeGet('edit-success-message');
        const submitBtn = safeGet('edit-submit-btn');

        if (errorDiv) errorDiv.style.display = 'none';
        if (successDiv) successDiv.style.display = 'none';
        if (errorList) errorList.innerHTML = '';

        const formData = new FormData(form);

        try {
            if (submitBtn) submitBtn.disabled = true;

            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: formData,
            });

            const data = await response.json();

            if (response.ok) {
                if (successDiv) successDiv.style.display = 'block';

                setTimeout(() => {
                    location.reload();
                }, 1200);
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
        } catch (err) {
            console.error('Update error:', err);
        } finally {
            if (submitBtn) submitBtn.disabled = false;
        }
    });
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
            'X-Requested-With': 'XMLHttpRequest',
        },
    })
        .then(async res => {
            const data = await res.json();

            if (res.ok) {
                alert('Deleted successfully');
                window.location.href = '/admin/users';
            } else {
                alert(data.message || 'Delete failed');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Error deleting user');
        });
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
                            <button type="button" class="act-btn"
                                onclick="openEditModal('${user.name.replace(/'/g, "\\'")}', '${user.email.replace(/'/g, "\\'")}', ${user.id})">
                                Edit
                            </button>
                            <a href="/admin/users/${user.id}" class="act-btn act-view">View</a>
                            <button type="button" class="act-btn act-del"
                                onclick="confirmDelete('${user.name.replace(/'/g, "\\'")}', ${user.id})">
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