@php
    $categoriesLink = $canBrowseEvents ? route('events.index', ['period' => 'upcoming']) : route('login');
@endphp

<section class="home-section">
    <div class="section-header">
        <h2 class="section-title">Browse by Category</h2>
        <a href="{{ $categoriesLink }}" class="section-link">{{ $canBrowseEvents ? 'See all ->' : 'Sign in ->' }}</a>
    </div>

    @if($categories->isNotEmpty())
        <div class="categories-grid">
            @foreach($categories as $category)
                @php
                    $categoryLink = $canBrowseEvents
                        ? route('events.index', ['period' => 'upcoming', 'category' => $category['name']])
                        : route('login');
                @endphp

                <a href="{{ $categoryLink }}" class="category-card">
                    <div class="category-card-head">
                        <span class="category-swatch" style="background: {{ $category['color'] }};"></span>
                        <div class="category-name">{{ $category['name'] }}</div>
                    </div>

                    <div class="category-meta">
                        <div>{{ $category['upcoming_events_count'] }} upcoming {{ \Illuminate\Support\Str::plural('event', (int) $category['upcoming_events_count']) }}</div>
                        <div>{{ number_format((int) $category['available_seats']) }} seats available</div>
                        <div>{{ $category['next_event_name'] ?: 'New listings coming soon' }}</div>
                        @if($category['next_event_date'])
                            <div>Next date: {{ $category['next_event_date'] }}</div>
                        @endif
                    </div>

                    <div class="category-foot">
                        <span>{{ $category['min_price'] !== null ? 'From RM' . number_format((float) $category['min_price'], 2) : 'Price TBA' }}</span>
                        <span>{{ $canBrowseEvents ? 'Open category' : 'Sign in to browse' }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="empty-panel">
            <div class="empty-panel-title">No active categories yet</div>
            <p class="empty-panel-text">As soon as upcoming events are added to categories, this section will fill in automatically.</p>
        </div>
    @endif
</section>
