<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Review;
use App\Models\Tour;
use App\Models\TourCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TourController extends Controller
{
    /**
     * Display a listing of the tours with filters.
     */
    public function index(Request $request): View
    {
        $query = Tour::with(['destination', 'category.parent', 'reviews'])
            ->where('is_active', true);

        // Search keyword
        if ($request->filled('q')) {
            $keyword = $request->string('q');
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        // Destination filter
        if ($request->filled('destination')) {
            $query->whereHas('destination', function ($q) use ($request) {
                $q->where('slug', $request->string('destination'))
                    ->orWhere('id', $request->input('destination'));
            });
        }

        // Category / Subcategory filter
        if ($request->filled('category')) {
            $categorySlug = $request->string('category');
            $category = TourCategory::where('slug', $categorySlug)
                ->orWhere('id', $categorySlug)
                ->first();

            if ($category) {
                if ($category->isParent()) {
                    $categoryIds = $category->children()->pluck('id')->push($category->id);
                    $query->whereIn('category_id', $categoryIds);
                } else {
                    $query->where('category_id', $category->id);
                }
            }
        }

        // Trip type filter
        if ($request->filled('type') && in_array($request->input('type'), ['open_trip', 'private_trip'])) {
            $query->where('type', $request->input('type'));
        }

        // Duration filter
        if ($request->filled('duration')) {
            $duration = (int) $request->input('duration');
            if ($duration === 1) {
                $query->where('duration_days', 1);
            } elseif ($duration === 3) {
                $query->whereBetween('duration_days', [2, 3]);
            } elseif ($duration === 4) {
                $query->where('duration_days', '>=', 4);
            }
        }

        // Sorting
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->orderBy('view_count', 'desc');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        $tours = $query->paginate(9)->withQueryString();
        $destinations = Destination::select('id', 'name', 'slug')->orderBy('name')->get();
        $categories = TourCategory::parents()
            ->with(['children' => function ($q) {
                $q->active()->orderBy('order')->orderBy('name');
            }])
            ->active()
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        return view('pages.tours.index', compact('tours', 'destinations', 'categories'));
    }

    /**
     * Display the specified tour details.
     */
    public function show(string $slug): View
    {
        $tour = Tour::with(['destination', 'agen', 'reviews' => function ($q) {
            $q->where('is_approved', true)->with('user')->latest();
        }])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $tour->incrementViews();

        $relatedTours = Tour::with(['destination', 'reviews'])
            ->where('is_active', true)
            ->where('id', '!=', $tour->id)
            ->where('destination_id', $tour->destination_id)
            ->take(3)
            ->get();

        return view('pages.tours.show', compact('tour', 'relatedTours'));
    }

    /**
     * Store a user review for the tour.
     */
    public function storeReview(Request $request, string $slug): RedirectResponse
    {
        $tour = Tour::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'title' => ['nullable', 'string', 'max:255'],
            'comment' => ['required', 'string', 'min:10', 'max:1000'],
        ]);

        Review::create([
            'user_id' => auth()->id(),
            'tour_id' => $tour->id,
            'rating' => $validated['rating'],
            'title' => $validated['title'] ?? null,
            'comment' => $validated['comment'],
            'is_approved' => true, // auto approve for demo
        ]);

        return back()->with('success', 'Terima kasih! Ulasan Anda berhasil dikirim.');
    }
}
