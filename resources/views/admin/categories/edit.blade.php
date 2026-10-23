@extends('layouts.web')

@section('title', 'Edit Kategori: ' . $category->name . ' – Panel Admin ALFITO')

@section('content')
<div class="py-12 bg-navy-950 min-h-screen text-slate-100">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumbs & Header -->
        <div class="mb-8">
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-2">
                <a href="{{ route('home') }}" class="hover:text-sky-400 transition">Beranda</a>
                <span>/</span>
                <a href="{{ route('dashboard') }}" class="hover:text-sky-400 transition">Dashboard</a>
                <span>/</span>
                <a href="{{ route('admin.categories.index') }}" class="hover:text-sky-400 transition">Kategori</a>
                <span>/</span>
                <span class="text-sky-400 font-bold">Edit #{{ $category->id }}</span>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight flex items-center gap-3">
                        <span>Edit {{ $category->isSubcategory() ? 'Sub-Kategori' : 'Kategori Utama' }}</span>
                        <span class="px-2.5 py-1 text-xs rounded-full bg-sky-500/20 text-sky-400 font-mono border border-sky-500/30">
                            #{{ $category->id }}
                        </span>
                    </h1>
                    <p class="text-sm text-slate-400 mt-1">
                        Perbarui nama, ikon, tema warna, deskripsi, atau ubah kategori induknya.
                    </p>
                </div>
                <!-- Delete button inside header -->
                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori \'{{ $category->name }}\'?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-4 py-2.5 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 hover:text-rose-300 border border-rose-500/30 text-xs font-semibold transition flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <span>Hapus Kategori</span>
                    </button>
                </form>
            </div>
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

        <form action="{{ route('admin.categories.update', $category) }}" method="POST"
              x-data="{
                  name: '{{ old('name', addslashes($category->name)) }}',
                  parentId: '{{ old('parent_id', $category->parent_id ?? '') }}',
                  icon: '{{ old('icon', $category->icon ?? '🎯') }}',
                  color: '{{ old('color', $category->color ?? 'sky') }}',
                  description: '{{ old('description', addslashes($category->description ?? '')) }}',
                  order: '{{ old('order', $category->order ?? 0) }}',
                  isActive: {{ old('is_active', $category->is_active ? 'true' : 'false') }},
                  slug: '{{ old('slug', $category->slug) }}',
                  generateSlug() {
                      if (!this.slug || this.slug === '') {
                          return this.name.toLowerCase()
                              .replace(/[^\w ]+/g, '')
                              .replace(/ +/g, '-');
                      }
                      return this.slug;
                  }
              }"
              class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Form Card 1: Hierarki & Kategori Induk -->
            <div class="glass-card p-6 rounded-2xl border border-slate-700/60 shadow-xl space-y-5">
                <h2 class="text-base font-bold text-white flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-sky-500/20 text-sky-400 text-xs flex items-center justify-center font-bold">1</span>
                    <span>Hierarki Kategori</span>
                </h2>

                <div>
                    <label for="parent_id" class="block text-xs font-semibold text-slate-300 mb-2">
                        Pilih Kategori Induk (Parent)
                    </label>
                    <select name="parent_id" id="parent_id" x-model="parentId"
                            class="glass-input w-full px-4 py-3 rounded-xl text-sm border border-slate-700 bg-navy-900 text-white focus:border-sky-500 transition">
                        <option value="" class="bg-navy-900 text-slate-300">
                            ⭐ [ Tanpa Induk — Kategori Utama ]
                        </option>
                        @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}" {{ (old('parent_id', $category->parent_id) == $parent->id) ? 'selected' : '' }} class="bg-navy-900 text-white">
                                {{ $parent->icon ?? '🏢' }} {{ $parent->name }} (Kategori Induk)
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-slate-400 mt-1.5">
                        <span x-show="parentId">Kategori ini adalah <strong class="text-sky-400">Sub-Kategori</strong> di bawah kategori induk terpilih.</span>
                        <span x-show="!parentId">Kategori ini adalah <strong class="text-emerald-400">Kategori Utama (Induk)</strong>.</span>
                    </p>
                </div>
            </div>

            <!-- Form Card 2: Informasi Detail Kategori -->
            <div class="glass-card p-6 rounded-2xl border border-slate-700/60 shadow-xl space-y-5">
                <h2 class="text-base font-bold text-white flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-sky-500/20 text-sky-400 text-xs flex items-center justify-center font-bold">2</span>
                    <span>Informasi & Identitas</span>
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- Nama Kategori -->
                    <div class="space-y-1.5 sm:col-span-2">
                        <label for="name" class="block text-xs font-semibold text-slate-300">
                            Nama Kategori / Sub-Kategori <span class="text-rose-400">*</span>
                        </label>
                        <input type="text" name="name" id="name" required x-model="name"
                               placeholder="Contoh: Outbound & Team Building"
                               class="glass-input w-full px-4 py-3 rounded-xl text-sm border border-slate-700 bg-navy-900 text-white placeholder-slate-500 focus:border-sky-500 transition">
                    </div>

                    <!-- Slug URL -->
                    <div class="space-y-1.5 sm:col-span-2">
                        <label for="slug" class="block text-xs font-semibold text-slate-300">
                            Slug URL
                        </label>
                        <div class="flex items-center">
                            <span class="px-3.5 py-3 rounded-l-xl bg-navy-800 border border-r-0 border-slate-700 text-slate-400 text-xs font-mono">
                                /kategori/
                            </span>
                            <input type="text" name="slug" id="slug" x-model="slug"
                                   class="glass-input w-full px-4 py-3 rounded-r-xl text-sm border border-slate-700 bg-navy-900 text-white placeholder-slate-500 font-mono focus:border-sky-500 transition">
                        </div>
                    </div>

                    <!-- Icon / Emoji -->
                    <div class="space-y-1.5">
                        <label for="icon" class="block text-xs font-semibold text-slate-300">
                            Icon atau Emoji <span class="text-rose-400">*</span>
                        </label>
                        <div class="flex items-center gap-2">
                            <input type="text" name="icon" id="icon" x-model="icon" maxlength="50"
                                   class="glass-input w-24 px-4 py-2.5 rounded-xl text-center text-lg border border-slate-700 bg-navy-900 text-white focus:border-sky-500 transition">
                            <!-- Quick emoji selector -->
                            <div class="flex flex-wrap gap-1">
                                @foreach(['🏢', '🎯', '📊', '👨‍👩‍👧‍👦', '✨', '🚀', '🏖️', '🏕️', '🚌', '⛰️', '🌊'] as $emoji)
                                    <button type="button" @click="icon = '{{ $emoji }}'"
                                            class="w-8 h-8 rounded-lg bg-navy-800 hover:bg-navy-700 border border-slate-700 text-sm flex items-center justify-center transition">
                                        {{ $emoji }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Tema Warna -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-300">Aksen Warna Tema</label>
                        <div class="grid grid-cols-6 gap-2 pt-1">
                            @foreach(['sky' => 'Biru Langit', 'emerald' => 'Hijau Zamrud', 'indigo' => 'Indigo Elegan', 'amber' => 'Kuning Emas', 'purple' => 'Ungu Glamour', 'rose' => 'Merah Muda'] as $key => $label)
                                <label class="flex flex-col items-center gap-1 cursor-pointer">
                                    <input type="radio" name="color" value="{{ $key }}" x-model="color" class="hidden">
                                    <div class="w-8 h-8 rounded-full border-2 transition flex items-center justify-center"
                                         :class="color === '{{ $key }}' ? 'border-white scale-110 shadow-lg' : 'border-transparent opacity-60 hover:opacity-100'"
                                         style="background-color: var(--tw-color-{{ $key }}-500, #38bdf8);">
                                        <span x-show="color === '{{ $key }}'" class="text-navy-950 text-xs font-bold">✓</span>
                                    </div>
                                    <span class="text-[10px] text-slate-400">{{ $key }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Urutan / Order -->
                    <div class="space-y-1.5">
                        <label for="order" class="block text-xs font-semibold text-slate-300">Urutan Tampil (Order)</label>
                        <input type="number" name="order" id="order" min="0" x-model="order"
                               class="glass-input w-full px-4 py-2.5 rounded-xl text-sm border border-slate-700 bg-navy-900 text-white focus:border-sky-500 transition">
                    </div>

                    <!-- Status Aktif -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-300">Status Visibilitas</label>
                        <div class="flex items-center gap-3 pt-2">
                            <label class="flex items-center gap-2 cursor-pointer text-sm">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }} class="w-4 h-4 rounded text-sky-500 focus:ring-sky-400 border-slate-700 bg-navy-900">
                                <span class="text-slate-200">Aktifkan & Tampilkan di Web</span>
                            </label>
                        </div>
                    </div>

                    <!-- Deskripsi Singkat -->
                    <div class="space-y-1.5 sm:col-span-2">
                        <label for="description" class="block text-xs font-semibold text-slate-300">
                            Deskripsi Singkat (Opsional)
                        </label>
                        <textarea name="description" id="description" rows="3" x-model="description"
                                  class="glass-input w-full px-4 py-3 rounded-xl text-sm border border-slate-700 bg-navy-900 text-white placeholder-slate-500 focus:border-sky-500 transition">{{ old('description', $category->description) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Form Card 3: Live Preview -->
            <div class="glass-card p-6 rounded-2xl border border-slate-700/60 shadow-xl space-y-3">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-2">
                    <span>👁️ Pratinjau Tampilan (Live Preview)</span>
                </h3>
                <div class="p-5 rounded-xl bg-navy-900/90 border border-slate-700 flex items-center justify-between">
                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-xl bg-sky-500/20 border border-sky-400/30 flex items-center justify-center text-2xl font-bold shadow-inner"
                             x-text="icon || '🏷️'">
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h4 class="text-base font-bold text-white" x-text="name || 'Nama Kategori / Sub-Kategori'"></h4>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold font-mono bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                    AKTIF
                                </span>
                            </div>
                            <p class="text-xs text-slate-400 mt-0.5 max-w-lg" x-text="description || 'Deskripsi singkat kategori akan tampil di sini...'"></p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-xs px-2.5 py-1 rounded-full bg-slate-800 text-slate-300 font-mono"
                              x-text="'/' + generateSlug()">
                        </span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4">
                <a href="{{ route('admin.categories.index') }}"
                   class="px-5 py-3 rounded-xl bg-navy-900 hover:bg-navy-800 text-slate-300 font-semibold text-xs border border-slate-700 transition">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-3 rounded-xl bg-gradient-to-r from-sky-500 to-sky-400 hover:from-sky-400 hover:to-sky-300 text-navy-950 font-bold text-xs shadow-lg shadow-sky-500/25 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
