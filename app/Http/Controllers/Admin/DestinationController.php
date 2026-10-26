<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DestinationController extends Controller
{
    public function __construct(
        public ImageService $imageService
    ) {}

    /**
     * Display a listing of destinations.
     */
    public function index(Request $request): View
    {
        $search = $request->query('q');

        $query = Destination::withCount('tours');

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('region', 'like', "%{$search}%");
        }

        $destinations = $query->orderBy('name')->paginate(12)->withQueryString();

        return view('admin.destinations.index', compact('destinations', 'search'));
    }

    /**
     * Show the form for creating a new destination.
     */
    public function create(): View
    {
        return view('admin.destinations.create');
    }

    /**
     * Store a newly created destination in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:150', 'unique:destinations,slug'],
            'region' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
            'thumbnail_file' => ['required', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:12288'],
            'description' => ['required', 'string'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $slug = ! empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['name']);
        $originalSlug = $slug;
        $counter = 1;
        while (Destination::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        $thumbnail = $this->imageService->resizeAndStore(
            $request->file('thumbnail_file'),
            'destinations',
            1600,
            1000,
            85
        );

        Destination::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'region' => $validated['region'],
            'country' => $validated['country'],
            'thumbnail' => $thumbnail,
            'description' => $validated['description'],
            'is_featured' => $request->boolean('is_featured'),
        ]);

        return redirect()->route('admin.destinations.index')->with('success', 'Destinasi baru berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified destination.
     */
    public function edit(Destination $destination): View
    {
        return view('admin.destinations.edit', compact('destination'));
    }

    /**
     * Update the specified destination in storage.
     */
    public function update(Request $request, Destination $destination): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:150', Rule::unique('destinations', 'slug')->ignore($destination->id)],
            'region' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
            'thumbnail_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:12288'],
            'description' => ['required', 'string'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $slug = ! empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['name']);
        $originalSlug = $slug;
        $counter = 1;
        while (Destination::where('slug', $slug)->where('id', '!=', $destination->id)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        $thumbnail = $destination->thumbnail;

        if ($request->hasFile('thumbnail_file')) {
            $this->deleteOldThumbnail($destination->thumbnail);
            $thumbnail = $this->imageService->resizeAndStore(
                $request->file('thumbnail_file'),
                'destinations',
                1600,
                1000,
                85
            );
        }

        $destination->update([
            'name' => $validated['name'],
            'slug' => $slug,
            'region' => $validated['region'],
            'country' => $validated['country'],
            'thumbnail' => $thumbnail,
            'description' => $validated['description'],
            'is_featured' => $request->boolean('is_featured'),
        ]);

        return redirect()->route('admin.destinations.index')->with('success', "Destinasi '{$destination->name}' berhasil diperbarui!");
    }

    /**
     * Remove the specified destination from storage.
     */
    public function destroy(Destination $destination): RedirectResponse
    {
        $name = $destination->name;
        $this->deleteOldThumbnail($destination->thumbnail);
        $destination->delete();

        return redirect()->route('admin.destinations.index')->with('success', "Destinasi '{$name}' berhasil dihapus.");
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured(Destination $destination): RedirectResponse
    {
        $destination->update(['is_featured' => ! $destination->is_featured]);
        $status = $destination->is_featured ? 'dijadikan destinasi unggulan' : 'dihapus dari unggulan';

        return back()->with('success', "Destinasi '{$destination->name}' berhasil {$status}.");
    }

    /**
     * Delete an existing thumbnail file from public storage if it was uploaded locally.
     */
    private function deleteOldThumbnail(?string $path): void
    {
        if (! $path || str_starts_with($path, 'http')) {
            return;
        }

        $relativePath = Str::after($path, '/storage/');
        if ($relativePath && Storage::disk('public')->exists($relativePath)) {
            Storage::disk('public')->delete($relativePath);
        }
    }
}
