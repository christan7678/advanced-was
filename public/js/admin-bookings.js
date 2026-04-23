(function () {
    function bookingsBase() {
        var holder = document.querySelector('[data-admin-bookings-base]');
        var raw = (holder && holder.getAttribute('data-admin-bookings-base')) || '';
        raw = String(raw).replace(/\/$/, '');
        return raw || '/admin/bookings';
    }

    function closeModal(id) {
        var el = document.getElementById(id);
        if (el) {
            el.style.display = 'none';
        }
    }

    function openModal(id) {
        var el = document.getElementById(id);
        if (el) {
            el.style.display = 'flex';
        }
    }

    window.closeModal = closeModal;

    window.openCancelModal = function (id, userName) {
        var sub = document.getElementById('cancel-modal-sub');
        if (sub) {
            sub.textContent = 'Cancel booking #' + id + (userName ? (' for ' + userName) : '') + '?';
        }

        var form = document.getElementById('cancel-form');
        if (form) {
            form.action = bookingsBase() + '/' + id + '/cancel';
        }

        openModal('cancel-modal');
    };

    window.openDeleteModal = function (id, userName) {
        var sub = document.getElementById('delete-modal-sub');
        if (sub) {
            sub.textContent = 'Delete booking #' + id + (userName ? (' for ' + userName) : '') + '?';
        }

        var form = document.getElementById('delete-form');
        if (form) {
            form.action = bookingsBase() + '/' + id;
        }

        openModal('delete-modal');
    };

    window.openCancelModalLegacy = window.openCancelModal;
    window.openDeleteModalLegacy = window.openDeleteModal;

    document.addEventListener('DOMContentLoaded', function () {
        document.addEventListener('click', function (e) {
            var target = e.target;

            if (target.matches('[data-close-modal]')) {
                var modalId = target.getAttribute('data-close-modal');
                closeModal(modalId);
            }

            if (target.classList.contains('modal-backdrop')) {
                target.style.display = 'none';
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeModal('cancel-modal');
                closeModal('delete-modal');
            }
        });
    });
})();