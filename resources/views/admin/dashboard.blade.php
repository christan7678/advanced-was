@extends('admin.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/admin-dashboard.css') }}">
@endpush

@section('topbar-actions')
    <span class="topbar-date">Mon, 14 Apr 2026</span>
@endsection

@section('content')

{{-- Summary cards --}}
<div class="stat-grid dashboard-stat-grid">
    <div class="stat-card">
        <div class="stat-label">Total Categories</div>
        <div class="stat-value">6</div>
        <div class="stat-note muted">All event categories</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Total Events</div>
        <div class="stat-value">24</div>
        <div class="stat-note positive">+3 this month</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Total Purchases</div>
        <div class="stat-value">1,248</div>
        <div class="stat-note positive">+58 this week</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Total Users</div>
        <div class="stat-value">860</div>
        <div class="stat-note positive">+589 this week</div>
    </div>
</div>

{{-- Row 2 --}}
<div class="dashboard-two-col">
    {{-- Purchases by Category --}}
    <div class="dash-card">
        <div class="dash-card-title">Purchases by Category</div>

        <div class="bar-row">
            <div class="bar-label">Concert</div>
            <div class="bar-track"><div class="bar-fill" style="width: 88%;"></div></div>
            <div class="bar-val">420</div>
        </div>

        <div class="bar-row">
            <div class="bar-label">Sports</div>
            <div class="bar-track"><div class="bar-fill" style="width: 74%;"></div></div>
            <div class="bar-val">350</div>
        </div>

        <div class="bar-row">
            <div class="bar-label">Exhibition</div>
            <div class="bar-track"><div class="bar-fill" style="width: 42%;"></div></div>
            <div class="bar-val">180</div>
        </div>

        <div class="bar-row">
            <div class="bar-label">Workshop</div>
            <div class="bar-track"><div class="bar-fill" style="width: 30%;"></div></div>
            <div class="bar-val">120</div>
        </div>

        <div class="bar-row">
            <div class="bar-label">Seminar</div>
            <div class="bar-track"><div class="bar-fill" style="width: 26%;"></div></div>
            <div class="bar-val">110</div>
        </div>

        <div class="bar-row">
            <div class="bar-label">Community</div>
            <div class="bar-track"><div class="bar-fill" style="width: 16%;"></div></div>
            <div class="bar-val">68</div>
        </div>
    </div>

    {{-- Payment Status --}}
    <div class="dash-card">
        <div class="dash-card-title">Payment Status</div>

        <div class="status-chart-wrap">
            <div class="status-donut">
                <div class="status-donut-center">
                    <div class="status-donut-total">1,248</div>
                    <div class="status-donut-sub">Purchases</div>
                </div>
            </div>

            <div class="status-legend">
                <div class="legend-row">
                    <span class="legend-dot legend-completed"></span>
                    <span class="legend-label">Completed</span>
                    <span class="legend-value">70%</span>
                </div>
                <div class="legend-row">
                    <span class="legend-dot legend-pending"></span>
                    <span class="legend-label">Pending</span>
                    <span class="legend-value">20%</span>
                </div>
                <div class="legend-row">
                    <span class="legend-dot legend-cancelled"></span>
                    <span class="legend-label">Cancelled</span>
                    <span class="legend-value">10%</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Row 3 --}}
<div class="dashboard-two-col">
    {{-- Booking Trend --}}
    <div class="dash-card">
        <div class="dash-card-title">Booking Trend</div>

        <div class="trend-chart">
            <div class="trend-bars">
                <div class="trend-col">
                    <div class="trend-bar" style="height: 34%;"></div>
                    <span class="trend-label">Mon</span>
                </div>
                <div class="trend-col">
                    <div class="trend-bar" style="height: 52%;"></div>
                    <span class="trend-label">Tue</span>
                </div>
                <div class="trend-col">
                    <div class="trend-bar" style="height: 45%;"></div>
                    <span class="trend-label">Wed</span>
                </div>
                <div class="trend-col">
                    <div class="trend-bar" style="height: 68%;"></div>
                    <span class="trend-label">Thu</span>
                </div>
                <div class="trend-col">
                    <div class="trend-bar" style="height: 76%;"></div>
                    <span class="trend-label">Fri</span>
                </div>
                <div class="trend-col">
                    <div class="trend-bar" style="height: 92%;"></div>
                    <span class="trend-label">Sat</span>
                </div>
                <div class="trend-col">
                    <div class="trend-bar" style="height: 61%;"></div>
                    <span class="trend-label">Sun</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="dash-card">
        <div class="dash-card-title">Recent Activity</div>

        <div class="recent-row">
            <div class="rec-dot status-completed"></div>
            <div class="rec-info">
                <div class="rec-name">Alice Tan completed payment</div>
                <div class="rec-sub">Music Festival 2026 · 2 tickets</div>
            </div>
            <span class="badge badge-completed">Completed</span>
        </div>

        <div class="recent-row">
            <div class="rec-dot status-pending"></div>
            <div class="rec-info">
                <div class="rec-name">Jason Lim pending payment</div>
                <div class="rec-sub">Basketball Championship 2026 · 4 tickets</div>
            </div>
            <span class="badge badge-pending">Pending</span>
        </div>

        <div class="recent-row">
            <div class="rec-dot status-cancelled"></div>
            <div class="rec-info">
                <div class="rec-name">Bryan Lee cancelled booking</div>
                <div class="rec-sub">Modern Art Expo 2026 · 1 ticket</div>
            </div>
            <span class="badge badge-cancelled">Cancelled</span>
        </div>

        <div class="recent-row">
            <div class="rec-dot status-completed"></div>
            <div class="rec-info">
                <div class="rec-name">Admin created new category</div>
                <div class="rec-sub">Exhibition</div>
            </div>
            <span class="badge badge-completed">Created</span>
        </div>
    </div>
</div>

{{-- Row 4 --}}
<div class="dash-card">
    <div class="dash-card-title">Top Performing Events</div>

    <div class="table-wrap dashboard-table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Event</th>
                    <th>Category</th>
                    <th>Venue</th>
                    <th>Purchases</th>
                    <th>Revenue</th>
                    <th>Payment Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="td-title">Music Festival 2026</div>
                        <div class="td-sub">Coldplay</div>
                    </td>
                    <td><span class="badge badge-category">Concert</span></td>
                    <td>Kuala Lumpur</td>
                    <td>420</td>
                    <td>RM 48,600</td>
                    <td><span class="badge badge-completed">Most Completed</span></td>
                </tr>

                <tr>
                    <td>
                        <div class="td-title">Basketball Championship 2026</div>
                        <div class="td-sub">Sports Hub</div>
                    </td>
                    <td><span class="badge badge-category">Sports</span></td>
                    <td>Johor Bahru</td>
                    <td>350</td>
                    <td>RM 72,400</td>
                    <td><span class="badge badge-pending">High Pending</span></td>
                </tr>

                <tr>
                    <td>
                        <div class="td-title">Modern Art Expo 2026</div>
                        <div class="td-sub">Art Vision Group</div>
                    </td>
                    <td><span class="badge badge-category">Exhibition</span></td>
                    <td>Penang</td>
                    <td>180</td>
                    <td>RM 21,600</td>
                    <td><span class="badge badge-cancelled">Some Cancelled</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection