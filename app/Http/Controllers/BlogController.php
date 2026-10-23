<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    /**
     * Display a listing of blog articles and travel guides.
     */
    public function index(Request $request): View
    {
        $query = Post::with(['author', 'destination'])
            ->where('is_published', true);

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('q')) {
            $keyword = $request->string('q');
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('excerpt', 'like', "%{$keyword}%")
                    ->orWhere('content', 'like', "%{$keyword}%");
            });
        }

        $posts = $query->latest('published_at')->paginate(6)->withQueryString();
        $categories = ['guide' => 'Panduan Wisata', 'tips' => 'Tips Liburan', 'destination' => 'Eksplorasi Destinasi'];

        return view('pages.blog.index', compact('posts', 'categories'));
    }

    /**
     * Display the specified blog post.
     */
    public function show(string $slug): View
    {
        $post = Post::with(['author', 'destination'])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $post->incrementViews();

        $relatedTours = Tour::where('is_active', true)
            ->when($post->destination_id, function ($q) use ($post) {
                $q->where('destination_id', $post->destination_id);
            })
            ->take(3)
            ->get();

        $recentPosts = Post::where('is_published', true)
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->take(4)
            ->get();

        return view('pages.blog.show', compact('post', 'relatedTours', 'recentPosts'));
    }
}
