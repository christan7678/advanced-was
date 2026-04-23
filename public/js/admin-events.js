(function () {

    var currentPage = 1;
    var rowsPerPage = 5;

    function filterFolders() {
        var searchEl = document.getElementById('categorySearch');
        var statusEl = document.getElementById('statusFilter');
        var emptyMsg = document.getElementById('noFolderFound');
        if (!searchEl || !statusEl) {
            return;
        }

        var search = searchEl.value.toLowerCase().trim();
        var status = statusEl.value.toLowerCase();
        var cards = document.querySelectorAll('.folder-card');
        var visibleCount = 0;

        cards.forEach(function (card) {
            var name = (card.dataset.name || '').toLowerCase();
            var cardStatus = (card.dataset.status || '').toLowerCase();
            var matchSearch = search === '' || name.indexOf(search) !== -1;
            var matchStatus = status === '' || cardStatus === status;

            if (matchSearch && matchStatus) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        if (emptyMsg) {
            emptyMsg.style.display = visibleCount === 0 ? 'block' : 'none';
        }
    }

    function getFilteredEventRows() {
        var searchEl = document.getElementById('eventSearch');
        var statusEl = document.getElementById('eventStatusFilter');

        if (!searchEl || !statusEl) {
            return [];
        }

        var search = searchEl.value.toLowerCase().trim();
        var status = statusEl.value.toLowerCase();
        var rows = document.querySelectorAll('#categoryEventsTable tr[data-name]');
        var matchedRows = [];

        rows.forEach(function (row) {
            var name = (row.dataset.name || '').toLowerCase();
            var rowStatus = (row.dataset.status || '').toLowerCase();

            var matchSearch = search === '' || name.indexOf(search) !== -1;
            var matchStatus = status === '' || rowStatus === status;

            if (matchSearch && matchStatus) {
                matchedRows.push(row);
            }
        });

        return matchedRows;
    }

    function renderPagination(totalItems) {
        var controls = document.getElementById('paginationControls');
        var pageInfo = document.getElementById('pageInfo');

        if (!controls || !pageInfo) {
            return;
        }

        controls.innerHTML = '';

        if (totalItems === 0) {
            pageInfo.textContent = 'Showing 0 event(s)';
            return;
        }

        var totalPages = Math.ceil(totalItems / rowsPerPage);
        var start = (currentPage - 1) * rowsPerPage + 1;
        var end = Math.min(currentPage * rowsPerPage, totalItems);

        pageInfo.textContent = 'Showing ' + start + ' to ' + end + ' of ' + totalItems + ' event(s)';

        if (totalPages <= 1) {
            return;
        }

        var prevBtn = document.createElement('button');
        prevBtn.type = 'button';
        prevBtn.textContent = 'Prev';
        prevBtn.className = 'act-btn';
        prevBtn.disabled = currentPage === 1;
        prevBtn.addEventListener('click', function () {
            if (currentPage > 1) {
                currentPage--;
                filterCategoryEvents();
            }
        });
        controls.appendChild(prevBtn);

        for (var i = 1; i <= totalPages; i++) {
            var pageBtn = document.createElement('button');
            pageBtn.type = 'button';
            pageBtn.textContent = i;
            pageBtn.className = 'act-btn';
            if (i === currentPage) {
                pageBtn.style.fontWeight = '700';
                pageBtn.style.border = '1px solid #111827';
            }

            (function (page) {
                pageBtn.addEventListener('click', function () {
                    currentPage = page;
                    filterCategoryEvents();
                });
            })(i);

            controls.appendChild(pageBtn);
        }

        var nextBtn = document.createElement('button');
        nextBtn.type = 'button';
        nextBtn.textContent = 'Next';
        nextBtn.className = 'act-btn';
        nextBtn.disabled = currentPage === totalPages;
        nextBtn.addEventListener('click', function () {
            if (currentPage < totalPages) {
                currentPage++;
                filterCategoryEvents();
            }
        });
        controls.appendChild(nextBtn);
    }

   function filterCategoryEvents() {
        var emptyRow = document.getElementById('noCategoryEventsRow');
        var rows = document.querySelectorAll('#categoryEventsTable tr[data-name]');
        var matchedRows = getFilteredEventRows();

        rows.forEach(function (row) {
            row.style.display = 'none';
        });

        var totalItems = matchedRows.length;
        var totalPages = Math.ceil(totalItems / rowsPerPage);

        if (currentPage > totalPages && totalPages > 0) {
            currentPage = totalPages;
        }

        if (totalItems === 0) {
            if (emptyRow) {
                emptyRow.style.display = '';
            }
            renderPagination(0);
            return;
        }

        if (emptyRow) {
            emptyRow.style.display = 'none';
        }

        var startIndex = (currentPage - 1) * rowsPerPage;
        var endIndex = startIndex + rowsPerPage;

        matchedRows.forEach(function (row, index) {
            if (index >= startIndex && index < endIndex) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });

        renderPagination(totalItems);
    }

    function eventsApiBase() {
        var holder = document.querySelector('[data-admin-events-base]');
        var raw = (holder && holder.getAttribute('data-admin-events-base')) || '';
        return String(raw).replace(/\/$/, '');
    }

    window.closeModal = function (id) {
        var m = document.getElementById(id);
        if (m) {
            m.style.display = 'none';
        }
    };

    window.openEventDeleteModal = function (id, name) {
        var sub = document.getElementById('delete-modal-sub');
        if (sub) {
            sub.textContent = 'Delete “' + (name || '') + '”? This cannot be undone.';
        }

        var form = document.getElementById('delete-form');
        if (form) {
            form.action = eventsApiBase() + '/' + id;
        }

        var modal = document.getElementById('delete-modal');
        if (modal) {
            modal.style.display = 'flex';
        }
    };

    document.addEventListener('DOMContentLoaded', function () {
        var cs = document.getElementById('categorySearch');
        var st = document.getElementById('statusFilter');
        if (cs && st) {
            cs.addEventListener('input', filterFolders);
            st.addEventListener('change', filterFolders);
        }

        var es = document.getElementById('eventSearch');
        var est = document.getElementById('eventStatusFilter');
        if (es && est) {
            es.addEventListener('input', function () {
                currentPage = 1;
                filterCategoryEvents();
            });

            est.addEventListener('change', function () {
                currentPage = 1;
                filterCategoryEvents();
            });
        }

        filterCategoryEvents();
    });
})();