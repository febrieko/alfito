@extends('layouts.web')

@section('title', 'Kelola Blog & Panduan Wisata – Panel Admin ALFITO')

@section('content')
<div class="py-12 bg-navy-950 min-h-screen text-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-2">
                    <a href="{{ route('home') }}" class="hover:text-sky-400 transition">Beranda</a>
                    <span>/</span>
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-sky-400 transition">Admin</a>
                    <span>/</span>
                    <span class="text-sky-400 font-bold">Kelola Blog</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight flex items-center gap-3">
                    <span>Artikel Blog & Travel Guide</span>
                    <span class="px-2.5 py-1 text-xs rounded-full bg-sky-500/20 text-sky-400 font-mono border border-sky-500/30">
                        Admin Only
                    </span>
                </h1>
                <p class="text-sm text-slate-400 mt-1">
                    Tulis dan publikasikan tips wisata, itinerary, kuliner, dan panduan perjalanan untuk meningkatkan SEO web.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('blog.index') }}" target="_blank"
                   class="px-4 py-2.5 rounded-xl bg-navy-800 hover:bg-navy-700 text-slate-200 text-xs font-semibold border border-slate-700/70 transition">
                    Lihat Blog di Web ↗
                </a>
                <a href="{{ route('admin.posts.create') }}" 
                   class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-sky-500 to-sky-400 hover:from-sky-400 hover:to-sky-300 text-navy-950 font-bold text-xs shadow-lg shadow-sky-500/25 transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>+ Tulis Artikel Baru</span>
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 text-sm flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-lg">✓</span>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-xs hover:text-white">✕</button>
            </div>
        @endif

        @include('admin.partials.nav')

        <!-- Posts Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($posts as $post)
                <div class="glass-card rounded-2xl border border-slate-700/60 shadow-xl overflow-hidden flex flex-col group">
                    <div class="relative h-44 overflow-hidden">
                        <img src="{{ $post->thumbnail }}" alt="{{ $post->title }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-navy-950 via-transparent to-black/30"></div>

                        <div class="absolute top-3 left-3">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-sky-500/80 backdrop-blur-md text-navy-950 uppercase">
                                {{ $post->category }}
                            </span>
                        </div>

                        <div class="absolute top-3 right-3">
                            <form action="{{ route('admin.posts.toggle-publish', $post) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="px-2.5 py-0.5 rounded-full text-[10px] font-bold backdrop-blur-md transition {{ $post->is_published ? 'bg-emerald-500/80 text-navy-950' : 'bg-slate-800/90 text-slate-300 border border-slate-700' }}">
                                    {{ $post->is_published ? '● Terbit' : '○ Draft' }}
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
                        <div>
                            <div class="text-[10px] text-slate-400 mb-1">
                                Oleh {{ $post->author->name }} • {{ $post->created_at->format('d M Y') }}
                            </div>
                            <h3 class="text-sm font-bold text-white group-hover:text-sky-400 transition line-clamp-2">
                                <a href="{{ route('blog.show', $post->slug) }}" target="_blank">
                                    {{ $post->title }}
                                </a>
                            </h3>
                            <p class="text-xs text-slate-400 line-clamp-2 mt-1.5 leading-relaxed">
                                {{ $post->excerpt }}
                            </p>
                        </div>

                        <div class="pt-3 border-t border-slate-800 flex items-center justify-between">
                            <span class="text-[10px] text-slate-400">👁️ {{ $post->view_count }} tayang</span>
                            <div class="flex items-center gap-1.5">
                                <a href="{{ route('admin.posts.edit', $post) }}"
                                   class="px-2.5 py-1.5 rounded-lg bg-navy-800 hover:bg-navy-700 text-sky-400 border border-slate-700 font-bold text-xs transition">
                                    Edit
                                </a>
                                <form action="{{ route('admin.posts.destroy', $post) }}" method="POST"
                                      onsubmit="return confirm('Hapus artikel \'{{ $post->title }}\'?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 transition text-xs">
                                        ✕
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 glass-card rounded-2xl border border-slate-700 text-slate-400">
                    Belum ada artikel blog.
                </div>
            @endforelse
        </div>

        @if($posts->hasPages())
            <div class="pt-4">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
