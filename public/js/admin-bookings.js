(function () {
    function bookingsBase() {
        var holder = document.querySelector('[data-admin-bookings-base]');
        var raw = (holder && holder.getAttribute('data-admin-bookings-base')) || '';
        return String(raw).replace(/\/$/, '');
    }

    function closeModal(id) {
        var el = document.getElementById(id);
        if (el) el.style.display = 'none';
    }

    window.closeModal = closeModal;

    window.openCancelModal = function (id, userName) {
        var sub = document.getElementById('cancel-modal-sub');
        if (sub) sub.textContent = 'Cancel booking #' + id + (userName ? (' for ' + userName) : '') + '?';
        var form = document.getElementById('cancel-form');
        if (form) form.action = bookingsBase() + '/' + id + '/cancel';
        var m = document.getElementById('cancel-modal');
        if (m) m.style.display = 'flex';
    };

    window.openDeleteModal = function (id, userName) {
        var sub = document.getElementById('delete-modal-sub');
        if (sub) sub.textContent = 'Delete booking #' + id + (userName ? (' for ' + userName) : '') + '?';
        var form = document.getElementById('delete-form');
        if (form) form.action = bookingsBase() + '/' + id;
        var m = document.getElementById('delete-modal');
        if (m) m.style.display = 'flex';
    };

    // Backward compatibility for any old inline handlers.
    window.openCancelModalLegacy = window.openCancelModal;
    window.openDeleteModalLegacy = window.openDeleteModal;
})();

