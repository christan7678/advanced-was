@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Categories</h2>
        @can('isAdmin')
        <a href="{{ route('categories.create') }}" class="btn btn-primary">+ New Category</a>
        @endcan
    </div>

    {{-- Success / Error messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Events</th>
                <th>Actions</th>
                @can('isAdmin')
                <th>Admin Actions</th>
                @endcan
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
            <tr>
                <td>{{ $category->id }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->events_count }}</td>
                <td>
                    <a href="{{ route('categories.show', $category) }}"
                        class="btn btn-sm btn-info">View</a>
                </td>
                @can('isAdmin')
                    <td>
                        <a href="{{ route('categories.edit', $category) }}"
                            class="btn btn-sm btn-warning">Edit</a>

                        <form action="{{ route('categories.destroy', $category) }}"
                            method="POST" class="d-inline"
                            onsubmit="return confirm('Delete this category?')">
                            @csrf
                            @method('DELETE')
                        <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                @endcan
            </tr>
            @empty
            <tr>
                @can('isAdmin')
                <td colspan="5" class="text-center text-muted">No categories found.</td>
                @else
                <td colspan="4" class="text-center text-muted">No categories found.</td>
                @endcan
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
