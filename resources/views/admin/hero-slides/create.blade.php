@extends('layouts.web')

@section('title', 'Tambah Slide Hero – Panel Admin ALFITO')

@section('content')
<div class="py-12 bg-navy-950 min-h-screen text-slate-100">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-2">
                <a href="{{ route('home') }}" class="hover:text-sky-400 transition">Beranda</a>
                <span>/</span>
                <a href="{{ route('admin.hero-slides.index') }}" class="hover:text-sky-400 transition">Slider Hero</a>
                <span>/</span>
                <span class="text-sky-400 font-bold">Tambah Baru</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                Tambah Slide Hero Baru
            </h1>
            <p class="text-sm text-slate-400 mt-1">
                Lengkapi informasi gambar dan headline untuk slide yang akan ditampilkan pada slider beranda.
            </p>
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

        <form action="{{ route('admin.hero-slides.store') }}" method="POST" enctype="multipart/form-data" 
              x-data="{
                  imageType: 'url',
                  imageUrl: '{{ old('image_url', 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=2000&q=85') }}',
                  previewSrc: 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=2000&q=85',
                  title: '{{ old('title', 'Pesona Pantai Eksotis & Terumbu Karang Nusantara') }}',
                  subtitle: '{{ old('subtitle', 'Temukan keindahan pulau tropis Indonesia dengan pemandu berpengalaman dan jaminan 100% Pasti Berangkat.') }}',
                  badge: '{{ old('badge', 'Destinasi Eksklusif Liburan') }}',
                  handleFileUpload(event) {
                      const file = event.target.files[0];
                      if (file) {
                          this.previewSrc = URL.createObjectURL(file);
                      }
                  }
              }"
              class="space-y-6">
            @csrf

            <!-- Card 1: Pilihan Gambar -->
            <div class="glass-card p-6 rounded-2xl border border-slate-700/60 shadow-xl space-y-5">
                <h2 class="text-base font-bold text-white flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-sky-500/20 text-sky-400 text-xs flex items-center justify-center font-bold">1</span>
                    <span>Gambar Background Slide</span>
                </h2>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2">Metode Input Gambar</label>
                    <div class="grid grid-cols-2 gap-3 max-w-md">
                        <label class="flex items-center gap-2 p-3 rounded-xl border cursor-pointer transition"
                               :class="imageType === 'url' ? 'bg-sky-500/15 border-sky-500 text-white font-semibold' : 'bg-navy-900 border-slate-700 text-slate-400 hover:border-slate-600'">
                            <input type="radio" name="image_type" value="url" x-model="imageType" class="hidden">
                            <span>🔗 URL Gambar Web</span>
                        </label>
                        <label class="flex items-center gap-2 p-3 rounded-xl border cursor-pointer transition"
                               :class="imageType === 'file' ? 'bg-sky-500/15 border-sky-500 text-white font-semibold' : 'bg-navy-900 border-slate-700 text-slate-400 hover:border-slate-600'">
                            <input type="radio" name="image_type" value="file" x-model="imageType" class="hidden">
                            <span>📁 Unggah File Gambar</span>
                        </label>
                    </div>
                </div>

                <!-- Input URL -->
                <div x-show="imageType === 'url'" class="space-y-2">
                    <label for="image_url" class="block text-xs font-semibold text-slate-300">
                        Link URL Gambar (Resolusi Tinggi min. 1920x1080) <span class="text-rose-400">*</span>
                    </label>
                    <input type="url" name="image_url" id="image_url" 
                           x-model="imageUrl"
                           @input="previewSrc = imageUrl"
                           placeholder="https://images.unsplash.com/photo-..."
                           class="glass-input w-full px-4 py-3 rounded-xl text-sm focus:outline-none placeholder-slate-500">
                    <p class="text-[11px] text-slate-400">Gunakan link langsung ke file gambar (Unsplash, CDN, atau server hosting).</p>
                </div>

                <!-- Input File Upload -->
                <div x-show="imageType === 'file'" class="space-y-2" x-cloak>
                    <label for="image_file" class="block text-xs font-semibold text-slate-300">
                        Pilih Berkas Gambar dari Komputer <span class="text-rose-400">*</span>
                    </label>
                    <input type="file" name="image_file" id="image_file" accept="image/jpeg,image/png,image/webp"
                           @change="handleFileUpload($event)"
                           class="w-full text-sm text-slate-300 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-sky-500 file:text-navy-950 hover:file:bg-sky-400 cursor-pointer bg-navy-900 border border-slate-700 rounded-xl p-2">
                    <p class="text-[11px] text-slate-400">Format yang didukung: JPG, PNG, WEBP. Maksimal ukuran 5MB.</p>
                </div>
            </div>

            <!-- Card 2: Headline & Konten Teks -->
            <div class="glass-card p-6 rounded-2xl border border-slate-700/60 shadow-xl space-y-5">
                <h2 class="text-base font-bold text-white flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-sky-500/20 text-sky-400 text-xs flex items-center justify-center font-bold">2</span>
                    <span>Teks Headline & Keterangan Slide</span>
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Badge Text -->
                    <div>
                        <label for="badge" class="block text-xs font-semibold text-slate-300 mb-1">
                            Teks Badge (Pita Informasi Kecil di Atas)
                        </label>
                        <input type="text" name="badge" id="badge" x-model="badge"
                               placeholder="Contoh: Tour Operator Resmi & Berlisensi"
                               class="glass-input w-full px-4 py-2.5 rounded-xl text-sm focus:outline-none placeholder-slate-500">
                    </div>

                    <!-- Order -->
                    <div>
                        <label for="order" class="block text-xs font-semibold text-slate-300 mb-1">
                            Urutan Tampil (Order)
                        </label>
                        <input type="number" name="order" id="order" value="{{ old('order', $nextOrder) }}" min="0"
                               class="glass-input w-full px-4 py-2.5 rounded-xl text-sm focus:outline-none">
                        <p class="text-[11px] text-slate-400 mt-1">Nomor lebih kecil tampil lebih awal pada putaran slider.</p>
                    </div>
                </div>

                <!-- Title / Headline -->
                <div>
                    <label for="title" class="block text-xs font-semibold text-slate-300 mb-1">
                        Judul Utama (Headline Besar) <span class="text-rose-400">*</span>
                    </label>
                    <input type="text" name="title" id="title" x-model="title" required
                           placeholder="Contoh: Eksplorasi Keajaiban Danau & Pura Beratan Bali"
                           class="glass-input w-full px-4 py-3 rounded-xl text-sm font-semibold text-white focus:outline-none placeholder-slate-500">
                </div>

                <!-- Subtitle / Deskripsi -->
                <div>
                    <label for="subtitle" class="block text-xs font-semibold text-slate-300 mb-1">
                        Deskripsi / Sub-headline
                    </label>
                    <textarea name="subtitle" id="subtitle" rows="3" x-model="subtitle"
                              placeholder="Deskripsi singkat yang menggugah selera liburan wisatawan..."
                              class="glass-input w-full px-4 py-2.5 rounded-xl text-sm focus:outline-none placeholder-slate-500"></textarea>
                </div>

                <!-- CTA Text & Link (Optional) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2 border-t border-slate-800">
                    <div>
                        <label for="cta_text" class="block text-xs font-semibold text-slate-300 mb-1">
                            Teks Tombol Aksi (Opsional)
                        </label>
                        <input type="text" name="cta_text" id="cta_text" value="{{ old('cta_text') }}"
                               placeholder="Contoh: Lihat Paket Tour"
                               class="glass-input w-full px-4 py-2.5 rounded-xl text-sm focus:outline-none placeholder-slate-500">
                    </div>
                    <div>
                        <label for="cta_link" class="block text-xs font-semibold text-slate-300 mb-1">
                            Link Tujuan Tombol (URL)
                        </label>
                        <input type="text" name="cta_link" id="cta_link" value="{{ old('cta_link') }}"
                               placeholder="Contoh: /paket-wisata atau https://..."
                               class="glass-input w-full px-4 py-2.5 rounded-xl text-sm focus:outline-none placeholder-slate-500">
                    </div>
                </div>

                <!-- Is Active Checkbox -->
                <div class="pt-2">
                    <label class="inline-flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                               class="w-5 h-5 rounded text-sky-500 focus:ring-sky-400 bg-navy-900 border-slate-700">
                        <span class="text-sm font-semibold text-white">Langsung aktifkan dan tampilkan di slider beranda</span>
                    </label>
                </div>
            </div>

            <!-- Card 3: Live Preview Simulation -->
            <div class="glass-card p-6 rounded-2xl border border-slate-700/60 shadow-xl space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-bold text-white flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-sky-500/20 text-sky-400 text-xs flex items-center justify-center font-bold">3</span>
                        <span>Pratinjau Tampilan Slide (Live Preview)</span>
                    </h2>
                    <span class="text-xs text-slate-400">Simulasi tampilan nyata di beranda</span>
                </div>

                <div class="relative w-full h-72 sm:h-80 rounded-2xl overflow-hidden border border-slate-700/80 shadow-2xl flex items-center justify-center text-center p-6">
                    <!-- Background -->
                    <img :src="previewSrc" alt="Preview Slide" 
                         class="absolute inset-0 w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-b from-navy-950/70 via-navy-900/75 to-navy-900"></div>

                    <!-- Overlay Content -->
                    <div class="relative z-10 max-w-xl mx-auto space-y-3">
                        <template x-if="badge">
                            <span class="inline-block px-3 py-1 rounded-full bg-sky-500/20 border border-sky-500/30 text-sky-300 text-xs font-semibold backdrop-blur-md"
                                  x-text="badge"></span>
                        </template>
                        <h3 class="text-xl sm:text-2xl font-black text-white leading-tight drop-shadow-md"
                            x-text="title || 'Judul Headline Slide Anda'"></h3>
                        <p class="text-xs sm:text-sm text-slate-200 line-clamp-2 max-w-md mx-auto drop-shadow"
                           x-text="subtitle || 'Deskripsi singkat mengenai paket atau destinasi liburan'"></p>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-4">
                <a href="{{ route('admin.hero-slides.index') }}" 
                   class="px-5 py-2.5 rounded-xl bg-navy-800 hover:bg-navy-700 text-slate-300 text-sm font-semibold border border-slate-700 transition">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-sky-500 to-sky-400 hover:from-sky-400 hover:to-sky-300 text-navy-950 font-bold text-sm shadow-lg shadow-sky-500/25 transition">
                    Simpan Slide Baru
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
