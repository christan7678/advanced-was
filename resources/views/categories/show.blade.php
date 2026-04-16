@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Category: {{ $category->name }}</h2>
            <div>
                <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning">Edit</a>
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>

        <p><strong>Created:</strong> {{ $category->created_at->format('d M Y') }}</p>

        <hr>

        <h4>Events in this Category ({{ $category->events->count() }})</h4>

        @if($category->events->isEmpty())
            <p class="text-muted">No events in this category yet.</p>
        @else
            <table class="table table-bordered">
                <thead class="table-secondary">
                    <tr>
                        <th>#</th>
                        <th>Event Name</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($category->events as $event)
                        <tr>
                            <td>{{ $event->id }}</td>
                            <td>{{ $event->name }}</td>
                            <td>{{ $event->date ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection