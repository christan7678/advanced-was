(function () {
    function getToolbar() {
        return document.querySelector('.toolbar');
    }

    function getSearchInput() {
        return document.querySelector('.toolbar-search');
    }

    function getStatusSelect() {
        return document.querySelector('.toolbar-select');
    }

    function filterBookings() {
        const searchInput = getSearchInput();
        const statusSelect = getStatusSelect();

        if (!searchInput || !statusSelect) {
            return;
        }

        const search = searchInput.value.toLowerCase().trim();
        const status = statusSelect.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');
        let visibleCount = 0;

        rows.forEach(function (row) {
            // skip empty row
            if (row.querySelector('.td-empty')) {
                row.style.display = 'none';
                return;
            }

            const cells = row.querySelectorAll('td');
 const bookingIdCell = cells[0];
            const bookingCodeCell = cells[1];
            const userNameCell = cells[2];
            const emailCell = cells[3];

            const bookingIdText = bookingIdCell ? bookingIdCell.textContent.toLowerCase() : '';
            const bookingCodeText = bookingCodeCell ? bookingCodeCell.textContent.toLowerCase() : '';
            const userNameText = userNameCell ? userNameCell.textContent.toLowerCase() : '';
            const emailText = emailCell ? emailCell.textContent.toLowerCase() : '';

            // search logic
            let matchSearch = false;
            if (search === '') {
                matchSearch = true;
            } else {
                matchSearch =
                    bookingIdText.includes(search) ||
                    bookingCodeText.includes(search) ||
                    userNameText.includes(search) ||
                    emailText.includes(search);
            }

            // status logic
            let matchStatus = false;
            const statusCell = row.querySelector('.badge');

            if (status === '') {
                matchStatus = true;
            } else if (statusCell) {
                const statusText = statusCell.textContent.toLowerCase().trim();
                matchStatus = statusText === status;
            }

            // apply
            if (matchSearch && matchStatus) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // empty message control
        const emptyCell = document.querySelector('.td-empty');
        if (emptyCell) {
            const emptyRow = emptyCell.closest('tr');
            if (emptyRow) {
                emptyRow.style.display = visibleCount === 0 ? '' : 'none';
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = getSearchInput();
        const statusSelect = getStatusSelect();
        const form = getToolbar();

        if (!form) return;

        let timer = null;

        // 🔍 SEARCH INPUT
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                filterBookings();

                clearTimeout(timer);
                timer = setTimeout(function () {
                    form.submit(); // backend search
                }, 500);
            });

            searchInput.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    form.submit();
                }
            });
        }

  
        if (statusSelect) {
            statusSelect.addEventListener('change', function () {
                filterBookings();
                form.submit();
            });
        }

        // first load
        filterBookings();
    });
})();