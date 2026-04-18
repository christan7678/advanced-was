(function () {
    function getBase() {
        var holder = document.querySelector('[data-admin-categories-base]');
        var raw = (holder && holder.getAttribute('data-admin-categories-base')) || window.adminCategoriesBase || '';
        return String(raw).replace(/\/$/, '');
    }

    function el(id) {
        return document.getElementById(id);
    }

    window.resetCatForm = function () {
        var form = el('cat-form');
        if (!form) return;
        form.action = getBase();
        form.method = 'post';
        var methodInput = el('form-method');
        if (methodInput) methodInput.value = 'POST';
        var editId = el('edit-id');
        if (editId) editId.value = '';
        var nameInput = el('cat-name');
        if (nameInput) nameInput.value = '';
        var descInput = el('cat-desc');
        if (descInput) descInput.value = '';
        var def = '#185FA5';
        var colorText = el('cat-color-text');
        var colorPicker = el('cat-color-picker');
        if (colorText) colorText.value = def;
        if (colorPicker) colorPicker.value = def;
        var heading = el('cat-form-heading');
        if (heading) heading.textContent = 'Add new category';
        var btn = el('submit-btn');
        if (btn) btn.textContent = 'Save category';
    };

    window.editCategory = function (id, name, description, color) {
        var form = el('cat-form');
        if (!form) return;
        form.action = base + '/' + id;
        form.method = 'post';
        var methodInput = el('form-method');
        if (methodInput) methodInput.value = 'PUT';
        var editId = el('edit-id');
        if (editId) editId.value = String(id);
        var nameInput = el('cat-name');
        if (nameInput) nameInput.value = name || '';
        var descInput = el('cat-desc');
        if (descInput) descInput.value = description || '';
        var col = color || '#185FA5';
        var colorText = el('cat-color-text');
        var colorPicker = el('cat-color-picker');
        if (colorText) colorText.value = col;
        if (colorPicker) colorPicker.value = col;
        var heading = el('cat-form-heading');
        if (heading) heading.textContent = 'Edit category';
        var btn = el('submit-btn');
        if (btn) btn.textContent = 'Update category';
    };

    window.openDeleteModal = function (id, name) {
        var sub = el('delete-modal-sub');
        if (sub) sub.textContent = 'Delete “' + (name || '') + '”? This cannot be undone.';
        var df = el('delete-form');
        if (df) df.action = getBase() + '/' + id;
        var m = el('delete-modal');
        if (m) m.style.display = 'flex';
    };

    window.closeModal = function (modalId) {
        var m = document.getElementById(modalId);
        if (m) m.style.display = 'none';
    };

    document.addEventListener('DOMContentLoaded', function () {
        var picker = el('cat-color-picker');
        var text = el('cat-color-text');
        if (picker && text) {
            picker.addEventListener('input', function () {
                text.value = picker.value;
            });
            text.addEventListener('input', function () {
                if (/^#[0-9A-Fa-f]{6}$/.test(text.value)) {
                    picker.value = text.value;
                }
            });
        }
    });
})();
