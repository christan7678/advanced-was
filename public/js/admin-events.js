(function () {
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

    function filterCategoryEvents() {
        var searchEl = document.getElementById('eventSearch');
        var statusEl = document.getElementById('eventStatusFilter');
        var emptyRow = document.getElementById('noCategoryEventsRow');
        if (!searchEl || !statusEl) {
            return;
        }

        var search = searchEl.value.toLowerCase().trim();
        var status = statusEl.value.toLowerCase();
        var rows = document.querySelectorAll('#categoryEventsTable tr[data-name]');
        var visibleCount = 0;

        rows.forEach(function (row) {
            var name = (row.dataset.name || '').toLowerCase();
            var rowStatus = (row.dataset.status || '').toLowerCase();
            var matchSearch = search === '' || name.indexOf(search) !== -1;
            var matchStatus = status === '' || rowStatus === status;

            if (matchSearch && matchStatus) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        if (emptyRow) {
            emptyRow.style.display = visibleCount === 0 ? '' : 'none';
        }
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
            es.addEventListener('input', filterCategoryEvents);
            est.addEventListener('change', filterCategoryEvents);
        }
    });
})();
