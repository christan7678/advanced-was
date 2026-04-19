@extends('admin.layout')

@section('title', 'Categories')
@section('page-title', 'Categories')

@section('content')
<div class="cat-page-grid" data-admin-categories-base="{{ rtrim(url('/admin/categories'), '/') }}">

    <div class="detail-card" id="cat-form-card">
        <div class="detail-card-title" id="cat-form-heading">Add new category</div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success" style="background:#d1fae5;color:#065f46;padding:10px 14px;border-radius:8px;margin-bottom:12px;font-size:13px;">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-error" style="background:#fee2e2;color:#991b1b;padding:10px 14px;border-radius:8px;margin-bottom:12px;font-size:13px;">
                {{ session('error') }}
            </div>
        @endif

        {{-- CREATE FORM --}}
        <form id="cat-form" action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <input type="hidden" id="form-method" name="_method" value="POST">
            <input type="hidden" id="edit-id" name="edit_id" value="">

            <div class="form-group">
                <label>Category name</label>
                <input class="form-input" type="text" id="cat-name" name="name"
                       placeholder="e.g. Sports" required
                       value="{{ old('name') }}">
                @error('name')
                    <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Colour</label>
                <div style="display:flex; align-items:center; gap:10px;">
                    <input class="form-input" type="text" id="cat-color-text" name="color"
                           value="{{ old('color', '#185FA5') }}" style="width:130px;">
                    <input type="color" id="cat-color-picker" value="{{ old('color', '#185FA5') }}"
                           style="width:36px;height:36px;border-radius:6px;border:1px solid #d1d5db;padding:2px;cursor:pointer;background:#fff;">
                </div>
            </div>

            <div style="display:flex; gap:8px; margin-top:6px;">
                <button type="button" class="btn-cancel" onclick="resetCatForm()">Clear</button>
                <button type="submit" class="btn-primary" id="submit-btn">Save category</button>
            </div>
        </form>
    </div>

    <div>
        <div style="font-size:15px; font-weight:700; color:#111827; margin-bottom:14px;">
            All categories
            <span style="font-weight:400; color:#9ca3af; font-size:13px;">({{ $categories->count() }})</span>
        </div>

        <div class="cat-list">
            @forelse($categories as $category)
            <div class="cat-row">
                <div class="cat-row-left">
                    <div class="cat-dot" style="background:{{ $category->color }};"></div>
                    <div>
                        <div class="cat-name">{{ $category->name }}</div>
                        <div class="td-sub">{{ $category->events()->count() }} event(s)</div>
                    </div>
                </div>
                <div style="display:flex; gap:6px;">
                    <a class="act-btn" href="{{ route('admin.categories.edit', $category) }}" style="text-decoration:none;">
                        Edit
                    </a>

                    <button class="act-btn act-del"
                        onclick="openDeleteModal({{ $category->id }}, '{{ addslashes($category->name) }}')">
                        Delete
                    </button>
                </div>
            </div>
            @empty
            <div style="text-align:center;color:#9ca3af;padding:32px 0;font-size:14px;">
                No categories yet. Add one above!
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- DELETE CONFIRM MODAL --}}
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
        <div class="modal-sub text-center" id="delete-modal-sub">This action cannot be undone.</div>
        <div class="modal-actions centered">
            <button type="button" class="btn-cancel" onclick="closeModal('delete-modal')">Cancel</button>
            <form id="delete-form" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">Yes, delete</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/categories.js') }}"></script>
@endsection