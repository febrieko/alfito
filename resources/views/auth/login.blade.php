@extends('layouts.web')

@section('title', 'Masuk ke Akun – ' . ($company->short_name ?? 'ALFITO'))

@section('content')
<div class="min-h-[calc(100vh-80px)] bg-navy-950 flex items-center justify-center py-10 px-4 sm:px-6 lg:px-8 relative overflow-hidden"
     x-data="{ showPassword: false }">
    
    <!-- Decorative Glowing Ambient Lights in Background -->
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-sky-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-5xl w-full grid grid-cols-1 lg:grid-cols-12 rounded-3xl overflow-hidden shadow-2xl border border-slate-800/80 bg-navy-900/60 backdrop-blur-xl relative z-10">
        
        <!-- Left Side: Brand Experience & Travel Showcase (7 Cols) -->
        <div class="hidden lg:flex lg:col-span-7 flex-col justify-between p-10 relative overflow-hidden bg-cover bg-center"
             style="background-image: url('https://images.unsplash.com/photo-1596401057633-54a8fe8ef647?auto=format&fit=crop&w=1400&q=85');">
            
            <!-- Dark Gradient Overlay -->
            <div class="absolute inset-0 bg-gradient-to-tr from-navy-950 via-navy-950/80 to-navy-900/40 z-0"></div>

            <!-- Top Brand & Badge -->
            <div class="relative z-10 space-y-4">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-navy-900/80 border border-sky-500/30 text-xs font-semibold text-sky-300 backdrop-blur-md shadow-lg">
                    <span class="w-2 h-2 rounded-full bg-sky-400 animate-pulse"></span>
                    <span>Portal Resmi {{ $company->short_name ?? 'ALFITO' }}</span>
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

            <!-- Middle Headline & Features -->
            <div class="relative z-10 my-10 space-y-5">
                <h2 class="text-3xl font-black text-white leading-tight">
                    Jelajahi Surga Nusantara Bersama Pelayanan Bintang 5.
                </h2>
                <p class="text-sm text-slate-300 leading-relaxed max-w-md font-light">
                    Masuk untuk mengelola pemesanan paket wisata, rencana perjalanan gathering kantor, invoice resmi, dan pantau status reservasi Anda secara real-time.
                </p>

                <!-- Value Props Pills -->
                <div class="space-y-2.5 pt-2 text-xs text-slate-200">
                    <div class="flex items-center gap-2.5">
                        <span class="w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold text-[10px] border border-emerald-500/30">✓</span>
                        <span>Open Trip Mingguan, Private Tour & Gathering Kantor</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span class="w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold text-[10px] border border-emerald-500/30">✓</span>
                        <span>Fasilitas Terjamin & Tour Leader Berlisensi Resmi HPI</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span class="w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold text-[10px] border border-emerald-500/30">✓</span>
                        <span>Konfirmasi Instan & Dukungan Customer Service 24/7</span>
                    </div>
                </div>
            </div>

            <!-- Bottom Testimonial Snippet -->
            <div class="relative z-10 p-4 rounded-2xl bg-navy-950/70 border border-slate-700/60 backdrop-blur-md flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="flex -space-x-2">
                        <img class="w-7 h-7 rounded-full border-2 border-navy-950 object-cover" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&q=80" alt="Traveler">
                        <img class="w-7 h-7 rounded-full border-2 border-navy-950 object-cover" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=100&q=80" alt="Traveler">
                        <img class="w-7 h-7 rounded-full border-2 border-navy-950 object-cover" src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=100&q=80" alt="Traveler">
                    </div>
                    <div>
                        <div class="text-[11px] font-bold text-white flex items-center gap-1">
                            <span class="text-amber-400">★★★★★</span>
                            <span>4.9 / 5.0</span>
                        </div>
                        <div class="text-[10px] text-slate-400">Dari 1.500+ Wisatawan & 50+ Instansi</div>
                    </div>
                </div>
                <span class="text-xs font-semibold text-sky-400">Terpercaya ✓</span>
            </div>
        </div>

        <!-- Right Side: Login Form (5 Cols) -->
        <div class="lg:col-span-5 p-8 sm:p-10 flex flex-col justify-between bg-navy-900/90 relative">
            <div>
                <!-- Top Navigation Link -->
                <div class="flex items-center justify-between mb-8">
                    <a href="{{ route('home') }}" class="text-xs font-semibold text-slate-400 hover:text-sky-400 transition flex items-center gap-1.5 group">
                        <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        <span>Kembali ke Beranda</span>
                    </a>

                    <!-- Mobile Brand Icon -->
                    <div class="lg:hidden flex items-center gap-2">
                        <span class="text-lg font-black text-white">{{ $company->short_name ?? 'ALFITO' }}</span>
                    </div>
                </div>

                <!-- Form Greeting -->
                <div class="mb-6 space-y-1">
                    <h1 class="text-2xl font-black text-white tracking-tight flex items-center gap-2">
                        <span>Selamat Datang</span>
                        <span>👋</span>
                    </h1>
                    <p class="text-xs text-slate-400">
                        Masuk untuk mengakses dashboard pemesanan & akun Anda.
                    </p>
                </div>

                <!-- Session Status / Errors -->
                @if (session('status'))
                    <div class="mb-4 p-3 rounded-xl bg-sky-500/15 border border-sky-500/30 text-sky-300 text-xs font-medium">
                        {{ session('status') }}
                    </div>
                @endif

                @if (isset($errors) && $errors->any())
                    <div class="mb-4 p-3 rounded-xl bg-rose-500/15 border border-rose-500/30 text-rose-300 text-xs font-medium">
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Main Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <!-- Email Field -->
                    <div class="space-y-1.5">
                        <label for="email" class="block text-xs font-semibold text-slate-300">
                            Alamat Email <span class="text-rose-400">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                                   placeholder="nama@email.com"
                                   class="glass-input w-full pl-10 pr-4 py-3 rounded-xl text-xs border border-slate-700 bg-navy-950 text-white placeholder-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/20 transition">
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-xs font-semibold text-slate-300">
                                Kata Sandi <span class="text-rose-400">*</span>
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-[11px] text-sky-400 hover:text-sky-300 transition">
                                    Lupa kata sandi?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required autocomplete="current-password"
                                   placeholder="Masukkan kata sandi..."
                                   class="glass-input w-full pl-10 pr-10 py-3 rounded-xl text-xs border border-slate-700 bg-navy-950 text-white placeholder-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/20 transition">
                            
                            <!-- Toggle Show/Hide Password -->
                            <button type="button" @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-500 hover:text-slate-300 transition focus:outline-none"
                                    tabindex="-1">
                                <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between pt-1">
                        <label for="remember_me" class="flex items-center gap-2 cursor-pointer text-xs text-slate-300">
                            <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 rounded text-sky-500 bg-navy-950 border-slate-700 focus:ring-sky-500">
                            <span>Ingat saya di perangkat ini</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                            class="w-full mt-2 py-3.5 px-4 rounded-xl bg-gradient-to-r from-sky-400 via-sky-500 to-blue-600 hover:from-sky-300 hover:to-blue-500 text-navy-950 font-black text-xs uppercase tracking-wider shadow-lg shadow-sky-500/25 transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                        <span>Masuk ke Akun</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </form>

            </div>

            <!-- Bottom Register & Help Links -->
            <div class="mt-6 pt-5 border-t border-slate-800 text-center space-y-2 text-xs">
                @if (Route::has('register'))
                    <p class="text-slate-400">
                        Belum memiliki akun?
                        <a href="{{ route('register') }}" class="font-bold text-sky-400 hover:text-sky-300 transition">
                            Daftar Akun Baru Sekarang ↗
                        </a>
                    </p>
                @endif
                <p class="text-[11px] text-slate-400">
                    Kendala login? Hubungi
                    <a href="{{ $company->getWhatsappUrl('Halo CS ALFITO, saya mengalami kendala saat hendak login ke akun saya.') }}" target="_blank" class="text-slate-300 hover:text-sky-400 underline">
                        Bantuan CS WhatsApp
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
