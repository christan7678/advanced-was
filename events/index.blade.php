@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/events.css') }}?v={{ time() }}">
@endsection

@section('content')

@php
    $selectedMonth = request('month');
    $selectedArtist = request('artist');
    $selectedCategory = request('category');

    $eventGroups = [
        'April 2026' => [
            [
                'id' => 1,
                'day' => 'WED',
                'number' => '29',
                'month_short' => 'APR',
                'image' => 'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?w=300&h=300&fit=crop',
                'title' => 'One Ok Rock 2026 Concert in Malaysia',
                'artist' => 'One OK Rock',
                'category' => 'Concert',
                'info' => '8:00 PM · Kuala Lumpur',
                'price' => 'From RM288 – RM888',
            ],
            [
                'id' => 2,
                'day' => 'WED',
                'number' => '29',
                'month_short' => 'APR',
                'image' => 'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?w=300&h=300&fit=crop',
                'title' => 'Music Festival 2026',
                'artist' => 'Coldplay',
                'category' => 'Concert',
                'info' => '8:00 PM · Kuala Lumpur',
                'price' => 'From RM288 – RM888',
            ],
            [
                'id' => 3,
                'day' => 'WED',
                'number' => '29',
                'month_short' => 'APR',
                'image' => 'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?w=300&h=300&fit=crop',
                'title' => 'Apink 10th Concert in Malaysia',
                'artist' => 'Apink',
                'category' => 'Concert',
                'info' => '8:00 PM · Kuala Lumpur',
                'price' => 'From RM288 – RM888',
            ],
        ],
        'May 2026' => [
            [
                'id' => 4,
                'day' => 'SUN',
                'number' => '04',
                'month_short' => 'MAY',
                'image' => 'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?w=300&h=300&fit=crop',
                'title' => 'Tech Conference 2026',
                'artist' => 'Future Lab',
                'category' => 'Conference',
                'info' => '9:00 AM · Selangor',
                'price' => 'From RM90 – RM150',
            ],
            [
                'id' => 5,
                'day' => 'FRI',
                'number' => '02',
                'month_short' => 'MAY',
                'image' => 'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?w=300&h=300&fit=crop',
                'title' => 'Food Carnival 2026',
                'artist' => 'Street Food Fair',
                'category' => 'Food & Drink',
                'info' => '5:00 PM · Johor Bahru',
                'price' => 'From RM30 – RM80',
            ],
        ],
    ];

    $allArtists = [
        ['name' => 'One OK Rock', 'count' => 1, 'image' => 'https://images.unsplash.com/photo-1511367461989-f85a21fda167?w=200&h=200&fit=crop'],
        ['name' => 'Coldplay', 'count' => 1, 'image' => 'https://images.unsplash.com/photo-1511367461989-f85a21fda167?w=200&h=200&fit=crop'],
        ['name' => 'Apink', 'count' => 1, 'image' => 'https://images.unsplash.com/photo-1511367461989-f85a21fda167?w=200&h=200&fit=crop'],
        ['name' => 'Future Lab', 'count' => 1, 'image' => 'https://images.unsplash.com/photo-1511367461989-f85a21fda167?w=200&h=200&fit=crop'],
        ['name' => 'Street Food Fair', 'count' => 1, 'image' => 'https://images.unsplash.com/photo-1511367461989-f85a21fda167?w=200&h=200&fit=crop'],
    ];

    $filteredGroups = [];

    foreach ($eventGroups as $groupMonth => $events) {
        if ($selectedMonth && $selectedMonth !== $groupMonth) {
            continue;
        }

        $filteredEvents = array_filter($events, function ($event) use ($selectedArtist, $selectedCategory) {
            $artistMatch = !$selectedArtist || $event['artist'] === $selectedArtist;
            $categoryMatch = !$selectedCategory || strtolower($event['category']) === strtolower($selectedCategory);            return $artistMatch && $categoryMatch;
        });

        if (!empty($filteredEvents)) {
            $filteredGroups[$groupMonth] = $filteredEvents;
        }
    }
@endphp

<div class="events-topbar">
    <h2 class="section-title">Upcoming Events</h2>

    <div class="event-filter-wrap" id="eventFilterWrap">
        <button class="filter-toggle-btn" id="filterToggleBtn" type="button">
            Filter
        </button>

        <div class="event-filter-dropdown" id="filterPanel">
            <div class="event-filter-panel">

                <div class="event-filter-group">
                    <div class="event-filter-label">Month</div>
                    <div class="event-filter-tabs">
                        <a href="{{ route('events.index', request()->except('month')) }}"
                           class="event-filter-tab {{ !$selectedMonth ? 'active' : '' }}">All</a>

                        <a href="{{ route('events.index', array_merge(request()->except('page'), ['month' => 'April 2026'])) }}"
                           class="event-filter-tab {{ $selectedMonth === 'April 2026' ? 'active' : '' }}">April</a>

                        <a href="{{ route('events.index', array_merge(request()->except('page'), ['month' => 'May 2026'])) }}"
                           class="event-filter-tab {{ $selectedMonth === 'May 2026' ? 'active' : '' }}">May</a>
                    </div>
                </div>

                <div class="event-filter-group">
                    <div class="event-filter-label">Category</div>
                    <div class="event-filter-tabs">
                        <a href="{{ route('events.index', request()->except('category')) }}"
                           class="event-filter-tab {{ !$selectedCategory ? 'active' : '' }}">All</a>

                        <a href="{{ route('events.index', array_merge(request()->except('page'), ['category' => 'Concert'])) }}"
                           class="event-filter-tab {{ strtolower((string) $selectedCategory) === 'concert' ? 'active' : '' }}">Concert</a>

                        <a href="{{ route('events.index', array_merge(request()->except('page'), ['category' => 'Conference'])) }}"
                           class="event-filter-tab {{ strtolower((string) $selectedCategory) === 'conference' ? 'active' : '' }}">Conference</a>

                        <a href="{{ route('events.index', array_merge(request()->except('page'), ['category' => 'Food & Drink'])) }}"
                           class="event-filter-tab {{ strtolower((string) $selectedCategory) === 'food & drink' ? 'active' : '' }}">Food & Drink</a>
                    </div>
                </div>

                <div class="event-filter-group">
                    <div class="event-filter-label">Artist</div>
                    <div class="event-filter-tabs">
                        <a href="{{ route('events.index', request()->except('artist')) }}"
                           class="event-filter-tab {{ !$selectedArtist ? 'active' : '' }}">All</a>

                        <a href="{{ route('events.index', array_merge(request()->except('page'), ['artist' => 'One OK Rock'])) }}"
                           class="event-filter-tab {{ $selectedArtist === 'One OK Rock' ? 'active' : '' }}">One OK Rock</a>

                        <a href="{{ route('events.index', array_merge(request()->except('page'), ['artist' => 'Coldplay'])) }}"
                           class="event-filter-tab {{ $selectedArtist === 'Coldplay' ? 'active' : '' }}">Coldplay</a>

                        <a href="{{ route('events.index', array_merge(request()->except('page'), ['artist' => 'Apink'])) }}"
                           class="event-filter-tab {{ $selectedArtist === 'Apink' ? 'active' : '' }}">Apink</a>

                        <a href="{{ route('events.index', array_merge(request()->except('page'), ['artist' => 'Future Lab'])) }}"
                           class="event-filter-tab {{ $selectedArtist === 'Future Lab' ? 'active' : '' }}">Future Lab</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="event-artist-section">
    <div class="event-artist-header">
        <h3 class="event-artist-title">Popular Artists</h3>
    </div>

    <div class="event-artist-list">
        <div class="event-artist-list">
            @foreach($allArtists as $artist)
                <div class="event-artist-card {{ $selectedArtist === $artist['name'] ? 'active' : '' }}">
                    <img src="{{ $artist['image'] }}" alt="{{ $artist['name'] }}">
                    <div class="event-artist-name">{{ $artist['name'] }}</div>
                    <div class="event-artist-count">{{ $artist['count'] }} event</div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@forelse($filteredGroups as $groupMonth => $events)
    <div class="month-group">
        <div class="month-label">{{ $groupMonth }}</div>

        <div class="event-grid">
            @foreach($events as $event)
                <div class="event-row">
                    <div class="event-left">
                        <div class="event-date">
                            <div class="day">{{ $event['day'] }}</div>
                            <div class="number">{{ $event['number'] }}</div>
                            <div class="month">{{ $event['month_short'] }}</div>
                        </div>
                    </div>

                    <div class="event-image">
                        <img src="{{ $event['image'] }}" alt="{{ $event['title'] }}">
                    </div>

                    <div class="event-details">
                        <div class="event-header">
                            <span class="event-category">{{ $event['category'] }}</span>
                        </div>

                        <div class="event-title">{{ $event['title'] }}</div>
                        <div class="event-artist">{{ $event['artist'] }}</div>
                        <div class="event-info">{{ $event['info'] }}</div>

                        <div class="event-footer">
                            <div class="event-price">{{ $event['price'] }}</div>
                        </div>

                        <div>
                            <a href="{{ route('events.show', $event['id']) }}" class="btn-buy">Find Tickets</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@empty
    <div class="event-empty-state">
        No events found for this filter.
    </div>
@endforelse

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterWrap = document.getElementById('eventFilterWrap');
        const filterBtn = document.getElementById('filterToggleBtn');
        const filterPanel = document.getElementById('filterPanel');

        filterBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            filterPanel.classList.toggle('open');
        });

        document.addEventListener('click', function (e) {
            if (!filterWrap.contains(e.target)) {
                filterPanel.classList.remove('open');
            }
        });

        @if(request('month') || request('artist') || request('category'))
            filterPanel.classList.add('open');
        @endif
    });
</script>

@endsection
