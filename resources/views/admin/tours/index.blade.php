@extends('layouts.web')

@section('title', 'Kelola Paket Wisata – Panel Admin ALFITO')

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
                    <span class="text-sky-400 font-bold">Kelola Paket Wisata</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight flex items-center gap-3">
                    <span>Daftar Paket Wisata</span>
                    <span class="px-2.5 py-1 text-xs rounded-full bg-sky-500/20 text-sky-400 font-mono border border-sky-500/30">
                        Admin Only
                    </span>
                </h1>
                <p class="text-sm text-slate-400 mt-1">
                    Tambah, edit, sesuaikan harga, destinasi, dan atur status aktif/unggulan setiap paket perjalanan.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('tours.index') }}" target="_blank"
                   class="px-4 py-2.5 rounded-xl bg-navy-800 hover:bg-navy-700 text-slate-200 text-xs font-semibold border border-slate-700/70 transition flex items-center gap-1.5">
                    <span>Lihat di Web ↗</span>
                </a>
                <a href="{{ route('admin.tours.create') }}" 
                   class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-sky-500 to-sky-400 hover:from-sky-400 hover:to-sky-300 text-navy-950 font-bold text-xs shadow-lg shadow-sky-500/25 transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>+ Tambah Paket Baru</span>
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

        <!-- Filter Bar -->
        <form action="{{ route('admin.tours.index') }}" method="GET" class="glass-card p-4 rounded-2xl border border-slate-700/60 shadow-lg">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-center text-xs">
                <div>
                    <label class="block text-slate-400 mb-1">Cari Judul Paket</label>
                    <input type="text" name="q" value="{{ $search }}" placeholder="Ketik kata kunci..."
                           class="glass-input w-full px-3 py-2 rounded-xl text-xs bg-navy-900 border border-slate-700 text-white">
                </div>
                <div>
                    <label class="block text-slate-400 mb-1">Destinasi</label>
                    <select name="destination_id" class="glass-input w-full px-3 py-2 rounded-xl text-xs bg-navy-900 border border-slate-700 text-white cursor-pointer">
                        <option value="">Semua Destinasi</option>
                        @foreach($destinations as $dest)
                            <option value="{{ $dest->id }}" {{ $destinationId == $dest->id ? 'selected' : '' }}>
                                {{ $dest->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-slate-400 mb-1">Kategori</label>
                    <select name="category_id" class="glass-input w-full px-3 py-2 rounded-xl text-xs bg-navy-900 border border-slate-700 text-white cursor-pointer">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $parent)
                            <optgroup label="{{ $parent->icon }} {{ $parent->name }}" class="bg-navy-900 text-sky-400 font-bold">
                                <option value="{{ $parent->id }}" {{ $categoryId == $parent->id ? 'selected' : '' }} class="bg-navy-900 text-white font-normal">
                                    ★ {{ $parent->name }}
                                </option>
                                @foreach($parent->children as $sub)
                                    <option value="{{ $sub->id }}" {{ $categoryId == $sub->id ? 'selected' : '' }} class="bg-navy-900 text-white font-normal">
                                        &nbsp;&nbsp;↳ {{ $sub->icon }} {{ $sub->name }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-slate-400 mb-1">Tipe Trip</label>
                    <select name="type" class="glass-input w-full px-3 py-2 rounded-xl text-xs bg-navy-900 border border-slate-700 text-white cursor-pointer">
                        <option value="">Semua Tipe</option>
                        <option value="open_trip" {{ $type === 'open_trip' ? 'selected' : '' }}>Open Trip</option>
                        <option value="private_trip" {{ $type === 'private_trip' ? 'selected' : '' }}>Private Trip</option>
                    </select>
                </div>
                <div class="pt-2 sm:pt-4 flex items-center gap-2">
                    <button type="submit" class="flex-1 py-2 rounded-xl bg-sky-500 hover:bg-sky-400 text-navy-950 font-bold text-xs transition">
                        Terapkan Filter
                    </button>
                    @if($search || $destinationId || $categoryId || $type)
                        <a href="{{ route('admin.tours.index') }}" class="p-2 text-slate-400 hover:text-white text-xs">Reset</a>
                    @endif
                </div>
            </div>
        </form>

        <!-- Tours Table -->
        <div class="glass-card rounded-2xl border border-slate-700/60 shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-navy-900 text-slate-400 uppercase font-bold tracking-wider border-b border-slate-800">
                        <tr>
                            <th class="py-3.5 px-4">Paket Wisata</th>
                            <th class="py-3.5 px-4">Destinasi & Kategori</th>
                            <th class="py-3.5 px-4">Durasi & Tipe</th>
                            <th class="py-3.5 px-4">Harga / Pax</th>
                            <th class="py-3.5 px-4 text-center">Status</th>
                            <th class="py-3.5 px-4 text-center">Unggulan</th>
                            <th class="py-3.5 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/80 font-medium">
                        @forelse($tours as $tour)
                            <tr class="hover:bg-navy-900/40 transition">
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $tour->thumbnail }}" alt="{{ $tour->title }}"
                                             class="w-14 h-10 object-cover rounded-lg border border-slate-700 flex-shrink-0">
                                        <div class="min-w-0">
                                            <a href="{{ route('tours.show', $tour->slug) }}" target="_blank"
                                               class="font-bold text-white text-sm hover:text-sky-400 transition truncate block max-w-xs">
                                                {{ $tour->title }}
                                            </a>
                                            <div class="text-[10px] text-slate-400">
                                                Agen: {{ $tour->agen->name }} • {{ $tour->bookings_count }} Pesanan
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-sky-400">📍 {{ $tour->destination->name }}</div>
                                    @if($tour->category)
                                        <div class="text-[10px] text-slate-300 mt-0.5">
                                            {{ $tour->category->icon }} {{ $tour->category->name }}
                                        </div>
                                    @else
                                        <div class="text-[10px] text-slate-500 italic mt-0.5">Tanpa kategori</div>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="text-slate-200">⏱️ {{ $tour->duration_days }}H{{ $tour->duration_nights ? $tour->duration_nights.'M' : '' }}</div>
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase mt-0.5 {{ $tour->type === 'open_trip' ? 'bg-sky-500/20 text-sky-400' : 'bg-amber-500/20 text-amber-400' }}">
                                        {{ $tour->type === 'open_trip' ? 'Open Trip' : 'Private Trip' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-emerald-400 text-sm">
                                        Rp {{ number_format($tour->price, 0, ',', '.') }}
                                    </div>
                                    <div class="text-[10px] text-slate-400">{{ $tour->min_pax }} - {{ $tour->max_pax }} Pax</div>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <form action="{{ route('admin.tours.toggle-active', $tour) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="px-2.5 py-1 rounded-full text-[10px] font-bold transition {{ $tour->is_active ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-slate-800 text-slate-400' }}">
                                            {{ $tour->is_active ? '● Aktif' : '○ Nonaktif' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <form action="{{ route('admin.tours.toggle-featured', $tour) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="px-2.5 py-1 rounded-full text-[10px] font-bold transition {{ $tour->is_featured ? 'bg-amber-500/20 text-amber-400 border border-amber-500/30' : 'bg-slate-800 text-slate-500' }}">
                                            {{ $tour->is_featured ? '★ Unggulan' : '☆ Reguler' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('admin.tours.edit', $tour) }}"
                                           class="px-2.5 py-1.5 rounded-lg bg-navy-800 hover:bg-navy-700 text-sky-400 border border-slate-700 transition font-bold text-xs">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.tours.destroy', $tour) }}" method="POST"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket wisata \'{{ $tour->title }}\'?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-1.5 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 transition">
                                                ✕
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-400">
                                    Belum ada paket wisata yang sesuai.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($tours->hasPages())
                <div class="p-4 border-t border-slate-800">
                    {{ $tours->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
