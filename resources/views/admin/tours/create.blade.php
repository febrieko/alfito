@extends('layouts.web')

@section('title', 'Tambah Paket Wisata – Panel Admin ALFITO')

@section('content')
<div class="py-12 bg-navy-950 min-h-screen text-slate-100">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-2">
                <a href="{{ route('home') }}" class="hover:text-sky-400 transition">Beranda</a>
                <span>/</span>
                <a href="{{ route('admin.dashboard') }}" class="hover:text-sky-400 transition">Admin</a>
                <span>/</span>
                <a href="{{ route('admin.tours.index') }}" class="hover:text-sky-400 transition">Paket Wisata</a>
                <span>/</span>
                <span class="text-sky-400 font-bold">Tambah Baru</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight flex items-center gap-3">
                <span>Tambah Paket Wisata Baru</span>
                <span class="px-2.5 py-1 text-xs rounded-full bg-sky-500/20 text-sky-400 font-mono border border-sky-500/30">
                    Form Paket & Itinerary
                </span>
            </h1>
            <p class="text-sm text-slate-400 mt-1">
                Lengkapi rincian paket perjalanan, rencana kegiatan per hari (itinerary), dan fasilitas include/exclude.
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

        <form action="{{ route('admin.tours.store') }}" method="POST" enctype="multipart/form-data"
              x-data="{
                  durationDays: {{ old('duration_days', 3) }},
                  durationNights: {{ old('duration_nights', 2) }},
                  days: [
                      {
                          day: 1,
                          title: 'Kedatangan, Penjemputan & Check-in Hotel',
                          activities: 'Penjemputan tim rombongan di meeting point (Bandara / Stasiun)\nPerjalanan menuju hotel / resort pilihan\nCheck-in & istirahat sejenak\nMakan malam bersama & briefing kegiatan'
                      },
                      {
                          day: 2,
                          title: 'Kegiatan Outbound, Fun Games & Gala Dinner',
                          activities: 'Sarapan pagi di resto hotel\nSesi Ice Breaking & Fun Team Building bersama fasilitator\nMakan siang prasmanan\nKegiatan wisata alam & sesi bebas\nGala dinner & awarding night perayaan'
                      },
                      {
                          day: 3,
                          title: 'Wisata Belanja Oleh-Oleh & Transfer Pulang',
                          activities: 'Sarapan pagi & proses check-out hotel\nKunjungan ke pusat oleh-oleh khas daerah\nMakan siang lokal\nPengantaran rombongan kembali ke bandara / stasiun'
                      }
                  ],
                  includesText: `Transportasi AC Pariwisata selama program tour\nAkomodasi Hotel / Resort berbintang sesuai pilihan durasi\nKonsumsi makan sesuai program itinerary\nTiket masuk seluruh objek wisata dalam jadwal\nFasilitator & Tour Leader profesional berlisensi\nDokumentasi foto & video perjalanan\nAir mineral harian selama kegiatan\nP3K Standar & Asuransi Perjalanan`,
                  excludesText: `Tiket pesawat / kereta api menuju meeting point\nPengeluaran pribadi (laundry, minibar, belanja pribadi)\nTipping sukarela untuk Tour Leader & Driver\nWahana berbayar opsional di luar jadwal`,
                  syncDays() {
                      const count = parseInt(this.durationDays) || 1;
                      if (this.days.length < count) {
                          for (let i = this.days.length + 1; i <= count; i++) {
                              this.days.push({
                                  day: i,
                                  title: 'Rencana Kegiatan Hari ke-' + i,
                                  activities: '08.00: Sarapan pagi\n09.30: Aktivitas wisata / agenda acara\n12.30: Makan siang bersama\n18.30: Makan malam & istirahat'
                              });
                          }
                      } else if (this.days.length > count && count >= 1) {
                          this.days = this.days.slice(0, count);
                      }
                      if (this.durationNights === '' || parseInt(this.durationNights) === Math.max(0, count - 2)) {
                          this.durationNights = Math.max(0, count - 1);
                      }
                  },
                  addDay() {
                      const newDayNumber = this.days.length + 1;
                      this.days.push({
                          day: newDayNumber,
                          title: 'Rencana Kegiatan Hari ke-' + newDayNumber,
                          activities: '08.00: Sarapan pagi\n10.00: Kegiatan wisata lanjutan\n12.30: Makan siang\n16.00: Selesai kegiatan'
                      });
                      this.durationDays = this.days.length;
                  },
                  removeDay(index) {
                      if (this.days.length > 1) {
                          this.days.splice(index, 1);
                          this.days.forEach((d, idx) => d.day = idx + 1);
                          this.durationDays = this.days.length;
                      }
                  },
                  addIncludePreset(text) {
                      if (!this.includesText.includes(text)) {
                          this.includesText = this.includesText ? (this.includesText.trim() + '\n' + text) : text;
                      }
                  },
                  addExcludePreset(text) {
                      if (!this.excludesText.includes(text)) {
                          this.excludesText = this.excludesText ? (this.excludesText.trim() + '\n' + text) : text;
                      }
                  }
              }"
              class="space-y-8">
            @csrf

            <!-- Card 1: Informasi Dasar -->
            <div class="glass-card p-6 rounded-2xl border border-slate-700/60 shadow-xl space-y-5">
                <h2 class="text-base font-bold text-white flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-sky-500/20 text-sky-400 text-xs flex items-center justify-center font-bold">1</span>
                    <span>Informasi Utama Paket</span>
                </h2>

                <div class="space-y-4">
                    <div>
                        <label for="title" class="block text-xs font-semibold text-slate-300 mb-1">Judul Paket Wisata <span class="text-rose-400">*</span></label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required placeholder="Contoh: Gathering Kantor Outbound & Rafting 3H2M di Coban Rondo"
                               class="glass-input w-full px-4 py-3 rounded-xl text-sm border border-slate-700 bg-navy-900 text-white placeholder-slate-500 focus:border-sky-500 transition">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="destination_id" class="block text-xs font-semibold text-slate-300 mb-1">Destinasi <span class="text-rose-400">*</span></label>
                            <select name="destination_id" id="destination_id" required class="glass-input w-full px-3 py-2.5 rounded-xl text-xs border border-slate-700 bg-navy-900 text-white cursor-pointer">
                                <option value="">Pilih Destinasi</option>
                                @foreach($destinations as $dest)
                                    <option value="{{ $dest->id }}" {{ old('destination_id') == $dest->id ? 'selected' : '' }}>
                                        📍 {{ $dest->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="category_id" class="block text-xs font-semibold text-slate-300 mb-1">Kategori / Sub-Kategori</label>
                            <select name="category_id" id="category_id" class="glass-input w-full px-3 py-2.5 rounded-xl text-xs border border-slate-700 bg-navy-900 text-white cursor-pointer">
                                <option value="">Tanpa Kategori</option>
                                @foreach($categories as $parent)
                                    <optgroup label="{{ $parent->icon }} {{ $parent->name }}" class="bg-navy-900 text-sky-400 font-bold">
                                        <option value="{{ $parent->id }}" {{ old('category_id') == $parent->id ? 'selected' : '' }} class="bg-navy-900 text-white font-normal">
                                            ★ {{ $parent->name }}
                                        </option>
                                        @foreach($parent->children as $sub)
                                            <option value="{{ $sub->id }}" {{ old('category_id') == $sub->id ? 'selected' : '' }} class="bg-navy-900 text-white font-normal">
                                                &nbsp;&nbsp;↳ {{ $sub->icon }} {{ $sub->name }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="user_id" class="block text-xs font-semibold text-slate-300 mb-1">Penanggung Jawab / Agen <span class="text-rose-400">*</span></label>
                            <select name="user_id" id="user_id" required class="glass-input w-full px-3 py-2.5 rounded-xl text-xs border border-slate-700 bg-navy-900 text-white cursor-pointer">
                                @foreach($agents as $agent)
                                    <option value="{{ $agent->id }}" {{ (old('user_id', auth()->id()) == $agent->id) ? 'selected' : '' }}>
                                        {{ $agent->name }} ({{ strtoupper($agent->role) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Durasi, Kapasitas & Harga -->
            <div class="glass-card p-6 rounded-2xl border border-slate-700/60 shadow-xl space-y-5">
                <h2 class="text-base font-bold text-white flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-sky-500/20 text-sky-400 text-xs flex items-center justify-center font-bold">2</span>
                    <span>Durasi, Peserta & Harga</span>
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-300 mb-1">Tipe Perjalanan <span class="text-rose-400">*</span></label>
                        <select name="type" required class="glass-input w-full px-3 py-2.5 rounded-xl text-xs border border-slate-700 bg-navy-900 text-white">
                            <option value="open_trip" {{ old('type') == 'open_trip' ? 'selected' : '' }}>Open Trip (Gabungan)</option>
                            <option value="private_trip" {{ old('type') == 'private_trip' ? 'selected' : '' }}>Private Trip (Eksklusif)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-300 mb-1">Durasi Hari <span class="text-rose-400">*</span></label>
                        <input type="number" name="duration_days" id="duration_days" min="1" max="30"
                               x-model="durationDays" @input="syncDays()" required
                               class="glass-input w-full px-3 py-2.5 rounded-xl text-xs border border-slate-700 bg-navy-900 text-white font-bold text-sky-400">
                        <span class="text-[10px] text-slate-400 mt-1 block">Rencana perjalanan otomatis disesuaikan</span>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-300 mb-1">Durasi Malam</label>
                        <input type="number" name="duration_nights" min="0" max="30"
                               x-model="durationNights"
                               class="glass-input w-full px-3 py-2.5 rounded-xl text-xs border border-slate-700 bg-navy-900 text-white">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-300 mb-1">Harga per Pax (Rp) <span class="text-rose-400">*</span></label>
                        <input type="number" name="price" min="0" step="1000" value="{{ old('price', 1500000) }}" required
                               class="glass-input w-full px-3 py-2.5 rounded-xl text-xs border border-slate-700 bg-navy-900 text-white font-mono font-bold text-emerald-400">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-300 mb-1">Minimal Pax <span class="text-rose-400">*</span></label>
                        <input type="number" name="min_pax" min="1" value="{{ old('min_pax', 1) }}" required
                               class="glass-input w-full px-3 py-2.5 rounded-xl text-xs border border-slate-700 bg-navy-900 text-white">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-300 mb-1">Maksimal Pax <span class="text-rose-400">*</span></label>
                        <input type="number" name="max_pax" min="1" value="{{ old('max_pax', 50) }}" required
                               class="glass-input w-full px-3 py-2.5 rounded-xl text-xs border border-slate-700 bg-navy-900 text-white">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block font-semibold text-slate-300 mb-1">Harga Private Trip (Opsional)</label>
                        <input type="number" name="price_private" min="0" step="1000" value="{{ old('price_private') }}" placeholder="Kosongkan jika tidak ada"
                               class="glass-input w-full px-3 py-2.5 rounded-xl text-xs border border-slate-700 bg-navy-900 text-white font-mono">
                    </div>
                </div>
            </div>

            <!-- Card 3: Rencana Perjalanan Wisata (Itinerary Dinamis) -->
            <div class="glass-card p-6 rounded-2xl border border-slate-700/60 shadow-xl space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h2 class="text-base font-bold text-white flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-sky-500/20 text-sky-400 text-xs flex items-center justify-center font-bold">3</span>
                            <span>Rencana Perjalanan Wisata (Itinerary)</span>
                        </h2>
                        <p class="text-xs text-slate-400 mt-1">
                            Atur jadwal dan daftar agenda kegiatan untuk setiap harinya. Form otomatis menyesuaikan dengan durasi paket (<span x-text="durationDays"></span> hari).
                        </p>
                    </div>
                    <button type="button" @click="addDay()"
                            class="px-3.5 py-2 rounded-xl bg-sky-500/20 hover:bg-sky-500/30 text-sky-300 font-bold text-xs border border-sky-500/30 transition flex items-center gap-1.5 self-start sm:self-center">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        <span>+ Tambah Hari</span>
                    </button>
                </div>

                <!-- Day Cards Container -->
                <div class="space-y-4">
                    <template x-for="(dayItem, index) in days" :key="index">
                        <div class="p-5 rounded-2xl bg-navy-900/90 border border-slate-800 space-y-3 relative transition hover:border-slate-700">
                            <!-- Hidden inputs for backend submission -->
                            <input type="hidden" :name="'itinerary[' + index + '][day]'" :value="dayItem.day">

                            <div class="flex items-center justify-between gap-3">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-8 h-8 rounded-xl bg-sky-500/20 text-sky-400 font-bold text-xs flex items-center justify-center border border-sky-400/30">
                                        H<span x-text="dayItem.day"></span>
                                    </span>
                                    <span class="font-bold text-white text-sm">Hari ke-<span x-text="dayItem.day"></span></span>
                                </div>
                                <button type="button" @click="removeDay(index)"
                                        x-show="days.length > 1"
                                        class="p-1 rounded-lg text-rose-400 hover:bg-rose-500/20 transition text-xs"
                                        title="Hapus Hari Ini">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>

                            <!-- Judul Hari -->
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-400 mb-1">Judul / Tema Hari</label>
                                <input type="text" :name="'itinerary[' + index + '][title]'"
                                       x-model="dayItem.title" required
                                       placeholder="Contoh: Kedatangan, Check-in & Gala Dinner"
                                       class="glass-input w-full px-3.5 py-2 rounded-xl text-xs border border-slate-700 bg-navy-950 text-white placeholder-slate-500 focus:border-sky-500">
                            </div>

                            <!-- Rincian Agenda Kegiatan -->
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-400 mb-1">
                                    Rincian Agenda Kegiatan <span class="text-slate-500 font-normal">(Tulis 1 kegiatan per baris)</span>
                                </label>
                                <textarea :name="'itinerary[' + index + '][activities]'"
                                          x-model="dayItem.activities" rows="4" required
                                          placeholder="08.00: Penjemputan peserta di meeting point&#10;12.00: Makan siang prasmanan&#10;14.00: Sesi Ice Breaking & Outbound Games&#10;18.30: Gala Dinner & Awarding Night"
                                          class="glass-input w-full px-3.5 py-2.5 rounded-xl text-xs border border-slate-700 bg-navy-950 text-slate-200 placeholder-slate-600 font-sans leading-relaxed"></textarea>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Card 4: Fasilitas (Include & Exclude) -->
            <div class="glass-card p-6 rounded-2xl border border-slate-700/60 shadow-xl space-y-6">
                <div>
                    <h2 class="text-base font-bold text-white flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-sky-500/20 text-sky-400 text-xs flex items-center justify-center font-bold">4</span>
                        <span>Fasilitas (Include & Exclude)</span>
                    </h2>
                    <p class="text-xs text-slate-400 mt-1">
                        Tuliskan rincian fasilitas apa saja yang sudah termasuk dan yang tidak termasuk dalam paket (1 fasilitas per baris).
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Includes -->
                    <div class="space-y-3 p-5 rounded-2xl bg-navy-900/80 border border-emerald-500/30">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xs font-bold text-emerald-400 uppercase tracking-wider flex items-center gap-1.5">
                                <span>✓</span> Fasilitas Termasuk (Include)
                            </h3>
                            <span class="text-[10px] text-slate-400">1 baris = 1 poin</span>
                        </div>

                        <!-- Quick Preset Chips -->
                        <div class="flex flex-wrap gap-1 text-[11px]">
                            <button type="button" @click="addIncludePreset('Transportasi AC Pariwisata selama trip')"
                                    class="px-2 py-0.5 rounded-lg bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-300 border border-emerald-500/20 transition">
                                + Transportasi AC
                            </button>
                            <button type="button" @click="addIncludePreset('Akomodasi Hotel / Resort berbintang')"
                                    class="px-2 py-0.5 rounded-lg bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-300 border border-emerald-500/20 transition">
                                + Hotel/Resort
                            </button>
                            <button type="button" @click="addIncludePreset('Makan sesuai jadwal itinerary')"
                                    class="px-2 py-0.5 rounded-lg bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-300 border border-emerald-500/20 transition">
                                + Konsumsi/Makan
                            </button>
                            <button type="button" @click="addIncludePreset('Tiket masuk seluruh objek wisata')"
                                    class="px-2 py-0.5 rounded-lg bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-300 border border-emerald-500/20 transition">
                                + Tiket Wisata
                            </button>
                            <button type="button" @click="addIncludePreset('Fasilitator & Tour Leader berlisensi')"
                                    class="px-2 py-0.5 rounded-lg bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-300 border border-emerald-500/20 transition">
                                + Tour Leader
                            </button>
                            <button type="button" @click="addIncludePreset('Dokumentasi foto & video perjalanan')"
                                    class="px-2 py-0.5 rounded-lg bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-300 border border-emerald-500/20 transition">
                                + Dokumentasi
                            </button>
                        </div>

                        <textarea name="includes" x-model="includesText" rows="7" required
                                  placeholder="Contoh:&#10;Transportasi AC Pariwisata&#10;Akomodasi Hotel 2 Malam&#10;Makan pagi, siang, dan malam&#10;Tiket masuk objek wisata&#10;Dokumentasi perjalanan"
                                  class="glass-input w-full px-3.5 py-2.5 rounded-xl text-xs border border-slate-700 bg-navy-950 text-slate-200 placeholder-slate-500 leading-relaxed font-sans"></textarea>
                    </div>

                    <!-- Excludes -->
                    <div class="space-y-3 p-5 rounded-2xl bg-navy-900/80 border border-rose-500/30">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xs font-bold text-rose-400 uppercase tracking-wider flex items-center gap-1.5">
                                <span>✕</span> Tidak Termasuk (Exclude)
                            </h3>
                            <span class="text-[10px] text-slate-400">1 baris = 1 poin</span>
                        </div>

                        <!-- Quick Preset Chips -->
                        <div class="flex flex-wrap gap-1 text-[11px]">
                            <button type="button" @click="addExcludePreset('Tiket pesawat / kereta api ke meeting point')"
                                    class="px-2 py-0.5 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-300 border border-rose-500/20 transition">
                                + Tiket Pesawat/Kereta
                            </button>
                            <button type="button" @click="addExcludePreset('Pengeluaran pribadi (laundry, minibar, belanja)')"
                                    class="px-2 py-0.5 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-300 border border-rose-500/20 transition">
                                + Pengeluaran Pribadi
                            </button>
                            <button type="button" @click="addExcludePreset('Tipping sukarela Guide & Driver')"
                                    class="px-2 py-0.5 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-300 border border-rose-500/20 transition">
                                + Tipping Sukarela
                            </button>
                            <button type="button" @click="addExcludePreset('Wahana berbayar di luar jadwal')"
                                    class="px-2 py-0.5 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-300 border border-rose-500/20 transition">
                                + Wahana Tambahan
                            </button>
                        </div>

                        <textarea name="excludes" x-model="excludesText" rows="7" required
                                  placeholder="Contoh:&#10;Tiket pesawat menuju meeting point&#10;Pengeluaran pribadi & laundry&#10;Tipping sukarela untuk driver"
                                  class="glass-input w-full px-3.5 py-2.5 rounded-xl text-xs border border-slate-700 bg-navy-950 text-slate-200 placeholder-slate-500 leading-relaxed font-sans"></textarea>
                    </div>
                </div>
            </div>

            <!-- Card 5: Gambar & Deskripsi Ringkas -->
            <div class="glass-card p-6 rounded-2xl border border-slate-700/60 shadow-xl space-y-6"
                 x-data="{
                     uploadMode: 'file', // 'file' or 'url'
                     files: [],
                     coverIndex: 0,
                     urlInputs: ['https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=80'],
                     handleFiles(fileList) {
                         const incoming = Array.from(fileList);
                         incoming.forEach(file => {
                             if (!file.type.startsWith('image/')) return;
                             const reader = new FileReader();
                             reader.onload = (e) => {
                                 this.files.push({
                                     file: file,
                                     name: file.name,
                                     size: this.formatBytes(file.size),
                                     rawSize: file.size,
                                     preview: e.target.result
                                 });
                                 this.syncFileInput();
                             };
                             reader.readAsDataURL(file);
                         });
                     },
                     removeFile(idx) {
                         this.files.splice(idx, 1);
                         if (this.coverIndex >= this.files.length) {
                             this.coverIndex = Math.max(0, this.files.length - 1);
                         }
                         this.syncFileInput();
                     },
                     setAsCover(idx) {
                         if (idx > 0 && this.files[idx]) {
                             const chosen = this.files.splice(idx, 1)[0];
                             this.files.unshift(chosen);
                             this.coverIndex = 0;
                             this.syncFileInput();
                         }
                     },
                     syncFileInput() {
                         try {
                             const dt = new DataTransfer();
                             this.files.forEach(f => dt.items.add(f.file));
                             if (this.$refs.galleryInput) {
                                 this.$refs.galleryInput.files = dt.files;
                             }
                         } catch (e) {
                             console.warn('DataTransfer sync:', e);
                         }
                     },
                     formatBytes(bytes) {
                         if (bytes === 0) return '0 B';
                         const k = 1024;
                         const sizes = ['B', 'KB', 'MB', 'GB'];
                         const i = Math.floor(Math.log(bytes) / Math.log(k));
                         return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
                     },
                     addUrl() {
                         this.urlInputs.push('');
                     },
                     removeUrl(idx) {
                         if (this.urlInputs.length > 1) {
                             this.urlInputs.splice(idx, 1);
                         }
                     }
                 }">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h2 class="text-base font-bold text-white flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-sky-500/20 text-sky-400 text-xs flex items-center justify-center font-bold">5</span>
                            <span>Gambar & Deskripsi Ringkas</span>
                        </h2>
                        <p class="text-xs text-slate-400 mt-0.5">
                            Upload foto langsung dari komputer / smartphone. Foto dengan resolusi / ukuran kebesaran akan <strong class="text-sky-300">di-resize otomatis oleh sistem</strong>.
                        </p>
                    </div>

                    <!-- Mode Switcher -->
                    <div class="inline-flex p-1 rounded-xl bg-navy-900 border border-slate-700/80 text-xs self-start sm:self-auto">
                        <button type="button" @click="uploadMode = 'file'"
                                :class="uploadMode === 'file' ? 'bg-sky-500 text-navy-950 font-bold shadow-md' : 'text-slate-400 hover:text-white'"
                                class="px-3 py-1.5 rounded-lg transition flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>Upload File Langsung</span>
                        </button>
                        <button type="button" @click="uploadMode = 'url'"
                                :class="uploadMode === 'url' ? 'bg-sky-500 text-navy-950 font-bold shadow-md' : 'text-slate-400 hover:text-white'"
                                class="px-3 py-1.5 rounded-lg transition flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                            <span>Link URL (Unsplash/CDN)</span>
                        </button>
                    </div>
                </div>

                <!-- Mode 1: Direct File Upload (Dynamic Multi-upload & Auto-resize) -->
                <div x-show="uploadMode === 'file'" class="space-y-4">
                    <!-- Hidden real multiple file input -->
                    <input type="file" x-ref="galleryInput" name="gallery_files[]" multiple accept="image/*" class="hidden"
                           @change="handleFiles($event.target.files)">

                    <!-- Drag & Drop Zone -->
                    <div @dragover.prevent="$el.classList.add('border-sky-400', 'bg-sky-500/10')"
                         @dragleave.prevent="$el.classList.remove('border-sky-400', 'bg-sky-500/10')"
                         @drop.prevent="$el.classList.remove('border-sky-400', 'bg-sky-500/10'); handleFiles($event.dataTransfer.files)"
                         @click="$refs.galleryInput.click()"
                         class="cursor-pointer border-2 border-dashed border-slate-700 hover:border-sky-500/60 rounded-2xl p-6 sm:p-8 text-center bg-navy-900/60 hover:bg-navy-900/90 transition group">
                        <div class="mx-auto w-12 h-12 rounded-2xl bg-sky-500/10 border border-sky-500/20 text-sky-400 flex items-center justify-center mb-3 group-hover:scale-110 transition duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                        </div>
                        <h4 class="text-sm font-bold text-white mb-1">
                            Pilih Foto atau Tarik & Lepas (Drag & Drop) ke Sini
                        </h4>
                        <p class="text-xs text-slate-400 max-w-md mx-auto">
                            Dapat memilih <span class="text-sky-300 font-semibold">lebih dari 1 foto sekaligus</span> (JPG, PNG, WebP).
                        </p>
                        <div class="inline-flex items-center gap-2 mt-3 px-3 py-1 rounded-full bg-navy-950/80 border border-emerald-500/30 text-[11px] text-emerald-300">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span>Auto-Resize Aktif: Gambar kebesaran otomatis diskalakan & dioptimasi</span>
                        </div>
                    </div>

                    <!-- Dynamic Previews List -->
                    <div x-show="files.length > 0" class="space-y-3 pt-2">
                        <div class="flex items-center justify-between">
                            <div class="text-xs font-bold text-slate-200 flex items-center gap-2">
                                <span>Foto Terpilih (<span x-text="files.length"></span> Foto)</span>
                                <span class="text-[10px] text-slate-400 font-normal">Foto urutan #1 otomatis menjadi Cover Utama</span>
                            </div>
                            <button type="button" @click="$refs.galleryInput.click()"
                                    class="text-xs font-semibold text-sky-400 hover:text-sky-300 transition flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                <span>Tambah Foto Lainnya</span>
                            </button>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                            <template x-for="(f, idx) in files" :key="idx">
                                <div class="relative group rounded-xl overflow-hidden border bg-navy-900 transition-all"
                                     :class="idx === 0 ? 'border-sky-400 ring-2 ring-sky-500/30 shadow-lg' : 'border-slate-700/80 hover:border-slate-600'">
                                    
                                    <!-- Aspect Ratio Container -->
                                    <div class="aspect-video w-full bg-navy-950 relative overflow-hidden">
                                        <img :src="f.preview" :alt="f.name" class="w-full h-full object-cover">
                                        
                                        <!-- Delete Button -->
                                        <button type="button" @click.stop="removeFile(idx)"
                                                class="absolute top-1.5 right-1.5 w-6 h-6 rounded-full bg-rose-600/90 hover:bg-rose-600 text-white flex items-center justify-center transition shadow-md"
                                                title="Hapus foto ini">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>

                                        <!-- Badge Status Cover -->
                                        <div class="absolute bottom-1.5 left-1.5 right-1.5 flex items-center justify-between">
                                            <template x-if="idx === 0">
                                                <span class="px-2 py-0.5 rounded-md bg-sky-500 text-navy-950 text-[10px] font-black uppercase tracking-wider shadow">
                                                    ★ Cover Utama
                                                </span>
                                            </template>
                                            <template x-if="idx !== 0">
                                                <button type="button" @click.stop="setAsCover(idx)"
                                                        class="px-2 py-0.5 rounded-md bg-navy-950/90 hover:bg-sky-500 hover:text-navy-950 text-sky-300 text-[10px] font-semibold transition border border-sky-500/30">
                                                    Jadikan Cover
                                                </button>
                                            </template>
                                        </div>
                                    </div>

                                    <!-- File Info & Resize Alert -->
                                    <div class="p-2 text-[10px] space-y-0.5">
                                        <div class="font-medium text-slate-200 truncate" x-text="f.name"></div>
                                        <div class="flex items-center justify-between text-slate-400">
                                            <span x-text="f.size"></span>
                                            <template x-if="f.rawSize > 2000000">
                                                <span class="text-amber-400 font-semibold">Auto-resize</span>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Mode 2: External URLs (Fallback / Unsplash) -->
                <div x-show="uploadMode === 'url'" class="space-y-3 p-4 rounded-xl bg-navy-900/60 border border-slate-700/60">
                    <div>
                        <label for="thumbnail" class="block text-xs font-semibold text-slate-300 mb-1">
                            URL Foto Utama (Cover Thumbnail) <span class="text-rose-400">*</span>
                        </label>
                        <input type="url" name="thumbnail" id="thumbnail"
                               value="{{ old('thumbnail', 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=80') }}"
                               placeholder="https://images.unsplash.com/..."
                               class="glass-input w-full px-4 py-2.5 rounded-xl text-xs border border-slate-700 bg-navy-900 text-white placeholder-slate-500 font-mono">
                    </div>

                    <!-- Dynamic URL Inputs for Gallery -->
                    <div class="space-y-2 pt-2">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-semibold text-slate-300">
                                URL Foto Pendukung Lainnya (Galeri Dinamis)
                            </label>
                            <button type="button" @click="addUrl()" class="text-xs font-semibold text-sky-400 hover:text-sky-300 transition flex items-center gap-1">
                                <span>+ Tambah URL Galeri</span>
                            </button>
                        </div>

                        <template x-for="(url, uidx) in urlInputs" :key="uidx">
                            <div class="flex items-center gap-2">
                                <input type="url" name="gallery_urls[]" x-model="urlInputs[uidx]"
                                       placeholder="https://images.unsplash.com/photo-..."
                                       class="glass-input flex-1 px-3 py-2 rounded-xl text-xs border border-slate-700 bg-navy-950 text-white font-mono placeholder-slate-600">
                                <button type="button" @click="removeUrl(uidx)"
                                        class="p-2 rounded-xl text-rose-400 hover:bg-rose-500/20 transition text-xs"
                                        title="Hapus baris ini">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Deskripsi Ringkas Paket -->
                <div class="space-y-1.5 pt-2 border-t border-slate-800">
                    <div class="flex items-center justify-between">
                        <label for="description" class="block text-xs font-semibold text-slate-300">
                            Deskripsi Ringkas & Gambaran Perjalanan <span class="text-rose-400">*</span>
                        </label>
                        <span class="text-[11px] text-slate-400">Dapat mencakup keunggulan, highlight, dan suasana trip</span>
                    </div>
                    <textarea name="description" id="description" rows="5" required
                              placeholder="Tuliskan deskripsi ringkas paket wisata ini...&#10;Contoh: Nikmati serunya gathering kantor dengan petualangan alam seru, fasilitas lengkap bintang 4, dan fasilitator berpengalaman yang siap mencairkan suasana tim Anda."
                              class="glass-input w-full px-4 py-3 rounded-xl text-xs border border-slate-700 bg-navy-900 text-white placeholder-slate-500 leading-relaxed font-sans">{{ old('description') }}</textarea>
                </div>

                <div class="flex items-center gap-6 pt-2">
                    <label class="flex items-center gap-2 cursor-pointer text-xs">
                        <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 rounded text-sky-500 bg-navy-900 border-slate-700">
                        <span class="text-slate-200">Aktifkan & Tampilkan di Web</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer text-xs">
                        <input type="checkbox" name="is_featured" value="1" class="w-4 h-4 rounded text-amber-500 bg-navy-900 border-slate-700">
                        <span class="text-slate-200">Jadikan Paket Unggulan (Featured)</span>
                    </label>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4">
                <a href="{{ route('admin.tours.index') }}" class="px-5 py-3 rounded-xl bg-navy-900 hover:bg-navy-800 text-slate-300 font-semibold text-xs border border-slate-700 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 rounded-xl bg-gradient-to-r from-sky-500 to-sky-400 hover:from-sky-400 hover:to-sky-300 text-navy-950 font-bold text-xs shadow-lg shadow-sky-500/25 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span>Simpan Paket Wisata</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
