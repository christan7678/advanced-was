@extends('admin.layout')

@section('title', 'New Event')
@section('page-title', 'New Event')

@section('topbar-actions')
    @if($prefillCategory)
        <a href="{{ route('admin.events.category', $prefillCategory) }}" class="btn-outline-sm">← Back to {{ $prefillCategory->name }}</a>
    @else
        <a href="{{ route('admin.events.index') }}" class="btn-outline-sm">← All categories</a>
    @endif
@endsection

@section('content')
<div class="detail-card" style="max-width:900px;">
    <div class="detail-card-title">Create event</div>

    @if($prefillCategory)
        <p class="td-sub" style="margin:0 0 14px;">Category: <strong>{{ $prefillCategory->name }}</strong> (you can change it below)</p>
    @endif

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
          action="{{ route('admin.events.store') }}"
          enctype="multipart/form-data"
          id="admin-event-form"
          data-sync-seats="1">
        @csrf
        @include('admin.events._form', [
            'submitLabel' => 'Create event',
            'event' => null,
            'categories' => $categories,
            'selectedCategoryId' => $selectedCategoryId,
            'isCreate' => true,
            'categoryRequired' => true,
            'defaultDate' => $defaultDate,
            'defaultTime' => $defaultTime,
            'cancelUrl' => $prefillCategory ? route('admin.events.category', $prefillCategory) : route('admin.events.index'),
        ])
    </form>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/admin-event-form.js') }}"></script>
@endsection
