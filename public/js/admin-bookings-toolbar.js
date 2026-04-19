(function() {
    function filterBookings() {
        const searchInput = document.querySelector('.toolbar-search');
        const statusSelect = document.querySelector('.toolbar-select');
        
        if (!searchInput || !statusSelect) {
            return;
        }

        const search = searchInput.value.toLowerCase().trim();
        const status = statusSelect.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');
        let visibleCount = 0;

        rows.forEach(function(row) {
            // Skip empty message rows
            if (row.querySelector('.td-empty')) {
                row.style.display = 'none';
                return;
            }

            const cells = row.querySelectorAll('td');
            let matchSearch = false;
            let matchStatus = false;

            // Check search in booking ID, user name, and email only
            if (search === '') {
                matchSearch = true;
            } else {
                // Booking ID (first column)
                const bookingIdCell = cells[0];
                // User name (second column)  
                const userNameCell = cells[1];
                // Email (third column)
                const emailCell = cells[2];
                
                const bookingIdText = bookingIdCell ? bookingIdCell.textContent.toLowerCase() : '';
                const userNameText = userNameCell ? userNameCell.textContent.toLowerCase() : '';
                const emailText = emailCell ? emailCell.textContent.toLowerCase() : '';
                
                matchSearch = bookingIdText.includes(search) || 
                             userNameText.includes(search) || 
                             emailText.includes(search);
            }

            // Check status filter
            const statusCell = row.querySelector('.badge');
            if (status === '') {
                matchStatus = true;
            } else if (statusCell) {
                matchStatus = statusCell.textContent.toLowerCase().includes(status);
            }

            if (matchSearch && matchStatus) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Show/hide empty message
        const emptyRow = document.querySelector('.td-empty');
        if (emptyRow) {
            const emptyRowParent = emptyRow.closest('tr');
            if (emptyRowParent) {
                emptyRowParent.style.display = visibleCount === 0 ? '' : 'none';
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('.toolbar-search');
        const statusSelect = document.querySelector('.toolbar-select');
        const form = document.querySelector('.toolbar');
        
        if (searchInput && statusSelect) {
            searchInput.addEventListener('input', filterBookings);
            statusSelect.addEventListener('change', filterBookings);
            
            // Prevent Enter key from submitting form
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    return false;
                }
            });
        }
        
        // Prevent form submission
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                return false;
            });
        }
    });
})();
