@extends('layouts.web')

@section('title', 'ALFITO – Tour & Travel Paket Wisata Indonesia Terpercaya')

@section('content')
    <!-- ── 1. Hero Section (Dynamic Slider) ───────────────────────── -->
    <section class="relative min-h-[640px] lg:min-h-[740px] flex items-center justify-center overflow-hidden py-16 lg:py-24"
             x-data="{
                 activeSlide: 0,
                 slidesCount: {{ $heroSlides->isNotEmpty() ? $heroSlides->count() : 1 }},
                 timer: null,
                 init() {
                     this.startAutoplay();
                 },
                 startAutoplay() {
                     if (this.slidesCount > 1) {
                         this.stopAutoplay();
                         this.timer = setInterval(() => {
                             this.activeSlide = (this.activeSlide + 1) % this.slidesCount;
                         }, 6000);
                     }
                 },
                 stopAutoplay() {
                     if (this.timer) {
                         clearInterval(this.timer);
                         this.timer = null;
                     }
                 }
             }"
             @mouseenter="stopAutoplay()"
             @mouseleave="startAutoplay()">

        <!-- Background Slides with Smooth Crossfade -->
        <div class="absolute inset-0 -z-10 overflow-hidden bg-navy-950">
            @if($heroSlides->isNotEmpty())
                @foreach($heroSlides as $idx => $slide)
                    <div class="absolute inset-0 transition-opacity duration-1000 ease-in-out pointer-events-none"
                         :class="activeSlide === {{ $idx }} ? 'opacity-100 z-10' : 'opacity-0 z-0'">
                        <img src="{{ $slide->image_url }}" 
                             alt="{{ $slide->title }}" 
                             class="w-full h-full object-cover object-center transform scale-105">
                        <div class="absolute inset-0 bg-gradient-to-b from-navy-950/75 via-navy-900/75 to-navy-900"></div>
                        <div class="absolute inset-0 bg-radial-gradient from-transparent via-navy-950/35 to-navy-900"></div>
                    </div>
                @endforeach
            @else
                <!-- Fallback single slide -->
                <div class="absolute inset-0">
                    <img src="https://images.unsplash.com/photo-1544644181-1484b3fdfc62?auto=format&fit=crop&w=2000&q=85" 
                         alt="Nusantara Indonesia" 
                         class="w-full h-full object-cover object-center scale-105">
                    <div class="absolute inset-0 bg-gradient-to-b from-navy-950/70 via-navy-900/75 to-navy-900"></div>
                </div>
            @endif
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 w-full">
            <!-- Admin Quick Link Pill -->
            @auth
                @if(auth()->user()->isAdmin())
                    <div class="mb-4">
                        <a href="{{ route('admin.hero-slides.index') }}" 
                           class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-500/20 hover:bg-amber-500/30 border border-amber-400/40 text-amber-300 text-xs font-semibold backdrop-blur-md transition shadow-md">
                            <span>⚙ Kelola Slide Hero (Admin)</span>
                            <span class="text-[10px] bg-amber-400 text-navy-950 font-bold px-1.5 rounded">Panel</span>
                        </a>
                    </div>
                @endif
            @endauth

            <!-- Slide Content (Stacked in CSS Grid to guarantee zero layout shift) -->
            <div class="relative grid grid-cols-1 items-center justify-center max-w-4xl mx-auto w-full min-h-[200px] sm:min-h-[240px]">
                @if($heroSlides->isNotEmpty())
                    @foreach($heroSlides as $idx => $slide)
                        <div class="col-start-1 row-start-1 w-full flex flex-col items-center justify-center transition-all duration-1000 ease-in-out transform"
                             :class="activeSlide === {{ $idx }} ? 'opacity-100 translate-y-0 pointer-events-auto' : 'opacity-0 translate-y-3 pointer-events-none'">
                            
                            <!-- Badge -->
                            @if($slide->badge)
                                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-sky-500/15 border border-sky-500/30 text-sky-400 text-xs sm:text-sm font-semibold mb-5 backdrop-blur-md">
                                    <span class="w-2 h-2 rounded-full bg-sky-400 animate-ping"></span>
                                    <span>{{ $slide->badge }}</span>
                                </div>
                            @endif

                            <!-- Headline -->
                            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black text-white tracking-tight leading-[1.15] max-w-4xl mx-auto drop-shadow-xl">
                                {{ $slide->title }}
                            </h1>

                            @if($slide->subtitle)
                                <p class="mt-4 sm:mt-5 text-sm sm:text-base lg:text-lg text-slate-300 max-w-2xl mx-auto font-light leading-relaxed drop-shadow">
                                    {{ $slide->subtitle }}
                                </p>
                            @endif

                            @if($slide->cta_text && $slide->cta_link)
                                <div class="mt-4">
                                    <a href="{{ $slide->cta_link }}" 
                                       class="inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-sky-500/20 hover:bg-sky-500 text-sky-300 hover:text-navy-950 border border-sky-400/40 text-xs sm:text-sm font-bold backdrop-blur-md transition">
                                        <span>{{ $slide->cta_text }}</span>
                                        <span>→</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class="col-start-1 row-start-1 w-full flex flex-col items-center justify-center">
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-sky-500/15 border border-sky-500/30 text-sky-400 text-xs sm:text-sm font-semibold mb-6 backdrop-blur-md">
                            <span class="w-2 h-2 rounded-full bg-sky-400 animate-ping"></span>
                            <span>Tour Operator Resmi & Berlisensi di Indonesia</span>
                        </div>
                        <h1 class="text-4xl sm:text-5xl lg:text-7xl font-black text-white tracking-tight leading-[1.1] max-w-4xl mx-auto">
                            Eksplorasi Keajaiban <br>
                            <span class="text-gradient-sky">Nusantara Indonesia</span>
                        </h1>
                        <p class="mt-6 text-base sm:text-lg lg:text-xl text-slate-300 max-w-2xl mx-auto font-light leading-relaxed">
                            Nikmati perjalanan tak terlupakan ke Bromo, Sailing Labuan Bajo, Raja Ampat & Bali dengan jaminan <span class="text-white font-medium">100% Pasti Berangkat</span> dan pelayanan kelas utama.
                        </p>
                    </div>
                @endif
            </div>

            <!-- Search Filter Card (Hero Search) -->
            <div class="mt-8 sm:mt-10 max-w-4xl mx-auto">
                <form action="{{ route('tours.index') }}" method="GET" class="glass-card p-4 sm:p-5 rounded-2xl sm:rounded-3xl shadow-2xl">
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 sm:gap-4 items-center">
                        <!-- Input Cari Kata Kunci -->
                        <div class="sm:col-span-5 text-left">
                            <label class="block text-xs font-semibold text-slate-400 mb-1 pl-1">Cari Paket / Wisata</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    🔍
                                </span>
                                <input type="text" name="q" placeholder="Contoh: Bromo, Sailing, Sunrise..." 
                                       class="glass-input w-full pl-10 pr-4 py-3 rounded-xl text-sm placeholder-slate-400 focus:outline-none">
                            </div>
                        </div>

                        <!-- Dropdown Destinasi -->
                        <div class="sm:col-span-4 text-left">
                            <label class="block text-xs font-semibold text-slate-400 mb-1 pl-1">Destinasi</label>
                            <div class="relative">
                                <select name="destination" class="glass-input w-full px-3.5 py-3 rounded-xl text-sm focus:outline-none appearance-none cursor-pointer">
                                    <option value="" class="bg-navy-900 text-white">Semua Destinasi</option>
                                    @foreach($allDestinations as $dest)
                                        <option value="{{ $dest->slug }}" class="bg-navy-900 text-white">{{ $dest->name }}</option>
                                    @endforeach
                                </select>
                                <span class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                                    ▼
                                </span>
                            </div>
                        </div>

                        <!-- Tombol Cari -->
                        <div class="sm:col-span-3 pt-1 sm:pt-5">
                            <button type="submit" class="w-full py-3.5 px-6 rounded-xl bg-gradient-to-r from-sky-400 to-blue-500 hover:from-sky-300 hover:to-blue-400 text-navy-950 font-bold text-sm shadow-lg shadow-sky-500/30 transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                <span>Cari Paket</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Trust Badges -->
                <div class="mt-8 flex flex-wrap justify-center items-center gap-6 sm:gap-10 text-xs sm:text-sm text-slate-300">
                    <div class="flex items-center gap-2">
                        <span class="text-sky-400 text-lg">★</span>
                        <span><strong>4.9 / 5.0</strong> dari 2.500+ Review</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-sky-400 text-lg">🛡️</span>
                        <span><strong>100%</strong> Garansi Berangkat</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-sky-400 text-lg">💳</span>
                        <span>Pembayaran Aman & Resmi</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ── 2. Featured Tours Section ────────────────────────────── -->
    <section class="py-16 sm:py-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
            <div>
                <span class="text-sky-400 text-xs font-bold uppercase tracking-widest block mb-2">Pilihan Terbaik</span>
                <h2 class="text-3xl sm:text-4xl font-black text-white">Paket Wisata Favorit</h2>
                <p class="text-slate-400 text-sm sm:text-base mt-2">Daftar perjalanan paling banyak dipesan dengan fasilitas terlengkap.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('tours.index') }}" class="text-sm font-semibold text-sky-400 hover:text-sky-300 flex items-center gap-1 group">
                    <span>Lihat Semua Paket ({{ \App\Models\Tour::where('is_active', true)->count() }})</span>
                    <span class="transform group-hover:translate-x-1 transition">→</span>
                </a>
            </div>
        </div>

        <!-- Tours Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($featuredTours as $tour)
                <div class="glass-card rounded-2xl overflow-hidden flex flex-col group">
                    <!-- Thumbnail with badges -->
                    <div class="relative h-56 sm:h-64 overflow-hidden">
                        <img src="{{ $tour->thumbnail }}" alt="{{ $tour->title }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-navy-950 via-transparent to-black/30"></div>

                        <!-- Type Badge -->
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

                        <!-- Duration Badge -->
                        <div class="absolute top-4 right-4 bg-navy-950/80 backdrop-blur-md px-2.5 py-1 rounded-lg text-xs font-medium text-slate-200 border border-slate-700/50">
                            ⏱️ {{ $tour->duration_days }}H{{ $tour->duration_nights ? $tour->duration_nights.'M' : '' }}
                        </div>

                        <!-- Destination Location -->
                        <div class="absolute bottom-3 left-4 text-xs font-medium text-slate-300 flex items-center gap-1.5">
                            <span class="text-sky-400">📍</span>
                            <span>{{ $tour->destination->name }}</span>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-5 sm:p-6 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-1 text-gold-400 text-xs">
                                    <span>★</span>
                                    <span class="font-bold text-white text-sm">{{ $tour->average_rating > 0 ? $tour->average_rating : '5.0' }}</span>
                                    <span class="text-slate-400">({{ $tour->reviews->count() ?: 1 }})</span>
                                </div>
                                <span class="text-xs text-slate-400">Min. {{ $tour->min_pax }} Pax</span>
                            </div>

                            <h3 class="text-lg font-bold text-white group-hover:text-sky-400 transition leading-snug">
                                <a href="{{ route('tours.show', $tour->slug) }}">{{ $tour->title }}</a>
                            </h3>

                            <p class="mt-2 text-xs text-slate-400 line-clamp-2 leading-relaxed">
                                {{ $tour->description }}
                            </p>
                        </div>

                        <!-- Pricing & CTA -->
                        <div class="mt-6 pt-4 border-t border-slate-800 flex items-center justify-between">
                            <div>
                                <span class="text-[11px] text-slate-400 block">Mulai dari</span>
                                <span class="text-lg sm:text-xl font-extrabold text-sky-400">
                                    Rp {{ number_format($tour->price, 0, ',', '.') }}
                                </span>
                                <span class="text-[10px] text-slate-400 block">/ orang</span>
                            </div>

                            <a href="{{ route('tours.show', $tour->slug) }}" 
                               class="px-4 py-2.5 rounded-xl bg-navy-800 hover:bg-sky-500 hover:text-navy-950 text-sky-400 text-xs font-bold transition border border-sky-500/30">
                                Detail Trip
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 text-slate-400">
                    Belum ada paket wisata yang ditambahkan.
                </div>
            @endforelse
        </div>
    </section>

    <!-- ── 3. Popular Destinations Section ──────────────────────── -->
    <section class="py-16 sm:py-24 bg-navy-950/40 border-y border-sky-500/15">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <span class="text-sky-400 text-xs font-bold uppercase tracking-widest block mb-2">Jelajahi Negeri</span>
                <h2 class="text-3xl sm:text-4xl font-black text-white">Destinasi Wisata Terpopuler</h2>
                <p class="text-slate-400 text-sm sm:text-base mt-2">Kumpulan tempat terindah dari Sabang sampai Merauke yang siap Anda jelajahi.</p>
            </div>

            <!-- Bento Destination Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($featuredDestinations as $index => $destination)
                    <a href="{{ route('destinations.show', $destination->slug) }}" 
                       class="relative group rounded-3xl overflow-hidden h-72 sm:h-80 block shadow-xl border border-slate-800 hover:border-sky-500/50 transition duration-300">
                        <img src="{{ $destination->thumbnail }}" alt="{{ $destination->name }}" 
                             class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-navy-950 via-navy-950/40 to-transparent"></div>

                        <div class="absolute inset-0 p-6 flex flex-col justify-end">
                            <span class="text-sky-400 text-xs font-semibold uppercase tracking-wider mb-1">
                                {{ $destination->region }}
                            </span>
                            <h3 class="text-xl sm:text-2xl font-black text-white group-hover:text-sky-400 transition">
                                {{ $destination->name }}
                            </h3>
                            <p class="text-xs text-slate-300 line-clamp-1 mt-1 font-light">
                                {{ $destination->description }}
                            </p>
                            <div class="mt-3 flex items-center justify-between text-xs text-slate-400">
                                <span class="bg-navy-950/80 px-2.5 py-1 rounded-full text-slate-200 font-medium">
                                    {{ $destination->tours_count }} Paket Wisata
                                </span>
                                <span class="text-sky-400 font-semibold group-hover:translate-x-1 transition">
                                    Jelajahi →
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ── 4. Why Choose Us (Mengapa ALFITO) ─────────────── -->
    <section class="py-16 sm:py-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-sky-400 text-xs font-bold uppercase tracking-widest block mb-2">Keunggulan Kami</span>
            <h2 class="text-3xl sm:text-4xl font-black text-white">Mengapa Memilih ALFITO?</h2>
            <p class="text-slate-400 text-sm sm:text-base mt-2">Komitmen kami memberikan standar liburan aman, nyaman, dan berkesan seumur hidup.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Pillar 1 -->
            <div class="glass-card-static p-6 rounded-2xl border border-slate-800 text-left hover:border-sky-500/40 transition">
                <div class="w-12 h-12 rounded-xl bg-sky-500/10 border border-sky-500/30 flex items-center justify-center text-2xl mb-5 text-sky-400">
                    🏛️
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Legalitas Resmi</h3>
                <p class="text-xs text-slate-400 leading-relaxed">
                    Terdaftar resmi di Kemenparekraf dan anggota asosiasi pariwisata nasional ASITA & HPI.
                </p>
            </div>

            <!-- Pillar 2 -->
            <div class="glass-card-static p-6 rounded-2xl border border-slate-800 text-left hover:border-sky-500/40 transition">
                <div class="w-12 h-12 rounded-xl bg-cyan-500/10 border border-cyan-500/30 flex items-center justify-center text-2xl mb-5 text-cyan-400">
                    🚢
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Armada & Kapal Prima</h3>
                <p class="text-xs text-slate-400 leading-relaxed">
                    Jeep 4x4 terawat dan Kapal Phinisi Deluxe ber-AC dengan perlengkapan keselamatan standar internasional.
                </p>
            </div>

            <!-- Pillar 3 -->
            <div class="glass-card-static p-6 rounded-2xl border border-slate-800 text-left hover:border-sky-500/40 transition">
                <div class="w-12 h-12 rounded-xl bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-2xl mb-5 text-amber-400">
                    📸
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Guide & Foto Berkualitas</h3>
                <p class="text-xs text-slate-400 leading-relaxed">
                    Tour leader ramah berpengalaman yang juga siap mengabadikan momen foto & drone cinematic Anda.
                </p>
            </div>

            <!-- Pillar 4 -->
            <div class="glass-card-static p-6 rounded-2xl border border-slate-800 text-left hover:border-sky-500/40 transition">
                <div class="w-12 h-12 rounded-xl bg-rose-500/10 border border-rose-500/30 flex items-center justify-center text-2xl mb-5 text-rose-400">
                    🏷️
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Harga Transparan</h3>
                <p class="text-xs text-slate-400 leading-relaxed">
                    Tanpa biaya tersembunyi. Seluruh tiket objek wisata dan rincian fasilitas tertera jelas sejak awal.
                </p>
            </div>
        </div>
    </section>

    <!-- ── 5. Testimonials Section ──────────────────────────────── -->
    <section class="py-16 sm:py-24 bg-navy-950/60 border-t border-sky-500/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <span class="text-sky-400 text-xs font-bold uppercase tracking-widest block mb-2">Cerita Mereka</span>
                <h2 class="text-3xl sm:text-4xl font-black text-white">Apa Kata Para Traveler?</h2>
                <p class="text-slate-400 text-sm sm:text-base mt-2">Pengalaman otentik dari ribuan wisatawan yang telah menjelajahi Nusantara bersama kami.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($reviews as $review)
                    <div class="glass-card-static p-6 rounded-2xl border border-slate-800 flex flex-col justify-between">
                        <div>
                            <!-- Star Rating -->
                            <div class="flex items-center gap-1 text-gold-400 text-sm mb-4">
                                @for($i = 0; $i < $review->rating; $i++)
                                    <span>★</span>
                                @endfor
                            </div>

                            @if($review->title)
                                <h4 class="text-white font-bold text-sm mb-2">"{{ $review->title }}"</h4>
                            @endif

                            <p class="text-xs text-slate-300 leading-relaxed italic">
                                "{{ $review->comment }}"
                            </p>
                        </div>

                        <div class="mt-6 pt-4 border-t border-slate-800/80 flex items-center gap-3">
                            <img src="{{ $review->user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($review->user->name).'&background=0ea5e9&color=ffffff' }}" 
                                 alt="{{ $review->user->name }}" class="w-10 h-10 rounded-full object-cover border border-sky-500/40">
                            <div>
                                <h5 class="text-sm font-bold text-white">{{ $review->user->name }}</h5>
                                <span class="text-[11px] text-sky-400 block font-medium">Trip: {{ $review->tour->title ?? 'Wisata Alam' }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ── 6. Travel Guides & Articles (Blog) ───────────────────── -->
    <section class="py-16 sm:py-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
            <div>
                <span class="text-sky-400 text-xs font-bold uppercase tracking-widest block mb-2">Inspirasi Liburan</span>
                <h2 class="text-3xl sm:text-4xl font-black text-white">Travel Guide & Tips</h2>
                <p class="text-slate-400 text-sm sm:text-base mt-2">Tips praktis, panduan itinerary, dan informasi destinasi terbaru.</p>
            </div>
            <a href="{{ route('blog.index') }}" class="text-sm font-semibold text-sky-400 hover:text-sky-300 flex items-center gap-1 group">
                <span>Lihat Semua Artikel</span>
                <span class="transform group-hover:translate-x-1 transition">→</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($latestPosts as $post)
                <div class="glass-card rounded-2xl overflow-hidden flex flex-col group">
                    <div class="relative h-48 overflow-hidden">
                        <img src="{{ $post->thumbnail }}" alt="{{ $post->title }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        <div class="absolute top-3 left-3 bg-navy-950/80 backdrop-blur-md px-2.5 py-1 rounded-md text-[11px] font-semibold text-sky-400 uppercase">
                            {{ $post->category }}
                        </div>
                    </div>
                    <div class="p-5 flex-1 flex flex-col justify-between">
                        <div>
                            <span class="text-[11px] text-slate-400 block mb-2">{{ $post->published_at?->format('d M Y') ?? 'Baru saja' }}</span>
                            <h3 class="text-base font-bold text-white group-hover:text-sky-400 transition leading-snug">
                                <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                            </h3>
                            <p class="mt-2 text-xs text-slate-400 line-clamp-2 leading-relaxed">
                                {{ $post->excerpt }}
                            </p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-slate-800">
                            <a href="{{ route('blog.show', $post->slug) }}" class="text-xs font-bold text-sky-400 hover:text-sky-300 flex items-center gap-1">
                                Baca Selengkapnya →
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- ── 7. Custom Trip Consultation Banner (CTA) ─────────────── -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-20">
        <div class="relative rounded-3xl overflow-hidden bg-gradient-to-r from-sky-900 via-blue-950 to-navy-950 border border-sky-500/30 p-8 sm:p-12 shadow-2xl">
            <div class="max-w-2xl relative z-10">
                <span class="px-3 py-1 rounded-full bg-sky-500 text-navy-950 text-xs font-extrabold uppercase tracking-wide inline-block mb-4">
                    Corporate & Family Gathering
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-white leading-tight">
                    Butuh Paket Custom untuk Rombongan Kantor atau Keluarga?
                </h2>
                <p class="mt-4 text-sm sm:text-base text-slate-200 leading-relaxed font-light">
                    Kami melayani pembuatan itinerary khusus sesuai anggaran, pilihan hotel, dan preferensi aktivitas Anda. Dapatkan penawaran terbaik dalam 15 menit!
                </p>
                <div class="mt-8 flex flex-wrap items-center gap-4">
                    <a href="{{ $company->getWhatsappUrl('Halo ' . ($company->short_name ?? 'ALFITO') . ', saya ingin konsultasi paket custom rombongan kantor / keluarga') }}" 
                       target="_blank"
                       class="px-6 py-3.5 rounded-xl bg-white hover:bg-sky-50 text-navy-950 font-bold text-sm shadow-xl flex items-center gap-2 transition transform hover:-translate-y-0.5">
                        <span>Konsultasi Gratis via WhatsApp</span>
                        <span>💬</span>
                    </a>
                    <a href="{{ route('tours.index', ['type' => 'private_trip']) }}" 
                       class="px-6 py-3.5 rounded-xl bg-navy-950/60 hover:bg-navy-950 text-white font-semibold text-sm border border-white/20 transition">
                        Lihat Pilihan Private Trip
                    </a>
                </div>
            </div>

            <!-- Decorative Glow -->
            <div class="absolute -right-20 -bottom-20 w-96 h-96 bg-sky-500/20 rounded-full blur-3xl pointer-events-none"></div>
        </div>
    </section>
@endsection
