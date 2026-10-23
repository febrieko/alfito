<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TourCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TourCategoryController extends Controller
{
    /**
     * Display a listing of categories and sub-categories.
     */
    public function index(Request $request): View
    {
        $selectedParentId = $request->query('parent_id');

        // Fetch parent categories for tabs/filter
        $parentCategories = TourCategory::parents()
            ->withCount(['children', 'tours'])
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        // Query categories to display
        $query = TourCategory::with(['parent', 'children'])
            ->withCount('tours');

        if ($selectedParentId === 'root') {
            $query->parents();
        } elseif ($selectedParentId) {
            $query->where('parent_id', $selectedParentId);
        }

        $categories = $query->orderBy('order')->orderBy('name')->get();

        // Find selected parent if filtered
        $activeParent = $selectedParentId && $selectedParentId !== 'root'
            ? TourCategory::find($selectedParentId)
            : null;

        return view('admin.categories.index', compact(
            'parentCategories',
            'categories',
            'selectedParentId',
            'activeParent'
        ));
    }

    /**
     * Show the form for creating a new category or sub-category.
     */
    public function create(Request $request): View
    {
        $preselectedParentId = $request->query('parent_id');

        $parentCategories = TourCategory::parents()
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        $nextOrder = (TourCategory::when($preselectedParentId, function ($query, $parentId) {
            return $query->where('parent_id', $parentId);
        }, function ($query) {
            return $query->whereNull('parent_id');
        })->max('order') ?? 0) + 1;

        return view('admin.categories.create', compact(
            'parentCategories',
            'preselectedParentId',
            'nextOrder'
        ));
    }

    /**
     * Store a newly created category or sub-category in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'parent_id' => ['nullable', 'exists:tour_categories,id'],
            'slug' => ['nullable', 'string', 'max:120', 'unique:tour_categories,slug'],
            'icon' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:500'],
            'color' => ['nullable', 'string', 'max:30'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $slug = ! empty($validated['slug'])
            ? Str::slug($validated['slug'])
            : Str::slug($validated['name']);

        // Ensure unique slug
        $originalSlug = $slug;
        $counter = 1;
        while (TourCategory::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        $category = TourCategory::create([
            'parent_id' => ($validated['parent_id'] ?? null) ?: null,
            'name' => $validated['name'],
            'slug' => $slug,
            'icon' => $validated['icon'] ?? '🏷️',
            'description' => $validated['description'] ?? null,
            'color' => $validated['color'] ?? 'sky',
            'order' => $validated['order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        $message = $category->parent_id
            ? "Sub-kategori '{$category->name}' berhasil ditambahkan ke kategori {$category->parent?->name}!"
            : "Kategori utama '{$category->name}' berhasil dibuat!";

        $redirectParentId = $category->parent_id ?? $category->id;

        return redirect()
            ->route('admin.categories.index', ['parent_id' => $redirectParentId])
            ->with('success', $message);
    }

    /**
     * Show the form for editing the specified category or sub-category.
     */
    public function edit(TourCategory $category): View
    {
        $parentCategories = TourCategory::parents()
            ->where('id', '!=', $category->id)
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Update the specified category or sub-category in storage.
     */
    public function update(Request $request, TourCategory $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'parent_id' => [
                'nullable',
                'exists:tour_categories,id',
                Rule::notIn([$category->id]),
            ],
            'slug' => [
                'nullable',
                'string',
                'max:120',
                Rule::unique('tour_categories', 'slug')->ignore($category->id),
            ],
            'icon' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:500'],
            'color' => ['nullable', 'string', 'max:30'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $slug = ! empty($validated['slug'])
            ? Str::slug($validated['slug'])
            : Str::slug($validated['name']);

        // Ensure unique slug
        $originalSlug = $slug;
        $counter = 1;
        while (TourCategory::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        $category->update([
            'parent_id' => ($validated['parent_id'] ?? null) ?: null,
            'name' => $validated['name'],
            'slug' => $slug,
            'icon' => $validated['icon'] ?? '🏷️',
            'description' => $validated['description'] ?? null,
            'color' => $validated['color'] ?? 'sky',
            'order' => $validated['order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        $redirectParentId = $category->parent_id ?? $category->id;

        return redirect()
            ->route('admin.categories.index', ['parent_id' => $redirectParentId])
            ->with('success', "Kategori '{$category->name}' berhasil diperbarui!");
    }

    /**
     * Remove the specified category or sub-category from storage.
     */
    public function destroy(TourCategory $category): RedirectResponse
    {
        $name = $category->name;
        $parentId = $category->parent_id;

        // If it's a parent category with subcategories, notify or reassign
        if ($category->children()->count() > 0) {
            // Delete or detach children
            $category->children()->update(['parent_id' => null]);
        }

        // Detach tours
        $category->tours()->update(['category_id' => null]);

        $category->delete();

        $redirectParams = $parentId ? ['parent_id' => $parentId] : [];

        return redirect()
            ->route('admin.categories.index', $redirectParams)
            ->with('success', "Kategori '{$name}' berhasil dihapus!");
    }

    /**
     * Toggle active status of a category.
     */
    public function toggleActive(TourCategory $category): RedirectResponse
    {
        $category->update([
            'is_active' => ! $category->is_active,
        ]);

        $status = $category->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Status kategori '{$category->name}' berhasil {$status}!");
    }
}
