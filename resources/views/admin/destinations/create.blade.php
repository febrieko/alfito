@extends('layouts.web')

@section('title', 'Tambah Destinasi Wisata – Panel Admin ALFITO')

@section('content')
<div class="py-12 bg-navy-950 min-h-screen text-slate-100">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-2">
                <a href="{{ route('home') }}" class="hover:text-sky-400 transition">Beranda</a>
                <span>/</span>
                <a href="{{ route('admin.dashboard') }}" class="hover:text-sky-400 transition">Admin</a>
                <span>/</span>
                <a href="{{ route('admin.destinations.index') }}" class="hover:text-sky-400 transition">Destinasi</a>
                <span>/</span>
                <span class="text-sky-400 font-bold">Tambah Baru</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight flex items-center gap-3">
                <span>Tambah Destinasi Baru</span>
                <span class="px-2.5 py-1 text-xs rounded-full bg-sky-500/20 text-sky-400 font-mono border border-sky-500/30">
                    Form Destinasi
                </span>
            </h1>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-rose-500/15 border border-rose-500/30 text-rose-300 text-sm">
                <div class="font-bold mb-1">Terdapat kesalahan pengisian:</div>
                <ul class="list-disc list-inside space-y-0.5 text-xs">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.destinations.store') }}" method="POST" class="glass-card p-6 rounded-2xl border border-slate-700/60 shadow-xl space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-xs font-semibold text-slate-300 mb-1">Nama Destinasi <span class="text-rose-400">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="Contoh: Labuan Bajo & Komodo"
                       class="glass-input w-full px-4 py-2.5 rounded-xl text-sm border border-slate-700 bg-navy-900 text-white placeholder-slate-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="region" class="block text-xs font-semibold text-slate-300 mb-1">Wilayah / Provinsi <span class="text-rose-400">*</span></label>
                    <input type="text" name="region" id="region" value="{{ old('region', 'Nusa Tenggara Timur') }}" required
                           class="glass-input w-full px-4 py-2.5 rounded-xl text-xs border border-slate-700 bg-navy-900 text-white">
                </div>
                <div>
                    <label for="country" class="block text-xs font-semibold text-slate-300 mb-1">Negara <span class="text-rose-400">*</span></label>
                    <input type="text" name="country" id="country" value="{{ old('country', 'Indonesia') }}" required
                           class="glass-input w-full px-4 py-2.5 rounded-xl text-xs border border-slate-700 bg-navy-900 text-white">
                </div>
            </div>

            <div>
                <label for="thumbnail" class="block text-xs font-semibold text-slate-300 mb-1">URL Foto Utama Destinasi <span class="text-rose-400">*</span></label>
                <input type="url" name="thumbnail" id="thumbnail" required value="{{ old('thumbnail', 'https://images.unsplash.com/photo-1516690561799-46d8f74f9abf?auto=format&fit=crop&w=1200&q=80') }}"
                       class="glass-input w-full px-4 py-2.5 rounded-xl text-xs border border-slate-700 bg-navy-900 text-white font-mono">
            </div>

            <div>
                <label for="description" class="block text-xs font-semibold text-slate-300 mb-1">Deskripsi Destinasi <span class="text-rose-400">*</span></label>
                <textarea name="description" id="description" rows="4" required placeholder="Deskripsikan keindahan alam, rute perjalanan, dan pesona tempat ini..."
                          class="glass-input w-full px-4 py-3 rounded-xl text-xs border border-slate-700 bg-navy-900 text-white placeholder-slate-500">{{ old('description') }}</textarea>
            </div>

            <div class="pt-2">
                <label class="flex items-center gap-2 cursor-pointer text-xs">
                    <input type="checkbox" name="is_featured" value="1" class="w-4 h-4 rounded text-amber-500 bg-navy-900 border-slate-700">
                    <span class="text-slate-200">Tampilkan sebagai Destinasi Unggulan di Beranda</span>
                </label>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800">
                <a href="{{ route('admin.destinations.index') }}" class="px-5 py-2.5 rounded-xl bg-navy-900 hover:bg-navy-800 text-slate-300 font-semibold text-xs border border-slate-700 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-sky-500 to-sky-400 hover:from-sky-400 hover:to-sky-300 text-navy-950 font-bold text-xs shadow-lg shadow-sky-500/25 transition">
                    Simpan Destinasi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
