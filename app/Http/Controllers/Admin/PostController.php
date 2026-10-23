<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PostController extends Controller
{
    /**
     * Display a listing of blog posts.
     */
    public function index(Request $request): View
    {
        $search = $request->query('q');

        $query = Post::with(['author', 'destination']);

        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        $posts = $query->latest()->paginate(12)->withQueryString();

        return view('admin.posts.index', compact('posts', 'search'));
    }

    /**
     * Show the form for creating a new post.
     */
    public function create(): View
    {
        $destinations = Destination::orderBy('name')->get();

        return view('admin.posts.create', compact('destinations'));
    }

    /**
     * Store a newly created post in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:posts,slug'],
            'destination_id' => ['nullable', 'exists:destinations,id'],
            'category' => ['required', 'string', 'max:50'],
            'thumbnail' => ['required', 'string', 'max:1000'],
            'excerpt' => ['required', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $slug = ! empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['title']);
        $originalSlug = $slug;
        $counter = 1;
        while (Post::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        $isPublished = $request->boolean('is_published', true);

        Post::create([
            'user_id' => auth()->id(),
            'destination_id' => ($validated['destination_id'] ?? null) ?: null,
            'title' => $validated['title'],
            'slug' => $slug,
            'category' => $validated['category'],
            'thumbnail' => $validated['thumbnail'],
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'is_published' => $isPublished,
            'published_at' => $isPublished ? now() : null,
        ]);

        return redirect()->route('admin.posts.index')->with('success', 'Artikel blog berhasil diterbitkan!');
    }

    /**
     * Show the form for editing the specified post.
     */
    public function edit(Post $post): View
    {
        $destinations = Destination::orderBy('name')->get();

        return view('admin.posts.edit', compact('post', 'destinations'));
    }

    /**
     * Update the specified post in storage.
     */
    public function update(Request $request, Post $post): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('posts', 'slug')->ignore($post->id)],
            'destination_id' => ['nullable', 'exists:destinations,id'],
            'category' => ['required', 'string', 'max:50'],
            'thumbnail' => ['required', 'string', 'max:1000'],
            'excerpt' => ['required', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $slug = ! empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['title']);
        $originalSlug = $slug;
        $counter = 1;
        while (Post::where('slug', $slug)->where('id', '!=', $post->id)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        $isPublished = $request->boolean('is_published');

        $post->update([
            'destination_id' => ($validated['destination_id'] ?? null) ?: null,
            'title' => $validated['title'],
            'slug' => $slug,
            'category' => $validated['category'],
            'thumbnail' => $validated['thumbnail'],
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'is_published' => $isPublished,
            'published_at' => $isPublished && ! $post->published_at ? now() : $post->published_at,
        ]);

        return redirect()->route('admin.posts.index')->with('success', "Artikel '{$post->title}' berhasil diperbarui!");
    }

    /**
     * Remove the specified post from storage.
     */
    public function destroy(Post $post): RedirectResponse
    {
        $title = $post->title;
        $post->delete();

        return redirect()->route('admin.posts.index')->with('success', "Artikel '{$title}' berhasil dihapus.");
    }

    /**
     * Toggle publish status.
     */
    public function togglePublish(Post $post): RedirectResponse
    {
        $newStatus = ! $post->is_published;
        $post->update([
            'is_published' => $newStatus,
            'published_at' => $newStatus && ! $post->published_at ? now() : $post->published_at,
        ]);

        $statusText = $newStatus ? 'diterbitkan' : 'disimpan sebagai draft';

        return back()->with('success', "Status artikel '{$post->title}' berhasil {$statusText}.");
    }
}
