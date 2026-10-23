@extends('layouts.web')

@section('title', $post->title . ' – ALFITO Blog')
@section('meta_description', Str::limit(strip_tags($post->excerpt ?? $post->content), 150))
@section('og_image', $post->thumbnail)

@section('content')
    <!-- Breadcrumb -->
    <div class="bg-navy-900/60 border-b border-slate-800 py-3">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 text-xs text-slate-400 flex items-center gap-2">
            <a href="{{ route('home') }}" class="hover:text-sky-400">Beranda</a>
            <span>/</span>
            <a href="{{ route('blog.index') }}" class="hover:text-sky-400">Travel Guide</a>
            <span>/</span>
            <span class="text-slate-200 truncate max-w-xs">{{ $post->title }}</span>
        </div>
    </div>

    <article class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
        <!-- Post Header -->
        <div class="space-y-4 mb-8 text-center sm:text-left">
            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                <span class="px-3 py-1 rounded-full bg-sky-500/20 text-sky-400 text-xs font-bold uppercase tracking-wide border border-sky-500/30">
                    {{ $post->category }}
                </span>
                @if($post->destination)
                    <a href="{{ route('destinations.show', $post->destination->slug) }}" class="text-xs text-slate-300 hover:text-sky-400 font-medium">
                        📍 {{ $post->destination->name }}
                    </a>
                @endif
            </div>

            <h1 class="text-3xl sm:text-5xl font-black text-white leading-tight">
                {{ $post->title }}
            </h1>

            <div class="flex items-center justify-center sm:justify-start gap-4 text-xs text-slate-400 pt-2 border-t border-slate-800">
                <span>Ditulis oleh: <strong class="text-white">{{ $post->author->name ?? 'Tim Editorial' }}</strong></span>
                <span>&bull;</span>
                <span>{{ $post->published_at?->format('d F Y') }}</span>
                <span>&bull;</span>
                <span>👁️ {{ $post->view_count }} pembaca</span>
            </div>
        </div>

        <!-- Featured Image -->
        <div class="h-72 sm:h-[450px] rounded-3xl overflow-hidden mb-10 shadow-2xl border border-slate-800">
            <img src="{{ $post->thumbnail }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
        </div>

        <!-- Content Body -->
        <div class="glass-card-static p-6 sm:p-10 rounded-3xl border border-slate-800 space-y-6 text-sm sm:text-base text-slate-200 leading-relaxed font-light">
            {!! nl2br(e($post->content)) !!}
        </div>

        <!-- Tags -->
        @if(!empty($post->tags) && is_array($post->tags))
            <div class="mt-8 flex flex-wrap items-center gap-2">
                <span class="text-xs text-slate-400 font-semibold mr-2">Tags:</span>
                @foreach($post->tags as $tag)
                    <span class="px-3 py-1 rounded-lg bg-navy-800 text-sky-400 text-xs border border-slate-700/60 font-medium">
                        #{{ $tag }}
                    </span>
                @endforeach
            </div>
        @endif

        <!-- Related Tours Banner -->
        @if($relatedTours->isNotEmpty())
            <div class="mt-16 pt-10 border-t border-slate-800">
                <h3 class="text-2xl font-bold text-white mb-6">Paket Wisata Terkait</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($relatedTours as $rel)
                        <div class="glass-card rounded-2xl overflow-hidden flex flex-col group">
                            <div class="relative h-40 overflow-hidden">
                                <img src="{{ $rel->thumbnail }}" alt="{{ $rel->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            </div>
                            <div class="p-4 flex-1 flex flex-col justify-between">
                                <h4 class="text-xs font-bold text-white group-hover:text-sky-400 transition">
                                    <a href="{{ route('tours.show', $rel->slug) }}">{{ $rel->title }}</a>
                                </h4>
                                <div class="mt-3 pt-2 border-t border-slate-800 flex items-center justify-between text-xs">
                                    <span class="font-extrabold text-sky-400">Rp {{ number_format($rel->price, 0, ',', '.') }}</span>
                                    <a href="{{ route('tours.show', $rel->slug) }}" class="text-slate-300 hover:text-sky-400 font-semibold">Lihat →</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </article>
@endsection
