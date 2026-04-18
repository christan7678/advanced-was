@extends('admin.layout')

@section('title', $event->name)
@section('page-title', 'Event detail')

@section('topbar-actions')
    <a href="{{ route('admin.events.edit', $event) }}" class="btn-primary">Edit</a>
    @if($event->category_id)
        <a href="{{ route('admin.events.category', $event->category_id) }}" class="btn-outline-sm">Category</a>
    @endif
    <a href="{{ route('admin.events.index') }}" class="btn-outline-sm">All categories</a>
@endsection

@section('content')
<div class="detail-card" style="max-width:720px;">
    <div class="detail-card-title">{{ $event->name }}</div>

    <div class="detail-row">
        <span class="detail-label">Category</span>
        <span class="detail-val">{{ $event->category->name ?? '—' }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Organiser</span>
        <span class="detail-val">{{ $event->organizer ?? '—' }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">When</span>
        <span class="detail-val">
            {{ $event->date ? $event->date->format('d M Y') : '—' }}
            @if($event->time)
                · {{ substr($event->time, 0, 5) }}
            @endif
        </span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Venue</span>
        <span class="detail-val">{{ $event->venue }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Price (RM)</span>
        <span class="detail-val">{{ number_format((float) $event->price, 2) }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Seats</span>
        <span class="detail-val">{{ $event->total_seats - $event->available_seats }} sold · {{ $event->available_seats }} left · {{ $event->total_seats }} total</span>
    </div>
    @if($event->description)
        <div style="margin-top:12px;font-size:14px;color:#374151;line-height:1.5;">{{ $event->description }}</div>
    @endif
</div>
@endsection
