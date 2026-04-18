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
        <div class="stat-note negative">Cancelled bookings</div>
    </div>
</div>
