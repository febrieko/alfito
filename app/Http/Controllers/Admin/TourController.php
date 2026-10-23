<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Tour;
use App\Models\TourCategory;
use App\Models\User;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TourController extends Controller
{
    /**
     * Display a listing of tours.
     */
    public function index(Request $request): View
    {
        $search = $request->query('q');
        $destinationId = $request->query('destination_id');
        $categoryId = $request->query('category_id');
        $type = $request->query('type');

        $query = Tour::with(['destination', 'category', 'agen'])->withCount('bookings');

        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        if ($destinationId) {
            $query->where('destination_id', $destinationId);
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($type) {
            $query->where('type', $type);
        }

        $tours = $query->latest()->paginate(12)->withQueryString();
        $destinations = Destination::orderBy('name')->get();
        $categories = TourCategory::parents()->with('children')->get();

        return view('admin.tours.index', compact('tours', 'destinations', 'categories', 'search', 'destinationId', 'categoryId', 'type'));
    }

    /**
     * Show the form for creating a new tour.
     */
    public function create(): View
    {
        $destinations = Destination::orderBy('name')->get();
        $categories = TourCategory::parents()->with('children')->get();
        $agents = User::whereIn('role', ['admin', 'agen'])->orderBy('name')->get();

        return view('admin.tours.create', compact('destinations', 'categories', 'agents'));
    }

    public function __construct(
        protected ImageService $imageService
    ) {}

    /**
     * Store a newly created tour in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:tours,slug'],
            'destination_id' => ['required', 'exists:destinations,id'],
            'category_id' => ['nullable', 'exists:tour_categories,id'],
            'user_id' => ['required', 'exists:users,id'],
            'type' => ['required', 'in:open_trip,private_trip'],
            'duration_days' => ['required', 'integer', 'min:1'],
            'duration_nights' => ['nullable', 'integer', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            'price_private' => ['nullable', 'numeric', 'min:0'],
            'min_pax' => ['required', 'integer', 'min:1'],
            'max_pax' => ['required', 'integer', 'gte:min_pax'],
            'thumbnail' => ['nullable', 'string', 'max:1000'],
            'thumbnail_file' => ['nullable', 'file', 'image', 'max:15360'],
            'gallery_files' => ['nullable', 'array'],
            'gallery_files.*' => ['file', 'image', 'max:15360'],
            'gallery_urls' => ['nullable', 'array'],
            'gallery_urls.*' => ['nullable', 'string', 'max:1000'],
            'description' => ['required', 'string'],
            'itinerary' => ['nullable', 'array'],
            'itinerary.*.day' => ['nullable', 'integer'],
            'itinerary.*.title' => ['nullable', 'string', 'max:255'],
            'itinerary.*.activities' => ['nullable'],
            'includes' => ['nullable'],
            'excludes' => ['nullable'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        // Process uploaded gallery files
        $uploadedGallery = [];
        if ($request->hasFile('gallery_files')) {
            foreach ($request->file('gallery_files') as $file) {
                if ($file->isValid()) {
                    $uploadedGallery[] = $this->imageService->resizeAndStore($file, 'tours');
                }
            }
        }

        // Determine thumbnail
        $thumbnail = null;
        if ($request->hasFile('thumbnail_file')) {
            $thumbnail = $this->imageService->resizeAndStore($request->file('thumbnail_file'), 'tours');
        } elseif (! empty($validated['thumbnail'])) {
            $thumbnail = $validated['thumbnail'];
        } elseif (! empty($uploadedGallery)) {
            $thumbnail = $uploadedGallery[0];
        } else {
            $thumbnail = 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=80';
        }

        // Combine all images
        $allImages = array_values(array_unique(array_filter(array_merge(
            [$thumbnail],
            $uploadedGallery,
            $request->input('gallery_urls', [])
        ))));

        $slug = ! empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['title']);
        $originalSlug = $slug;
        $counter = 1;
        while (Tour::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        Tour::create([
            'user_id' => $validated['user_id'],
            'destination_id' => $validated['destination_id'],
            'category_id' => ($validated['category_id'] ?? null) ?: null,
            'title' => $validated['title'],
            'slug' => $slug,
            'type' => $validated['type'],
            'duration_days' => $validated['duration_days'],
            'duration_nights' => $validated['duration_nights'] ?? 0,
            'price' => $validated['price'],
            'price_private' => $validated['price_private'] ?? null,
            'min_pax' => $validated['min_pax'],
            'max_pax' => $validated['max_pax'],
            'thumbnail' => $thumbnail,
            'images' => ! empty($allImages) ? $allImages : [$thumbnail],
            'description' => $validated['description'],
            'itinerary' => $this->formatItinerary($request->input('itinerary')),
            'includes' => $this->cleanStringArray($request->input('includes')),
            'excludes' => $this->cleanStringArray($request->input('excludes')),
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.tours.index')->with('success', 'Paket wisata berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified tour.
     */
    public function edit(Tour $tour): View
    {
        $destinations = Destination::orderBy('name')->get();
        $categories = TourCategory::parents()->with('children')->get();
        $agents = User::whereIn('role', ['admin', 'agen'])->orderBy('name')->get();

        return view('admin.tours.edit', compact('tour', 'destinations', 'categories', 'agents'));
    }

    /**
     * Update the specified tour in storage.
     */
    public function update(Request $request, Tour $tour): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('tours', 'slug')->ignore($tour->id)],
            'destination_id' => ['required', 'exists:destinations,id'],
            'category_id' => ['nullable', 'exists:tour_categories,id'],
            'user_id' => ['required', 'exists:users,id'],
            'type' => ['required', 'in:open_trip,private_trip'],
            'duration_days' => ['required', 'integer', 'min:1'],
            'duration_nights' => ['nullable', 'integer', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            'price_private' => ['nullable', 'numeric', 'min:0'],
            'min_pax' => ['required', 'integer', 'min:1'],
            'max_pax' => ['required', 'integer', 'gte:min_pax'],
            'thumbnail' => ['nullable', 'string', 'max:1000'],
            'thumbnail_file' => ['nullable', 'file', 'image', 'max:15360'],
            'gallery_files' => ['nullable', 'array'],
            'gallery_files.*' => ['file', 'image', 'max:15360'],
            'existing_images' => ['nullable', 'array'],
            'existing_images.*' => ['string'],
            'selected_thumbnail' => ['nullable', 'string'],
            'description' => ['required', 'string'],
            'itinerary' => ['nullable', 'array'],
            'itinerary.*.day' => ['nullable', 'integer'],
            'itinerary.*.title' => ['nullable', 'string', 'max:255'],
            'itinerary.*.activities' => ['nullable'],
            'includes' => ['nullable'],
            'excludes' => ['nullable'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        // Process newly uploaded gallery files
        $newGallery = [];
        if ($request->hasFile('gallery_files')) {
            foreach ($request->file('gallery_files') as $file) {
                if ($file->isValid()) {
                    $newGallery[] = $this->imageService->resizeAndStore($file, 'tours');
                }
            }
        }

        // Kept existing images (or default to current tour images if field wasn't sent)
        $keptImages = $request->has('existing_images')
            ? ($request->input('existing_images') ?? [])
            : ($tour->images ?? [$tour->thumbnail]);

        // Determine thumbnail
        $thumbnail = $tour->thumbnail;
        if ($request->hasFile('thumbnail_file')) {
            $thumbnail = $this->imageService->resizeAndStore($request->file('thumbnail_file'), 'tours');
        } elseif (! empty($request->input('selected_thumbnail'))) {
            $thumbnail = $request->input('selected_thumbnail');
        } elseif (! empty($validated['thumbnail'])) {
            $thumbnail = $validated['thumbnail'];
        } elseif (empty($thumbnail) && ! empty($newGallery)) {
            $thumbnail = $newGallery[0];
        }

        // Combine kept images, new uploads, and ensure thumbnail is included
        $allImages = array_values(array_unique(array_filter(array_merge(
            [$thumbnail],
            $keptImages,
            $newGallery
        ))));

        $slug = ! empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['title']);
        $originalSlug = $slug;
        $counter = 1;
        while (Tour::where('slug', $slug)->where('id', '!=', $tour->id)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        $tour->update([
            'user_id' => $validated['user_id'],
            'destination_id' => $validated['destination_id'],
            'category_id' => ($validated['category_id'] ?? null) ?: null,
            'title' => $validated['title'],
            'slug' => $slug,
            'type' => $validated['type'],
            'duration_days' => $validated['duration_days'],
            'duration_nights' => $validated['duration_nights'] ?? 0,
            'price' => $validated['price'],
            'price_private' => $validated['price_private'] ?? null,
            'min_pax' => $validated['min_pax'],
            'max_pax' => $validated['max_pax'],
            'thumbnail' => $thumbnail,
            'images' => ! empty($allImages) ? $allImages : [$thumbnail],
            'description' => $validated['description'],
            'itinerary' => $this->formatItinerary($request->input('itinerary')),
            'includes' => $this->cleanStringArray($request->input('includes')),
            'excludes' => $this->cleanStringArray($request->input('excludes')),
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.tours.index')->with('success', "Paket wisata '{$tour->title}' berhasil diperbarui!");
    }

    /**
     * Remove the specified tour from storage.
     */
    public function destroy(Tour $tour): RedirectResponse
    {
        $title = $tour->title;
        $tour->delete();

        return redirect()->route('admin.tours.index')->with('success', "Paket wisata '{$title}' berhasil dihapus.");
    }

    /**
     * Toggle active status.
     */
    public function toggleActive(Tour $tour): RedirectResponse
    {
        $tour->update(['is_active' => ! $tour->is_active]);
        $status = $tour->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Status paket '{$tour->title}' berhasil {$status}.");
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured(Tour $tour): RedirectResponse
    {
        $tour->update(['is_featured' => ! $tour->is_featured]);
        $status = $tour->is_featured ? 'dijadikan unggulan' : 'dihapus dari unggulan';

        return back()->with('success', "Paket '{$tour->title}' berhasil {$status}.");
    }

    /**
     * Format and clean itinerary data.
     */
    private function formatItinerary(?array $rawItinerary): ?array
    {
        if (empty($rawItinerary)) {
            return null;
        }

        $formatted = [];
        $dayIndex = 1;

        foreach ($rawItinerary as $item) {
            $title = trim($item['title'] ?? '');
            $rawActivities = $item['activities'] ?? [];

            if (is_string($rawActivities)) {
                $activities = array_values(array_filter(array_map('trim', explode("\n", $rawActivities))));
            } elseif (is_array($rawActivities)) {
                $activities = array_values(array_filter(array_map('trim', $rawActivities)));
            } else {
                $activities = [];
            }

            if (! empty($title) || ! empty($activities)) {
                $formatted[] = [
                    'day' => (int) ($item['day'] ?? $dayIndex),
                    'title' => $title ?: "Hari ke-{$dayIndex}",
                    'activities' => $activities,
                ];
                $dayIndex++;
            }
        }

        return ! empty($formatted) ? $formatted : null;
    }

    /**
     * Clean array of strings (for includes and excludes).
     */
    private function cleanStringArray(mixed $raw): ?array
    {
        if (is_string($raw)) {
            $items = array_values(array_filter(array_map('trim', explode("\n", $raw))));

            return ! empty($items) ? $items : null;
        }

        if (is_array($raw)) {
            $items = array_values(array_filter(array_map('trim', $raw)));

            return ! empty($items) ? $items : null;
        }

        return null;
    }
}
