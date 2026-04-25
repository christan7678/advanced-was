@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/user/events.css') }}?v={{ time() }}">
@endsection

@section('content')

<div class="events-page">

    <div class="events-topbar">
        <h2 class="section-title">
            {{ $period === 'past' ? 'Past Events' : 'Upcoming Events' }}
        </h2>

        <div class="events-control-row">
            <div class="event-filter-tabs">
                <a href="{{ route('events.index', array_merge(request()->except('page', 'period'), ['period' => 'upcoming'])) }}"
                   class="event-filter-tab {{ $period !== 'past' ? 'active' : '' }}">Upcoming</a>

                <a href="{{ route('events.index', array_merge(request()->except('page', 'period'), ['period' => 'past'])) }}"
                   class="event-filter-tab {{ $period === 'past' ? 'active' : '' }}">Past</a>
            </div>

            <div class="event-filter-wrap" id="eventFilterWrap">
                <button class="filter-toggle-btn" id="filterToggleBtn" type="button">
                    Filter
                </button>

                <div class="event-filter-dropdown" id="filterPanel">
                    <div class="event-filter-panel">
                        <div class="event-filter-group">
                            <div class="event-filter-label">Search</div>

                            <form method="GET" action="{{ route('events.index') }}" class="event-search-form">
                                <input type="hidden" name="period" value="{{ $period }}">

                                @if($category)
                                    <input type="hidden" name="category" value="{{ $category }}">
                                @endif

                                @if($artist)
                                    <input type="hidden" name="artist" value="{{ $artist }}">
                                @endif

                                <input type="text" name="q" placeholder="Search by event name..." value="{{ $searchQuery }}">
                                <button type="submit">Search</button>
                            </form>
                        </div>

                        @if($categories->count() > 0)
                            <div class="event-filter-group">
                                <div class="event-filter-label">Category</div>

                                <div class="event-filter-tabs">
                                    <a href="{{ route('events.index', request()->except('category')) }}"
                                       class="event-filter-tab {{ !$category ? 'active' : '' }}">All Categories</a>

                                    @foreach($categories as $cat)
                                        <a href="{{ route('events.index', array_merge(request()->except('page'), ['category' => $cat->name])) }}"
                                           class="event-filter-tab {{ $category === $cat->name ? 'active' : '' }}">
                                            {{ $cat->name }} ({{ $cat->events_count }})
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if($artists->count() > 0)
                            <div class="event-filter-group">
                                <div class="event-filter-label">Artist / Performer</div>

                                <div class="event-filter-tabs">
                                    <a href="{{ route('events.index', request()->except('artist')) }}"
                                       class="event-filter-tab {{ !$artist ? 'active' : '' }}">All Artists</a>

                                    @foreach($artists as $art)
                                        <a href="{{ route('events.index', array_merge(request()->except('page'), ['artist' => $art])) }}"
                                           class="event-filter-tab {{ $artist === $art ? 'active' : '' }}">{{ $art }}</a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @forelse($groupedEvents as $month => $events)
        <div class="month-group">
            <div class="month-label">{{ $month }}</div>

            <div class="event-grid">
                @foreach($events as $event)
                    <div class="event-card">
                        <div class="event-card-image">
                            <img
                                src="{{ $event->image ? asset('storage/' . $event->image) : 'https://images.unsplash.com/photo-1511578314322-379afb476865?w=300&h=300&fit=crop' }}"
                                alt="{{ $event->name }}"
                            >

                            <div class="event-date-badge">
                                <span>{{ $event->date->format('D') }}</span>
                                <strong>{{ $event->date->format('d') }}</strong>
                                <small>{{ $event->date->format('M') }}</small>
                            </div>
                        </div>

                        <div class="event-card-body">
                            @if($event->category)
                                <span class="event-category">{{ $event->category->name }}</span>
                            @endif

                            <h3 class="event-title">{{ $event->name }}</h3>

                            @if($event->artist)
                                <div class="event-artist">{{ $event->artist }}</div>
                            @endif

                            <div class="event-info">
                                {{ $event->time }} · {{ $event->venue }}
                            </div>

                            <div class="event-seat">
                                @if($event->date && $event->date->lt(today()))
                                    Event Ended
                                @elseif($event->status === 'sold_out' || $event->available_seats <= 0)
                                    Sold Out
                                @else
                                    {{ $event->available_seats }} seats available
                                @endif
                            </div>

                            <div class="event-card-footer">
                                <div class="event-price">
                                    RM{{ number_format($event->price, 2) }}
                                </div>

                                <a href="{{ route('events.show', $event->id) }}" class="btn-buy">
                                    {{ $period === 'past' ? 'View Details' : 'View & Book' }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="event-empty-state">
            <div class="empty-title">
                {{ $period === 'past' ? 'No Past Events Found' : 'No Upcoming Events Found' }}
            </div>
            <div class="empty-text">Try adjusting your filters or come back later for more exciting events!</div>
            <a href="{{ route('events.index', ['period' => $period]) }}" class="empty-btn">Clear Filters</a>
        </div>
    @endforelse

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterWrap = document.getElementById('eventFilterWrap');
        const filterBtn = document.getElementById('filterToggleBtn');
        const filterPanel = document.getElementById('filterPanel');

        if (filterBtn && filterPanel) {
            filterBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                filterPanel.classList.toggle('open');
            });

            document.addEventListener('click', function (e) {
                if (!filterWrap.contains(e.target)) {
                    filterPanel.classList.remove('open');
                }
            });

            @if($category || $artist || $searchQuery)
                filterPanel.classList.add('open');
            @endif
        }
    });
</script>

@endsection