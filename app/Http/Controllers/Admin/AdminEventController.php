<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AdminEventController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,super_admin']);
    }

    /**
     * Category folder grid (admin events home).
     */
    public function folderIndex()
    {
        $categories = Category::withCount('events')->orderBy('name')->get();

        return view('admin.events.index', compact('categories'));
    }

    /**
     * Events listed under one category.
     */
    public function categoryEvents(Category $category)
    {
        $events = $category->events()
            ->orderByDesc('date')
            ->orderByDesc('time')
            ->get();

        $totalSold = $events->sum(function ($event) {
            return max(0, (int) $event->total_seats - (int) $event->available_seats);
        });

        $eventChartData = [];
        $currentPercent = 0;

        foreach ($events as $event) {
            $sold = max(0, (int) $event->total_seats - (int) $event->available_seats);

            $percent = $totalSold > 0
                ? round(($sold / $totalSold) * 100, 2)
                : 0;

            $eventChartData[] = [
                'name' => $event->name,
                'sold' => $sold,
                'percent' => $percent,
                'start' => $currentPercent,
                'end' => min(100, $currentPercent + $percent),
            ];

            $currentPercent += $percent;
        }

        return view('admin.events.category', compact('category', 'events','totalSold','eventChartData'));
    }

    public function create(Request $request)
    {
        $categories = Category::orderBy('name')->get();

        if ($categories->isEmpty()) {
            return redirect()
                ->route('admin.categories.index')
                ->with('error', 'Add at least one category before creating an event.');
        }

        $selectedCategoryId = null;
        if ($request->filled('category_id')) {
            $selectedCategoryId = (int) $request->input('category_id');
            if ($selectedCategoryId < 1) {
                $selectedCategoryId = null;
            }
        }
        if ($selectedCategoryId && !$categories->firstWhere('id', $selectedCategoryId)) {
            $selectedCategoryId = null;
        }

        $prefillCategory = $selectedCategoryId
            ? Category::find($selectedCategoryId)
            : null;

        $defaultDate = Carbon::now()->format('Y-m-d');
        $defaultTime = '20:00';

        return view('admin.events.create', compact(
            'categories',
            'selectedCategoryId',
            'prefillCategory',
            'defaultDate',
            'defaultTime'
        ));
    }

    public function store(Request $request)
    {
        $data = $this->validatedEventData($request);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('events', 'public');
        }

        $event = Event::create($data);

        return $this->redirectAfterSave($event, 'Event created successfully!');
    }

    public function show(Event $event)
    {
        $event->load('category');

        return view('admin.events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        $categories = Category::orderBy('name')->get();
        $event->load('category');

        return view('admin.events.edit', compact('event', 'categories'));
    }

    public function update(Request $request, Event $event)
    {
        $data = $this->validatedEventData($request);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('events', 'public');
        }

        $event->update($data);

        return $this->redirectAfterSave($event->fresh(), 'Event updated successfully!');
    }

    public function destroy(Event $event)
    {
        $categoryId = $event->category_id;
        $event->delete();

        if ($categoryId) {
            return redirect()
                ->route('admin.events.category', $categoryId)
                ->with('success', 'Event deleted successfully!');
        }

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event deleted successfully!');
    }

    private function validatedEventData(Request $request): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'artist' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'time' => 'required',
            'venue' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'total_seats' => 'required|integer|min:1',
            'available_seats' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
            'organizer' => 'nullable|string|max:255',
        ];

        $request->validate($rules);

        if ((int) $request->available_seats > (int) $request->total_seats) {
            throw ValidationException::withMessages([
                'available_seats' => ['Available seats cannot exceed total seats.'],
            ]);
        }

        $data = $request->only([
            'name',
            'artist',
            'description',
            'date',
            'time',
            'venue',
            'price',
            'total_seats',
            'available_seats',
            'category_id',
            'organizer',
        ]);

        if (!$request->hasFile('image')) {
            unset($data['image']);
        }

        return $data;
    }

    private function redirectAfterSave(Event $event, string $message)
    {
        if ($event->category_id) {
            return redirect()
                ->route('admin.events.category', $event->category_id)
                ->with('success', $message);
        }

        return redirect()
            ->route('admin.events.index')
            ->with('success', $message);
    }

    /**
     * Used by Blade: upcoming vs past for badges.
     */
    public static function eventRowStatus(Event $event): string
    {
        if (!$event->date) {
            return 'draft';
        }

        $dateStr = $event->date instanceof Carbon
            ? $event->date->format('Y-m-d')
            : (string) $event->date;
        $dt = Carbon::parse($dateStr . ' ' . ($event->time ?? '23:59:59'));

        return $dt->isFuture() ? 'published' : 'draft';
    }
}
