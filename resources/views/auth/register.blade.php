@extends('layouts.web')

@section('title', 'Daftar Akun Baru – ' . ($company->short_name ?? 'ALFITO'))

@section('content')
<div class="min-h-[calc(100vh-80px)] bg-navy-950 flex items-center justify-center py-10 px-4 sm:px-6 lg:px-8 relative overflow-hidden"
     x-data="{ showPassword: false }">
    
    <!-- Ambient Lights -->
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-sky-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-5xl w-full grid grid-cols-1 lg:grid-cols-12 rounded-3xl overflow-hidden shadow-2xl border border-slate-800/80 bg-navy-900/60 backdrop-blur-xl relative z-10">
        
        <!-- Left Side: Brand Experience (7 Cols) -->
        <div class="hidden lg:flex lg:col-span-7 flex-col justify-between p-10 relative overflow-hidden bg-cover bg-center"
             style="background-image: url('https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?auto=format&fit=crop&w=1400&q=85');">
            
            <div class="absolute inset-0 bg-gradient-to-tr from-navy-950 via-navy-950/80 to-navy-900/40 z-0"></div>

            <div class="relative z-10 space-y-4">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-navy-900/80 border border-sky-500/30 text-xs font-semibold text-sky-300 backdrop-blur-md shadow-lg">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Registrasi Member Resmi</span>
                </div>

                <div class="flex items-center gap-3">
                    @if(!empty($company->logo_url))
                        <img src="{{ $company->logo_url }}" alt="{{ $company->company_name }}" class="h-12 w-auto object-contain">
                    @else
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-sky-400 to-blue-600 flex items-center justify-center text-navy-950 font-black text-2xl shadow-xl shadow-sky-500/25">
                            🧭
                        </div>
                    @endif
                    <div>
                        <span class="text-2xl font-black tracking-tight text-white block">
                            {{ $company->short_name ?? 'ALFITO' }}
                        </span>
                        <span class="text-xs uppercase tracking-widest text-sky-300 font-semibold block -mt-1">
                            {{ $company->tagline ?? 'Tour & Travel Indonesia' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="relative z-10 my-8 space-y-4">
                <h2 class="text-3xl font-black text-white leading-tight">
                    Bergabung Bersama Ribuan Penjelajah Indonesia.
                </h2>
                <p class="text-sm text-slate-300 leading-relaxed max-w-md font-light">
                    Buat akun gratis untuk menikmati kemudahan reservasi paket tour, penawaran harga gathering spesial, dan riwayat pesanan yang terintegrasi.
                </p>

                <div class="space-y-2.5 pt-2 text-xs text-slate-200">
                    <div class="flex items-center gap-2.5">
                        <span class="w-5 h-5 rounded-full bg-sky-500/20 text-sky-400 flex items-center justify-center font-bold text-[10px] border border-sky-500/30">★</span>
                        <span>Diskon Khusus Member & Promo Flash Sale Awal Bulan</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span class="w-5 h-5 rounded-full bg-sky-500/20 text-sky-400 flex items-center justify-center font-bold text-[10px] border border-sky-500/30">★</span>
                        <span>Prioritas Booking Kuota Open Trip Populer</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span class="w-5 h-5 rounded-full bg-sky-500/20 text-sky-400 flex items-center justify-center font-bold text-[10px] border border-sky-500/30">★</span>
                        <span>Konsultasi Bebas Biaya untuk Paket Gathering Perusahaan</span>
                    </div>
                </div>
            </div>

            <div class="relative z-10 text-xs text-slate-400">
                &copy; {{ date('Y') }} {{ $company->company_name ?? 'ALFITO' }}. Wisata Aman, Nyaman & Berkesan.
            </div>
        </div>

        <!-- Right Side: Register Form (5 Cols) -->
        <div class="lg:col-span-5 p-8 sm:p-10 flex flex-col justify-between bg-navy-900/90 relative">
            <div>
                <div class="flex items-center justify-between mb-6">
                    <a href="{{ route('home') }}" class="text-xs font-semibold text-slate-400 hover:text-sky-400 transition flex items-center gap-1.5 group">
                        <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        <span>Beranda</span>
                    </a>
                    <a href="{{ route('login') }}" class="text-xs font-bold text-sky-400 hover:text-sky-300 transition">
                        Sudah punya akun? Masuk
                    </a>
                </div>

                <div class="mb-6 space-y-1">
                    <h1 class="text-2xl font-black text-white tracking-tight">
                        Buat Akun Baru
                    </h1>
                    <p class="text-xs text-slate-400">
                        Lengkapi formulir singkat di bawah ini untuk memulai petualangan.
                    </p>
                </div>

                @if (isset($errors) && $errors->any())
                    <div class="mb-4 p-3 rounded-xl bg-rose-500/15 border border-rose-500/30 text-rose-300 text-xs font-medium">
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-3.5">
                    @csrf

                    <!-- Name -->
                    <div class="space-y-1">
                        <label for="name" class="block text-xs font-semibold text-slate-300">
                            Nama Lengkap <span class="text-rose-400">*</span>
                        </label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                               placeholder="Nama Anda"
                               class="glass-input w-full px-4 py-2.5 rounded-xl text-xs border border-slate-700 bg-navy-950 text-white placeholder-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/20 transition">
                    </div>

                    <!-- Email -->
                    <div class="space-y-1">
                        <label for="email" class="block text-xs font-semibold text-slate-300">
                            Alamat Email <span class="text-rose-400">*</span>
                        </label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                               placeholder="nama@email.com"
                               class="glass-input w-full px-4 py-2.5 rounded-xl text-xs border border-slate-700 bg-navy-950 text-white placeholder-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/20 transition">
                    </div>

                    <!-- Password -->
                    <div class="space-y-1">
                        <label for="password" class="block text-xs font-semibold text-slate-300">
                            Kata Sandi <span class="text-rose-400">*</span>
                        </label>
                        <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required autocomplete="new-password"
                               placeholder="Minimal 8 karakter"
                               class="glass-input w-full px-4 py-2.5 rounded-xl text-xs border border-slate-700 bg-navy-950 text-white placeholder-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/20 transition">
                    </div>

                    <!-- Confirm Password -->
                    <div class="space-y-1">
                        <label for="password_confirmation" class="block text-xs font-semibold text-slate-300">
                            Konfirmasi Kata Sandi <span class="text-rose-400">*</span>
                        </label>
                        <input id="password_confirmation" :type="showPassword ? 'text' : 'password'" name="password_confirmation" required autocomplete="new-password"
                               placeholder="Ulangi kata sandi"
                               class="glass-input w-full px-4 py-2.5 rounded-xl text-xs border border-slate-700 bg-navy-950 text-white placeholder-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/20 transition">
                    </div>

                    <!-- Toggle Show Password -->
                    <div class="flex items-center gap-2 pt-1 text-xs text-slate-400">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" @change="showPassword = !showPassword" class="w-3.5 h-3.5 rounded text-sky-500 bg-navy-950 border-slate-700 focus:ring-sky-500">
                            <span>Tampilkan kata sandi</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                            class="w-full mt-2 py-3 px-4 rounded-xl bg-gradient-to-r from-sky-400 via-sky-500 to-blue-600 hover:from-sky-300 hover:to-blue-500 text-navy-950 font-black text-xs uppercase tracking-wider shadow-lg shadow-sky-500/25 transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                        <span>Daftar Akun Sekarang</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </form>
            </div>

            <!-- Footer Login link -->
            <div class="mt-6 pt-4 border-t border-slate-800 text-center text-xs text-slate-400">
                Sudah memiliki akun?
                <a href="{{ route('login') }}" class="font-bold text-sky-400 hover:text-sky-300 transition">
                    Masuk ke Akun Anda
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
