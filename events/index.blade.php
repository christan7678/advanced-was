@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/events.css') }}?v={{ time() }}">
@endsection

@section('content')

<h2 class="section-title">Upcoming Events</h2>

{{-- April 2026 --}}
<div class="month-group">
    <div class="month-label">April 2026</div>
    <div class="event-grid">

        {{-- 1st event --}}
        <div class="event-row">
            <div class="event-left">
                <div class="event-date">
                    <div class="day">WED</div>
                    <div class="number">29</div>
                    <div class="month">APR</div>
                </div>
            </div>

            <div class="event-image">
                <img src="https://images.unsplash.com/photo-1511578314322-379afb476865?w=300&h=300&fit=crop" alt="Music Festival 2026">
            </div>

            <div class="event-details">
                <div class="event-header">
                    <span class="event-category">Concert</span>
                </div>
                <div class="event-title">One Ok Rock 2026 Concert in Malaysia</div>
                <div class="event-artist">Coldplay</div>
                <div class="event-info">8:00 PM · Kuala Lumpur</div>
                <div class="event-footer">
                    <div class="event-price">From RM288 – RM888</div>
                </div>
                <div><a href="{{ route('events.show', 1) }}" class="btn-buy">Find Tickets</a></div>
            </div>
        </div>

        {{-- 2nd event --}}
        <div class="event-row">
            <div class="event-left">
                <div class="event-date">
                    <div class="day">WED</div>
                    <div class="number">29</div>
                    <div class="month">APR</div>
                </div>
            </div>

            <div class="event-image">
                <img src="https://images.unsplash.com/photo-1511578314322-379afb476865?w=300&h=300&fit=crop" alt="Music Festival 2026">
            </div>

            <div class="event-details">
                <div class="event-header">
                    <span class="event-category">Concert</span>
                </div>
                <div class="event-title">Music Festival 2026</div>
                <div class="event-artist">Coldplay</div>
                <div class="event-info">8:00 PM · Kuala Lumpur</div>
                <div class="event-footer">
                    <div class="event-price">From RM288 – RM888</div>
                </div>
                <div><a href="{{ route('events.show', 1) }}" class="btn-buy">Find Tickets</a></div>
            </div>
        </div>

        {{-- 3rd event --}}
        <div class="event-row">
            <div class="event-left">
                <div class="event-date">
                    <div class="day">WED</div>
                    <div class="number">29</div>
                    <div class="month">APR</div>
                </div>
            </div>

            <div class="event-image">
                <img src="https://images.unsplash.com/photo-1511578314322-379afb476865?w=300&h=300&fit=crop" alt="Music Festival 2026">
            </div>

            <div class="event-details">
                <div class="event-header">
                    <span class="event-category">Concert</span>
                </div>
                <div class="event-title">Apink 10th Concert in Malaysia</div>
                <div class="event-artist">Coldplay</div>
                <div class="event-info">8:00 PM · Kuala Lumpur</div>
                <div class="event-footer">
                    <div class="event-price">From RM288 – RM888</div>
                </div>
                <div><a href="{{ route('events.show', 1) }}" class="btn-buy">Find Tickets</a></div>
            </div>
        </div>

    </div>
</div>

{{-- May 2026 --}}
<div class="month-group">
    <div class="month-label">May 2026</div>
    <div class="event-grid">

        {{-- 1st event --}}
        <div class="event-row">
            <div class="event-left">
                <div class="event-date">
                    <div class="day">WED</div>
                    <div class="number">29</div>
                    <div class="month">APR</div>
                </div>
            </div>

            <div class="event-image">
                <img src="https://images.unsplash.com/photo-1511578314322-379afb476865?w=300&h=300&fit=crop" alt="Music Festival 2026">
            </div>

            <div class="event-details">
                <div class="event-header">
                    <span class="event-category">Concert</span>
                </div>
                <div class="event-title">Music Festival 2026</div>
                <div class="event-artist">Coldplay</div>
                <div class="event-info">8:00 PM · Kuala Lumpur</div>
                <div class="event-footer">
                    <div class="event-price">From RM288 – RM888</div>
                </div>
                <div><a href="{{ route('events.show', 1) }}" class="btn-buy">Find Tickets</a></div>
            </div>
        </div>

        {{-- 2nd event --}}
        <div class="event-row">
            <div class="event-left">
                <div class="event-date">
                    <div class="day">WED</div>
                    <div class="number">29</div>
                    <div class="month">APR</div>
                </div>
            </div>

            <div class="event-image">
                <img src="https://images.unsplash.com/photo-1511578314322-379afb476865?w=300&h=300&fit=crop" alt="Music Festival 2026">
            </div>

            <div class="event-details">
                <div class="event-header">
                    <span class="event-category">Concert</span>
                </div>
                <div class="event-title">Music Festival 2026</div>
                <div class="event-artist">Coldplay</div>
                <div class="event-info">8:00 PM · Kuala Lumpur</div>
                <div class="event-footer">
                    <div class="event-price">From RM288 – RM888</div>
                </div>
                <div><a href="{{ route('events.show', 1) }}" class="btn-buy">Find Tickets</a></div>
            </div>
        </div>

    </div>
</div>

@endsection