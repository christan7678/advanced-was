@php
    /** @var \Illuminate\Support\Collection $categories */
    /** @var \App\Models\Event|null $event */
    $e = $event ?? null;
    $isCreate = $isCreate ?? false;
    $categoryRequired = $categoryRequired ?? true;
    $defaultDate = $defaultDate ?? null;
    $defaultTime = $defaultTime ?? null;
    $catId = old('category_id', $selectedCategoryId ?? ($e ? $e->category_id : ''));

    $dateValue = old('date', ($e && $e->date) ? $e->date->format('Y-m-d') : ($defaultDate ?? ''));

    $timeVal = old('time', null);
    if ($timeVal === null) {
        if ($e && $e->time) {
            $timeVal = is_string($e->time) && strlen($e->time) >= 5 ? substr($e->time, 0, 5) : $e->time;
        } else {
            $timeVal = $defaultTime ?? '';
        }
    } elseif (is_string($timeVal) && strlen($timeVal) >= 5) {
        $timeVal = substr($timeVal, 0, 5);
    }

    $totalDefault = old('total_seats', $e ? $e->total_seats : '100');
    $availDefault = old('available_seats', $e ? $e->available_seats : '100');
@endphp

<div class="form-grid">
    <div class="form-group form-full">
        <label>Event title</label>
        <input class="form-input" type="text" name="name" required
               value="{{ old('name', $e ? $e->name : '') }}" placeholder="Music Festival 2026">
        @error('name')
            <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group form-full">
        <label>Artist</label>
        <input class="form-input" type="text" name="artist"
               value="{{ old('artist', $e ? ($e->artist ?? '') : '') }}" placeholder="Artist or band name">
        @error('artist')
            <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group form-full">
        <label>Organiser</label>
        <input class="form-input" type="text" name="organizer"
               value="{{ old('organizer', $e ? ($e->organizer ?? '') : '') }}" placeholder="Artist or organiser name">
        @error('organizer')
            <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label>Category @if($categoryRequired)<span style="color:#dc2626">*</span>@endif</label>
        <select class="form-input" name="category_id" @if($categoryRequired) required @endif>
            @if($categoryRequired)
                <option value="" {{ (string) $catId === '' ? 'selected' : '' }}>Select category</option>
            @else
                <option value="">— None —</option>
            @endif
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ (string) $catId === (string) $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        @error('category_id')
            <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label>Venue</label>
        <input class="form-input" type="text" name="venue" required
               value="{{ old('venue', $e ? $e->venue : '') }}" placeholder="Kuala Lumpur">
        @error('venue')
            <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label>Date</label>
        <input class="form-input" type="date" name="date" required value="{{ $dateValue }}">
        @error('date')
            <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label>Time</label>
        <input class="form-input" type="time" name="time" required value="{{ $timeVal }}">
        @error('time')
            <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label>Price (RM)</label>
        <input class="form-input" type="number" step="0.01" min="0" name="price" required
               value="{{ old('price', $e ? $e->price : '') }}" placeholder="188.00">
        @error('price')
            <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label>Total seats</label>
        <input class="form-input" type="number" min="1" name="total_seats" id="evt-total-seats" required
               value="{{ $totalDefault }}" placeholder="500">
        @error('total_seats')
            <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label>Available seats</label>
        <input class="form-input" type="number" min="0" name="available_seats" id="evt-available-seats" required
               value="{{ $availDefault }}" placeholder="500">
        @error('available_seats')
            <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
        @enderror
    </div>

    @if($isAdmin ?? false)
    <div class="form-group">
        <label>Status</label>
        <select class="form-input" name="status">
            @foreach($statuses ?? ['active', 'inactive', 'sold_out', 'cancelled'] as $status)
                <option value="{{ $status }}" {{ old('status', $e?->status ?? 'inactive') === $status ? 'selected' : '' }}>
                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                </option>
            @endforeach
        </select>
        @error('status')
            <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
        @enderror
    </div>
    @endif

    @if($isCreate)
        <div class="form-group form-full">
            <label class="form-input" style="display:flex;align-items:center;gap:8px;font-weight:400;cursor:pointer;border:none;padding:0;background:transparent;">
                <input type="checkbox" id="evt-sync-seats-check" checked style="width:auto;">
                Keep available seats equal to total when total changes
            </label>
        </div>
    @endif

    <div class="form-group form-full">
        <label>Description</label>
        <textarea class="form-input form-textarea" name="description" rows="4"
                  placeholder="Describe the event...">{{ old('description', $e ? ($e->description ?? '') : '') }}</textarea>
        @error('description')
            <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group form-full">
        <label>Event image</label>
        <input class="form-input" type="file" name="image" id="evt-image-input" accept="image/*">
        @error('image')
            <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
        @enderror
        @if($e && $e->image)
            <div class="td-sub" style="margin-top:6px;">Current file: {{ $e->image }}</div>
        @endif
        <div id="evt-image-preview-wrap" style="margin-top:10px;display:none;">
            <img id="evt-image-preview" src="" alt="Preview" style="max-width:280px;max-height:180px;border-radius:8px;border:1px solid #e5e7eb;">
        </div>
    </div>
</div>

<div class="modal-actions" style="margin-top:8px; display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
    @isset($cancelUrl)
        <a href="{{ $cancelUrl }}" class="btn-cancel" style="text-decoration:none;display:inline-flex;align-items:center;justify-content:center;">Cancel</a>
    @endisset
    <button type="submit" class="btn-primary">{{ $submitLabel ?? 'Save' }}</button>
</div>
