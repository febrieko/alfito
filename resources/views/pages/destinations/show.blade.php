@extends('layouts.web')

@section('title', $destination->name . ' – Destinasi Wisata ALFITO')
@section('meta_description', Str::limit(strip_tags($destination->description), 150))
@section('og_image', $destination->thumbnail)

@push('styles')
    <!-- Leaflet.js CSS for Interactive Map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <style>
        .leaflet-container {
            border-radius: 1rem;
            z-index: 10;
        }
    </style>
@endpush

@section('content')
    <!-- Hero Header -->
    <div class="relative h-72 sm:h-96 flex items-center justify-center overflow-hidden">
        <img src="{{ $destination->thumbnail }}" alt="{{ $destination->name }}" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-navy-950 via-navy-950/60 to-black/40"></div>

        <div class="absolute bottom-10 left-0 right-0 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center sm:text-left">
            <span class="px-3 py-1 rounded-full bg-sky-500/20 text-sky-400 border border-sky-500/30 text-xs font-bold uppercase tracking-wider inline-block mb-3">
                {{ $destination->region }} &bull; {{ $destination->country }}
            </span>
            <h1 class="text-3xl sm:text-5xl font-black text-white tracking-tight">
                {{ $destination->name }}
            </h1>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            <!-- Left: Description & Available Tours (8 cols) -->
            <div class="lg:col-span-8 space-y-10">
                <!-- Overview -->
                <div class="glass-card-static p-6 sm:p-8 rounded-3xl border border-slate-800">
                    <h3 class="text-xl font-bold text-white mb-4">Tentang {{ $destination->name }}</h3>
                    <p class="text-sm text-slate-300 leading-relaxed">
                        {{ $destination->description }}
                    </p>
                </div>

                <!-- Available Tours in this Destination -->
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-white">Paket Wisata di {{ $destination->name }}</h3>
                        <span class="text-xs text-slate-400 font-medium">{{ $tours->total() }} Paket Ditemukan</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @forelse($tours as $tour)
                            <div class="glass-card rounded-2xl overflow-hidden flex flex-col group">
                                <div class="relative h-48 overflow-hidden">
                                    <img src="{{ $tour->thumbnail }}" alt="{{ $tour->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                    <div class="absolute top-3 left-3">
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $tour->type === 'open_trip' ? 'bg-sky-500 text-navy-950' : 'bg-amber-500 text-navy-950' }}">
                                            {{ $tour->type === 'open_trip' ? 'OPEN TRIP' : 'PRIVATE TRIP' }}
                                        </span>
                                    </div>
                                    <div class="absolute top-3 right-3 bg-navy-950/80 px-2 py-0.5 rounded text-[11px] text-slate-200">
                                        {{ $tour->duration_days }}H
                                    </div>
                                </div>
                                <div class="p-4 flex-1 flex flex-col justify-between">
                                    <div>
                                        <h4 class="text-sm font-bold text-white group-hover:text-sky-400 transition mb-2">
                                            <a href="{{ route('tours.show', $tour->slug) }}">{{ $tour->title }}</a>
                                        </h4>
                                        <p class="text-xs text-slate-400 line-clamp-2">{{ $tour->description }}</p>
                                    </div>
                                    <div class="mt-4 pt-3 border-t border-slate-800 flex items-center justify-between">
                                        <span class="text-sm font-extrabold text-sky-400">Rp {{ number_format($tour->price, 0, ',', '.') }}</span>
                                        <a href="{{ route('tours.show', $tour->slug) }}" class="px-3 py-1.5 rounded-lg bg-sky-500/10 text-sky-400 text-xs font-bold hover:bg-sky-500 hover:text-navy-950 transition">
                                            Detail Trip
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-2 glass-card-static p-6 rounded-2xl text-center text-slate-400 text-sm">
                                Belum ada paket wisata aktif untuk destinasi ini.
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-8">
                        {{ $tours->links() }}
                    </div>
                </div>
            </div>

            <!-- Right: Interactive Leaflet Map & Info (4 cols) -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Leaflet Interactive Map -->
                @if($destination->map_lat && $destination->map_lng)
                    <div class="glass-card-static p-4 rounded-3xl border border-slate-800">
                        <h4 class="text-sm font-bold text-white mb-3 flex items-center gap-2">
                            <span>🗺️</span> Peta Lokasi Destinasi
                        </h4>
                        <div id="map" class="h-64 w-full shadow-inner"></div>
                        <div class="mt-3 text-[11px] text-slate-400 flex justify-between">
                            <span>Lat: {{ $destination->map_lat }}</span>
                            <span>Lng: {{ $destination->map_lng }}</span>
                        </div>
                    </div>
                @endif

                <!-- Travel Tips / Guide Articles -->
                @if($posts->isNotEmpty())
                    <div class="glass-card-static p-5 rounded-3xl border border-slate-800">
                        <h4 class="text-sm font-bold text-white mb-4">Artikel & Tips Wisata</h4>
                        <div class="space-y-4">
                            @foreach($posts as $post)
                                <a href="{{ route('blog.show', $post->slug) }}" class="flex items-center gap-3 group">
                                    <img src="{{ $post->thumbnail }}" alt="{{ $post->title }}" class="w-14 h-14 rounded-xl object-cover">
                                    <div class="flex-1 min-w-0">
                                        <h5 class="text-xs font-bold text-slate-200 group-hover:text-sky-400 transition truncate">{{ $post->title }}</h5>
                                        <span class="text-[10px] text-slate-400 block mt-0.5">{{ $post->published_at?->format('d M Y') }}</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Contact Consultation Box -->
                <div class="glass-card p-6 rounded-3xl border border-sky-500/30 text-center space-y-3">
                    <span class="text-3xl block">🧭</span>
                    <h4 class="text-base font-bold text-white">Butuh Jadwal Trip Sendiri?</h4>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Kami siap merancang private tour ke {{ $destination->name }} sesuai tanggal pilihan Anda.
                    </p>
                    <a href="{{ $company->getWhatsappUrl('Halo ' . ($company->short_name ?? 'ALFITO') . ', saya ingin tanya private trip ke ' . $destination->name) }}" target="_blank"
                       class="inline-block w-full py-2.5 rounded-xl bg-sky-500 hover:bg-sky-400 text-navy-950 font-bold text-xs transition">
                        Chat WhatsApp CS
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @if($destination->map_lat && $destination->map_lng)
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const lat = {{ $destination->map_lat }};
                const lng = {{ $destination->map_lng }};
                const map = L.map('map').setView([lat, lng], 10);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                L.marker([lat, lng]).addTo(map)
                    .bindPopup('<strong>{{ addslashes($destination->name) }}</strong><br>{{ addslashes($destination->region) }}')
                    .openPopup();
            });
        </script>
    @endif
@endpush
