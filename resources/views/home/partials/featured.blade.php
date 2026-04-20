@php
    $featuredLink = $canBrowseEvents ? route('events.index', ['period' => 'upcoming']) : route('login');
@endphp

<section class="home-section">
    <div class="section-header">
        <h2 class="section-title">Featured Events</h2>
        <a href="{{ $featuredLink }}" class="section-link">{{ $canBrowseEvents ? 'See all ->' : 'Sign in ->' }}</a>
    </div>

    @if($featuredEvents->isNotEmpty())
        <div class="featured-grid">
            @foreach($featuredEvents as $event)
                @php
                    $eventLink = $canBrowseEvents ? route('events.show', $event->id) : route('login');
                    $eventColor = $event->category?->color ?: '#0b3770';
                    $meta = collect([
                        $event->artist ?: $event->organizer,
                        $event->date ? $event->date->format('D d M Y') : null,
                        $event->venue,
                    ])->filter()->implode(' | ');
                    $available = (int) $event->available_seats > 0 && $event->status !== 'sold_out';
                @endphp

                <div class="featured-card">
                    <div class="featured-card-top" style="background: linear-gradient(135deg, {{ $eventColor }}, #111827 140%);"></div>
                    <div class="featured-card-body">
                        <div class="featured-tag">{{ $event->category?->name ?? 'Featured' }}</div>
                        <div class="featured-name">{{ $event->name }}</div>
                        <div class="featured-meta">{{ $meta ?: 'Event details are being updated.' }}</div>

                        <div class="featured-status {{ $available ? 'available' : 'sold-out' }}">
                            {{ $available ? $event->available_seats . ' seats available' : 'Currently sold out' }}
                        </div>
                        <div class="featured-note">
                            {{ (float) $event->price > 0 ? 'From RM' . number_format((float) $event->price, 2) : 'Free entry' }}
                        </div>

                        <div class="featured-footer">
                            <div class="featured-price">{{ $available ? 'Booking open' : 'View details' }}</div>
                            <a href="{{ $eventLink }}" class="featured-btn">{{ $canBrowseEvents ? ($available ? 'Find Tickets' : 'View Details') : 'Sign In' }}</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-panel">
            <div class="empty-panel-title">No featured events right now</div>
            <p class="empty-panel-text">Create or publish upcoming events to populate this featured section automatically.</p>
        </div>
    @endif
</section>
