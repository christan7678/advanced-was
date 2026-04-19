@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/user/events.css') }}?v={{ time() }}">
@endsection

@section('content')

<!-- Events Top Bar -->
<div class="events-topbar">
    <h2 class="section-title">Upcoming Events</h2>

    <div class="event-filter-wrap" id="eventFilterWrap">
        <button class="filter-toggle-btn" id="filterToggleBtn" type="button">
            Filter
        </button>

        <div class="event-filter-dropdown" id="filterPanel">
            <div class="event-filter-panel">

                <!-- Search -->
                <div class="event-filter-group">
                    <div class="event-filter-label">Search</div>
                    <form method="GET" action="{{ route('events.index') }}" style="display: grid; gap: 8px;">
                        <input 
                            type="text" 
                            name="q" 
                            placeholder="Search by event name..." 
                            value="{{ $searchQuery }}"
                            style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px;"
                        >
                        <button type="submit" style="background: #2563eb; color: white; padding: 8px; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 13px;">
                            Search
                        </button>
                    </form>
                </div>

                <!-- Category Filter -->
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

                <!-- Artist Filter -->
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

<!-- Events List by Month -->
@forelse($groupedEvents as $month => $events)
    <div class="month-group">
        <div class="month-label"> {{ $month }}</div>

        <div class="event-grid">
            @foreach($events as $event)
                <div class="event-row">
                    <!-- Date Column -->
                    <div class="event-left">
                        <div class="event-date">
                            <div class="day">{{ $event->date->format('D') }}</div>
                            <div class="number">{{ $event->date->format('d') }}</div>
                            <div class="month">{{ $event->date->format('M') }}</div>
                        </div>
                    </div>

                    <!-- Image Column -->
                    <div class="event-image">
                        <img 
                            src="{{ $event->image ? asset('storage/' . $event->image) : 'https://images.unsplash.com/photo-1511578314322-379afb476865?w=300&h=300&fit=crop' }}" 
                            alt="{{ $event->name }}"
                        >
                    </div>

                    <!-- Details Column -->
                    <div class="event-details">
                        <!-- Category Badge -->
                        <div class="event-header">
                            @if($event->category)
                                <span class="event-category">{{ $event->category->name }}</span>
                            @endif
                        </div>

                        <!-- Event Title -->
                        <div class="event-title">{{ $event->name }}</div>

                        <!-- Artist -->
                        @if($event->artist)
                            <div class="event-artist">{{ $event->artist }}</div>
                        @endif

                        <!-- Info (Time & Venue) -->
                        <div class="event-info">
                            {{ $event->time }} ·  {{ $event->venue }}
                        </div>

                        <!-- Availability -->
                        <div style="font-size: 12px; color: #6b7280; margin-bottom: 8px;">
                            @if($event->available_seats > 0)
                                ✅ {{ $event->available_seats }} seats available
                            @else
                                ❌ Sold Out
                            @endif
                        </div>

                        <!-- Footer with Price and Button -->
                        <div class="event-footer">
                            <div class="event-price" style="color: #059669; font-weight: 700;">
                                ${{ number_format($event->price, 2) }}
                            </div>
                        </div>

                        <a href="{{ route('events.show', $event->id) }}" class="btn-buy">
                            View & Book
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@empty
    <div class="event-empty-state">
        <div style="font-size: 18px; font-weight: 600; color: #1f2937; margin-bottom: 5px;">No Events Found</div>
        <div style="color: #6b7280;">Try adjusting your filters or come back later for more exciting events!</div>
        <a href="{{ route('events.index') }}" style="display: inline-block; margin-top: 15px; background: #2563eb; color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: 600;">
            Clear Filters
        </a>
    </div>
@endforelse

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