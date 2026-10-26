@extends('layouts.web')

@section('title', 'Pengaturan Profil Akun – ' . ($company->short_name ?? 'ALFITO'))

@section('content')
<div class="py-10 bg-navy-950 min-h-screen text-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- Breadcrumb Navigation -->
        <nav class="flex items-center gap-2 text-xs text-slate-400">
            <a href="{{ route('home') }}" class="hover:text-sky-400 transition">Beranda</a>
            <span>/</span>
            <a href="{{ route('dashboard') }}" class="hover:text-sky-400 transition">Dashboard</a>
            <span>/</span>
            <span class="text-sky-400 font-semibold">Pengaturan Profil & Keamanan</span>
        </nav>

        <!-- Profile Header Hero Banner -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-navy-900 via-navy-850 to-navy-900 border border-slate-800 p-6 sm:p-8 shadow-2xl">
            <!-- Ambient Glow -->
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-sky-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div class="flex items-center gap-5">
                    <!-- Avatar Photo or Initials -->
                    @if($user->avatar)
                        <img src="{{ asset(ltrim($user->avatar, '/')) }}" alt="{{ $user->name }}"
                             class="w-16 h-16 rounded-2xl object-cover border-2 border-sky-400 shadow-xl shadow-sky-500/25 shrink-0">
                    @else
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-sky-400 via-sky-500 to-blue-600 flex items-center justify-center text-navy-950 font-black text-2xl shadow-xl shadow-sky-500/25 shrink-0">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif

                    <div class="space-y-1">
                        <div class="flex flex-wrap items-center gap-2.5">
                            <h1 class="text-2xl font-black text-white tracking-tight">
                                {{ $user->name }}
                            </h1>
                            <span class="px-2.5 py-0.5 rounded-lg bg-sky-500/20 text-sky-400 font-mono text-[11px] uppercase font-bold border border-sky-500/30">
                                {{ $user->role ?? 'Member' }}
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-lg bg-emerald-500/20 text-emerald-400 text-[11px] font-semibold border border-emerald-500/30">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                Akun Aktif
                            </span>
                        </div>
                        <p class="text-xs text-slate-400">
                            {{ $user->email }} &bull; Bergabung sejak {{ $user->created_at ? $user->created_at->format('d F Y') : '-' }}
                        </p>
                    </div>
                </div>

                <!-- Back to Dashboard Button -->
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <a href="{{ route('dashboard') }}"
                       class="px-4 py-2.5 rounded-xl bg-navy-800 hover:bg-navy-750 text-slate-200 hover:text-white font-bold text-xs border border-slate-700/80 transition flex items-center gap-2">
                        <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        <span>Kembali ke Dashboard</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Form Cards Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Card 1: Update Profile Information -->
            <div class="p-6 sm:p-8 rounded-3xl bg-navy-900/80 border border-slate-800/80 backdrop-blur-xl shadow-xl">
                @include('profile.partials.update-profile-information-form')
            </div>

            <!-- Card 2: Update Password -->
            <div class="p-6 sm:p-8 rounded-3xl bg-navy-900/80 border border-slate-800/80 backdrop-blur-xl shadow-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <!-- Help & CS Notice Snippet -->
        <div class="rounded-2xl bg-gradient-to-r from-navy-900 to-navy-850 border border-slate-800/80 p-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400">
            <div class="flex items-center gap-3">
                <span class="text-xl">🛡️</span>
                <div>
                    <span class="font-semibold text-slate-200 block">Privasi & Keamanan Akun</span>
                    <span class="text-slate-400 text-[11px]">Data pribadi Anda dilindungi dengan enkripsi standar industri dan kepatuhan privasi resmi ALFITO.</span>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ $company->getWhatsappUrl('Halo CS ALFITO, saya butuh bantuan terkait pengaturan akun saya') }}"
                   target="_blank"
                   class="px-4 py-2 rounded-xl bg-navy-800 hover:bg-navy-750 text-sky-400 border border-sky-500/30 hover:border-sky-400 font-semibold transition flex items-center gap-2">
                    <span>Hubungi CS WhatsApp</span>
                    <span>↗</span>
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
