@extends('layouts.web')

@section('title', 'Kelola Destinasi – Panel Admin ALFITO')

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
                    <span class="text-sky-400 font-bold">Kelola Destinasi</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight flex items-center gap-3">
                    <span>Daftar Destinasi Wisata</span>
                    <span class="px-2.5 py-1 text-xs rounded-full bg-sky-500/20 text-sky-400 font-mono border border-sky-500/30">
                        Admin Only
                    </span>
                </h1>
                <p class="text-sm text-slate-400 mt-1">
                    Atur lokasi wisata, daerah provinsi, thumbnail gambar, dan status unggulan destinasi.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('destinations.index') }}" target="_blank"
                   class="px-4 py-2.5 rounded-xl bg-navy-800 hover:bg-navy-700 text-slate-200 text-xs font-semibold border border-slate-700/70 transition">
                    Lihat di Web ↗
                </a>
                <a href="{{ route('admin.destinations.create') }}" 
                   class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-sky-500 to-sky-400 hover:from-sky-400 hover:to-sky-300 text-navy-950 font-bold text-xs shadow-lg shadow-sky-500/25 transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>+ Tambah Destinasi</span>
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

        <!-- Grid of Destinations -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($destinations as $dest)
                <div class="glass-card rounded-2xl border border-slate-700/60 shadow-xl overflow-hidden flex flex-col group">
                    <div class="relative h-48 overflow-hidden">
                        <img src="{{ $dest->thumbnail }}" alt="{{ $dest->name }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-navy-950 via-transparent to-black/40"></div>

                        <div class="absolute top-3 left-3">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-navy-950/80 backdrop-blur-md text-sky-400 border border-sky-400/30">
                                {{ $dest->region }}
                            </span>
                        </div>

                        <div class="absolute top-3 right-3">
                            <form action="{{ route('admin.destinations.toggle-featured', $dest) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="px-2.5 py-1 rounded-full text-[10px] font-bold backdrop-blur-md transition {{ $dest->is_featured ? 'bg-amber-500 text-navy-950 shadow-md' : 'bg-navy-950/80 text-slate-400 hover:text-white border border-slate-700' }}">
                                    {{ $dest->is_featured ? '★ Unggulan' : '☆ Reguler' }}
                                </button>
                            </form>
                        </div>

                        <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between">
                            <h3 class="text-lg font-black text-white drop-shadow-md truncate">{{ $dest->name }}</h3>
                            <span class="text-xs font-bold text-sky-300 bg-sky-950/80 px-2 py-0.5 rounded-lg border border-sky-500/30">
                                {{ $dest->tours_count }} Paket
                            </span>
                        </div>
                    </div>

                    <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
                        <p class="text-xs text-slate-300 line-clamp-2">{{ $dest->description }}</p>

                        <div class="pt-2 border-t border-slate-800 flex items-center justify-between">
                            <span class="text-[11px] font-mono text-slate-400">/{{ $dest->slug }}</span>
                            <div class="flex items-center gap-1.5">
                                <a href="{{ route('admin.destinations.edit', $dest) }}"
                                   class="px-2.5 py-1.5 rounded-lg bg-navy-800 hover:bg-navy-700 text-sky-400 border border-slate-700 font-bold text-xs transition">
                                    Edit
                                </a>
                                <form action="{{ route('admin.destinations.destroy', $dest) }}" method="POST"
                                      onsubmit="return confirm('Hapus destinasi \'{{ $dest->name }}\'? Paket yang terhubung akan kehilangan destinasi.')">
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
                    Belum ada destinasi wisata terdaftar.
                </div>
            @endforelse
        </div>

        @if($destinations->hasPages())
            <div class="pt-4">
                {{ $destinations->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
