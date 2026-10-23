@extends('layouts.web')

@section('title', $tour->title . ' – ALFITO')
@section('meta_description', Str::limit(strip_tags($tour->description), 150))
@section('og_image', $tour->thumbnail)

@section('content')
    <!-- Breadcrumb & Top Bar -->
    <div class="bg-navy-900/60 border-b border-slate-800 py-3">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-xs text-slate-400 flex items-center gap-2">
            <a href="{{ route('home') }}" class="hover:text-sky-400">Beranda</a>
            <span>/</span>
            <a href="{{ route('tours.index') }}" class="hover:text-sky-400">Paket Wisata</a>
            <span>/</span>
            <a href="{{ route('destinations.show', $tour->destination->slug) }}" class="hover:text-sky-400">{{ $tour->destination->name }}</a>
            <span>/</span>
            <span class="text-slate-200 font-medium truncate max-w-xs">{{ $tour->title }}</span>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ 
        pax: 2, 
        basePrice: {{ $tour->price }},
        travelDate: '{{ now()->addDays(3)->format('Y-m-d') }}',
        activeTab: 'itinerary',
        get totalPrice() {
            return this.pax * this.basePrice;
        },
        get formattedTotal() {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(this.totalPrice);
        },
        get whatsappUrl() {
            const waPhone = '{{ $company->clean_whatsapp ?? "6281234567890" }}';
            const brand = '{{ addslashes($company->short_name ?? "ALFITO") }}';
            const msg = encodeURIComponent(`Halo ${brand}, saya tertarik pesan paket:\n*${'{{ addslashes($tour->title) }}'}*\n- Tanggal: ${this.travelDate}\n- Jumlah Pax: ${this.pax} orang\n- Estimasi Total: ${this.formattedTotal}\nMohon info ketersediaan slotnya ya.`);
            return `https://wa.me/${waPhone}?text=${msg}`;
        }
    }">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            <!-- Left Column: Media & Tour Content (8 cols) -->
            <div class="lg:col-span-8 space-y-8">
                <!-- Title & Meta Header -->
                <div>
                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        @if($tour->type === 'open_trip')
                            <span class="px-3 py-1 rounded-full bg-sky-500/20 text-sky-400 border border-sky-500/30 font-bold text-xs">
                                OPEN TRIP (GABUNGAN)
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full bg-amber-500/20 text-amber-400 border border-amber-500/30 font-bold text-xs">
                                PRIVATE TRIP (EKSKLUSIF)
                            </span>
                        @endif

                        <span class="px-3 py-1 rounded-full bg-navy-800 text-slate-300 text-xs font-medium border border-slate-700/50">
                            ⏱️ {{ $tour->duration_days }} Hari {{ $tour->duration_nights ? $tour->duration_nights.' Malam' : '' }}
                        </span>

                        <div class="flex items-center gap-1 text-gold-400 text-xs pl-2">
                            <span>★</span>
                            <span class="font-bold text-white text-sm">{{ $tour->average_rating ?: '5.0' }}</span>
                            <span class="text-slate-400">({{ $tour->reviews->count() ?: 1 }} ulasan)</span>
                        </div>
                    </div>

                    <h1 class="text-2xl sm:text-4xl font-black text-white leading-tight">
                        {{ $tour->title }}
                    </h1>

                    <p class="mt-2 text-sm text-slate-400 flex items-center gap-2">
                        <span class="text-sky-400">📍</span>
                        <span>{{ $tour->destination->name }} &bull; {{ $tour->destination->region }}</span>
                    </p>
                </div>

                <!-- Photo Gallery Showcase (Interactive with Smooth Crossfade) -->
                @php
                    $galleryImages = collect([$tour->thumbnail])
                        ->merge($tour->images ?? [])
                        ->unique()
                        ->values();
                @endphp

                <div class="space-y-3" x-data="{ activeImage: '{{ $tour->thumbnail }}' }">
                    <!-- Main Big Image with Silky Smooth Crossfade Animation -->
                    <div class="relative h-80 sm:h-[430px] rounded-2xl overflow-hidden shadow-2xl border border-sky-500/25 bg-navy-950/80">
                        @foreach($galleryImages as $index => $img)
                            <img src="{{ $img }}" 
                                 alt="{{ $tour->title }} - Foto {{ $index + 1 }}" 
                                 class="absolute inset-0 w-full h-full object-cover transition-all duration-500 ease-in-out {{ $index === 0 ? 'opacity-100 scale-100 z-10' : 'opacity-0 scale-[1.02] z-0' }}"
                                 :class="activeImage === '{{ $img }}' 
                                     ? 'opacity-100 scale-100 z-10 pointer-events-auto' 
                                     : 'opacity-0 scale-[1.02] z-0 pointer-events-none'">
                        @endforeach

                        <!-- Subtle Gradient Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-navy-950/40 via-transparent to-transparent pointer-events-none z-20"></div>
                    </div>

                    <!-- Thumbnails -->
                    @if($galleryImages->count() > 1)
                        <div class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                            @foreach($galleryImages as $index => $img)
                                <button type="button" 
                                        @click="activeImage = '{{ $img }}'"
                                        class="relative h-20 sm:h-28 rounded-xl overflow-hidden transition-all duration-300 cursor-pointer focus:outline-none group text-left"
                                        :class="activeImage === '{{ $img }}' 
                                            ? 'ring-2 ring-sky-400 border-2 border-sky-400 scale-[1.02] shadow-lg shadow-sky-500/40 opacity-100' 
                                            : 'border border-slate-700/80 opacity-60 hover:opacity-100 hover:border-sky-500/50 hover:scale-[1.01]'">
                                    <img src="{{ $img }}" alt="{{ $tour->title }} - Thumbnail {{ $index + 1 }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Navigation Tabs -->
                <div class="border-b border-slate-800 flex gap-6 text-sm font-semibold">
                    <button @click="activeTab = 'itinerary'" 
                            :class="activeTab === 'itinerary' ? 'text-sky-400 border-b-2 border-sky-400 pb-3' : 'text-slate-400 hover:text-slate-200 pb-3'">
                        Itinerary Perjalanan
                    </button>
                    <button @click="activeTab = 'facilities'" 
                            :class="activeTab === 'facilities' ? 'text-sky-400 border-b-2 border-sky-400 pb-3' : 'text-slate-400 hover:text-slate-200 pb-3'">
                        Fasilitas (Include/Exclude)
                    </button>
                    <button @click="activeTab = 'reviews'" 
                            :class="activeTab === 'reviews' ? 'text-sky-400 border-b-2 border-sky-400 pb-3' : 'text-slate-400 hover:text-slate-200 pb-3'">
                        Ulasan Traveler ({{ $tour->reviews->count() }})
                    </button>
                </div>

                <!-- Tab 1: Itinerary -->
                <div x-show="activeTab === 'itinerary'" class="space-y-6">
                    <div class="glass-card-static p-6 rounded-2xl border border-slate-800">
                        <h3 class="text-lg font-bold text-white mb-4">Deskripsi Paket</h3>
                        <p class="text-sm text-slate-300 leading-relaxed">
                            {{ $tour->description }}
                        </p>
                    </div>

                    <!-- Day by Day Timeline -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-bold text-white mb-2">Rencana Perjalanan (Itinerary)</h3>
                        @if(!empty($tour->itinerary) && is_array($tour->itinerary))
                            @foreach($tour->itinerary as $day)
                                <div class="glass-card-static p-5 rounded-2xl border border-slate-800">
                                    <div class="flex items-center gap-3 mb-3">
                                        <span class="w-8 h-8 rounded-lg bg-sky-500/20 text-sky-400 font-bold text-xs flex items-center justify-center border border-sky-500/30">
                                            H{{ $day['day'] ?? 1 }}
                                        </span>
                                        <h4 class="text-base font-bold text-white">{{ $day['title'] ?? 'Program Hari ke-'.$loop->iteration }}</h4>
                                    </div>
                                    @if(!empty($day['activities']) && is_array($day['activities']))
                                        <ul class="space-y-2 text-xs sm:text-sm text-slate-300 pl-4 border-l-2 border-sky-500/30">
                                            @foreach($day['activities'] as $act)
                                                <li class="relative pl-3 before:absolute before:-left-[19px] before:top-2 before:w-2 before:h-2 before:rounded-full before:bg-sky-400">
                                                    {{ $act }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <div class="glass-card-static p-5 rounded-2xl text-slate-400 text-sm">
                                Itinerary detail akan disesuaikan dengan waktu kedatangan Anda.
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Tab 2: Facilities -->
                <div x-show="activeTab === 'facilities'" class="space-y-6" x-cloak>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Includes -->
                        <div class="glass-card-static p-6 rounded-2xl border border-slate-800">
                            <h4 class="text-base font-bold text-sky-400 mb-4 flex items-center gap-2">
                                <span>✓</span> Fasilitas Termasuk (Include)
                            </h4>
                            <ul class="space-y-2.5 text-xs sm:text-sm text-slate-300">
                                @if(!empty($tour->includes) && is_array($tour->includes))
                                    @foreach($tour->includes as $inc)
                                        <li class="flex items-start gap-2">
                                            <span class="text-sky-400 font-bold">✓</span>
                                            <span>{{ $inc }}</span>
                                        </li>
                                    @endforeach
                                @else
                                    <li class="text-slate-400">Hubungi CS untuk daftar include lengkap.</li>
                                @endif
                            </ul>
                        </div>

                        <!-- Excludes -->
                        <div class="glass-card-static p-6 rounded-2xl border border-slate-800">
                            <h4 class="text-base font-bold text-rose-400 mb-4 flex items-center gap-2">
                                <span>✕</span> Tidak Termasuk (Exclude)
                            </h4>
                            <ul class="space-y-2.5 text-xs sm:text-sm text-slate-300">
                                @if(!empty($tour->excludes) && is_array($tour->excludes))
                                    @foreach($tour->excludes as $exc)
                                        <li class="flex items-start gap-2">
                                            <span class="text-rose-400 font-bold">✕</span>
                                            <span>{{ $exc }}</span>
                                        </li>
                                    @endforeach
                                @else
                                    <li class="text-slate-400">Pengeluaran pribadi & kebutuhan di luar paket.</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Tab 3: Reviews -->
                <div x-show="activeTab === 'reviews'" class="space-y-6" x-cloak>
                    <!-- Reviews List -->
                    <div class="space-y-4">
                        @forelse($tour->reviews as $rev)
                            <div class="glass-card-static p-5 rounded-2xl border border-slate-800">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $rev->user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($rev->user->name).'&background=10b981&color=ffffff' }}" 
                                             alt="{{ $rev->user->name }}" class="w-9 h-9 rounded-full object-cover">
                                        <div>
                                            <h5 class="text-sm font-bold text-white">{{ $rev->user->name }}</h5>
                                            <span class="text-[11px] text-slate-400">{{ $rev->created_at->format('d M Y') }}</span>
                                        </div>
                                    </div>
                                    <div class="text-gold-400 text-sm">
                                        @for($i = 0; $i < $rev->rating; $i++)
                                            <span>★</span>
                                        @endfor
                                    </div>
                                </div>
                                @if($rev->title)
                                    <h6 class="text-sm font-semibold text-white mb-1">{{ $rev->title }}</h6>
                                @endif
                                <p class="text-xs text-slate-300 leading-relaxed">
                                    {{ $rev->comment }}
                                </p>
                            </div>
                        @empty
                            <p class="text-slate-400 text-sm py-4">Belum ada ulasan untuk paket ini. Jadilah yang pertama memberikan ulasan!</p>
                        @endforelse
                    </div>

                    <!-- Review Form -->
                    @auth
                        <div class="glass-card-static p-6 rounded-2xl border border-slate-800 mt-6">
                            <h4 class="text-base font-bold text-white mb-4">Tulis Pengalaman Liburan Anda</h4>
                            <form action="{{ route('tours.review', $tour->slug) }}" method="POST" class="space-y-4">
                                @csrf
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-300 mb-1">Beri Nilai Rating (Bintang)</label>
                                        <select name="rating" class="glass-input w-full px-3 py-2 rounded-xl text-xs" required>
                                            <option value="5" class="bg-navy-900">★★★★★ (5 Bintang - Sempurna)</option>
                                            <option value="4" class="bg-navy-900">★★★★☆ (4 Bintang - Sangat Bagus)</option>
                                            <option value="3" class="bg-navy-900">★★★☆☆ (3 Bintang - Cukup)</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-300 mb-1">Judul Ulasan (Opsional)</label>
                                        <input type="text" name="title" placeholder="Contoh: Trip Bromo Terbaik!" 
                                               class="glass-input w-full px-3 py-2 rounded-xl text-xs">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-300 mb-1">Komentar / Pengalaman Anda</label>
                                    <textarea name="comment" rows="3" placeholder="Ceritakan bagaimana pelayanan driver, kebersihan kapal/jeep, dan keindahan spot..." 
                                              class="glass-input w-full px-3 py-2 rounded-xl text-xs" required></textarea>
                                </div>
                                <button type="submit" class="px-5 py-2.5 rounded-xl bg-sky-500 hover:bg-sky-400 text-navy-950 font-bold text-xs transition">
                                    Kirim Ulasan
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="glass-card-static p-4 rounded-xl border border-slate-800 text-center text-xs text-slate-400">
                            Silakan <a href="{{ route('login') }}" class="text-sky-400 font-bold underline">Login</a> untuk memberikan ulasan.
                        </div>
                    @endauth
                </div>
            </div>

            <!-- Right Column: Sticky Booking Widget (4 cols) -->
            <div class="lg:col-span-4">
                <div class="sticky top-28 space-y-6">
                    <div class="glass-card p-6 rounded-3xl shadow-2xl border border-slate-700/80">
                        <div class="pb-5 border-b border-slate-800">
                            <span class="text-xs text-slate-400 block mb-1">Harga Mulai Dari</span>
                            <div class="flex items-baseline gap-2">
                                <span class="text-2xl sm:text-3xl font-black text-sky-400">
                                    Rp {{ number_format($tour->price, 0, ',', '.') }}
                                </span>
                                <span class="text-xs text-slate-400">/ orang</span>
                            </div>
                        </div>

                        <!-- Interactive Booking Inputs -->
                        <div class="py-5 space-y-4">
                            <!-- Travel Date -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Pilih Tanggal Berangkat</label>
                                <input type="date" x-model="travelDate" 
                                       class="glass-input w-full px-3.5 py-2.5 rounded-xl text-xs text-white">
                            </div>

                            <!-- Pax Counter -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Jumlah Peserta (Pax)</label>
                                <div class="flex items-center justify-between glass-input px-4 py-2 rounded-xl">
                                    <button type="button" @click="if(pax > 1) pax--" 
                                            class="w-7 h-7 rounded-lg bg-navy-800 text-white font-bold flex items-center justify-center hover:bg-sky-500 hover:text-navy-950 transition">
                                        -
                                    </button>
                                    <span class="text-sm font-bold text-white" x-text="pax + ' Orang'"></span>
                                    <button type="button" @click="pax++" 
                                            class="w-7 h-7 rounded-lg bg-navy-800 text-white font-bold flex items-center justify-center hover:bg-sky-500 hover:text-navy-950 transition">
                                        +
                                    </button>
                                </div>
                            </div>

                            <!-- Subtotal Calculation Box -->
                            <div class="p-4 rounded-xl bg-navy-950/70 border border-slate-800 space-y-2 text-xs">
                                <div class="flex justify-between text-slate-400">
                                    <span x-text="pax + ' Pax × Rp ' + new Intl.NumberFormat('id-ID').format(basePrice)"></span>
                                    <span x-text="formattedTotal"></span>
                                </div>
                                <div class="flex justify-between font-bold text-white pt-2 border-t border-slate-800 text-sm">
                                    <span>Total Biaya</span>
                                    <span class="text-sky-400 font-extrabold" x-text="formattedTotal"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3">
                            <!-- Booking Form Online -->
                            <a :href="'{{ route('booking.create', $tour->slug) }}' + '?pax=' + pax + '&date=' + travelDate"
                               class="w-full py-3.5 rounded-xl bg-gradient-to-r from-sky-400 to-blue-500 hover:from-sky-300 hover:to-blue-400 text-navy-950 font-bold text-sm text-center block shadow-lg shadow-sky-500/30 transition transform hover:-translate-y-0.5">
                                Pesan Sekarang via Web
                            </a>

                            <!-- Instant WhatsApp Booking -->
                            <a :href="whatsappUrl" target="_blank"
                               class="w-full py-3.5 rounded-xl bg-navy-800 hover:bg-navy-700 text-sky-400 font-bold text-xs text-center border border-sky-500/40 flex items-center justify-center gap-2 transition">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.299.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.144.39-.086.159.058 1.011.477 1.184.564.173.087.289.13.332.202.043.072.043.419-.101.824z"/></svg>
                                <span>Pesan Cepat via WhatsApp</span>
                            </a>
                        </div>

                        <!-- Trust bullets -->
                        <div class="mt-5 pt-4 border-t border-slate-800 text-[11px] text-slate-400 space-y-1.5">
                            <p class="flex items-center gap-1.5">
                                <span class="text-sky-400">✓</span> Konfirmasi instan tanpa ribet
                            </p>
                            <p class="flex items-center gap-1.5">
                                <span class="text-sky-400">✓</span> Pembayaran aman rekening resmi PT
                            </p>
                            <p class="flex items-center gap-1.5">
                                <span class="text-sky-400">✓</span> Free konsultasi penyesuaian jadwal
                            </p>
                        </div>
                    </div>

                    <!-- Operator Agen Card -->
                    @if($tour->agen)
                        <div class="glass-card-static p-4 rounded-2xl border border-slate-800 flex items-center gap-3">
                            <img src="{{ $tour->agen->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($tour->agen->name) }}" 
                                 alt="{{ $tour->agen->name }}" class="w-12 h-12 rounded-xl object-cover">
                            <div>
                                <span class="text-[10px] text-slate-400 uppercase font-semibold">Diorganisir Oleh:</span>
                                <h5 class="text-sm font-bold text-white">{{ $tour->agen->name }}</h5>
                                <span class="text-[11px] text-sky-400">Verified Travel Operator</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Related Tours -->
        @if($relatedTours->isNotEmpty())
            <div class="mt-20 pt-12 border-t border-slate-800">
                <h3 class="text-2xl font-bold text-white mb-6">Paket Lain di {{ $tour->destination->name }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($relatedTours as $rel)
                        <div class="glass-card rounded-2xl overflow-hidden flex flex-col group">
                            <div class="relative h-44 overflow-hidden">
                                <img src="{{ $rel->thumbnail }}" alt="{{ $rel->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            </div>
                            <div class="p-4 flex-1 flex flex-col justify-between">
                                <h4 class="text-sm font-bold text-white group-hover:text-sky-400 transition">
                                    <a href="{{ route('tours.show', $rel->slug) }}">{{ $rel->title }}</a>
                                </h4>
                                <div class="mt-4 pt-3 border-t border-slate-800 flex items-center justify-between">
                                    <span class="text-sm font-extrabold text-sky-400">Rp {{ number_format($rel->price, 0, ',', '.') }}</span>
                                    <a href="{{ route('tours.show', $rel->slug) }}" class="text-xs text-slate-300 hover:text-sky-400 font-semibold">Lihat →</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
