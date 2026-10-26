@extends('layouts.web')

@section('title', 'Tambah Pengguna Baru – Panel Admin ALFITO')

@section('content')
<div class="py-12 bg-navy-950 min-h-screen text-slate-100">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <!-- Breadcrumbs & Header -->
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-2">
                <a href="{{ route('home') }}" class="hover:text-sky-400 transition">Beranda</a>
                <span>/</span>
                <a href="{{ route('admin.dashboard') }}" class="hover:text-sky-400 transition">Admin</a>
                <span>/</span>
                <a href="{{ route('admin.users.index') }}" class="hover:text-sky-400 transition">Pengguna</a>
                <span>/</span>
                <span class="text-sky-400 font-bold">Tambah Pengguna Baru</span>
            </div>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight flex items-center gap-3">
                        <span>Tambah Pengguna Baru</span>
                        <span class="px-2.5 py-1 text-xs rounded-full bg-sky-500/20 text-sky-400 font-mono border border-sky-500/30">
                            Admin Form
                        </span>
                    </h1>
                    <p class="text-sm text-slate-400 mt-1">
                        Daftarkan akun baru untuk Member, Agen Tur mitra, atau sesama Administrator sistem.
                    </p>
                </div>
                <div>
                    <a href="{{ route('admin.users.index') }}"
                       class="px-4 py-2.5 rounded-xl bg-navy-900 hover:bg-navy-850 text-slate-300 hover:text-white font-bold text-xs border border-slate-700/80 transition flex items-center gap-2">
                        <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        <span>Kembali ke Daftar Pengguna</span>
                    </a>
                </div>
            </div>
        </div>

        @if($errors->any())
            <div class="p-4 rounded-xl bg-rose-500/15 border border-rose-500/30 text-rose-300 text-sm">
                <div class="font-bold mb-1 flex items-center gap-2">
                    <span>✕</span>
                    <span>Terdapat kesalahan pada input form:</span>
                </div>
                <ul class="list-disc list-inside space-y-0.5 text-xs text-rose-200">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Form Card 1: Data Akun -->
            <div class="glass-card p-6 sm:p-8 rounded-3xl border border-slate-800 bg-navy-900/80 shadow-xl space-y-6">
                <h2 class="text-lg font-bold text-white flex items-center gap-2 pb-3 border-b border-slate-800">
                    <span>👤</span>
                    <span>Informasi Data Diri & Akun</span>
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Lengkap -->
                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                            Nama Lengkap <span class="text-rose-400">*</span>
                        </label>
                        <input id="name" name="name" type="text"
                               class="w-full px-4 py-3 rounded-xl bg-navy-950 border border-slate-700/80 text-white placeholder-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/20 text-sm transition"
                               value="{{ old('name') }}" placeholder="Contoh: Budi Pratama" required autofocus>
                    </div>

                    <!-- Alamat Email -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                            Alamat Email <span class="text-rose-400">*</span>
                        </label>
                        <input id="email" name="email" type="email"
                               class="w-full px-4 py-3 rounded-xl bg-navy-950 border border-slate-700/80 text-white placeholder-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/20 text-sm transition"
                               value="{{ old('email') }}" placeholder="nama@email.com" required>
                    </div>

                    <!-- Nomor HP / WhatsApp -->
                    <div>
                        <label for="phone" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                            Nomor WhatsApp / HP
                        </label>
                        <input id="phone" name="phone" type="text"
                               class="w-full px-4 py-3 rounded-xl bg-navy-950 border border-slate-700/80 text-white placeholder-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/20 text-sm transition"
                               value="{{ old('phone') }}" placeholder="0812xxxxxxxx">
                    </div>

                    <!-- Role Pengguna -->
                    <div>
                        <label for="role" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                            Hak Akses Role <span class="text-rose-400">*</span>
                        </label>
                        <select id="role" name="role" required
                                class="w-full px-4 py-3 rounded-xl bg-navy-950 border border-slate-700/80 text-white focus:border-sky-400 focus:ring-2 focus:ring-sky-500/20 text-sm transition cursor-pointer">
                            <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>👤 Member (Pelanggan Trip)</option>
                            <option value="agen" {{ old('role') === 'agen' ? 'selected' : '' }}>💼 Agen Tur (Mitra Pengelola Tour)</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>🛡️ Administrator (Akses Penuh)</option>
                        </select>
                    </div>
                </div>

                <!-- Foto Profil / Avatar -->
                <div class="pt-2">
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                        Foto Profil / Avatar (Opsional)
                    </label>
                    <input type="file" name="avatar_file" accept="image/*"
                           class="w-full px-4 py-2.5 rounded-xl bg-navy-950 border border-slate-700/80 text-slate-300 text-xs focus:border-sky-400 transition">
                    <p class="text-[11px] text-slate-400 mt-1.5">
                        <span class="text-sky-400 font-semibold">⚡ Auto-Resize:</span> Foto besar otomatis di-resize dan dikompresi menjadi WebP berkualitas tinggi.
                    </p>
                </div>

                <!-- Status Aktif / Nonaktif Toggle -->
                <div class="pt-4 border-t border-slate-800/80 flex items-center justify-between">
                    <div>
                        <span class="block text-sm font-bold text-white">Status Keaktifan Akun</span>
                        <span class="block text-xs text-slate-400">Pengguna dengan status nonaktif tidak dapat login ke sistem.</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-navy-950 peer-focus:outline-none rounded-full peer border border-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                        <span class="ml-3 text-xs font-semibold text-slate-300 peer-checked:text-emerald-400">Aktif</span>
                    </label>
                </div>
            </div>

            <!-- Form Card 2: Keamanan Sandi -->
            <div class="glass-card p-6 sm:p-8 rounded-3xl border border-slate-800 bg-navy-900/80 shadow-xl space-y-6">
                <h2 class="text-lg font-bold text-white flex items-center gap-2 pb-3 border-b border-slate-800">
                    <span>🔒</span>
                    <span>Kata Sandi Akun</span>
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                            Kata Sandi Baru <span class="text-rose-400">*</span>
                        </label>
                        <input id="password" name="password" type="password"
                               class="w-full px-4 py-3 rounded-xl bg-navy-950 border border-slate-700/80 text-white placeholder-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/20 text-sm transition"
                               placeholder="Minimal 8 karakter..." required autocomplete="new-password">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                            Konfirmasi Kata Sandi <span class="text-rose-400">*</span>
                        </label>
                        <input id="password_confirmation" name="password_confirmation" type="password"
                               class="w-full px-4 py-3 rounded-xl bg-navy-950 border border-slate-700/80 text-white placeholder-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/20 text-sm transition"
                               placeholder="Ulangi kata sandi..." required autocomplete="new-password">
                    </div>
                </div>

                <!-- Bio / Keterangan Tambahan -->
                <div>
                    <label for="bio" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                        Bio / Catatan Internal (Opsional)
                    </label>
                    <textarea id="bio" name="bio" rows="3"
                              class="w-full px-4 py-3 rounded-xl bg-navy-950 border border-slate-700/80 text-white placeholder-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/20 text-sm transition"
                              placeholder="Catatan tambahan mengenai agen/pengguna ini...">{{ old('bio') }}</textarea>
                </div>
            </div>

            <!-- Submit Button Bar -->
            <div class="flex items-center justify-end gap-4 pt-2">
                <a href="{{ route('admin.users.index') }}"
                   class="px-5 py-3 rounded-xl bg-navy-900 hover:bg-navy-850 text-slate-300 font-bold text-xs border border-slate-700/80 transition">
                    Batal
                </a>
                <button type="submit"
                        class="px-7 py-3 rounded-xl bg-gradient-to-r from-sky-500 via-sky-400 to-blue-500 hover:from-sky-400 hover:to-blue-400 text-navy-950 font-black text-xs uppercase tracking-wider shadow-lg shadow-sky-500/25 transition transform hover:-translate-y-0.5 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>Simpan Pengguna Baru</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
