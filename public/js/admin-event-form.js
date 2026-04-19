(function () {
    document.addEventListener('DOMContentLoaded', function () {
        var form = document.getElementById('admin-event-form');
        if (!form) {
            return;
        }

        var totalEl = document.getElementById('evt-total-seats');
        var availEl = document.getElementById('evt-available-seats');
        var syncCheck = document.getElementById('evt-sync-seats-check');
        var syncFromForm = form.getAttribute('data-sync-seats') === '1';

        function syncAvailableToTotal() {
            if (!syncFromForm || !totalEl || !availEl) {
                return;
            }
            if (syncCheck && !syncCheck.checked) {
                return;
            }
            var t = parseInt(totalEl.value, 10);
            if (!isNaN(t) && t >= 0) {
                availEl.value = String(t);
            }
        }

        if (totalEl && availEl && syncFromForm) {
            totalEl.addEventListener('input', syncAvailableToTotal);
            totalEl.addEventListener('change', syncAvailableToTotal);
            if (syncCheck) {
                syncCheck.addEventListener('change', function () {
                    if (syncCheck.checked) {
                        syncAvailableToTotal();
                    }
                });
            }
            syncAvailableToTotal();
        }

        var fileInput = document.getElementById('evt-image-input');
        var previewWrap = document.getElementById('evt-image-preview-wrap');
        var previewImg = document.getElementById('evt-image-preview');

        if (fileInput && previewWrap && previewImg) {
            fileInput.addEventListener('change', function () {
                var file = fileInput.files && fileInput.files[0];
                if (!file || !file.type || file.type.indexOf('image/') !== 0) {
                    previewWrap.style.display = 'none';
                    previewImg.src = '';
                    return;
                }
                var url = URL.createObjectURL(file);
                previewImg.src = url;
                previewWrap.style.display = 'block';
            });
        }
    });
})();
