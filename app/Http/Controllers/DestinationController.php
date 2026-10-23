<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DestinationController extends Controller
{
    /**
     * Display a listing of destinations.
     */
    public function index(Request $request): View
    {
        $query = Destination::withCount(['tours' => function ($q) {
            $q->where('is_active', true);
        }]);

        if ($request->filled('region')) {
            $query->where('region', 'like', "%{$request->input('region')}%");
        }

        $destinations = $query->orderByDesc('is_featured')
            ->orderByDesc('tours_count')
            ->paginate(12)
            ->withQueryString();

        $regions = Destination::select('region')->distinct()->pluck('region');

        return view('pages.destinations.index', compact('destinations', 'regions'));
    }

    /**
     * Display the specified destination with tours and location data.
     */
    public function show(string $slug): View
    {
        $destination = Destination::where('slug', $slug)->firstOrFail();
        $destination->incrementViews();

        $tours = Tour::with(['reviews'])
            ->where('destination_id', $destination->id)
            ->where('is_active', true)
            ->latest()
            ->paginate(6);

        $posts = $destination->posts()
            ->where('is_published', true)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('pages.destinations.show', compact('destination', 'tours', 'posts'));
    }
}
