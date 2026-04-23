@extends('admin.layout')

@section('title', $category->name . ' — Events')
@section('page-title', $category->name)

@section('topbar-actions')
    <a href="{{ route('admin.events.index') }}" class="btn-outline-sm">← All categories</a>
    <a href="{{ route('admin.events.create', ['category_id' => $category->id]) }}" class="btn-primary">+ Create event</a>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success flash"
            style="background:#d1fae5;color:#065f46;padding:10px 14px;border-radius:8px;margin-bottom:14px;font-size:13px;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-error flash"
            style="background:#fee2e2;color:#991b1b;padding:10px 14px;border-radius:8px;margin-bottom:14px;font-size:13px;">
            {{ session('error') }}
        </div>
    @endif
    <form class="toolbar" onsubmit="return false;">
        <input id="eventSearch" class="toolbar-search" type="text" placeholder="Search event name...">

        <select id="eventStatusFilter" class="toolbar-select">
            <option value="">All status</option>
            <option value="published">Upcoming</option>
            <option value="draft">Ended</option>
        </select>
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Event</th>
                    <th>Date</th>
                    <th>Venue</th>
                    <th>Price (RM)</th>
                    <th>Tickets</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody id="categoryEventsTable">
                @forelse($events as $event)
                    @php
                        $rowStatus = \App\Http\Controllers\Admin\AdminEventController::eventRowStatus($event);
                        $sold = max(0, (int) $event->total_seats - (int) $event->available_seats);
                    @endphp
                    <tr data-name="{{ strtolower($event->name) }}" data-status="{{ $rowStatus }}">
                        <td>
                            <div class="td-title">{{ $event->name }}</div>
                            <div class="td-sub">{{ $event->organizer ?? '—' }}</div>
                        </td>
                        <td>
                            {{ $event->date ? $event->date->format('d M Y') : '—' }}
                            @if($event->time)
                                <div class="td-sub">{{ substr($event->time, 0, 5) }}</div>
                            @endif
                        </td>
                        <td>{{ $event->venue }}</td>
                        <td>{{ number_format((float) $event->price, 2) }}</td>
                        <td>{{ $sold }} / {{ $event->total_seats }}</td>
                        <td>
                            <span class="badge badge-{{ $rowStatus }}">
                                {{ $rowStatus === 'published' ? 'Upcoming' : 'Ended' }}
                            </span>
                        </td>
                        <td>
                            <div class="action-group">
                                <a href="{{ route('admin.events.edit', $event) }}" class="act-btn">Edit</a>
                                <a href="{{ route('admin.events.show', $event) }}" class="act-btn act-view">View</a>
                                <a href="{{ route('admin.bookings.index') }}?event_id={{ $event->id }}"
                                    class="act-btn">Bookings</a>
                                <button type="button" class="act-btn act-del"
                                    onclick="openEventDeleteModal({{ $event->id }}, @json($event->name))">Delete</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="td-empty">No events in this category yet.</td>
                    </tr>
                @endforelse

                <tr id="noCategoryEventsRow" style="display:none;">
                    <td colspan="7" class="td-empty">No matching events found.</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="pagination-row" style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;">
        <div class="page-info" id="pageInfo">Showing {{ $events->count() }} event(s)</div>

        <div id="paginationControls" class="pagination-controls" style="display:flex;gap:6px;align-items:center;flex-wrap:wrap;">
        </div>
    </div>

    <div class="modal-backdrop" id="delete-modal" style="display:none;">
        <div class="modal modal-sm">
            <div class="modal-icon-danger">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2">
                    <polyline points="3 6 5 6 21 6" />
                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                    <path d="M10 11v6M14 11v6M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
                </svg>
            </div>
            <div class="modal-title text-center">Delete event?</div>
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
    <script src="{{ asset('js/admin-events.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var flashes = document.querySelectorAll('.flash');

            flashes.forEach(function (flash) {
                setTimeout(function () {
                    flash.style.display = 'none';
                }, 1500);
            });
        });
</script>
@endsection