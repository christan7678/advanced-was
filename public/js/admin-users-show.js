// Initialize userId from the HTML
const userId = parseInt(document.querySelector('[data-user-id]')?.getAttribute('data-user-id') || '0');

// Edit form submit handler
if (document.getElementById('edit-form')) {
    document.getElementById('edit-form').addEventListener('submit', async function(e) {
        e.preventDefault();

        const form = this;
        const errorDiv = document.getElementById('edit-error-messages');
        const errorList = document.getElementById('edit-error-list');
        const successDiv = document.getElementById('edit-success-message');

        // Clear previous messages
        errorDiv.style.display = 'none';
        successDiv.style.display = 'none';
        errorList.innerHTML = '';

        // Get form data
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
                // Success - show message and refresh page
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
}

function openEditModal() {
    document.getElementById('edit-modal').style.display = 'flex';

    // Clear error/success messages
    document.getElementById('edit-error-messages').style.display = 'none';
    document.getElementById('edit-success-message').style.display = 'none';
}

function closeModal(id) {
    document.getElementById(id).style.display = 'none';
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
            window.location.href = '/admin/users';
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

// Auto-refresh bookings every 5 seconds
function refreshBookings() {
    fetch(`/admin/users/${userId}/bookings`)
        .then(response => response.json())
        .then(data => {
            const bookingList = document.getElementById('booking-list');
            const bookingCount = document.getElementById('booking-count');
            
            // Update booking count
            bookingCount.textContent = data.total;
            
            // Generate HTML for bookings
            if (data.bookings.length === 0) {
                bookingList.innerHTML = '<div style="text-align:center; padding:20px; color:#9ca3af;">No bookings found for this user.</div>';
            } else {
                bookingList.innerHTML = data.bookings.map(booking => `
                    <div class="recent-row">
                        <div class="rec-dot status-upcoming"></div>
                        <div class="rec-info">
                            <div class="rec-name">${booking.event_name}</div>
                            <div class="rec-sub">${booking.quantity} ticket(s) · ${booking.category} · ${booking.date}</div>
                        </div>
                        <span class="badge badge-upcoming">${booking.status}</span>
                    </div>
                `).join('');
            }
        })
        .catch(error => console.error('Error refreshing bookings:', error));
}

// Refresh bookings every 5 seconds
setInterval(refreshBookings, 5000);

// Initial refresh
refreshBookings();
