<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class HeroSlideController extends Controller
{
    /**
     * Display a listing of the hero slides.
     */
    public function index(): View
    {
        $slides = HeroSlide::ordered()->get();

        return view('admin.hero-slides.index', compact('slides'));
    }

    /**
     * Show the form for creating a new hero slide.
     */
    public function create(): View
    {
        $nextOrder = (HeroSlide::max('order') ?? 0) + 1;

        return view('admin.hero-slides.create', compact('nextOrder'));
    }

    /**
     * Store a newly created hero slide in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:500'],
            'badge' => ['nullable', 'string', 'max:100'],
            'image_type' => ['required', 'in:url,file'],
            'image_url' => ['required_if:image_type,url', 'nullable', 'url', 'max:1000'],
            'image_file' => ['required_if:image_type,file', 'nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'cta_text' => ['nullable', 'string', 'max:100'],
            'cta_link' => ['nullable', 'string', 'max:255'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $imagePath = '';
        if ($validated['image_type'] === 'file' && $request->hasFile('image_file')) {
            $imagePath = $request->file('image_file')->store('hero-slides', 'public');
        } else {
            $imagePath = $validated['image_url'] ?? '';
        }

        HeroSlide::create([
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? null,
            'badge' => $validated['badge'] ?? null,
            'image' => $imagePath,
            'cta_text' => $validated['cta_text'] ?? null,
            'cta_link' => $validated['cta_link'] ?? null,
            'order' => $validated['order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('admin.hero-slides.index')
            ->with('success', 'Slide hero baru berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified hero slide.
     */
    public function edit(HeroSlide $heroSlide): View
    {
        return view('admin.hero-slides.edit', compact('heroSlide'));
    }

    /**
     * Update the specified hero slide in storage.
     */
    public function update(Request $request, HeroSlide $heroSlide): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:500'],
            'badge' => ['nullable', 'string', 'max:100'],
            'image_type' => ['required', 'in:url,file,keep'],
            'image_url' => ['required_if:image_type,url', 'nullable', 'url', 'max:1000'],
            'image_file' => ['required_if:image_type,file', 'nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'cta_text' => ['nullable', 'string', 'max:100'],
            'cta_link' => ['nullable', 'string', 'max:255'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $imagePath = $heroSlide->image;

        if ($validated['image_type'] === 'file' && $request->hasFile('image_file')) {
            // Delete old file if stored locally
            if (! Str::startsWith($heroSlide->image, ['http://', 'https://', '//'])) {
                Storage::disk('public')->delete($heroSlide->image);
            }
            $imagePath = $request->file('image_file')->store('hero-slides', 'public');
        } elseif ($validated['image_type'] === 'url') {
            $imagePath = $validated['image_url'];
        }

        $heroSlide->update([
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? null,
            'badge' => $validated['badge'] ?? null,
            'image' => $imagePath,
            'cta_text' => $validated['cta_text'] ?? null,
            'cta_link' => $validated['cta_link'] ?? null,
            'order' => $validated['order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.hero-slides.index')
            ->with('success', 'Slide hero berhasil diperbarui.');
    }

    /**
     * Remove the specified hero slide from storage.
     */
    public function destroy(HeroSlide $heroSlide): RedirectResponse
    {
        if (! Str::startsWith($heroSlide->image, ['http://', 'https://', '//'])) {
            Storage::disk('public')->delete($heroSlide->image);
        }

        $heroSlide->delete();

        return redirect()
            ->route('admin.hero-slides.index')
            ->with('success', 'Slide hero berhasil dihapus.');
    }

    /**
     * Toggle the active status of the specified hero slide.
     */
    public function toggleActive(HeroSlide $heroSlide): RedirectResponse
    {
        $heroSlide->update([
            'is_active' => ! $heroSlide->is_active,
        ]);

        $status = $heroSlide->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()
            ->route('admin.hero-slides.index')
            ->with('success', "Slide \"{$heroSlide->title}\" berhasil {$status}.");
    }
}
