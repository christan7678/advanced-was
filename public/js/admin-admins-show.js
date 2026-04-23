function safeGet(id) {
    return document.getElementById(id);
}

function openEditModal() {
    const modal = safeGet('edit-modal');
    if (modal) modal.style.display = 'flex';
}

function openChangePasswordModal() {
    const modal = safeGet('changePassword-modal');
    if (modal) modal.style.display = 'flex';
}

function closeModal(id) {
    const el = safeGet(id);
    if (el) el.style.display = 'none';
}

// CHANGE PASSWORD
const changePasswordForm = safeGet('change-password-form');

if (changePasswordForm) {
    changePasswordForm.addEventListener('submit', async function (e) {
        e.preventDefault();

        const form = this;
        const errorDiv = safeGet('password-error-messages');
        const errorList = safeGet('password-error-list');
        const successDiv = safeGet('password-success-message');
        const submitBtn = safeGet('password-submit-btn');

        if (errorDiv) errorDiv.style.display = 'none';
        if (successDiv) successDiv.style.display = 'none';
        if (errorList) errorList.innerHTML = '';

        const formData = new FormData(form);

        try {
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Updating...';
            }

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

                form.reset();

                setTimeout(() => {
                    closeModal('changePassword-modal');
                }, 1000);
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
            console.error('Change password error:', err);

            if (errorList) {
                const li = document.createElement('li');
                li.textContent = 'Something went wrong. Please try again.';
                errorList.appendChild(li);
            }

            if (errorDiv) errorDiv.style.display = 'block';
        } finally {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Update Password';
            }
        }
    });
}


// EDIT ADMIN
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
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Updating...';
            }

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
                }, 1000);
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
            console.error('Update admin error:', err);

            if (errorList) {
                const li = document.createElement('li');
                li.textContent = 'Something went wrong. Please try again.';
                errorList.appendChild(li);
            }

            if (errorDiv) errorDiv.style.display = 'block';
        } finally {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Update Account';
            }
        }
    });
}

// DELETE ADMIN
function confirmDelete(name, adminId) {
    if (confirm(`Are you sure you want to delete ${name}?`)) {
        deleteAdmin(adminId);
    }
}

function deleteAdmin(adminId) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        alert('Missing CSRF token');
        return;
    }

    fetch(`/admin/admins/${adminId}`, {
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
                alert(data.message || 'Deleted successfully');
                window.location.href = '/admin/admins';
            } else {
                alert(data.message || 'Delete failed');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Error deleting admin');
        });
}