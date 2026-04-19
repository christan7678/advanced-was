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
