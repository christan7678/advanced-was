// Form submit handler for account modal
document.getElementById('account-form').addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = this;
    const errorDiv = document.getElementById('error-messages');
    const errorList = document.getElementById('error-list');
    const successDiv = document.getElementById('success-message');
    const submitBtn = document.getElementById('account-submit-btn');

    // Clear previous messages
    errorDiv.style.display = 'none';
    successDiv.style.display = 'none';
    errorList.innerHTML = '';

    // Get form data
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
            // Success - show message and reload
            successDiv.style.display = 'block';
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            // Validation errors
            if (data.errors) {
                Object.keys(data.errors).forEach(field => {
                    const messages = data.errors[field];
                    messages.forEach(message => {
                        const li = document.createElement('li');
                        li.textContent = message;
                        errorList.appendChild(li);
                    });
                });
            } else if (data.message) {
                const li = document.createElement('li');
                li.textContent = data.message;
                errorList.appendChild(li);
            }
            errorDiv.style.display = 'block';
        }
    } catch (error) {
        console.error('Form submit error:', error);
        const li = document.createElement('li');
        li.textContent = 'An error occurred. Please try again.';
        errorList.appendChild(li);
        errorDiv.style.display = 'block';
    }
});

function openAccountModal(type = 'user') {
    document.getElementById('account-modal').style.display = 'flex';

    const form = document.getElementById('account-form');
    const title = document.getElementById('account-modal-title');
    const submitBtn = document.getElementById('account-submit-btn');
    const nameInput = document.getElementById('account-name');
    const emailInput = document.getElementById('account-email');
    const passwordField = document.getElementById('password-field');
    const passwordConfirmField = document.getElementById('password-confirmation-field');
    const passwordSection = document.getElementById('password-section');

    // Clear error/success messages
    document.getElementById('error-messages').style.display = 'none';
    document.getElementById('success-message').style.display = 'none';

    // Reset form
    nameInput.value = '';
    emailInput.value = '';
    passwordField.value = '';
    passwordConfirmField.value = '';

    // Set form action for creating new account
    form.action = '/admin/users';
    form.method = 'POST';

    // Make password required for new accounts
    passwordField.required = true;
    passwordConfirmField.required = true;
    passwordSection.style.display = 'block';
    document.getElementById('password-required').style.display = 'inline';
    document.getElementById('password-confirm-required').style.display = 'inline';

    title.textContent = 'Add User';
    submitBtn.textContent = 'Save User';
}

function openEditModal(name, email, userId) {
    document.getElementById('account-modal').style.display = 'flex';

    const form = document.getElementById('account-form');
    const title = document.getElementById('account-modal-title');
    const submitBtn = document.getElementById('account-submit-btn');
    const nameInput = document.getElementById('account-name');
    const emailInput = document.getElementById('account-email');
    const passwordField = document.getElementById('password-field');
    const passwordConfirmField = document.getElementById('password-confirmation-field');
    const passwordSection = document.getElementById('password-section');

    // Clear error/success messages
    document.getElementById('error-messages').style.display = 'none';
    document.getElementById('success-message').style.display = 'none';

    // Set form action for updating account - use template string to ensure userId is included
    form.action = `/admin/users/${userId}`;
    form.method = 'POST';

    // Remove existing _method field if any
    let methodField = form.querySelector('input[name="_method"]');
    if (methodField) {
        methodField.remove();
    }

    // Create and add new _method field for PUT request
    methodField = document.createElement('input');
    methodField.type = 'hidden';
    methodField.name = '_method';
    methodField.value = 'PUT';
    
    // Insert after CSRF token
    const csrfField = form.querySelector('input[name="_token"]');
    csrfField.parentNode.insertBefore(methodField, csrfField.nextSibling);

    nameInput.value = name;
    emailInput.value = email;
    passwordField.value = '';
    passwordConfirmField.value = '';

    // Hide password fields for editing
    passwordField.required = false;
    passwordConfirmField.required = false;
    passwordSection.style.display = 'none';

    title.textContent = 'Edit Account';
    submitBtn.textContent = 'Update Account';
}

function confirmDelete(name, userId) {
    if (confirm(`Are you sure you want to delete ${name}? This action cannot be undone.`)) {
        deleteUser(userId);
    }
}

function deleteUser(userId) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        alert('Security token not found. Please refresh the page.');
        return;
    }
    
    const token = csrfToken.getAttribute('content');
    
    fetch(`/admin/users/${userId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json',
        },
    })
    .then(async response => {
        const data = await response.json();
        if (response.ok) {
            alert('Account deleted successfully!');
            location.reload();
        } else {
            alert('Failed to delete account: ' + (data.error || 'Unknown error'));
            console.error('Error response:', data);
        }
    })
    .catch(error => {
        console.error('Delete error:', error);
        alert('An error occurred while deleting the account. Please check the console.');
    });
}

function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}

function filterAccounts() {
    const search = document.getElementById('accountSearch').value.toLowerCase().trim();

    // Call backend API to search users
    fetch(`/admin/users/search?q=${encodeURIComponent(search)}`)
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('usersTableBody');
            const noUsersRow = document.getElementById('noUsersRow');
            const usersCountText = document.getElementById('usersCountText');

            // Clear existing rows except noUsersRow
            const rows = tableBody.querySelectorAll('tr[data-type]');
            rows.forEach(row => row.remove());

            if (data.users.length === 0) {
                // Show no results message
                noUsersRow.style.display = '';
                usersCountText.textContent = '0 accounts';
            } else {
                // Hide no results message
                noUsersRow.style.display = 'none';

                // Build and insert new rows
                data.users.forEach(user => {
                    const tr = document.createElement('tr');
                    tr.setAttribute('data-type', 'user');
                    tr.setAttribute('data-name', user.name);
                    tr.setAttribute('data-email', user.email);
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
                                <button type="button" class="act-btn" onclick="openEditModal('${user.name.replace(/'/g, "\\'")}', '${user.email.replace(/'/g, "\\'")}', ${user.id})">Edit</button>
                                <a href="/admin/users/${user.id}" class="act-btn act-view">View</a>
                                <button type="button" class="act-btn act-del" onclick="confirmDelete('${user.name.replace(/'/g, "\\'")}', ${user.id})">Delete</button>
                            </div>
                        </td>
                    `;
                    tableBody.appendChild(tr);
                });

                usersCountText.textContent = data.users.length + (data.users.length === 1 ? ' account' : ' accounts');
            }
        })
        .catch(error => {
            console.error('Search error:', error);
        });
}


document.getElementById('accountSearch').addEventListener('input', filterAccounts);
