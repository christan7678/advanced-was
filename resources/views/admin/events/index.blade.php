@extends('admin.layout')

@section('title', 'Events')
@section('page-title', 'Events')

@section('topbar-actions')
<a href="{{ route('admin.categories.index') }}" class="btn-primary">
    + Create category
</a>
@endsection

@section('content')
<form class="toolbar" onsubmit="return false;">
    <input id="categorySearch" class="toolbar-search" type="text" placeholder="Search category...">

    <select id="statusFilter" class="toolbar-select">
        <option value="">All status</option>
        <option value="active">Active</option>
        <option value="draft">Draft</option>
    </select>

    <button type="button" class="btn-outline-sm" onclick="filterFolders()">Filter</button>
    <button type="button" class="btn-outline-sm" onclick="resetFolderFilters()">Reset</button>
</form>







<div class="folder-grid" id="folderGrid">

    {{--Concert--}}
    <a href="{{ route('admin.events.category', ['category' => 'concert']) }}" class="folder-card"
        data-name="Concert"
        data-status="active">
        <div class="folder-top">
            <div class="folder-icon">
                <svg width="30" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M3 6.5A2.5 2.5 0 0 1 5.5 4H10l1.6 2H18.5A2.5 2.5 0 0 1 21 8.5v8A2.5 2.5 0 0 1 18.5 19h-13A2.5 2.5 0 0 1 3 16.5v-10Z" fill="#F4B400"/>
                    <path d="M3 9h18v7.5A2.5 2.5 0 0 1 18.5 19h-13A2.5 2.5 0 0 1 3 16.5V9Z" fill="#FFD54F"/>
                </svg>
            </div>
            <span class="folder-badge folder-badge-active">Active</span>
        </div>
        <div class="folder-title">Concert</div>
        <div class="folder-meta">12 events</div>
        <div class="folder-sub">Music shows, live performance, festival</div>
    </a>

    {{--Workshop--}}
    <a href="{{ route('admin.events.category', ['category' => 'workshop']) }}" class="folder-card"
        data-name="Workshop"
        data-status="draft">
        <div class="folder-top">
            <div class="folder-icon">
                <svg width="30" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M3 6.5A2.5 2.5 0 0 1 5.5 4H10l1.6 2H18.5A2.5 2.5 0 0 1 21 8.5v8A2.5 2.5 0 0 1 18.5 19h-13A2.5 2.5 0 0 1 3 16.5v-10Z" fill="#F4B400"/>
                    <path d="M3 9h18v7.5A2.5 2.5 0 0 1 18.5 19h-13A2.5 2.5 0 0 1 3 16.5V9Z" fill="#FFD54F"/>
                </svg>
            </div>
            <span class="folder-badge folder-badge-active">Active</span>
        </div>
        <div class="folder-title">Workshop</div>
        <div class="folder-meta">5 events</div>
        <div class="folder-sub">Learning, training, seminar, class</div>
    </a>

    {{--Seminar--}}
    <a href="{{ route('admin.events.category', ['category' => 'seminar']) }}" class="folder-card"
        data-name="Seminar"
        data-status="active">
        <div class="folder-top">
            <div class="folder-icon">
                <svg width="30" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M3 6.5A2.5 2.5 0 0 1 5.5 4H10l1.6 2H18.5A2.5 2.5 0 0 1 21 8.5v8A2.5 2.5 0 0 1 18.5 19h-13A2.5 2.5 0 0 1 3 16.5v-10Z" fill="#F4B400"/>
                    <path d="M3 9h18v7.5A2.5 2.5 0 0 1 18.5 19h-13A2.5 2.5 0 0 1 3 16.5V9Z" fill="#FFD54F"/>
                </svg>
            </div>
            <span class="folder-badge folder-badge-active">Active</span>
        </div>
        <div class="folder-title">Seminar</div>
        <div class="folder-meta">6 events</div>
        <div class="folder-sub">Talk, conference, academic sharing</div>
    </a>

    {{--Exhibition--}}
    <a href="{{ route('admin.events.category', ['category' => 'exhibition']) }}" class="folder-card"
        data-name="Exhibition"
        data-status="active">
        <div class="folder-top">
            <div class="folder-icon">
                <svg width="30" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M3 6.5A2.5 2.5 0 0 1 5.5 4H10l1.6 2H18.5A2.5 2.5 0 0 1 21 8.5v8A2.5 2.5 0 0 1 18.5 19h-13A2.5 2.5 0 0 1 3 16.5v-10Z" fill="#F4B400"/>
                    <path d="M3 9h18v7.5A2.5 2.5 0 0 1 18.5 19h-13A2.5 2.5 0 0 1 3 16.5V9Z" fill="#FFD54F"/>
                </svg>
            </div>
            <span class="folder-badge folder-badge-active">Active</span>
        </div>
        <div class="folder-title">Exhibition</div>
        <div class="folder-meta">4 events</div>
        <div class="folder-sub">Art show, expo, gallery, showcase</div>
    </a>

    {{--Community--}}
    <a href="{{ route('admin.events.category', ['category' => 'community']) }}" class="folder-card"
        data-name="Community"
        data-status="active">
        <div class="folder-top">
            <div class="folder-icon">
                <svg width="30" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M3 6.5A2.5 2.5 0 0 1 5.5 4H10l1.6 2H18.5A2.5 2.5 0 0 1 21 8.5v8A2.5 2.5 0 0 1 18.5 19h-13A2.5 2.5 0 0 1 3 16.5v-10Z" fill="#F4B400"/>
                    <path d="M3 9h18v7.5A2.5 2.5 0 0 1 18.5 19h-13A2.5 2.5 0 0 1 3 16.5V9Z" fill="#FFD54F"/>
                </svg>
            </div>
            <span class="folder-badge folder-badge-draft">Draft</span>
        </div>
        <div class="folder-title">Community</div>
        <div class="folder-meta">3 events</div>
        <div class="folder-sub">Public, volunteer, social gathering</div>
    </a>
</div>





<div id="noFolderFound" class="td-empty" style="display:none; background:#fff; border:1px solid #e5e7eb; border-radius:16px;">
    No matching category found.
</div>






{{-- Create / Edit Event Modal --}}
<div class="modal-backdrop" id="event-modal" style="display:none;">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">Create / Edit Event</div>
            <button type="button" class="modal-close" onclick="closeModal('event-modal')">✕</button>
        </div>

        <form>
            <div class="form-grid">
                <div class="form-group form-full">
                    <label>Event title</label>
                    <input class="form-input" type="text" placeholder="Music Festival 2026">
                </div>

                <div class="form-group form-full">
                    <label>Artist / organiser</label>
                    <input class="form-input" type="text" placeholder="Coldplay">
                </div>

                <div class="form-group">
                    <label>Category</label>
                    <select class="form-input">
                        <option>Concert</option>
                        <option>Sports</option>
                        <option>Workshop</option>
                        <option>Seminar</option>
                        <option>Exhibition</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Venue / city</label>
                    <input class="form-input" type="text" placeholder="Kuala Lumpur">
                </div>

                <div class="form-group">
                    <label>Date</label>
                    <input class="form-input" type="date">
                </div>

                <div class="form-group">
                    <label>Time</label>
                    <input class="form-input" type="time">
                </div>

                <div class="form-group">
                    <label>Price from</label>
                    <input class="form-input" type="number" placeholder="188">
                </div>

                <div class="form-group">
                    <label>Price to</label>
                    <input class="form-input" type="number" placeholder="688">
                </div>

                <div class="form-group">
                    <label>Total tickets</label>
                    <input class="form-input" type="number" placeholder="500">
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select class="form-input">
                        <option>Draft</option>
                        <option>Published</option>
                    </select>
                </div>

                <div class="form-group form-full">
                    <label>Description</label>
                    <textarea class="form-input form-textarea" placeholder="Describe the event..."></textarea>
                </div>

                <div class="form-group form-full">
                    <label>Event image</label>
                    <input class="form-input" type="file">
                </div>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeModal('event-modal')">Cancel</button>
                <button type="button" class="btn-primary">Save event</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openModal(id) {
        document.getElementById(id).style.display = 'flex';
    }

    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }

    function filterFolders() {
        const search = document.getElementById('categorySearch').value.toLowerCase().trim();
        const status = document.getElementById('statusFilter').value.toLowerCase();
        const cards = document.querySelectorAll('.folder-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const name = card.dataset.name.toLowerCase();
            const cardStatus = card.dataset.status.toLowerCase();

            const matchSearch = search === '' || name.includes(search);
            const matchStatus = status === '' || cardStatus === status;

            if (matchSearch && matchStatus) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        document.getElementById('noFolderFound').style.display = visibleCount === 0 ? 'block' : 'none';
    }

    function resetFolderFilters() {
        document.getElementById('categorySearch').value = '';
        document.getElementById('statusFilter').value = '';
        filterFolders();
    }

    document.getElementById('categorySearch').addEventListener('input', filterFolders);
    document.getElementById('statusFilter').addEventListener('change', filterFolders);
</script>
@endsection