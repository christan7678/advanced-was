@extends('admin.layout')

@section('title', 'Category Bookings')
@section('page-title', 'Category Bookings')

@section('topbar-actions')
<a href="{{ route('admin.events.category', ['category' => request('category')]) }}"
   class="btn-outline-sm">
   ← Back to events
</a>
@endsection

@section('content')
@php
    $category = request('category', 'concert');

    $categories = [
        'concert' => [
            'name' => 'Concert',
            'title' => 'Music Festival 2026',
            'organiser' => 'Coldplay',
            'venue' => 'Kuala Lumpur',
            'date' => '20 May 2026',
            'time' => '8:00 PM',
            'tickets' => '320 / 500',
            'total_bookings' => '320',
            'completed' => '200',
            'pending' => '80',
            'cancelled' => '40',
            'revenue' => 'RM 48,600',
            'rows' => [
                ['id' => '#B001', 'user' => 'Alice Tan',  'email' => 'alice@gmail.com',  'tickets' => '2', 'paid' => 'RM376', 'booked_on' => '12 Apr 2026', 'status' => 'pending'],
                ['id' => '#B002', 'user' => 'Bryan Lee',  'email' => 'bryan@gmail.com',  'tickets' => '1', 'paid' => 'RM188', 'booked_on' => '10 Apr 2026', 'status' => 'cancelled'],
                ['id' => '#B003', 'user' => 'Chloe Ng',   'email' => 'chloe@gmail.com',  'tickets' => '3', 'paid' => 'RM564', 'booked_on' => '09 Apr 2026', 'status' => 'pending'],
                ['id' => '#B004', 'user' => 'Daniel Wong','email' => 'daniel@gmail.com', 'tickets' => '4', 'paid' => 'RM752', 'booked_on' => '08 Apr 2026', 'status' => 'completed'],
            ],
        ],

        'exhibition' => [
            'name' => 'Exhibition',
            'title' => 'Modern Art Expo 2026',
            'organiser' => 'Art Vision Group',
            'venue' => 'Penang Convention Centre',
            'date' => '15 Jun 2026',
            'time' => '10:00 AM',
            'tickets' => '180 / 300',
            'total_bookings' => '180',
            'completed' => '200',
            'pending' => '80',
            'cancelled' => '40',
            'revenue' => 'RM 21,600',
            'rows' => [
                ['id' => '#E001', 'user' => 'Eva Lim',     'email' => 'eva@gmail.com',     'tickets' => '2', 'paid' => 'RM240', 'booked_on' => '05 Apr 2026', 'status' => 'pending'],
                ['id' => '#E002', 'user' => 'Farah Noor',  'email' => 'farah@gmail.com',   'tickets' => '1', 'paid' => 'RM120', 'booked_on' => '03 Apr 2026', 'status' => 'completed'],
                ['id' => '#E003', 'user' => 'Gary Tan',    'email' => 'gary@gmail.com',    'tickets' => '3', 'paid' => 'RM360', 'booked_on' => '02 Apr 2026', 'status' => 'cancelled'],
                ['id' => '#E004', 'user' => 'Hui Wen',     'email' => 'huiwen@gmail.com',  'tickets' => '2', 'paid' => 'RM240', 'booked_on' => '01 Apr 2026', 'status' => 'pending'],
            ],
        ],

        'workshop' => [
            'name' => 'Workshop',
            'title' => 'Startup Growth Workshop',
            'organiser' => 'TechOrg',
            'venue' => 'Penang Tech Space',
            'date' => '12 Jun 2026',
            'time' => '2:00 PM',
            'tickets' => '120 / 300',
            'total_bookings' => '120',
            'completed' => '200',
            'pending' => '80',
            'cancelled' => '40',
            'revenue' => 'RM 12,800',
            'rows' => [
                ['id' => '#W001', 'user' => 'Megan Tan',   'email' => 'megan@gmail.com',   'tickets' => '1', 'paid' => 'RM80',  'booked_on' => '06 Apr 2026', 'status' => 'pending'],
                ['id' => '#W002', 'user' => 'Nathan Goh',  'email' => 'nathan@gmail.com',  'tickets' => '2', 'paid' => 'RM160', 'booked_on' => '05 Apr 2026', 'status' => 'completed'],
                ['id' => '#W003', 'user' => 'Olivia Ng',   'email' => 'olivia@gmail.com',  'tickets' => '1', 'paid' => 'RM80',  'booked_on' => '04 Apr 2026', 'status' => 'cancelled'],
            ],
        ],

        'seminar' => [
            'name' => 'Seminar',
            'title' => 'Future Tech Seminar',
            'organiser' => 'UTAR Tech Council',
            'venue' => 'Sungai Long Campus',
            'date' => '18 Jun 2026',
            'time' => '1:30 PM',
            'tickets' => '240 / 400',
            'total_bookings' => '240',
            'completed' => '200',
            'pending' => '80',
            'cancelled' => '40',
            'revenue' => 'RM 9,600',
            'rows' => [
                ['id' => '#SE001', 'user' => 'Paul Chan',  'email' => 'paul@gmail.com',    'tickets' => '2', 'paid' => 'RM80',  'booked_on' => '07 Apr 2026', 'status' => 'pending'],
                ['id' => '#SE002', 'user' => 'Queenie Lim','email' => 'queenie@gmail.com', 'tickets' => '1', 'paid' => 'RM40',  'booked_on' => '06 Apr 2026', 'status' => 'completed'],
                ['id' => '#SE003', 'user' => 'Ryan Ong',   'email' => 'ryan@gmail.com',    'tickets' => '3', 'paid' => 'RM120', 'booked_on' => '05 Apr 2026', 'status' => 'pending'],
            ],
        ],

        'community' => [
            'name' => 'Community',
            'title' => 'Neighbourhood Gathering',
            'organiser' => 'Community Club',
            'venue' => 'KL Community Hall',
            'date' => '30 Jun 2026',
            'time' => '6:00 PM',
            'tickets' => '90 / 150',
            'total_bookings' => '90',
            'completed' => '200',
            'pending' => '80',
            'cancelled' => '40',
            'revenue' => 'RM 4,500',
            'rows' => [
                ['id' => '#C001', 'user' => 'Sarah Lim',   'email' => 'sarah@gmail.com',   'tickets' => '2', 'paid' => 'RM100', 'booked_on' => '03 Apr 2026', 'status' => 'pending'],
                ['id' => '#C002', 'user' => 'Thomas Lee',  'email' => 'thomas@gmail.com',  'tickets' => '1', 'paid' => 'RM50',  'booked_on' => '02 Apr 2026', 'status' => 'cancelled'],
                ['id' => '#C003', 'user' => 'Uma Wong',    'email' => 'uma@gmail.com',     'tickets' => '3', 'paid' => 'RM150', 'booked_on' => '01 Apr 2026', 'status' => 'completed'],
            ],
        ],
    ];

    $current = $categories[$category] ?? $categories['concert'];
@endphp

<div class="detail-card" style="margin-bottom:16px;">
    <div class="detail-card-title">{{ $current['title'] }}</div>

    <div class="detail-two-col" style="grid-template-columns: 1fr 1fr; gap: 12px;">
        <div>
            <div class="detail-row">
                <span class="detail-label">Category</span>
                <span class="detail-val">{{ $current['name'] }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Artist / Organiser</span>
                <span class="detail-val">{{ $current['organiser'] }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Venue</span>
                <span class="detail-val">{{ $current['venue'] }}</span>
            </div>
        </div>

        <div>
            <div class="detail-row">
                <span class="detail-label">Date</span>
                <span class="detail-val">{{ $current['date'] }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Time</span>
                <span class="detail-val">{{ $current['time'] }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Tickets Sold</span>
                <span class="detail-val">{{ $current['tickets'] }}</span>
            </div>
        </div>
    </div>
</div>

<div class="stat-grid" style="margin-bottom:18px;">
    <div class="stat-card">
        <div class="stat-label">Total Bookings</div>
        <div class="stat-value">{{ $current['total_bookings'] }}</div>
        <div class="stat-note nothings">All booking records</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Completed</div>
        <div class="stat-value">{{ $current['completed'] }}</div>
        <div class="stat-note positive">Successful payments</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Pending</div>
        <div class="stat-value">{{ $current['pending'] }}</div>
        <div class="stat-note muted">Awaiting payment</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Cancelled</div>
        <div class="stat-value">{{ $current['cancelled'] }}</div>
        <div class="stat-note negative" >Cancelled bookings</div>
    </div>
</div>

<form class="toolbar">
    <input class="toolbar-search" type="text" placeholder="Search user name or email...">

    <select class="toolbar-select">
        <option>All status</option>
        <option>Completed</option>
        <option>Pending</option>
        <option>Cancelled</option>
    </select>

    <select class="toolbar-select">
        <option>All dates</option>
        <option>This week</option>
        <option>This month</option>
    </select>

    <select class="toolbar-select">
        <option>Sort by newest</option>
        <option>Sort by oldest</option>
        <option>Highest tickets</option>
        <option>Lowest tickets</option>
    </select>

    <button type="button" class="btn-outline-sm">Filter</button>
</form>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>User</th>
                <th>Email</th>
                <th>Tickets</th>
                <th>Total Paid</th>
                <th>Booked On</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            @foreach($current['rows'] as $row)
                <tr>
                    <td>{{ $row['id'] }}</td>
                    <td><div class="td-title">{{ $row['user'] }}</div></td>
                    <td class="td-sub">{{ $row['email'] }}</td>
                    <td>{{ $row['tickets'] }}</td>
                    <td>{{ $row['paid'] }}</td>
                    <td>{{ $row['booked_on'] }}</td>
                    <td>
                        <span class="badge badge-{{ $row['status'] }}">
                            {{ ucfirst($row['status']) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.bookings.show', ['category' => $category]) }}" class="act-btn act-view">View</a>

                        @if($row['status'] === 'cancelled')
                            <button type="button" class="act-btn act-del" onclick="openDeleteModal()">Delete</button>
                        @elseif($row['status'] !== 'completed')
                            <button type="button" class="act-btn act-del" onclick="openCancelModal()">Cancel</button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="pagination-row">
    <div class="page-info">Showing 1–{{ count($current['rows']) }} of {{ $current['total_bookings'] }} bookings</div>

    <div class="page-btns">
        <button class="page-btn" disabled>←</button>
        <button class="page-btn active">1</button>
        <button class="page-btn">2</button>
        <button class="page-btn">→</button>
    </div>
</div>

<div class="modal-backdrop" id="cancel-modal" style="display:none;">
    <div class="modal modal-sm">
        <div class="modal-icon-warning">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
        </div>
        <div class="modal-title text-center">Cancel this booking?</div>
        <div class="modal-sub text-center">This booking will be marked as cancelled. UI only.</div>
        <div class="modal-actions centered">
            <button type="button" class="btn-cancel" onclick="closeModal('cancel-modal')">Go back</button>
            <button type="button" class="btn-warning">Yes, cancel it</button>
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
        <div class="modal-title text-center">Delete booking?</div>
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
    function openCancelModal() {
        document.getElementById('cancel-modal').style.display = 'flex';
    }

    function openDeleteModal() {
        document.getElementById('delete-modal').style.display = 'flex';
    }

    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }
</script>
@endsection