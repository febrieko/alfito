@extends('layouts.web')

@section('title', 'Kelola Slider Hero – Panel Admin ALFITO')

@section('content')
<div class="py-12 bg-navy-950 min-h-screen text-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumbs & Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-2">
                    <a href="{{ route('home') }}" class="hover:text-sky-400 transition">Beranda</a>
                    <span>/</span>
                    <a href="{{ route('dashboard') }}" class="hover:text-sky-400 transition">Dashboard</a>
                    <span>/</span>
                    <span class="text-sky-400 font-bold">Kelola Slider Hero</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight flex items-center gap-3">
                    <span>Pengaturan Slider Hero</span>
                    <span class="px-2.5 py-1 text-xs rounded-full bg-sky-500/20 text-sky-400 font-mono border border-sky-500/30">
                        Admin Only
                    </span>
                </h1>
                <p class="text-sm text-slate-400 mt-1">
                    Atur gambar slide beranda, judul headline, urutan rotasi, dan status aktif.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" target="_blank"
                   class="px-4 py-2.5 rounded-xl bg-navy-800 hover:bg-navy-700 text-slate-200 text-xs font-semibold border border-slate-700/70 transition flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <span>Lihat di Beranda</span>
                </a>
                <a href="{{ route('admin.hero-slides.create') }}" 
                   class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-sky-500 to-sky-400 hover:from-sky-400 hover:to-sky-300 text-navy-950 font-bold text-xs shadow-lg shadow-sky-500/25 transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>Tambah Slide Baru</span>
                </a>
            </div>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 text-sm flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-200">✕</button>
            </div>
        @endif

        @include('admin.partials.nav')

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div class="glass-card p-5 rounded-2xl">
                <span class="text-xs font-semibold text-slate-400 uppercase">Total Slide</span>
                <p class="text-3xl font-black text-white mt-1">{{ $slides->count() }}</p>
                <span class="text-xs text-sky-400 mt-1 block">Slide tersimpan dalam sistem</span>
            </div>
            <div class="glass-card p-5 rounded-2xl">
                <span class="text-xs font-semibold text-slate-400 uppercase">Slide Aktif Ditampilkan</span>
                <p class="text-3xl font-black text-emerald-400 mt-1">{{ $slides->where('is_active', true)->count() }}</p>
                <span class="text-xs text-emerald-300/70 mt-1 block">Tampil otomatis bergantian di beranda</span>
            </div>
            <div class="glass-card p-5 rounded-2xl">
                <span class="text-xs font-semibold text-slate-400 uppercase">Slide Nonaktif / Draft</span>
                <p class="text-3xl font-black text-amber-400 mt-1">{{ $slides->where('is_active', false)->count() }}</p>
                <span class="text-xs text-amber-300/70 mt-1 block">Disembunyikan dari beranda</span>
            </div>
        </div>

        <!-- Slide Table -->
        <div class="glass-card rounded-2xl overflow-hidden border border-slate-700/60 shadow-2xl">
            <div class="p-5 border-b border-slate-700/60 flex items-center justify-between">
                <h2 class="text-base font-bold text-white flex items-center gap-2">
                    <span>Daftar Slide Slider</span>
                    <span class="text-xs font-normal text-slate-400">({{ $slides->count() }} slide)</span>
                </h2>
                <span class="text-xs text-slate-400">Urutan terkecil (1) akan diputar paling awal</span>
            </div>

            @if($slides->isEmpty())
                <div class="p-12 text-center">
                    <div class="w-16 h-16 mx-auto rounded-full bg-navy-800 flex items-center justify-center text-slate-400 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="text-base font-bold text-white">Belum Ada Slide</h3>
                    <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">Mulai tambahkan slide pertama Anda dengan mengunggah gambar atau memasukkan URL gambar berkualitas tinggi.</p>
                    <a href="{{ route('admin.hero-slides.create') }}" class="inline-flex items-center gap-2 mt-4 px-4 py-2 rounded-xl bg-sky-500 hover:bg-sky-400 text-navy-950 font-bold text-xs transition">
                        + Tambah Slide Sekarang
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-300">
                        <thead class="bg-navy-900/90 text-xs uppercase font-semibold text-slate-400 border-b border-slate-800">
                            <tr>
                                <th class="px-5 py-4 w-16 text-center">Urutan</th>
                                <th class="px-5 py-4 w-44">Preview Gambar</th>
                                <th class="px-5 py-4">Konten Slide (Headline & Badge)</th>
                                <th class="px-5 py-4 w-28 text-center">Status</th>
                                <th class="px-5 py-4 w-36 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/80">
                            @foreach($slides as $slide)
                                <tr class="hover:bg-navy-800/40 transition">
                                    <!-- Order Number -->
                                    <td class="px-5 py-4 text-center">
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-navy-800 text-sky-400 font-mono font-bold text-xs border border-slate-700">
                                            {{ $slide->order }}
                                        </span>
                                    </td>

                                    <!-- Image Preview -->
                                    <td class="px-5 py-4">
                                        <div class="relative w-36 h-20 rounded-xl overflow-hidden border border-slate-700/80 shadow-md group">
                                            <img src="{{ $slide->image_url }}" alt="{{ $slide->title }}" 
                                                 class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                                            <a href="{{ $slide->image_url }}" target="_blank"
                                               class="absolute inset-0 bg-navy-950/60 opacity-0 group-hover:opacity-100 flex items-center justify-center text-xs font-semibold text-white transition">
                                                🔍 Perbesar
                                            </a>
                                        </div>
                                    </td>

                                    <!-- Content -->
                                    <td class="px-5 py-4">
                                        <div class="space-y-1">
                                            @if($slide->badge)
                                                <span class="inline-block px-2 py-0.5 rounded-full text-[11px] font-semibold bg-sky-500/15 text-sky-400 border border-sky-500/30">
                                                    {{ $slide->badge }}
                                                </span>
                                            @endif
                                            <h3 class="font-bold text-white text-base leading-snug">
                                                {{ $slide->title }}
                                            </h3>
                                            @if($slide->subtitle)
                                                <p class="text-xs text-slate-400 line-clamp-2 max-w-xl">
                                                    {{ $slide->subtitle }}
                                                </p>
                                            @endif
                                            @if($slide->cta_text)
                                                <div class="flex items-center gap-2 pt-1 text-[11px] text-slate-400">
                                                    <span class="text-sky-400">Tombol CTA:</span>
                                                    <span class="font-semibold text-white">{{ $slide->cta_text }}</span>
                                                    <span>→</span>
                                                    <span class="font-mono text-slate-500 truncate max-w-xs">{{ $slide->cta_link }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Status Toggle -->
                                    <td class="px-5 py-4 text-center">
                                        <form action="{{ route('admin.hero-slides.toggle', $slide) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    title="Klik untuk ubah status aktif/non-aktif"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold transition {{ $slide->is_active ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 hover:bg-emerald-500/30' : 'bg-slate-800 text-slate-400 border border-slate-700 hover:bg-slate-700' }}">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $slide->is_active ? 'bg-emerald-400' : 'bg-slate-500' }}"></span>
                                                <span>{{ $slide->is_active ? 'Aktif' : 'Non-Aktif' }}</span>
                                            </button>
                                        </form>
                                    </td>

                                    <!-- Actions -->
                                    <td class="px-5 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.hero-slides.edit', $slide) }}" 
                                               class="px-3 py-1.5 rounded-lg bg-navy-800 hover:bg-sky-500/20 text-slate-300 hover:text-sky-300 border border-slate-700 text-xs font-semibold transition"
                                               title="Edit slide">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.hero-slides.destroy', $slide) }}" method="POST"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus slide «{{ $slide->title }}»?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="px-3 py-1.5 rounded-lg bg-rose-500/10 hover:bg-rose-500/25 text-rose-400 border border-rose-500/30 text-xs font-semibold transition"
                                                        title="Hapus slide">
                                                    Hapus
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
</div>
@endsection
