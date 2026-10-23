@extends('layouts.web')

@section('title', 'Kelola Kategori & Sub-Kategori – Panel Admin ALFITO')

@section('content')
<div class="py-12 bg-navy-950 min-h-screen text-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <!-- Breadcrumbs & Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-2">
                    <a href="{{ route('home') }}" class="hover:text-sky-400 transition">Beranda</a>
                    <span>/</span>
                    <a href="{{ route('dashboard') }}" class="hover:text-sky-400 transition">Dashboard</a>
                    <span>/</span>
                    <span class="text-sky-400 font-bold">Kelola Kategori & Sub-Kategori</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight flex items-center gap-3">
                    <span>Kategori & Sub-Kategori Wisata</span>
                    <span class="px-2.5 py-1 text-xs rounded-full bg-sky-500/20 text-sky-400 font-mono border border-sky-500/30">
                        Admin Only
                    </span>
                </h1>
                <p class="text-sm text-slate-400 mt-1">
                    Kelola struktur kategori seperti <strong>Gathering Kantor</strong> beserta sub-kategori (Outbound, Raker, Family Gathering, dll).
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('tours.index') }}" target="_blank"
                   class="px-4 py-2.5 rounded-xl bg-navy-800 hover:bg-navy-700 text-slate-200 text-xs font-semibold border border-slate-700/70 transition flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <span>Lihat di Web</span>
                </a>
                <a href="{{ route('admin.categories.create') }}" 
                   class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-sky-500 to-sky-400 hover:from-sky-400 hover:to-sky-300 text-navy-950 font-bold text-xs shadow-lg shadow-sky-500/25 transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>Tambah Kategori / Sub</span>
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

        <!-- Quick Summary Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div class="glass-card p-5 rounded-2xl border border-slate-700/60 shadow-lg flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-sky-500/20 text-sky-400 flex items-center justify-center text-2xl font-bold border border-sky-400/30">
                    🏢
                </div>
                <div>
                    <div class="text-2xl font-black text-white">{{ $parentCategories->count() }}</div>
                    <div class="text-xs text-slate-400 font-medium">Kategori Utama (Induk)</div>
                </div>
            </div>

            <div class="glass-card p-5 rounded-2xl border border-slate-700/60 shadow-lg flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-2xl font-bold border border-emerald-400/30">
                    🎯
                </div>
                <div>
                    <div class="text-2xl font-black text-white">
                        {{ \App\Models\TourCategory::whereNotNull('parent_id')->count() }}
                    </div>
                    <div class="text-xs text-slate-400 font-medium">Sub-Kategori Aktif</div>
                </div>
            </div>

            <div class="glass-card p-5 rounded-2xl border border-slate-700/60 shadow-lg flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-2xl font-bold border border-amber-400/30">
                    📦
                </div>
                <div>
                    <div class="text-2xl font-black text-white">
                        {{ \App\Models\Tour::whereNotNull('category_id')->count() }}
                    </div>
                    <div class="text-xs text-slate-400 font-medium">Paket Wisata Terkategori</div>
                </div>
            </div>
        </div>

        <!-- Filter Tabs by Parent Category -->
        <div class="flex items-center gap-2 overflow-x-auto pb-2 border-b border-slate-800">
            <a href="{{ route('admin.categories.index') }}"
               class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 whitespace-nowrap {{ !$selectedParentId ? 'bg-sky-500 text-navy-950 shadow-md shadow-sky-500/20' : 'bg-navy-900/80 hover:bg-navy-800 text-slate-300 border border-slate-700/60' }}">
                <span>🌐 Semua Kategori</span>
            </a>

            @foreach($parentCategories as $parent)
                <a href="{{ route('admin.categories.index', ['parent_id' => $parent->id]) }}"
                   class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 whitespace-nowrap {{ $selectedParentId == $parent->id ? 'bg-sky-500 text-navy-950 shadow-md shadow-sky-500/20' : 'bg-navy-900/80 hover:bg-navy-800 text-slate-300 border border-slate-700/60' }}">
                    <span>{{ $parent->icon ?? '🏷️' }}</span>
                    <span>{{ $parent->name }}</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $selectedParentId == $parent->id ? 'bg-navy-950/40 text-navy-950 font-black' : 'bg-slate-800 text-slate-400' }}">
                        {{ $parent->children_count }} sub
                    </span>
                </a>
            @endforeach
        </div>

        <!-- Category Content Sections -->
        <div class="space-y-8">
            @forelse($parentCategories as $parent)
                @if(!$selectedParentId || $selectedParentId == $parent->id)
                    <div class="glass-card rounded-2xl border border-slate-700/60 shadow-xl overflow-hidden">
                        <!-- Parent Header Card -->
                        <div class="p-6 bg-gradient-to-r from-navy-900 via-navy-850 to-navy-900 border-b border-slate-700/60 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-start sm:items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl bg-sky-500/20 border border-sky-400/30 flex items-center justify-center text-3xl shadow-inner">
                                    {{ $parent->icon ?? '🏢' }}
                                </div>
                                <div>
                                    <div class="flex items-center gap-2.5">
                                        <h2 class="text-xl font-black text-white tracking-tight">{{ $parent->name }}</h2>
                                        <span class="px-2 py-0.5 text-[11px] rounded font-mono font-bold {{ $parent->is_active ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-slate-700 text-slate-400' }}">
                                            {{ $parent->is_active ? 'AKTIF' : 'NONAKTIF' }}
                                        </span>
                                        <span class="text-xs font-mono text-slate-400">/{{ $parent->slug }}</span>
                                    </div>
                                    <p class="text-xs text-slate-300 mt-1 max-w-2xl">
                                        {{ $parent->description ?: 'Kategori utama paket perjalanan & gathering.' }}
                                    </p>
                                    <div class="flex items-center gap-4 mt-2 text-xs text-slate-400">
                                        <span>Sub-kategori: <strong class="text-sky-400">{{ $parent->children->count() }}</strong></span>
                                        <span>•</span>
                                        <span>Total Paket: <strong class="text-emerald-400">{{ $parent->tours_count }}</strong></span>
                                        <span>•</span>
                                        <span>Urutan: <strong class="text-slate-300">#{{ $parent->order }}</strong></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions for Parent Category -->
                            <div class="flex items-center gap-2 self-start sm:self-center">
                                <a href="{{ route('admin.categories.create', ['parent_id' => $parent->id]) }}"
                                   class="px-3.5 py-2 rounded-xl bg-sky-500/20 hover:bg-sky-500/30 text-sky-300 font-bold text-xs border border-sky-500/30 transition flex items-center gap-1.5 shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                    <span>+ Sub-Kategori Baru</span>
                                </a>
                                <a href="{{ route('admin.categories.edit', $parent) }}"
                                   class="p-2 rounded-xl bg-navy-800 hover:bg-navy-700 text-slate-300 hover:text-white border border-slate-700 transition"
                                   title="Edit Kategori Induk">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.categories.toggle', $parent) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="p-2 rounded-xl bg-navy-800 hover:bg-navy-700 {{ $parent->is_active ? 'text-emerald-400' : 'text-slate-500' }} border border-slate-700 transition"
                                            title="Ubah Status Aktif">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Sub-categories List Table -->
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-sm font-bold text-slate-200 uppercase tracking-wider flex items-center gap-2">
                                    <span>Daftar Sub-Kategori {{ $parent->name }}</span>
                                    <span class="text-xs px-2 py-0.5 rounded-full bg-navy-800 text-sky-400 font-mono">
                                        {{ $parent->children->count() }} item
                                    </span>
                                </h3>
                                <a href="{{ route('admin.categories.create', ['parent_id' => $parent->id]) }}"
                                   class="text-xs font-semibold text-sky-400 hover:text-sky-300 transition flex items-center gap-1">
                                    <span>+ Tambah Sub-Kategori</span>
                                </a>
                            </div>

                            @if($parent->children->isEmpty())
                                <div class="text-center py-8 px-4 rounded-xl border border-dashed border-slate-700/80 bg-navy-900/40">
                                    <div class="text-3xl mb-2">📂</div>
                                    <div class="text-sm font-semibold text-slate-300">Belum ada sub-kategori untuk {{ $parent->name }}</div>
                                    <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                                        Tambahkan sub-kategori seperti Outbound, Rapat Kerja, atau Family Gathering agar agen dan pengunjung dapat mengelompokkan paket dengan rapi.
                                    </p>
                                    <a href="{{ route('admin.categories.create', ['parent_id' => $parent->id]) }}"
                                       class="inline-flex items-center gap-1.5 mt-4 px-4 py-2 rounded-xl bg-sky-500 hover:bg-sky-400 text-navy-950 font-bold text-xs transition">
                                        <span>+ Tambah Sub-Kategori Sekarang</span>
                                    </a>
                                </div>
                            @else
                                <div class="overflow-x-auto rounded-xl border border-slate-800">
                                    <table class="w-full text-left text-xs">
                                        <thead class="bg-navy-900 text-slate-400 uppercase font-bold tracking-wider border-b border-slate-800">
                                            <tr>
                                                <th class="py-3 px-4 w-12 text-center">Urutan</th>
                                                <th class="py-3 px-4">Sub-Kategori</th>
                                                <th class="py-3 px-4">Slug URL</th>
                                                <th class="py-3 px-4">Deskripsi Singkat</th>
                                                <th class="py-3 px-4 text-center">Paket Wisata</th>
                                                <th class="py-3 px-4 text-center">Status</th>
                                                <th class="py-3 px-4 text-right">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-800/80 bg-navy-950/60 font-medium">
                                            @foreach($parent->children as $sub)
                                                <tr class="hover:bg-navy-900/40 transition">
                                                    <td class="py-3 px-4 text-center font-mono text-slate-400">
                                                        #{{ $sub->order }}
                                                    </td>
                                                    <td class="py-3 px-4">
                                                        <div class="flex items-center gap-2.5">
                                                            <div class="w-8 h-8 rounded-lg bg-navy-800 border border-slate-700 flex items-center justify-center text-lg flex-shrink-0">
                                                                {{ $sub->icon ?? '🏷️' }}
                                                            </div>
                                                            <div>
                                                                <div class="font-bold text-white text-sm">{{ $sub->name }}</div>
                                                                @if($sub->color)
                                                                    <span class="inline-block w-2 h-2 rounded-full bg-{{ $sub->color }}-500 mr-1"></span>
                                                                    <span class="text-[10px] text-slate-400 capitalize">{{ $sub->color }} theme</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="py-3 px-4 font-mono text-slate-400 text-[11px]">
                                                        /{{ $sub->slug }}
                                                    </td>
                                                    <td class="py-3 px-4 text-slate-300 max-w-xs truncate" title="{{ $sub->description }}">
                                                        {{ $sub->description ?: '-' }}
                                                    </td>
                                                    <td class="py-3 px-4 text-center">
                                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $sub->tours->count() > 0 ? 'bg-sky-500/20 text-sky-400 border border-sky-500/30' : 'bg-slate-800 text-slate-400' }}">
                                                            {{ $sub->tours->count() }} paket
                                                        </span>
                                                    </td>
                                                    <td class="py-3 px-4 text-center">
                                                        <form action="{{ route('admin.categories.toggle', $sub) }}" method="POST" class="inline-block">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" 
                                                                    class="px-2.5 py-1 rounded-full text-[11px] font-bold transition {{ $sub->is_active ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 hover:bg-emerald-500/30' : 'bg-slate-800 text-slate-400 hover:bg-slate-700' }}">
                                                                {{ $sub->is_active ? '● Aktif' : '○ Nonaktif' }}
                                                            </button>
                                                        </form>
                                                    </td>
                                                    <td class="py-3 px-4 text-right">
                                                        <div class="flex items-center justify-end gap-1.5">
                                                            <a href="{{ route('admin.categories.edit', $sub) }}"
                                                               class="px-2.5 py-1.5 rounded-lg bg-navy-800 hover:bg-navy-700 text-slate-200 hover:text-white border border-slate-700 transition flex items-center gap-1"
                                                               title="Edit Sub-Kategori">
                                                                <svg class="w-3.5 h-3.5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                                <span>Edit</span>
                                                            </a>
                                                            <form action="{{ route('admin.categories.destroy', $sub) }}" method="POST"
                                                                  onsubmit="return confirm('Hapus sub-kategori \'{{ $sub->name }}\'? Paket yang menggunakan kategori ini akan dilepas hubungannya.')"
                                                                  class="inline-block">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" 
                                                                        class="p-1.5 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 hover:text-rose-300 border border-rose-500/30 transition"
                                                                        title="Hapus Sub-Kategori">
                                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            @empty
                <div class="glass-card p-12 text-center rounded-2xl border border-slate-700/60">
                    <div class="text-4xl mb-3">🏢</div>
                    <h3 class="text-lg font-bold text-white">Belum Ada Kategori</h3>
                    <p class="text-xs text-slate-400 mt-1 max-w-md mx-auto">
                        Mulai dengan menambahkan kategori utama seperti <strong>Gathering Kantor</strong>, lalu tambahkan sub-kategori di dalamnya.
                    </p>
                    <a href="{{ route('admin.categories.create') }}" 
                       class="inline-flex items-center gap-2 mt-5 px-5 py-2.5 rounded-xl bg-sky-500 hover:bg-sky-400 text-navy-950 font-bold text-xs transition">
                        <span>+ Buat Kategori Pertama</span>
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
