@extends('admin.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/admin-dashboard.css') }}">
@endpush

@section('topbar-actions')
    <span class="topbar-date" id="admin-dashboard-live-datetime" title="Local date & time"></span>
@endsection

@section('content')

    {{-- Summary cards --}}
    <div class="stat-grid dashboard-stat-grid">
        <div class="stat-card">
            <div class="stat-label">Total Categories</div>
            <div class="stat-value">{{ $totalCategories }}</div>
            <div class="stat-note muted">All event categories</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Total Events</div>
            <div class="stat-value">{{ $totalEvents }}</div>
            <div class="stat-note positive">+{{ $monthEvents }} this month</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Total Purchases</div>
            <div class="stat-value">{{ $totalPurchases }}</div>
            <div class="stat-note positive">+{{ $weekPurchases }} this week</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Total Users</div>
            <div class="stat-value">{{ $totalUsers }}</div>
            <div class="stat-note positive">+{{ $weekUsers }} this week</div>
        </div>
    </div>

    {{-- Row 2 --}}
    <div class="dashboard-two-col">
        {{-- Purchases by Category --}}
        <div class="dash-card">
            <div class="dash-card-title">Purchases by Category</div>

            @if($purchasesByCategory->isEmpty() || $purchasesByCategory->sum('total_purchases') == 0)
                <p class="text-muted">No purchases yet.</p>
            @else
                @foreach($purchasesByCategory as $category)
                    <div class="bar-row">
                        <a href="{{ route('admin.events.category', ['category' => $category->id]) }}"
                            class="bar-label text-decoration-none">
                            {{ $category->name }}
                        </a>
                        <div class="bar-track">
                            <div class="bar-fill" style="width: {{ round(($category->total_purchases / $maxSeats) * 100) }}%;">
                            </div>
                        </div>
                        <div class="bar-val">{{ $category->total_purchases }}</div>
                    </div>
                @endforeach
            @endif
        </div>

        {{-- Payment Status --}}
        <div class="dash-card">
            <div class="dash-card-title">Payment Status For All Event</div>

            <div class="status-chart-wrap">
                <div class="status-donut" style="background: conic-gradient(
                        #16a34a 0% {{ $completedEnd }}%,
                        #a1c626 {{ $completedEnd }}% {{ $pendingEnd }}%,
                        #d97706 {{ $pendingEnd }}% {{ $refundedEnd }}%,
                        #dc2626 {{ $refundedEnd }}% 100%
                    );">
                    <div class="status-donut-center">
                        <div class="status-donut-total">{{ $totalPurchases }}</div>
                        <div class="status-donut-sub">Purchases</div>
                    </div>
                </div>

                <div class="status-legend">
                    <div class="legend-row">
                        <span class="legend-dot legend-completed"></span>
                        <span class="legend-label">Completed</span>
                        <span class="legend-value">{{ $completed }}%</span>
                    </div>
                    <div class="legend-row">
                        <span class="legend-dot legend-pending"></span>
                        <span class="legend-label">Pending</span>
                        <span class="legend-value">{{ $pending }}%
                    </div>
                    <div class="legend-row">
                        <span class="legend-dot legend-refunded"></span>
                        <span class="legend-label">refunded</span>
                        <span class="legend-value">{{ $refunded }}%</span>
                    </div>
                    <div class="legend-row">
                        <span class="legend-dot legend-cancelled"></span>
                        <span class="legend-label">Cancelled</span>
                        <span class="legend-value">{{ $cancelled }}%</span>
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
                    @foreach($bookingTrend as $day)
                        <div class="trend-col">
                            <span class="trend-val">{{ $day['total'] }}</span>
                            <div class="trend-bar" style="height: {{ round(($day['total'] / $maxTrend) * 100) }}%;"></div>
                            <span class="trend-label">{{ $day['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="dash-card">
            <div class="dash-card-title">Recent User Activity</div>
            @foreach($recentActivities as $activity)
                <div class="recent-row">
                    <div class="rec-dot status-{{ $activity->payment_status }}"></div>
                    <div class="rec-info">
                        <div class="rec-name">
                            {{ $activity->user->name }}

                            @if($activity->payment_status === 'completed')
                                completed payment
                            @elseif($activity->payment_status === 'pending')
                                pending payment
                            @elseif($activity->payment_status === 'refunded')
                                refunded payment
                            @elseif($activity->payment_status === 'cancelled')
                                cancelled payment
                            @endif
                        </div>
                        <div class="rec-sub">
                            {{ $activity->event->name }} · {{ $activity->number_of_seats }}
                            {{ $activity->number_of_seats == 1 ? 'ticket' : 'tickets' }}
                        </div>
                    </div>
                    <span class="badge badge-{{ $activity->payment_status }}"> {{ ucfirst($activity->payment_status) }}</span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Row 4 --}}
    <div class="dash-card">
        <div class="dash-card-title">Top 3 Performing Events</div>

        <div class="table-wrap dashboard-table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Category</th>
                        <th>Venue</th>
                        <th>Purchases</th>
                        <th>Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topEvents as $event)
                        @php
                            $statuses = [
                                'completed' => $event->completed_count,
                                'pending' => $event->pending_count,
                                'cancelled' => $event->cancelled_count,
                            ];
                            $dominant = array_keys($statuses, max($statuses))[0];
                        @endphp
                        <tr>
                            <td>
                                <div class="td-title">{{ $event->name }}</div>
                                <div class="td-sub">{{ $event->description }}</div>
                            </td>
                            <td><span class="badge badge-category">{{ $event->category->name }}</span></td>
                            <td>{{ $event->venue }}</td>
                            <td>{{ $event->total_purchases }}</td>
                            <td>RM {{ number_format($event->total_revenue * $event->price, 2) }}</td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No events found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection

@section('scripts')
<script src="{{ asset('js/admin-dashboard.js') }}"></script>
@endsection