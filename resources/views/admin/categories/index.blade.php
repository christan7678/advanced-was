@extends('admin.layout')

@section('title', 'Categories')
@section('page-title', 'Categories')

@section('content')
<div class="cat-page-grid">

    <div class="detail-card" id="cat-form-card">
        <div class="detail-card-title" id="cat-form-heading">Add new category</div>

        <form id="cat-form">
            <div class="form-group">
                <label>Category name</label>
                <input class="form-input" type="text" id="cat-name" placeholder="e.g. Sports" required>
            </div>

            <div class="form-group">
                <label>Slug</label>
                <input class="form-input" type="text" id="cat-slug" placeholder="e.g. sports">
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea class="form-input form-textarea" id="cat-desc" placeholder="Short description..."></textarea>
            </div>

            <div class="form-group">
                <label>Colour</label>
                <div style="display:flex; align-items:center; gap:10px;">
                    <input class="form-input" type="text" id="cat-color-text" value="#185FA5" style="width:130px;">
                    <input type="color" id="cat-color-picker" value="#185FA5"
                           style="width:36px;height:36px;border-radius:6px;border:1px solid #d1d5db;padding:2px;cursor:pointer;background:#fff;">
                </div>
            </div>

            <div style="display:flex; gap:8px; margin-top:6px;">
                <button type="button" class="btn-cancel" onclick="resetCatForm()">Clear</button>
                <button type="button" class="btn-primary">Save category</button>
            </div>
        </form>
    </div>

    <div>
        <div style="font-size:15px; font-weight:700; color:#111827; margin-bottom:14px;">
            All categories <span style="font-weight:400; color:#9ca3af; font-size:13px;">(3)</span>
        </div>

        <div class="cat-list">
            <div class="cat-row">
                <div class="cat-row-left">
                    <div class="cat-dot" style="background:#185FA5;"></div>
                    <div>
                        <div class="cat-name">Concert</div>
                        <div class="td-sub">8 event(s)</div>
                    </div>
                </div>
                <div style="display:flex; gap:6px;">
                    <button class="act-btn" onclick="editCategory('Concert', 'concert', 'Music events', '#185FA5')">Edit</button>
                    <button class="act-btn act-del" onclick="openDeleteModal()">Delete</button>
                </div>
            </div>

            <div class="cat-row">
                <div class="cat-row-left">
                    <div class="cat-dot" style="background:#059669;"></div>
                    <div>
                        <div class="cat-name">Workshop</div>
                        <div class="td-sub">3 event(s)</div>
                    </div>
                </div>
                <div style="display:flex; gap:6px;">
                    <button class="act-btn" onclick="editCategory('Workshop', 'workshop', 'Learning events', '#059669')">Edit</button>
                    <button class="act-btn act-del" onclick="openDeleteModal()">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-backdrop" id="delete-modal" style="display:none;">
    <div class="modal modal-sm">
        <div class="modal-icon-danger">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2">
                <polyline points="3 6 5 6 21 6"/>
                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                <path d="M10 11v6M14 11v6M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
            </svg>
        </div>
        <div class="modal-title text-center">Delete category?</div>
        <div class="modal-sub text-center">UI only popup.</div>
        <div class="modal-actions centered">
            <button type="button" class="btn-cancel" onclick="closeModal('delete-modal')">Cancel</button>
            <button type="button" class="btn-danger">Yes, delete</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const colorText = document.getElementById('cat-color-text');
const colorPicker = document.getElementById('cat-color-picker');

colorText.addEventListener('input', () => colorPicker.value = colorText.value);
colorPicker.addEventListener('input', () => colorText.value = colorPicker.value);

function editCategory(name, slug, description, color) {
    document.getElementById('cat-form-heading').textContent = 'Edit category';
    document.getElementById('cat-name').value = name;
    document.getElementById('cat-slug').value = slug;
    document.getElementById('cat-desc').value = description;
    document.getElementById('cat-color-text').value = color;
    document.getElementById('cat-color-picker').value = color;
}

function resetCatForm() {
    document.getElementById('cat-form-heading').textContent = 'Add new category';
    document.getElementById('cat-form').reset();
    document.getElementById('cat-color-text').value = '#185FA5';
    document.getElementById('cat-color-picker').value = '#185FA5';
}

function openDeleteModal() {
    document.getElementById('delete-modal').style.display = 'flex';
}
function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}
</script>
@endsection