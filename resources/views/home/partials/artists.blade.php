@php
    $artistsLink = $canBrowseEvents ? route('events.index', ['period' => 'upcoming']) : route('login');
    $fallbackImages = [
        'https://images.unsplash.com/photo-1501386761578-eac5c94b800a?w=300&h=300&fit=crop',
        'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=300&h=300&fit=crop',
        'https://images.unsplash.com/photo-1516280440614-37939bbacd81?w=300&h=300&fit=crop',
        'https://images.unsplash.com/photo-1508214751196-bcfd4ca60f91?w=300&h=300&fit=crop',
    ];
@endphp

<section class="home-section artist-section">
    <div class="section-header">
        <div>
            <div class="section-label">PERFORMING SOON</div>
            <h2 class="section-title">Popular Artists</h2>
        </div>
        <a href="{{ $artistsLink }}" class="section-link">{{ $canBrowseEvents ?  'See all ->' : 'Sign in ->' }}</a>
    </div>

    @if($popularArtists->isNotEmpty())
        <div class="artist-list">
            @foreach($popularArtists as $index => $artist)
                @php
                    $artistImage = $artist['image']
                        ? (\Illuminate\Support\Str::startsWith($artist['image'], ['http://', 'https://']) ? $artist['image'] : asset('storage/' . $artist['image']))
                        : $fallbackImages[$index % count($fallbackImages)];
                    $artistLink = $canBrowseEvents
                        ? route('events.index', ['period' => 'upcoming', 'artist' => $artist['name']])
                        : route('login');
                @endphp

                <a href="{{ $artistLink }}" class="artist-card">
                    <img src="{{ $artistImage }}" alt="{{ $artist['name'] }}">
                    <div class="artist-name">{{ $artist['name'] }}</div>
                    <div class="artist-meta">
                        {{ $artist['events_count'] }} upcoming {{ \Illuminate\Support\Str::plural('event', (int) $artist['events_count']) }}
                        @if($artist['next_event_date'])
                            | Next {{ $artist['next_event_date'] }}
                        @endif
                    </div>
                    @if($artist['next_event_name'])
                        <div class="artist-submeta">{{ $artist['next_event_name'] }}</div>
                    @endif
                </a>
            @endforeach
        </div>
    @else
        <div class="empty-panel">
            <div class="empty-panel-title">No featured artists yet</div>
            <p class="empty-panel-text">Once upcoming events with artist names are added, this section will update automatically.</p>
        </div>
    @endif
</section>
