@extends('layouts.web')

@section('title', 'Katalog Paket Wisata Indonesia – Open Trip & Private Trip')
@section('meta_description', 'Temukan berbagai pilihan paket wisata open trip dan private trip ke destinasi terfavorit di Indonesia seperti Bromo, Labuan Bajo, Raja Ampat, dan Nusa Penida.')

@section('content')
    <!-- Header Banner -->
    <div class="bg-navy-900 border-b border-slate-800 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <span class="text-sky-400 text-xs font-bold uppercase tracking-widest block mb-2">Eksplorasi Katalog</span>
            <h1 class="text-3xl sm:text-4xl font-black text-white">Semua Paket Wisata</h1>
            <p class="text-slate-400 text-sm mt-2">Pilih paket perjalanan impian Anda dengan fasilitas terlengkap dan harga terbaik.</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <!-- Quick Category Navigation Pills -->
        <div class="flex items-center gap-2 overflow-x-auto pb-3 mb-6 no-scrollbar">
            <a href="{{ route('tours.index') }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 whitespace-nowrap {{ !request('category') ? 'bg-sky-500 text-navy-950 shadow-md shadow-sky-500/20' : 'bg-navy-900/90 text-slate-300 hover:bg-navy-800 border border-slate-700/60' }}">
                <span>🌐 Semua Kategori</span>
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('tours.index', array_merge(request()->except('category', 'page'), ['category' => $cat->slug])) }}"
                   class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 whitespace-nowrap {{ request('category') == $cat->slug ? 'bg-sky-500 text-navy-950 shadow-md shadow-sky-500/20' : 'bg-navy-900/90 text-slate-300 hover:bg-navy-800 border border-slate-700/60' }}">
                    <span>{{ $cat->icon ?? '🏷️' }}</span>
                    <span>{{ $cat->name }}</span>
                </a>
                @foreach($cat->children as $sub)
                    <a href="{{ route('tours.index', array_merge(request()->except('category', 'page'), ['category' => $sub->slug])) }}"
                       class="px-3 py-1.5 rounded-lg text-[11px] font-semibold transition flex items-center gap-1 whitespace-nowrap {{ request('category') == $sub->slug ? 'bg-sky-400 text-navy-950 font-bold' : 'bg-navy-950/80 text-slate-400 hover:text-slate-200 border border-slate-800' }}">
                        <span>{{ $sub->icon ?? '↳' }}</span>
                        <span>{{ $sub->name }}</span>
                    </a>
                @endforeach
            @endforeach
        </div>

        <!-- Filter Bar -->
        <form action="{{ route('tours.index') }}" method="GET" class="glass-card p-4 rounded-2xl mb-10">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3 items-center">
                <!-- Search -->
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Cari Nama Paket</label>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Ketik nama paket..." 
                           class="glass-input w-full px-3 py-2 rounded-xl text-xs">
                </div>

                <!-- Kategori / Sub-Kategori -->
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Kategori</label>
                    <select name="category" class="glass-input w-full px-3 py-2 rounded-xl text-xs cursor-pointer">
                        <option value="" class="bg-navy-900">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <optgroup label="{{ $cat->icon ?? '📁' }} {{ $cat->name }}" class="bg-navy-900 text-sky-400 font-bold">
                                <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }} class="bg-navy-900 text-white font-normal">
                                    ★ Semua {{ $cat->name }}
                                </option>
                                @foreach($cat->children as $sub)
                                    <option value="{{ $sub->slug }}" {{ request('category') == $sub->slug ? 'selected' : '' }} class="bg-navy-900 text-white font-normal">
                                        &nbsp;&nbsp;↳ {{ $sub->icon ?? '🏷️' }} {{ $sub->name }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>

                <!-- Destinasi -->
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Destinasi</label>
                    <select name="destination" class="glass-input w-full px-3 py-2 rounded-xl text-xs cursor-pointer">
                        <option value="" class="bg-navy-900">Semua Destinasi</option>
                        @foreach($destinations as $dest)
                            <option value="{{ $dest->slug }}" {{ request('destination') == $dest->slug ? 'selected' : '' }} class="bg-navy-900">
                                {{ $dest->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tipe Trip -->
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Tipe Perjalanan</label>
                    <select name="type" class="glass-input w-full px-3 py-2 rounded-xl text-xs cursor-pointer">
                        <option value="" class="bg-navy-900">Semua Tipe</option>
                        <option value="open_trip" {{ request('type') == 'open_trip' ? 'selected' : '' }} class="bg-navy-900">Open Trip (Gabungan)</option>
                        <option value="private_trip" {{ request('type') == 'private_trip' ? 'selected' : '' }} class="bg-navy-900">Private Trip (Eksklusif)</option>
                    </select>
                </div>

                <!-- Urutkan -->
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Urutkan</label>
                    <select name="sort" class="glass-input w-full px-3 py-2 rounded-xl text-xs cursor-pointer">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }} class="bg-navy-900">Terbaru</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }} class="bg-navy-900">Harga Terendah</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }} class="bg-navy-900">Harga Tertinggi</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }} class="bg-navy-900">Paling Populer</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="pt-1 sm:pt-4">
                    <button type="submit" class="w-full py-2.5 rounded-xl bg-sky-500 hover:bg-sky-400 text-navy-950 font-bold text-xs transition shadow-md shadow-sky-500/20">
                        Terapkan Filter
                    </button>
                </div>
            </div>
        </form>

        <!-- Tours Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($tours as $tour)
                <div class="glass-card rounded-2xl overflow-hidden flex flex-col group">
                    <div class="relative h-56 overflow-hidden">
                        <img src="{{ $tour->thumbnail }}" alt="{{ $tour->title }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-navy-950 via-transparent to-black/30"></div>

                        <div class="absolute top-4 left-4">
                            @if($tour->type === 'open_trip')
                                <span class="px-3 py-1 rounded-full bg-sky-500 text-navy-950 font-bold text-xs shadow-md">
                                    OPEN TRIP
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-amber-500 text-navy-950 font-bold text-xs shadow-md">
                                    PRIVATE TRIP
                                </span>
                            @endif
                        </div>

                        <div class="absolute top-4 right-4 bg-navy-950/80 backdrop-blur-md px-2.5 py-1 rounded-lg text-xs font-medium text-slate-200 border border-slate-700/50">
                            ⏱️ {{ $tour->duration_days }}H{{ $tour->duration_nights ? $tour->duration_nights.'M' : '' }}
                        </div>

                        <div class="absolute bottom-3 left-4 text-xs font-medium text-slate-300 flex items-center gap-1.5">
                            <span class="text-sky-400">📍</span>
                            <span>{{ $tour->destination->name }}</span>
                        </div>

                        @if($tour->category)
                            <div class="absolute bottom-3 right-4 bg-navy-950/85 backdrop-blur-md px-2.5 py-1 rounded-lg text-[10px] font-bold text-sky-300 border border-sky-400/30 flex items-center gap-1 shadow-sm">
                                <span>{{ $tour->category->icon ?? '🏷️' }}</span>
                                <span>{{ $tour->category->name }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="p-5 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-1 text-gold-400 text-xs">
                                    <span>★</span>
                                    <span class="font-bold text-white text-sm">{{ $tour->average_rating > 0 ? $tour->average_rating : '5.0' }}</span>
                                    <span class="text-slate-400">({{ $tour->reviews->count() ?: 1 }})</span>
                                </div>
                                <span class="text-xs text-slate-400">Min. {{ $tour->min_pax }} Pax</span>
                            </div>

                            <h3 class="text-base sm:text-lg font-bold text-white group-hover:text-sky-400 transition leading-snug">
                                <a href="{{ route('tours.show', $tour->slug) }}">{{ $tour->title }}</a>
                            </h3>

                            <p class="mt-2 text-xs text-slate-400 line-clamp-2 leading-relaxed">
                                {{ $tour->description }}
                            </p>
                        </div>

                        <div class="mt-6 pt-4 border-t border-slate-800 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] text-slate-400 block">Mulai dari</span>
                                <span class="text-lg font-extrabold text-sky-400">
                                    Rp {{ number_format($tour->price, 0, ',', '.') }}
                                </span>
                                <span class="text-[10px] text-slate-400 block">/ orang</span>
                            </div>

                            <a href="{{ route('tours.show', $tour->slug) }}" 
                               class="px-3.5 py-2 rounded-xl bg-navy-800 hover:bg-sky-500 hover:text-navy-950 text-sky-400 text-xs font-bold transition border border-sky-500/30">
                                Detail & Pesan
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-16 glass-card-static rounded-2xl">
                    <p class="text-slate-400 text-base">Tidak ada paket wisata yang cocok dengan kriteria pencarian Anda.</p>
                    <a href="{{ route('tours.index') }}" class="mt-4 inline-block px-4 py-2 rounded-xl bg-sky-500 text-navy-950 font-bold text-xs">
                        Reset Filter
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-12">
            {{ $tours->links() }}
        </div>
    </div>
@endsection
