@extends('layouts.web')

@section('title', 'Travel Guide & Tips Liburan Indonesia – ALFITO')
@section('meta_description', 'Kumpulan panduan wisata, tips liburan, rekomendasi outfit bromo, dan waktu terbaik sailing Labuan Bajo.')

@section('content')
    <div class="bg-navy-900 border-b border-slate-800 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <span class="text-sky-400 text-xs font-bold uppercase tracking-widest block mb-2">Inspirasi & Edukasi</span>
            <h1 class="text-3xl sm:text-4xl font-black text-white">Travel Guide & Tips</h1>
            <p class="text-slate-400 text-sm mt-2">Dapatkan informasi terlengkap sebelum memulai petualangan Anda di Indonesia.</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <!-- Category Filter & Search -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-10">
            <!-- Categories -->
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('blog.index') }}" 
                   class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ !request('category') ? 'bg-sky-500 text-navy-950' : 'bg-navy-800 text-slate-300 hover:text-white' }}">
                    Semua Kategori
                </a>
                @foreach($categories as $key => $name)
                    <a href="{{ route('blog.index', ['category' => $key]) }}" 
                       class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ request('category') === $key ? 'bg-sky-500 text-navy-950' : 'bg-navy-800 text-slate-300 hover:text-white' }}">
                        {{ $name }}
                    </a>
                @endforeach
            </div>

            <!-- Search -->
            <form action="{{ route('blog.index') }}" method="GET" class="w-full sm:w-72">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari artikel..." 
                       class="glass-input w-full px-4 py-2 rounded-xl text-xs">
            </form>
        </div>

        <!-- Articles Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($posts as $post)
                <div class="glass-card rounded-3xl overflow-hidden flex flex-col group">
                    <div class="relative h-52 overflow-hidden">
                        <img src="{{ $post->thumbnail }}" alt="{{ $post->title }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        <div class="absolute top-4 left-4 bg-navy-950/80 backdrop-blur-md px-3 py-1 rounded-md text-[10px] font-bold text-sky-400 uppercase tracking-wide">
                            {{ $post->category }}
                        </div>
                    </div>

                    <div class="p-6 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-2 text-xs text-slate-400 mb-2">
                                <span>{{ $post->published_at?->format('d M Y') ?? 'Baru saja' }}</span>
                                <span>&bull;</span>
                                <span>👁️ {{ $post->view_count }} views</span>
                            </div>

                            <h3 class="text-lg font-bold text-white group-hover:text-sky-400 transition leading-snug mb-2">
                                <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                            </h3>

                            <p class="text-xs text-slate-300 line-clamp-3 leading-relaxed">
                                {{ $post->excerpt }}
                            </p>
                        </div>

                        <div class="mt-6 pt-4 border-t border-slate-800 flex items-center justify-between">
                            <span class="text-xs text-slate-400 font-medium">Oleh: {{ $post->author->name ?? 'Admin' }}</span>
                            <a href="{{ route('blog.show', $post->slug) }}" class="text-xs font-bold text-sky-400 hover:text-sky-300">
                                Baca Artikel →
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-16 text-slate-400">
                    Tidak ada artikel ditemukan.
                </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $posts->links() }}
        </div>
    </div>
@endsection
