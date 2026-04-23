<?php
namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EventController extends Controller
{
    public function __construct()
    {
        // index + show: both admin and user (authenticated)
        $this->middleware('auth')->only(['index', 'show']);

        // CRUD: admin only
        $this->middleware(['auth', 'role:admin,super_admin'])->except(['index', 'show']);
    }

    public function index()
    {
        $period = request('period', 'upcoming');
        $category = request('category');
        $artist = request('artist');
        $searchQuery = request('q');

        $query = Event::with('category')
            ->orderBy('date', $period === 'past' ? 'desc' : 'asc');

        if ($period === 'past') {
            $query->whereDate('date', '<', today());
        } else {
            $query->whereDate('date', '>=', today());
        }

        // Filter by category
        if ($category) {
            $query->whereHas('category', function ($q) use ($category) {
                $q->where('name', $category);
            });
        }

        // Filter by artist
        if ($artist) {
            $query->where('artist', 'like', '%' . $artist . '%');
        }

        // Search by name
        if ($searchQuery) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('name', 'like', '%' . $searchQuery . '%')
                  ->orWhere('artist', 'like', '%' . $searchQuery . '%')
                  ->orWhere('description', 'like', '%' . $searchQuery . '%');
            });
        }

        $events = $query->get();

        // Group events by month
        $groupedEvents = $events->groupBy(function ($event) {
            return $event->date->format('F Y');
        });

        // Get available categories and artists
        $categories = Category::withCount('events')->get();
        $artists = Event::where('artist', '!=', null)
            ->distinct('artist')
            ->pluck('artist')
            ->filter()
            ->values();

        return view('events.index', compact('groupedEvents', 'categories', 'artists', 'category', 'artist', 'searchQuery', 'period'));
    }

    public function create()
    {
        Gate::authorize('administration');

        $categories = Category::all();
        return view('events.create', compact('categories'));
    }

    public function store(Request $request)
    {
        Gate::authorize('administration');

        $request->validate([
            'name' => 'required|string|max:255',
            'artist' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'time' => 'required',
            'venue' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'total_seats' => 'required|integer|min:1',
            'available_seats' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:2048',
            'organizer' => 'nullable|string|max:255',
        ]);

        $data = $request->except('image');

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('events', 'public');
        }

        Event::create($data);

        return redirect()->route('events.index')
            ->with('success', 'Event created successfully!');
    }

    public function show(Event $event)
    {
        $event->load('category');
        return view('events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        Gate::authorize('administration');

        $categories = Category::all();
        return view('events.edit', compact('event', 'categories'));
    }

    public function update(Request $request, Event $event)
    {
        Gate::authorize('administration');

        $request->validate([
            'name' => 'required|string|max:255',
            'artist' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'time' => 'required',
            'venue' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'total_seats' => 'required|integer|min:1',
            'available_seats' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:2048',
            'organizer' => 'nullable|string|max:255',
        ]);

        $data = $request->except('image');

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('events', 'public');
        }

        $event->update($data);

        return redirect()->route('events.index')
            ->with('success', 'Event updated successfully!');
    }

    public function destroy(Event $event)
    {
        Gate::authorize('administration');

        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Event deleted successfully!');
    }
}
