@extends('admin.layout')

@section('title', 'Category Events')
@section('page-title', 'Category Events')

@section('topbar-actions')
    <a href="{{ route('admin.events.index') }}" class="btn-outline-sm">← Back to categories</a>
    <button type="button" class="btn-primary" onclick="openModal('event-modal')">+ Create event</button>
@endsection

@section('content')
@php
    $category = request('category', 'concert');

    $categoryData = [
        'concert' => [
            'name' => 'Concert',
            'status' => 'Active',
            'description' => 'Live performance, music show, festival, artist showcase.',
            'events' => [
                [
                    'title' => 'Music Festival 2026',
                    'organiser' => 'Coldplay',
                    'date' => '20 May 2026',
                    'venue' => 'Kuala Lumpur',
                    'price' => '188 – 688',
                    'tickets' => '320 / 500',
                    'status' => 'published',
                    'event_key' => 'music-festival-2026',
                ],
                [
                    'title' => 'Summer Night Concert',
                    'organiser' => 'Live Nation MY',
                    'date' => '15 Jun 2026',
                    'venue' => 'Shah Alam',
                    'price' => '120 – 450',
                    'tickets' => '280 / 400',
                    'status' => 'published',
                    'event_key' => 'summer-night-concert',
                ],
                [
                    'title' => 'Acoustic Live Session',
                    'organiser' => 'SoundLab',
                    'date' => '01 Jul 2026',
                    'venue' => 'Penang',
                    'price' => '80 – 150',
                    'tickets' => '90 / 150',
                    'status' => 'draft',
                    'event_key' => 'acoustic-live-session',
                ],
            ],
        ],

        'sports' => [
            'name' => 'Sports',
            'status' => 'Active',
            'description' => 'Tournament, championship, match, sports challenge.',
            'events' => [
                [
                    'title' => 'Basketball Championship 2026',
                    'organiser' => 'Sports Hub',
                    'date' => '05 Jul 2026',
                    'venue' => 'Johor Bahru',
                    'price' => '50 – 120',
                    'tickets' => '850 / 1000',
                    'status' => 'published',
                    'event_key' => 'basketball-championship-2026',
                ],
                [
                    'title' => 'Badminton Open 2026',
                    'organiser' => 'MY Arena',
                    'date' => '12 Jul 2026',
                    'venue' => 'Kuala Lumpur',
                    'price' => '40 – 100',
                    'tickets' => '430 / 700',
                    'status' => 'published',
                    'event_key' => 'badminton-open-2026',
                ],
                [
                    'title' => 'Football Finals 2026',
                    'organiser' => 'National League',
                    'date' => '22 Jul 2026',
                    'venue' => 'Bukit Jalil',
                    'price' => '60 – 200',
                    'tickets' => '920 / 1200',
                    'status' => 'draft',
                    'event_key' => 'football-finals-2026',
                ],
            ],
        ],

        'workshop' => [
            'name' => 'Workshop',
            'status' => 'Draft',
            'description' => 'Learning, training, practical class, hands-on session.',
            'events' => [
                [
                    'title' => 'Startup Growth Workshop',
                    'organiser' => 'TechOrg',
                    'date' => '12 Jun 2026',
                    'venue' => 'Penang',
                    'price' => '80 – 150',
                    'tickets' => '120 / 300',
                    'status' => 'draft',
                    'event_key' => 'startup-growth-workshop',
                ],
                [
                    'title' => 'UX Design Bootcamp',
                    'organiser' => 'Design Crew',
                    'date' => '25 Jun 2026',
                    'venue' => 'Cyberjaya',
                    'price' => '120 – 220',
                    'tickets' => '75 / 100',
                    'status' => 'published',
                    'event_key' => 'ux-design-bootcamp',
                ],
            ],
        ],

        'seminar' => [
            'name' => 'Seminar',
            'status' => 'Active',
            'description' => 'Conference, talk, academic seminar, expert sharing.',
            'events' => [
                [
                    'title' => 'Future Tech Seminar',
                    'organiser' => 'UTAR Tech Council',
                    'date' => '18 Jun 2026',
                    'venue' => 'Sungai Long',
                    'price' => '40 – 80',
                    'tickets' => '240 / 400',
                    'status' => 'published',
                    'event_key' => 'future-tech-seminar',
                ],
                [
                    'title' => 'AI Industry Talk',
                    'organiser' => 'Open Knowledge Hub',
                    'date' => '29 Jun 2026',
                    'venue' => 'Kuala Lumpur',
                    'price' => '30 – 50',
                    'tickets' => '160 / 250',
                    'status' => 'published',
                    'event_key' => 'ai-industry-talk',
                ],
            ],
        ],

        'exhibition' => [
            'name' => 'Exhibition',
            'status' => 'Active',
            'description' => 'Art gallery, expo, product showcase, display event.',
            'events' => [
                [
                    'title' => 'Modern Art Expo 2026',
                    'organiser' => 'Art Vision Group',
                    'date' => '15 Jun 2026',
                    'venue' => 'Penang Convention Centre',
                    'price' => '120 – 240',
                    'tickets' => '180 / 300',
                    'status' => 'published',
                    'event_key' => 'modern-art-expo-2026',
                ],
                [
                    'title' => 'Innovation Showcase',
                    'organiser' => 'Future Makers',
                    'date' => '26 Jun 2026',
                    'venue' => 'KLCC',
                    'price' => '50 – 90',
                    'tickets' => '210 / 280',
                    'status' => 'draft',
                    'event_key' => 'innovation-showcase',
                ],
            ],
        ],

        'community' => [
            'name' => 'Community',
            'status' => 'Draft',
            'description' => 'Volunteer, gathering, neighbourhood and public engagement.',
            'events' => [
                [
                    'title' => 'Neighbourhood Gathering',
                    'organiser' => 'Community Club',
                    'date' => '30 Jun 2026',
                    'venue' => 'KL Community Hall',
                    'price' => '50 – 80',
                    'tickets' => '90 / 150',
                    'status' => 'published',
                    'event_key' => 'neighbourhood-gathering',
                ],
                [
                    'title' => 'Volunteer Day 2026',
                    'organiser' => 'Youth Connect',
                    'date' => '08 Jul 2026',
                    'venue' => 'Subang Jaya',
                    'price' => 'Free',
                    'tickets' => '65 / 120',
                    'status' => 'draft',
                    'event_key' => 'volunteer-day-2026',
                ],
            ],
        ],
    ];

    $current = $categoryData[$category] ?? $categoryData['concert'];
@endphp

<div class="detail-card" style="margin-bottom:18px;">
    <div class="detail-card-title">{{ $current['name'] }}</div>

    <div class="detail-two-col" style="grid-template-columns: 1fr 1fr; gap: 12px;">
        <div>
            <div class="detail-row">
                <span class="detail-label">Category Name</span>
                <span class="detail-val">{{ $current['name'] }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Status</span>
                <span class="detail-val">{{ $current['status'] }}</span>
            </div>
        </div>

        <div>
            <div class="detail-row">
                <span class="detail-label">Total Events</span>
                <span class="detail-val">{{ count($current['events']) }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Description</span>
                <span class="detail-val">{{ $current['description'] }}</span>
            </div>
        </div>
    </div>
</div>

<form class="toolbar" onsubmit="return false;">
    <input id="eventSearch" class="toolbar-search" type="text" placeholder="Search event name...">

    <select id="eventStatusFilter" class="toolbar-select">
        <option value="">All status</option>
        <option value="published">Published</option>
        <option value="draft">Draft</option>
    </select>

    <button type="button" class="btn-outline-sm" onclick="filterCategoryEvents()">Filter</button>
    <button type="button" class="btn-outline-sm" onclick="resetCategoryEvents()">Reset</button>
</form>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Event</th>
                <th>Date</th>
                <th>Venue</th>
                <th>Price (RM)</th>
                <th>Tickets Sold</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody id="categoryEventsTable">
            @foreach($current['events'] as $event)
                <tr data-name="{{ strtolower($event['title']) }}"
                    data-status="{{ strtolower($event['status']) }}">
                    <td>
                        <div class="td-title">{{ $event['title'] }}</div>
                        <div class="td-sub">{{ $event['organiser'] }}</div>
                    </td>
                    <td>{{ $event['date'] }}</td>
                    <td>{{ $event['venue'] }}</td>
                    <td>{{ $event['price'] }}</td>
                    <td>{{ $event['tickets'] }}</td>
                    <td>
                        <span class="badge badge-{{ $event['status'] }}">
                            {{ ucfirst($event['status']) }}
                        </span>
                    </td>
                    <td>
                        <div class="action-group">
                            <button type="button" class="act-btn" onclick="openModal('event-modal')">Edit</button>
                            <a href="{{ route('admin.bookings.index', ['category' => $category, 'event' => $event['event_key']]) }}"
                               class="act-btn act-view">
                                View Purchases
                            </a>
                            <button type="button" class="act-btn act-del" onclick="openDeleteModal()">Delete</button>
                        </div>
                    </td>
                </tr>
            @endforeach

            <tr id="noCategoryEventsRow" style="display:none;">
                <td colspan="7" class="td-empty">No matching events found.</td>
            </tr>
        </tbody>
    </table>
</div>

<div class="pagination-row">
    <div class="page-info">Showing 1–{{ count($current['events']) }} of {{ count($current['events']) }} events</div>
    <div class="page-btns">
        <button class="page-btn" disabled>←</button>
        <button class="page-btn active">1</button>
        <button class="page-btn" disabled>→</button>
    </div>
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
                    <input class="form-input" type="text" placeholder="Event title">
                </div>

                <div class="form-group form-full">
                    <label>Artist / organiser</label>
                    <input class="form-input" type="text" placeholder="Organiser name">
                </div>

                <div class="form-group">
                    <label>Category</label>
                    <select class="form-input">
                        <option>Concert</option>
                        <option>Sports</option>
                        <option>Workshop</option>
                        <option>Seminar</option>
                        <option>Exhibition</option>
                        <option>Community</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Venue / city</label>
                    <input class="form-input" type="text" placeholder="Venue">
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
                    <input class="form-input" type="number" placeholder="100">
                </div>

                <div class="form-group">
                    <label>Price to</label>
                    <input class="form-input" type="number" placeholder="500">
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

{{-- Delete Modal --}}
<div class="modal-backdrop" id="delete-modal" style="display:none;">
    <div class="modal modal-sm">
        <div class="modal-icon-danger">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2">
                <polyline points="3 6 5 6 21 6"/>
                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                <path d="M10 11v6M14 11v6M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
            </svg>
        </div>
        <div class="modal-title text-center">Delete event?</div>
        <div class="modal-sub text-center">This is UI only popup.</div>
        <div class="modal-actions centered">
            <button type="button" class="btn-cancel" onclick="closeModal('delete-modal')">Cancel</button>
            <button type="button" class="btn-danger">Yes, delete</button>
        </div>
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

    function openDeleteModal() {
        document.getElementById('delete-modal').style.display = 'flex';
    }

    function filterCategoryEvents() {
        const search = document.getElementById('eventSearch').value.toLowerCase().trim();
        const status = document.getElementById('eventStatusFilter').value.toLowerCase();
        const rows = document.querySelectorAll('#categoryEventsTable tr[data-name]');
        let visibleCount = 0;

        rows.forEach(row => {
            const name = row.dataset.name.toLowerCase();
            const rowStatus = row.dataset.status.toLowerCase();

            const matchSearch = search === '' || name.includes(search);
            const matchStatus = status === '' || rowStatus === status;

            if (matchSearch && matchStatus) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        document.getElementById('noCategoryEventsRow').style.display = visibleCount === 0 ? '' : 'none';
    }

    function resetCategoryEvents() {
        document.getElementById('eventSearch').value = '';
        document.getElementById('eventStatusFilter').value = '';
        filterCategoryEvents();
    }

    document.getElementById('eventSearch').addEventListener('input', filterCategoryEvents);
    document.getElementById('eventStatusFilter').addEventListener('change', filterCategoryEvents);
</script>
@endsection