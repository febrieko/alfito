<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\HeroSlide;
use App\Models\Post;
use App\Models\Review;
use App\Models\Tour;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Show the homepage with featured tours, destinations, reviews, and blog articles.
     */
    public function index(): View
    {
        $featuredTours = Tour::with(['destination', 'reviews'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->take(6)
            ->get();

        $openTrips = Tour::with(['destination', 'reviews'])
            ->where('is_active', true)
            ->where('type', 'open_trip')
            ->latest()
            ->take(6)
            ->get();

        $privateTrips = Tour::with(['destination', 'reviews'])
            ->where('is_active', true)
            ->where('type', 'private_trip')
            ->latest()
            ->take(6)
            ->get();

        $featuredDestinations = Destination::withCount('tours')
            ->orderByDesc('is_featured')
            ->orderByDesc('view_count')
            ->take(6)
            ->get();

        $allDestinations = Destination::select('id', 'name', 'slug')
            ->orderBy('name')
            ->get();

        $reviews = Review::with(['user', 'tour'])
            ->where('is_approved', true)
            ->latest()
            ->take(6)
            ->get();

        $latestPosts = Post::with('destination')
            ->where('is_published', true)
            ->latest('published_at')
            ->take(3)
            ->get();

        $heroSlides = HeroSlide::active()->ordered()->get();

        return view('pages.home', compact(
            'heroSlides',
            'featuredTours',
            'openTrips',
            'privateTrips',
            'featuredDestinations',
            'allDestinations',
            'reviews',
            'latestPosts'
        ));
    }
}
