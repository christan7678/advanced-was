@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Edit Event</h2>

        <form action="{{ route('events.update', $event) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name', $event->name) }}" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" rows="3"
                    class="form-control @error('description') is-invalid @enderror">{{ old('description', $event->description) }}</textarea>
                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="row">
                <div class="col mb-3">
                    <label class="form-label">Date <span class="text-danger">*</span></label>
                    <input type="date" name="date" class="form-control @error('date') is-invalid @enderror"
                        value="{{ old('date', $event->date) }}" required>
                    @error('date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col mb-3">
                    <label class="form-label">Time <span class="text-danger">*</span></label>
                    <input type="time" name="time" class="form-control @error('time') is-invalid @enderror"
                        value="{{ old('time', $event->time) }}" required>
                    @error('time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Venue <span class="text-danger">*</span></label>
                <input type="text" name="venue" class="form-control @error('venue') is-invalid @enderror"
                    value="{{ old('venue', $event->venue) }}" required>
                @error('venue') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="row">
                <div class="col mb-3">
                    <label class="form-label">Price (RM) <span class="text-danger">*</span></label>
                    <input type="number" name="price" step="0.01" class="form-control @error('price') is-invalid @enderror"
                        value="{{ old('price', $event->price) }}" required>
                    @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col mb-3">
                    <label class="form-label">Total Seats <span class="text-danger">*</span></label>
                    <input type="number" name="total_seats" class="form-control @error('total_seats') is-invalid @enderror"
                        value="{{ old('total_seats', $event->total_seats) }}" required>
                    @error('total_seats') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col mb-3">
                    <label class="form-label">Available Seats <span class="text-danger">*</span></label>
                    <input type="number" name="available_seats"
                        class="form-control @error('available_seats') is-invalid @enderror"
                        value="{{ old('available_seats', $event->available_seats) }}" required>
                    @error('available_seats') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                    <option value="">— Select Category —</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $event->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Organizer</label>
                <input type="text" name="organizer" class="form-control @error('organizer') is-invalid @enderror"
                    value="{{ old('organizer', $event->organizer) }}">
                @error('organizer') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Image</label>
                @if($event->image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $event->image) }}" alt="Current Image" width="150">
                    </div>
                @endif
                <input type="file" name="image" accept="image/*" class="form-control @error('image') is-invalid @enderror">
                <small class="text-muted">Leave empty to keep current image.</small>
                @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn btn-warning">Update Event</button>
            <a href="{{ route('events.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection