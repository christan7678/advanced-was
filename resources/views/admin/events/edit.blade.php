@extends('admin.layout')

@section('title', 'Edit Event')
@section('page-title', 'Edit Event')

@section('topbar-actions')
    @if($event->category_id)
        <a href="{{ route('admin.events.category', $event->category_id) }}" class="btn-outline-sm">← Back to category</a>
    @else
        <a href="{{ route('admin.events.index') }}" class="btn-outline-sm">← All categories</a>
    @endif
@endsection

@section('content')
<div class="detail-card" style="max-width:900px;">
    <div class="detail-card-title">Edit event</div>

    @if($errors->any())
        <div class="alert alert-error" style="background:#fee2e2;color:#991b1b;padding:10px 14px;border-radius:8px;margin-bottom:14px;font-size:13px;">
            <strong>Please fix the following:</strong>
            <ul style="margin:8px 0 0 18px;padding:0;">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST"
          action="{{ route('admin.events.update', $event) }}"
          enctype="multipart/form-data"
          id="admin-event-form"
          data-sync-seats="0">
        @csrf
        @method('PUT')
        @include('admin.events._form', [
            'submitLabel' => 'Update event',
            'event' => $event,
            'categories' => $categories,
            'selectedCategoryId' => null,
            'isCreate' => false,
            'categoryRequired' => true,
            'defaultDate' => null,
            'defaultTime' => null,
            'cancelUrl' => $event->category_id ? route('admin.events.category', $event->category_id) : route('admin.events.index'),
        ])
    </form>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/admin-event-form.js') }}"></script>
@endsection
