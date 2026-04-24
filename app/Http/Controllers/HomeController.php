<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //only guest can access
        //$this->middleware('guest');

        //only auth can access
        //$this->middleware('auth');

        // empty ==> both guest and auth can access home page
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin'])) {
            return redirect()->route('admin.dashboard');
        }

        return view('home.home', $this->buildViewData());
    }

    public function live(): JsonResponse
    {
        $viewData = $this->buildViewData();

        return response()->json([
            'hero' => view('home.partials.hero', $viewData)->render(),
            'artists' => view('home.partials.artists', $viewData)->render(),
            'featured' => view('home.partials.featured', $viewData)->render(),
            'categories' => view('home.partials.categories', $viewData)->render(),
        ]);
    }

    protected function buildViewData(): array
    {
        $today = today();

        $upcomingEvents = Event::with('category')
            ->whereDate('date', '>=', $today)
            ->orderBy('date')
            ->orderBy('time')
            ->get();

        $heroEvents = $upcomingEvents
            ->where('status', '!=', 'sold_out')
            ->take(3)
            ->values();

        if ($heroEvents->count() < 3) {
            $heroEvents = $heroEvents
                ->concat(
                    $upcomingEvents
                        ->whereNotIn('id', $heroEvents->pluck('id')->all())
                        ->take(3 - $heroEvents->count())
                )
                ->values();
        }

        $featuredEvents = $upcomingEvents
            ->whereNotIn('id', $heroEvents->pluck('id')->all())
            ->take(3)
            ->values();

        if ($featuredEvents->count() < 3) {
            $featuredEvents = $featuredEvents
                ->concat(
                    $upcomingEvents
                        ->whereNotIn('id', $heroEvents->pluck('id')->merge($featuredEvents->pluck('id'))->all())
                        ->take(3 - $featuredEvents->count())
                )
                ->values();
        }

        $popularArtists = $upcomingEvents
            ->filter(function ($event) {
                $artist = trim((string) $event->artist);

                return $artist !== '' && !in_array(strtoupper($artist), ['NULL', '-']);
            })
            ->groupBy(function ($event) {
                return trim((string) $event->artist);
            })
            ->map(function ($events, $artist) {
                $sorted = $events->sortBy('date')->values();
                $first = $sorted->first();

                return [
                    'name' => $artist,
                    'events_count' => $events->count(),
                    'image' => $first ? $first->image : null,
                    'next_event_name' => $first ? $first->name : null,
                    'next_event_date' => $first && $first->date ? $first->date->format('d M Y') : null,
                ];
            })
            ->sortByDesc('events_count')
            ->take(4)
            ->values();

        $categoryInsights = Category::with([
            'events' => function ($query) use ($today) {
                $query->whereDate('date', '>=', $today)->orderBy('date');
            }
        ])
            ->get()
            ->map(function ($category) {
                $events = $category->events;

                if ($events->isEmpty()) {
                    return null;
                }

                $nextEvent = $events->first();

                return [
                    'name' => $category->name,
                    'color' => $category->color ?: '#0b3770',
                    'upcoming_events_count' => $events->count(),
                    'available_seats' => $nextEvent ? $nextEvent->available_seats : 0,
                    'price' => $nextEvent ? $nextEvent->price : 0,
                    'next_event_name' => $nextEvent ? $nextEvent->name : null,
                    'next_event_date' => $nextEvent && $nextEvent->date ? $nextEvent->date->format('d M Y') : null,
                ];
            })
            ->filter()
            ->sortByDesc('upcoming_events_count')
            ->values();

        $categories = $categoryInsights->take(6)->values();

        return [
            'heroEvents' => $heroEvents,
            'featuredEvents' => $featuredEvents,
            'popularArtists' => $popularArtists,
            'categories' => $categories,
            'canBrowseEvents' => $this->canBrowseEvents(),
        ];
    }

    protected function canBrowseEvents(): bool
    {
        return auth( )->check();
    }
}
