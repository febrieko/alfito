@extends('layouts.web')

@section('title', 'Destinasi Wisata Indonesia – ALFITO')
@section('meta_description', 'Jelajahi kumpulan destinasi wisata terbaik di Indonesia: Bromo, Labuan Bajo, Raja Ampat, Bali, dan Yogyakarta.')

@section('content')
    <div class="bg-navy-900 border-b border-slate-800 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <span class="text-sky-400 text-xs font-bold uppercase tracking-widest block mb-2">Jelajahi Surga Negeri</span>
            <h1 class="text-3xl sm:text-4xl font-black text-white">Destinasi Wisata Populer</h1>
            <p class="text-slate-400 text-sm mt-2">Pilih lokasi impian Anda dan temukan paket tour terbaik yang tersedia.</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <!-- Region Filter Pills -->
        <div class="flex flex-wrap gap-2 mb-10">
            <a href="{{ route('destinations.index') }}" 
               class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ !request('region') ? 'bg-sky-500 text-navy-950' : 'bg-navy-800 text-slate-300 hover:text-white' }}">
                Semua Wilayah
            </a>
            @foreach($regions as $region)
                <a href="{{ route('destinations.index', ['region' => $region]) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ request('region') === $region ? 'bg-sky-500 text-navy-950' : 'bg-navy-800 text-slate-300 hover:text-white' }}">
                    {{ $region }}
                </a>
            @endforeach
        </div>

        <!-- Destinations Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($destinations as $destination)
                <div class="glass-card rounded-3xl overflow-hidden flex flex-col group">
                    <div class="relative h-64 overflow-hidden">
                        <img src="{{ $destination->thumbnail }}" alt="{{ $destination->name }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-navy-950 via-transparent to-black/30"></div>

                        <div class="absolute top-4 left-4 bg-navy-950/80 backdrop-blur-md px-3 py-1 rounded-full text-[11px] font-semibold text-sky-400 border border-sky-500/30">
                            {{ $destination->region }}
                        </div>

                        <div class="absolute bottom-4 left-4 right-4 flex items-center justify-between text-xs">
                            <span class="bg-navy-900/90 text-white font-semibold px-2.5 py-1 rounded-lg">
                                {{ $destination->tours_count }} Paket Wisata
                            </span>
                        </div>
                    </div>

                    <div class="p-6 flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-white group-hover:text-sky-400 transition mb-2">
                                <a href="{{ route('destinations.show', $destination->slug) }}">{{ $destination->name }}</a>
                            </h3>
                            <p class="text-xs text-slate-300 line-clamp-3 leading-relaxed">
                                {{ $destination->description }}
                            </p>
                        </div>

                        <div class="mt-6 pt-4 border-t border-slate-800 flex items-center justify-between">
                            <span class="text-xs text-slate-400">Negara: {{ $destination->country }}</span>
                            <a href="{{ route('destinations.show', $destination->slug) }}" 
                               class="text-xs font-bold text-sky-400 hover:text-sky-300 flex items-center gap-1 group-hover:translate-x-1 transition">
                                Lihat Paket →
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-12">
            {{ $destinations->links() }}
        </div>
    </div>
@endsection
