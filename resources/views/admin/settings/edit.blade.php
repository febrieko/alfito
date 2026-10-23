@extends('layouts.web')

@section('title', 'Profil Perusahaan & Kontak – Panel Admin ALFITO')

@section('content')
<div class="py-12 bg-navy-950 min-h-screen text-slate-100">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-2">
                    <a href="{{ route('home') }}" class="hover:text-sky-400 transition">Beranda</a>
                    <span>/</span>
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-sky-400 transition">Admin</a>
                    <span>/</span>
                    <span class="text-sky-400 font-bold">Profil Perusahaan</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight flex items-center gap-3">
                    <span>Profil Perusahaan & Kontak</span>
                    <span class="px-2.5 py-1 text-xs rounded-full bg-sky-500/20 text-sky-400 font-mono border border-sky-500/30">
                        Pengaturan Resmi
                    </span>
                </h1>
                <p class="text-sm text-slate-400 mt-1 max-w-2xl">
                    Perbarui nama perusahaan, nomor WhatsApp customer service, nomor telepon kantor, alamat resmi, jam operasional, serta rekening pembayaran.
                </p>
            </div>

            <!-- Live WhatsApp Test Button -->
            <div class="flex items-center gap-2">
                <a href="{{ $setting->getWhatsappUrl('Halo CS, tes koneksi nomor WhatsApp dari panel admin') }}" target="_blank"
                   class="px-4 py-2.5 rounded-xl bg-emerald-500/15 hover:bg-emerald-500/25 text-emerald-400 border border-emerald-500/30 font-bold text-xs transition flex items-center gap-2 shadow-lg shadow-emerald-500/10">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.299.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.144.39-.086.159.058 1.011.477 1.184.564.173.087.289.13.332.202.043.072.043.419-.101.824z"/></svg>
                    <span>Tes Chat WhatsApp: {{ $setting->whatsapp_number }}</span>
                </a>
            </div>
        </div>

        <!-- Navigation Bar Admin -->
        @include('admin.partials.nav')

        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 text-sm flex items-center justify-between">
                <div class="flex items-center gap-2 font-semibold">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 rounded-xl bg-rose-500/15 border border-rose-500/30 text-rose-300 text-sm">
                <div class="font-bold mb-1">Terdapat kesalahan pengisian data:</div>
                <ul class="list-disc list-inside space-y-0.5 text-xs">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data"
              x-data="{
                  bankAccounts: {{ json_encode($setting->bank_accounts ?? [
                      ['bank' => 'BCA', 'account_number' => '123-456-7890', 'account_name' => 'PT ALFITO WISATA NUSANTARA'],
                      ['bank' => 'Mandiri', 'account_number' => '144-00-1234567-8', 'account_name' => 'PT ALFITO WISATA NUSANTARA']
                  ]) }},
                  addBank() {
                      this.bankAccounts.push({ bank: '', account_number: '', account_name: '{{ addslashes($setting->company_name) }}' });
                  },
                  removeBank(idx) {
                      if (this.bankAccounts.length > 1) {
                          this.bankAccounts.splice(idx, 1);
                      }
                  }
              }"
              class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Card 1: Identitas & Nama Perusahaan -->
            <div class="glass-card p-6 rounded-2xl border border-slate-700/60 shadow-xl space-y-5">
                <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                    <h2 class="text-base font-bold text-white flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-sky-500/20 text-sky-400 text-xs flex items-center justify-center font-bold">1</span>
                        <span>Identitas & Nama Perusahaan</span>
                    </h2>
                    <span class="text-xs text-slate-400">Tampil di header, invoice & footer</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="company_name" class="block text-xs font-semibold text-slate-300 mb-1">
                            Nama Lengkap / Resmi Perusahaan <span class="text-rose-400">*</span>
                        </label>
                        <input type="text" name="company_name" id="company_name" required
                               value="{{ old('company_name', $setting->company_name) }}"
                               placeholder="Contoh: PT ALFITO WISATA NUSANTARA atau ALFITO Tour & Travel"
                               class="glass-input w-full px-4 py-2.5 rounded-xl text-xs border border-slate-700 bg-navy-900 text-white placeholder-slate-500 focus:border-sky-500">
                    </div>

                    <div>
                        <label for="short_name" class="block text-xs font-semibold text-slate-300 mb-1">
                            Nama Singkat / Brand Call Name <span class="text-rose-400">*</span>
                        </label>
                        <input type="text" name="short_name" id="short_name" required
                               value="{{ old('short_name', $setting->short_name) }}"
                               placeholder="Contoh: ALFITO"
                               class="glass-input w-full px-4 py-2.5 rounded-xl text-xs border border-slate-700 bg-navy-900 text-white placeholder-slate-500 focus:border-sky-500 font-bold">
                    </div>

                    <div class="sm:col-span-2">
                        <label for="tagline" class="block text-xs font-semibold text-slate-300 mb-1 flex items-center justify-between">
                            <span class="flex items-center gap-1.5">
                                <span>✨</span>
                                <span>Slogan / Tagline Perusahaan</span>
                            </span>
                            <span class="text-[11px] text-slate-400 font-normal">Tampil di header, sub-judul brand, & tagline promo</span>
                        </label>
                        <input type="text" name="tagline" id="tagline"
                               value="{{ old('tagline', $setting->tagline) }}"
                               placeholder="Contoh: Spesialis Gathering Kantor & Tour Wisata Indonesia"
                               class="glass-input w-full px-4 py-2.5 rounded-xl text-xs border border-slate-700 bg-navy-900 text-white placeholder-slate-500 focus:border-sky-500">
                    </div>

                    <div class="sm:col-span-2">
                        <label for="description" class="block text-xs font-semibold text-slate-300 mb-1">
                            Keterangan Ringkas / Tentang Perusahaan
                        </label>
                        <textarea name="description" id="description" rows="3"
                                  placeholder="Jelaskan gambaran umum perusahaan, keunggulan layanan, dan rekam jejak tour operator Anda..."
                                  class="glass-input w-full px-4 py-3 rounded-xl text-xs border border-slate-700 bg-navy-900 text-white placeholder-slate-500 leading-relaxed">{{ old('description', $setting->description) }}</textarea>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="license_info" class="block text-xs font-semibold text-slate-300 mb-1">
                            Informasi Legalitas / Izin Usaha Pariwisata
                        </label>
                        <input type="text" name="license_info" id="license_info"
                               value="{{ old('license_info', $setting->license_info) }}"
                               placeholder="Contoh: Berlisensi Resmi ASITA & HPI No. 503/BPW/2026 / TDUP Pariwisata"
                               class="glass-input w-full px-4 py-2.5 rounded-xl text-xs border border-slate-700 bg-navy-900 text-white placeholder-slate-500 focus:border-sky-500">
                    </div>
                </div>
            </div>

            <!-- Card 2: Kontak Utama & WhatsApp -->
            <div class="glass-card p-6 rounded-2xl border border-slate-700/60 shadow-xl space-y-5">
                <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                    <h2 class="text-base font-bold text-white flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-sky-500/20 text-sky-400 text-xs flex items-center justify-center font-bold">2</span>
                        <span>Kontak Utama & Nomor WhatsApp</span>
                    </h2>
                    <span class="text-xs text-emerald-400 font-semibold flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Terhubung ke Tombol Chat Seluruh Web
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- WhatsApp Number with Highlighting -->
                    <div class="sm:col-span-2 p-4 rounded-xl bg-gradient-to-r from-emerald-500/10 via-navy-900 to-navy-900 border border-emerald-500/30">
                        <label for="whatsapp_number" class="block text-xs font-bold text-emerald-300 mb-1 flex items-center gap-1.5">
                            <svg class="w-4 h-4 fill-current text-emerald-400" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.299.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.144.39-.086.159.058 1.011.477 1.184.564.173.087.289.13.332.202.043.072.043.419-.101.824z"/></svg>
                            <span>Nomor WhatsApp Customer Service (Hotline CS) <span class="text-rose-400">*</span></span>
                        </label>
                        <p class="text-[11px] text-slate-400 mb-2">
                            Bisa diawali <code class="text-emerald-300">08...</code> atau <code class="text-emerald-300">628...</code>. Sistem otomatis mengonversi ke link WA resmi.
                        </p>
                        <input type="text" name="whatsapp_number" id="whatsapp_number" required
                               value="{{ old('whatsapp_number', $setting->whatsapp_number) }}"
                               placeholder="Contoh: 081234567890 atau 6281234567890"
                               class="glass-input w-full px-4 py-3 rounded-xl text-sm font-mono font-bold border border-emerald-500/50 bg-navy-950 text-emerald-300 placeholder-slate-600 focus:border-emerald-400">
                    </div>

                    <div>
                        <label for="phone" class="block text-xs font-semibold text-slate-300 mb-1">
                            Nomor Telepon Kantor
                        </label>
                        <input type="text" name="phone" id="phone"
                               value="{{ old('phone', $setting->phone) }}"
                               placeholder="Contoh: 0812-3456-7890 atau (0341) 712345"
                               class="glass-input w-full px-4 py-2.5 rounded-xl text-xs border border-slate-700 bg-navy-900 text-white placeholder-slate-500 focus:border-sky-500">
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-300 mb-1">
                            Email Resmi Kantor
                        </label>
                        <input type="email" name="email" id="email"
                               value="{{ old('email', $setting->email) }}"
                               placeholder="Contoh: info@alfito.com atau cs@alfito.test"
                               class="glass-input w-full px-4 py-2.5 rounded-xl text-xs border border-slate-700 bg-navy-900 text-white placeholder-slate-500 focus:border-sky-500">
                    </div>

                    <div>
                        <label for="operational_hours" class="block text-xs font-semibold text-slate-300 mb-1">
                            Jam Operasional Pelayanan
                        </label>
                        <input type="text" name="operational_hours" id="operational_hours"
                               value="{{ old('operational_hours', $setting->operational_hours) }}"
                               placeholder="Contoh: 08:00 - 22:00 WIB (Senin - Minggu)"
                               class="glass-input w-full px-4 py-2.5 rounded-xl text-xs border border-slate-700 bg-navy-900 text-white placeholder-slate-500 focus:border-sky-500">
                    </div>

                    <div>
                        <label for="address" class="block text-xs font-semibold text-slate-300 mb-1">
                            Alamat Kantor & Lokasi Operasional
                        </label>
                        <textarea name="address" id="address" rows="2"
                                  placeholder="Contoh: Jl. Danau Toba No. 45, Sawojajar, Kota Malang, Jawa Timur 65139"
                                  class="glass-input w-full px-4 py-2 rounded-xl text-xs border border-slate-700 bg-navy-900 text-white placeholder-slate-500 leading-relaxed">{{ old('address', $setting->address) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Card 3: Media Sosial -->
            <div class="glass-card p-6 rounded-2xl border border-slate-700/60 shadow-xl space-y-5">
                <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                    <h2 class="text-base font-bold text-white flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-sky-500/20 text-sky-400 text-xs flex items-center justify-center font-bold">3</span>
                        <span>Akun Media Sosial Resmi</span>
                    </h2>
                    <span class="text-xs text-slate-400">Link channel promosi & portofolio trip</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="instagram_url" class="block text-xs font-semibold text-slate-300 mb-1 flex items-center gap-1.5">
                            <span>📸 Instagram</span>
                        </label>
                        <input type="url" name="instagram_url" id="instagram_url"
                               value="{{ old('instagram_url', $setting->instagram_url) }}"
                               placeholder="https://instagram.com/alfito.travel"
                               class="glass-input w-full px-4 py-2.5 rounded-xl text-xs border border-slate-700 bg-navy-900 text-white placeholder-slate-500 font-mono">
                    </div>

                    <div>
                        <label for="threads_url" class="block text-xs font-semibold text-slate-300 mb-1 flex items-center gap-1.5">
                            <span>🧵 Threads</span>
                        </label>
                        <input type="url" name="threads_url" id="threads_url"
                               value="{{ old('threads_url', $setting->threads_url) }}"
                               placeholder="https://threads.net/@alfito.travel"
                               class="glass-input w-full px-4 py-2.5 rounded-xl text-xs border border-slate-700 bg-navy-900 text-white placeholder-slate-500 font-mono">
                    </div>

                    <div>
                        <label for="tiktok_url" class="block text-xs font-semibold text-slate-300 mb-1 flex items-center gap-1.5">
                            <span>🎵 TikTok</span>
                        </label>
                        <input type="url" name="tiktok_url" id="tiktok_url"
                               value="{{ old('tiktok_url', $setting->tiktok_url) }}"
                               placeholder="https://tiktok.com/@alfito.travel"
                               class="glass-input w-full px-4 py-2.5 rounded-xl text-xs border border-slate-700 bg-navy-900 text-white placeholder-slate-500 font-mono">
                    </div>

                    <div>
                        <label for="facebook_url" class="block text-xs font-semibold text-slate-300 mb-1 flex items-center gap-1.5">
                            <span>📘 Facebook Page</span>
                        </label>
                        <input type="url" name="facebook_url" id="facebook_url"
                               value="{{ old('facebook_url', $setting->facebook_url) }}"
                               placeholder="https://facebook.com/alfito.travel"
                               class="glass-input w-full px-4 py-2.5 rounded-xl text-xs border border-slate-700 bg-navy-900 text-white placeholder-slate-500 font-mono">
                    </div>

                    <div class="sm:col-span-2">
                        <label for="youtube_url" class="block text-xs font-semibold text-slate-300 mb-1 flex items-center gap-1.5">
                            <span>▶️ YouTube Channel</span>
                        </label>
                        <input type="url" name="youtube_url" id="youtube_url"
                               value="{{ old('youtube_url', $setting->youtube_url) }}"
                               placeholder="https://youtube.com/@alfitotravel"
                               class="glass-input w-full px-4 py-2.5 rounded-xl text-xs border border-slate-700 bg-navy-900 text-white placeholder-slate-500 font-mono">
                    </div>
                </div>
            </div>

            <!-- Card 4: Rekening Bank Pembayaran (Dinamis) -->
            <div class="glass-card p-6 rounded-2xl border border-slate-700/60 shadow-xl space-y-5">
                <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                    <div>
                        <h2 class="text-base font-bold text-white flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-sky-500/20 text-sky-400 text-xs flex items-center justify-center font-bold">4</span>
                            <span>Rekening Bank Resmi Perusahaan</span>
                        </h2>
                        <p class="text-xs text-slate-400 mt-0.5">
                            Rekening pembayaran yang ditampilkan kepada pelanggan saat konfirmasi invoice pemesanan.
                        </p>
                    </div>
                    <button type="button" @click="addBank()"
                            class="px-3 py-1.5 rounded-xl bg-sky-500/20 hover:bg-sky-500/30 text-sky-400 border border-sky-500/30 text-xs font-bold transition flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Tambah Rekening Bank</span>
                    </button>
                </div>

                <div class="space-y-3">
                    <template x-for="(acc, bidx) in bankAccounts" :key="bidx">
                        <div class="p-4 rounded-xl bg-navy-900/80 border border-slate-700/80 grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                            <div class="sm:col-span-3">
                                <label class="block text-[11px] font-semibold text-slate-400 mb-1">Nama Bank / E-Wallet</label>
                                <input type="text" :name="'bank_accounts[' + bidx + '][bank]'" x-model="acc.bank"
                                       placeholder="Contoh: BCA / Mandiri / BNI / QRIS" required
                                       class="glass-input w-full px-3 py-2 rounded-xl text-xs border border-slate-700 bg-navy-950 text-white font-bold">
                            </div>

                            <div class="sm:col-span-4">
                                <label class="block text-[11px] font-semibold text-slate-400 mb-1">Nomor Rekening</label>
                                <input type="text" :name="'bank_accounts[' + bidx + '][account_number]'" x-model="acc.account_number"
                                       placeholder="Contoh: 123-456-7890" required
                                       class="glass-input w-full px-3 py-2 rounded-xl text-xs border border-slate-700 bg-navy-950 text-sky-300 font-mono font-bold">
                            </div>

                            <div class="sm:col-span-4">
                                <label class="block text-[11px] font-semibold text-slate-400 mb-1">Atas Nama Pemilik</label>
                                <input type="text" :name="'bank_accounts[' + bidx + '][account_name]'" x-model="acc.account_name"
                                       placeholder="Contoh: PT ALFITO WISATA NUSANTARA" required
                                       class="glass-input w-full px-3 py-2 rounded-xl text-xs border border-slate-700 bg-navy-950 text-slate-200">
                            </div>

                            <div class="sm:col-span-1 flex justify-end sm:justify-center pt-2 sm:pt-4">
                                <button type="button" @click="removeBank(bidx)"
                                        x-show="bankAccounts.length > 1"
                                        class="p-2 rounded-lg text-rose-400 hover:bg-rose-500/20 transition text-xs"
                                        title="Hapus rekening ini">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Card 5: Logo & Tampilan Footer -->
            <div class="glass-card p-6 rounded-2xl border border-slate-700/60 shadow-xl space-y-5">
                <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                    <h2 class="text-base font-bold text-white flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-sky-500/20 text-sky-400 text-xs flex items-center justify-center font-bold">5</span>
                        <span>Logo & Teks Footer</span>
                    </h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">
                            Upload Logo Perusahaan (Sekaligus Favicon)
                        </label>
                        <input type="file" name="logo_file" accept="image/*"
                               class="glass-input w-full px-3 py-2 rounded-xl text-xs border border-slate-700 bg-navy-900 text-slate-300">
                        <p class="text-[11px] text-slate-400 mt-1">Format PNG transparansi atau SVG/WebP disarankan. Otomatis diterapkan pada header, dashboard member, panel admin, serta ikon tab browser (favicon).</p>
                        @if($setting->logo_url)
                            <div class="mt-2.5 p-2.5 rounded-xl bg-navy-950 inline-flex items-center gap-3 border border-slate-800">
                                <img src="{{ $setting->logo_url }}" alt="Logo Perusahaan" class="h-10 w-auto object-contain">
                                <span class="text-[10px] text-emerald-400 font-semibold bg-emerald-500/15 px-2 py-0.5 rounded border border-emerald-500/30">
                                    Aktif di Header, Dashboard & Favicon
                                </span>
                            </div>
                        @endif
                    </div>

                    <div>
                        <label for="footer_text" class="block text-xs font-semibold text-slate-300 mb-1">
                            Teks Hak Cipta / Footer Tambahan
                        </label>
                        <input type="text" name="footer_text" id="footer_text"
                               value="{{ old('footer_text', $setting->footer_text) }}"
                               placeholder="Contoh: All rights reserved. Platform Tour & Travel Terpercaya di Indonesia."
                               class="glass-input w-full px-4 py-2.5 rounded-xl text-xs border border-slate-700 bg-navy-900 text-white placeholder-slate-500">
                    </div>
                </div>
            </div>

            <!-- Submit Button Bar -->
            <div class="flex items-center justify-end gap-3 pt-4">
                <a href="{{ route('admin.dashboard') }}" class="px-5 py-3 rounded-xl bg-navy-900 hover:bg-navy-800 text-slate-300 font-semibold text-xs border border-slate-700 transition">
                    Kembali ke Dashboard
                </a>
                <button type="submit" class="px-6 py-3 rounded-xl bg-gradient-to-r from-sky-500 to-sky-400 hover:from-sky-400 hover:to-sky-300 text-navy-950 font-bold text-xs shadow-lg shadow-sky-500/25 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span>Simpan Perubahan Profil Perusahaan</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
