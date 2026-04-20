@php
    $browseLink = $canBrowseEvents ? route('events.index', ['period' => 'upcoming']) : route('login');
    $fallbackImages = [
        'https://images.unsplash.com/photo-1511578314322-379afb476865?w=1600&h=700&fit=crop',
        'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?w=1600&h=700&fit=crop',
        'https://images.unsplash.com/photo-1515169067868-5387ec356754?w=1600&h=700&fit=crop',
    ];
@endphp

<section class="hero-slider">
    <div class="hero-slides" id="heroSlides">
        @forelse($heroEvents as $index => $event)
            @php
                $heroLink = $canBrowseEvents ? route('events.show', $event->id) : route('login');
                $image = $event->image
                    ? (\Illuminate\Support\Str::startsWith($event->image, ['http://', 'https://']) ? $event->image : asset('storage/' . $event->image))
                    : $fallbackImages[$index % count($fallbackImages)];
                $heroMeta = collect([
                    $event->artist ?: $event->organizer,
                    $event->date ? $event->date->format('D, d M Y') : null,
                    $event->venue,
                ])->filter()->implode(' | ');

                if ((int) $event->available_seats === 0 || $event->status === 'sold_out') {
                    $statusLabel = 'Sold Out';
                } elseif ((int) $event->available_seats <= 20) {
                    $statusLabel = 'Selling Fast';
                } else {
                    $statusLabel = 'Featured Event';
                }
            @endphp

            <div class="hero-slide {{ $index === 0 ? 'active' : '' }}">
                <div class="hero-overlay"></div>
                <img src="{{ $image }}" alt="{{ $event->name }}">

                <div class="hero-content">
                    <div class="hero-badge">{{ $statusLabel }}</div>
                    <h1 class="hero-title">{{ $event->name }}</h1>
                    <p class="hero-subtitle">{{ $heroMeta ?: 'Fresh event details are being updated now.' }}</p>

                    <div class="hero-meta-row">
                        <span class="hero-meta-pill">{{ $event->category?->name ?? 'Featured' }}</span>
                        <span class="hero-meta-pill">
                            {{ (int) $event->available_seats > 0 ? $event->available_seats . ' seats left' : 'Currently sold out' }}
                        </span>
                        <span class="hero-meta-pill">
                            {{ (float) $event->price > 0 ? 'From RM' . number_format((float) $event->price, 2) : 'Free entry' }}
                        </span>
                    </div>

                    <div class="hero-actions">
                        <a href="{{ $heroLink }}" class="hero-primary-btn">{{ $canBrowseEvents ? 'Find Tickets' : 'Sign In' }}</a>
                        <a href="{{ $browseLink }}" class="hero-secondary-btn">{{ $canBrowseEvents ? 'Browse Events' : 'Explore After Login' }}</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="hero-slide active hero-empty">
                <div class="hero-overlay"></div>
                <img src="{{ $fallbackImages[0] }}" alt="Upcoming events">

                <div class="hero-content">
                    <div class="hero-badge">Live Updates</div>
                    <h1 class="hero-title">Fresh events are landing soon</h1>
                    <p class="hero-subtitle">
                        The homepage is now connected to live event data, and new listings will appear here as soon as they are published.
                    </p>

                    <div class="hero-actions">
                        <a href="{{ $browseLink }}" class="hero-primary-btn">{{ $canBrowseEvents ? 'Browse Events' : 'Sign In' }}</a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    @if($heroEvents->count() > 1)
        <button class="hero-arrow hero-arrow-left" id="heroPrev" type="button">
            <span>&#10094;</span>
        </button>

        <button class="hero-arrow hero-arrow-right" id="heroNext" type="button">
            <span>&#10095;</span>
        </button>

        <div class="hero-dots" id="heroDots">
            @foreach($heroEvents as $index => $event)
                <button class="hero-dot {{ $index === 0 ? 'active' : '' }}" type="button" data-slide="{{ $index }}"></button>
            @endforeach
        </div>
    @endif
</section>
