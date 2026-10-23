@extends('layouts.web')

@section('title', 'Tulis Artikel Blog Baru – Panel Admin ALFITO')

@section('content')
<div class="py-12 bg-navy-950 min-h-screen text-slate-100">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-2">
                <a href="{{ route('home') }}" class="hover:text-sky-400 transition">Beranda</a>
                <span>/</span>
                <a href="{{ route('admin.dashboard') }}" class="hover:text-sky-400 transition">Admin</a>
                <span>/</span>
                <a href="{{ route('admin.posts.index') }}" class="hover:text-sky-400 transition">Blog</a>
                <span>/</span>
                <span class="text-sky-400 font-bold">Tulis Baru</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight flex items-center gap-3">
                <span>Tulis Artikel Blog & Panduan Wisata</span>
                <span class="px-2.5 py-1 text-xs rounded-full bg-sky-500/20 text-sky-400 font-mono border border-sky-500/30">
                    Form Artikel
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

        <form action="{{ route('admin.posts.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="glass-card p-6 rounded-2xl border border-slate-700/60 shadow-xl space-y-5">
                <div>
                    <label for="title" class="block text-xs font-semibold text-slate-300 mb-1">Judul Artikel <span class="text-rose-400">*</span></label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required placeholder="Contoh: 10 Rekomendasi Lokasi Outbound & Gathering Kantor Terbaik di Malang"
                           class="glass-input w-full px-4 py-3 rounded-xl text-sm border border-slate-700 bg-navy-900 text-white placeholder-slate-500 focus:border-sky-500">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="category" class="block text-xs font-semibold text-slate-300 mb-1">Kategori Artikel <span class="text-rose-400">*</span></label>
                        <select name="category" id="category" required class="glass-input w-full px-3 py-2.5 rounded-xl text-xs border border-slate-700 bg-navy-900 text-white">
                            <option value="Panduan Wisata" {{ old('category') === 'Panduan Wisata' ? 'selected' : '' }}>Panduan Wisata</option>
                            <option value="Gathering & Outbound" {{ old('category') === 'Gathering & Outbound' ? 'selected' : '' }}>Gathering & Outbound</option>
                            <option value="Tips & Trik" {{ old('category') === 'Tips & Trik' ? 'selected' : '' }}>Tips & Trik</option>
                            <option value="Kuliner & Budaya" {{ old('category') === 'Kuliner & Budaya' ? 'selected' : '' }}>Kuliner & Budaya</option>
                        </select>
                    </div>

                    <div>
                        <label for="destination_id" class="block text-xs font-semibold text-slate-300 mb-1">Terkait Destinasi (Opsional)</label>
                        <select name="destination_id" id="destination_id" class="glass-input w-full px-3 py-2.5 rounded-xl text-xs border border-slate-700 bg-navy-900 text-white">
                            <option value="">Tidak Terikat Destinasi</option>
                            @foreach($destinations as $dest)
                                <option value="{{ $dest->id }}" {{ old('destination_id') == $dest->id ? 'selected' : '' }}>
                                    📍 {{ $dest->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label for="thumbnail" class="block text-xs font-semibold text-slate-300 mb-1">URL Gambar Header Artikel <span class="text-rose-400">*</span></label>
                    <input type="url" name="thumbnail" id="thumbnail" required value="{{ old('thumbnail', 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1200&q=80') }}"
                           class="glass-input w-full px-4 py-2.5 rounded-xl text-xs border border-slate-700 bg-navy-900 text-white font-mono">
                </div>

                <div>
                    <label for="excerpt" class="block text-xs font-semibold text-slate-300 mb-1">Ringkasan Singkat (Excerpt) <span class="text-rose-400">*</span></label>
                    <textarea name="excerpt" id="excerpt" rows="2" required placeholder="Ringkasan isi artikel untuk preview kartu dan meta description..."
                              class="glass-input w-full px-4 py-2.5 rounded-xl text-xs border border-slate-700 bg-navy-900 text-white placeholder-slate-500">{{ old('excerpt') }}</textarea>
                </div>

                <div>
                    <label for="content" class="block text-xs font-semibold text-slate-300 mb-1">Konten Lengkap Artikel <span class="text-rose-400">*</span></label>
                    <textarea name="content" id="content" rows="12" required placeholder="Tuliskan isi artikel lengkap di sini..."
                              class="glass-input w-full px-4 py-3 rounded-xl text-xs border border-slate-700 bg-navy-900 text-white placeholder-slate-500 leading-relaxed font-sans">{{ old('content') }}</textarea>
                </div>

                <div class="pt-2">
                    <label class="flex items-center gap-2 cursor-pointer text-xs">
                        <input type="checkbox" name="is_published" value="1" checked class="w-4 h-4 rounded text-emerald-500 bg-navy-900 border-slate-700">
                        <span class="text-slate-200">Terbitkan Langsung ke Publik</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4">
                <a href="{{ route('admin.posts.index') }}" class="px-5 py-2.5 rounded-xl bg-navy-900 hover:bg-navy-800 text-slate-300 font-semibold text-xs border border-slate-700 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-sky-500 to-sky-400 hover:from-sky-400 hover:to-sky-300 text-navy-950 font-bold text-xs shadow-lg shadow-sky-500/25 transition">
                    Simpan & Terbitkan Artikel
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
