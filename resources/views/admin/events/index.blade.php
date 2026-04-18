@extends('admin.layout')

@section('title', 'Events')
@section('page-title', 'Events')

@section('topbar-actions')
    <a href="{{ route('admin.categories.index') }}" class="btn-outline-sm">Manage categories</a>
    <a href="{{ route('admin.events.create') }}" class="btn-primary">+ New event</a>
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success" style="background:#d1fae5;color:#065f46;padding:10px 14px;border-radius:8px;margin-bottom:14px;font-size:13px;">
        {{ session('success') }}
    </div>
@endif

<form class="toolbar" onsubmit="return false;">
    <input id="categorySearch" class="toolbar-search" type="text" placeholder="Search category...">

    <select id="statusFilter" class="toolbar-select">
        <option value="">All</option>
        <option value="active">Has events</option>
        <option value="draft">No events</option>
    </select>
</form>

<div class="folder-grid" id="folderGrid">
    @forelse($categories as $category)
        @php
            $hasEvents = $category->events_count > 0;
            $status = $hasEvents ? 'active' : 'draft';
        @endphp
        <a href="{{ route('admin.events.category', $category) }}" class="folder-card"
            data-name="{{ e($category->name) }}"
            data-status="{{ $status }}">
            <div class="folder-top">
                <div class="folder-icon">
                    <svg width="30" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M3 6.5A2.5 2.5 0 0 1 5.5 4H10l1.6 2H18.5A2.5 2.5 0 0 1 21 8.5v8A2.5 2.5 0 0 1 18.5 19h-13A2.5 2.5 0 0 1 3 16.5v-10Z" fill="#F4B400"/>
                        <path d="M3 9h18v7.5A2.5 2.5 0 0 1 18.5 19h-13A2.5 2.5 0 0 1 3 16.5V9Z" fill="#FFD54F"/>
                    </svg>
                </div>
                <span class="folder-badge {{ $hasEvents ? 'folder-badge-active' : 'folder-badge-draft' }}">
                    {{ $hasEvents ? 'Active' : 'Empty' }}
                </span>
            </div>
            <div class="folder-title">{{ $category->name }}</div>
            <div class="folder-meta">{{ $category->events_count }} event(s)</div>
            <div class="folder-sub">{{ \Illuminate\Support\Str::limit($category->description ?? '—', 80) }}</div>
        </a>
    @empty
        <div class="td-empty" style="grid-column:1/-1;background:#fff;border:1px solid #e5e7eb;border-radius:16px;padding:32px;text-align:center;">
            No categories yet. <a href="{{ route('admin.categories.index') }}">Create one</a> first.
        </div>
    @endforelse
</div>

<div id="noFolderFound" class="td-empty" style="display:none; background:#fff; border:1px solid #e5e7eb; border-radius:16px;">
    No matching category found.
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/admin-events.js') }}"></script>
@endsection
