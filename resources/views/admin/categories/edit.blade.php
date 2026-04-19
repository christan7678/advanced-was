@extends('admin.layout')

@section('title', 'Edit Category')
@section('page-title', 'Edit Category')

@section('content')
<div class="detail-card" style="max-width:720px;">
    <div class="detail-card-title">Edit category</div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success" style="background:#d1fae5;color:#065f46;padding:10px 14px;border-radius:8px;margin:12px 0;font-size:13px;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-error" style="background:#fee2e2;color:#991b1b;padding:10px 14px;border-radius:8px;margin:12px 0;font-size:13px;">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.categories.update', $category) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Category name</label>
            <input class="form-input" type="text" name="name" required
                   value="{{ old('name', $category->name) }}">
            @error('name')
                <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Colour</label>
            <div style="display:flex; align-items:center; gap:10px;">
                <input class="form-input" type="text" id="cat-color-text" name="color"
                       value="{{ old('color', $category->color ?? '#185FA5') }}" style="width:130px;">
                <input type="color" id="cat-color-picker" value="{{ old('color', $category->color ?? '#185FA5') }}"
                       style="width:36px;height:36px;border-radius:6px;border:1px solid #d1d5db;padding:2px;cursor:pointer;background:#fff;">
            </div>
        </div>

        <div style="display:flex; gap:8px; margin-top:6px;">
            <a href="{{ route('admin.categories.index') }}" class="btn-cancel" style="text-decoration:none;display:inline-flex;align-items:center;justify-content:center;">
                Cancel
            </a>
            <button type="submit" class="btn-primary">Update category</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/categories.js') }}"></script>
@endsection

