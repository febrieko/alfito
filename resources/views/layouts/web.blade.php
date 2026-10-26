<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'ALFITO – Paket Wisata Bromo, Labuan Bajo & Tour Indonesia')</title>
    <meta name="description" content="@yield('meta_description', 'Spesialis paket open trip dan private trip terbaik di Indonesia: Bromo Midnight, Sailing Komodo Labuan Bajo, Raja Ampat, dan Nusa Penida Bali.')">

    <!-- Open Graph / SEO -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('title', 'ALFITO – Tour & Travel Indonesia')">
    <meta property="og:description" content="@yield('meta_description', 'Paket wisata terpercaya dengan armada prima dan pemandu berlisensi.')">
    <meta property="og:image" content="@yield('og_image', 'https://images.unsplash.com/photo-1596401057633-54a8fe8ef647?auto=format&fit=crop&w=1200&q=80')">

    <!-- Favicon -->
    <link rel="icon" href="{{ $company->effective_favicon_url ?? 'data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🧭</text></svg>' }}">
    @if(!empty($company->logo_url))
        <link rel="apple-touch-icon" href="{{ $company->logo_url }}">
    @endif

    <!-- Fonts & Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('styles')
</head>
<body class="bg-navy-900 text-slate-100 font-sans antialiased min-h-screen flex flex-col" x-data="{ mobileMenuOpen: false }">

    <!-- Top Announcement Bar -->
    <div class="bg-gradient-to-r from-navy-950 via-navy-900 to-navy-950 text-xs py-2 px-4 border-b border-sky-500/20">
        <div class="max-w-7xl mx-auto flex flex-wrap justify-between items-center gap-2">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-sky-400 text-navy-950 animate-pulse">PROMO</span>
                <span class="text-sky-100">Diskon s/d 20% Paket Open Trip Liburan! Keberangkatan setiap akhir pekan.</span>
            </div>
            <div class="flex items-center gap-5 text-slate-300">
                <a href="{{ $company->getWhatsappUrl('Halo ' . ($company->short_name ?? 'ALFITO') . ', saya ingin konsultasi paket wisata') }}" target="_blank" class="hover:text-sky-400 flex items-center gap-1.5 transition">
                    <svg class="w-3.5 h-3.5 text-sky-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.299.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.144.39-.086.159.058 1.011.477 1.184.564.173.087.289.13.332.202.043.072.043.419-.101.824z"/></svg>
                    <span>Hotline CS: <strong>{{ $company->phone ?? $company->whatsapp_number ?? '0812-3456-7890' }}</strong></span>
                </a>
                <span class="hidden md:inline text-slate-500">|</span>
                <span class="hidden md:inline text-slate-400">Jam Operasional: {{ $company->operational_hours ?? '08:00 - 22:00 WIB' }}</span>
            </div>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <header class="sticky top-0 z-50 glass-nav transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    @if(!empty($company->logo_url))
                        <img src="{{ $company->logo_url }}" alt="{{ $company->company_name }}" class="h-11 w-auto object-contain">
                    @else
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-tr from-sky-400 to-blue-600 flex items-center justify-center text-navy-950 font-black text-2xl shadow-lg shadow-sky-500/25 group-hover:scale-105 transition duration-300">
                            🧭
                        </div>
                    @endif
                    <div>
                        <span class="text-xl sm:text-2xl font-black tracking-tight text-white block">
                            {{ $company->short_name ?? 'ALFITO' }}
                        </span>
                        <span class="text-[10px] uppercase tracking-widest text-sky-300 font-semibold block -mt-1">
                            {{ $company->tagline ?? 'Tour & Travel Indonesia' }}
                        </span>
                    </div>
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center gap-7 text-sm font-medium">
                    <a href="{{ route('home') }}" class="transition hover:text-sky-400 {{ request()->routeIs('home') ? 'text-sky-400 font-semibold' : 'text-slate-200' }}">
                        Beranda
                    </a>
                    <a href="{{ route('tours.index') }}" class="transition hover:text-sky-400 {{ request()->routeIs('tours.*') ? 'text-sky-400 font-semibold' : 'text-slate-200' }}">
                        Paket Wisata
                    </a>
                    <a href="{{ route('tours.index', ['type' => 'open_trip']) }}" class="transition hover:text-sky-400 text-slate-200 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-sky-400 animate-ping"></span>
                        Open Trip
                    </a>
                    <a href="{{ route('destinations.index') }}" class="transition hover:text-sky-400 {{ request()->routeIs('destinations.*') ? 'text-sky-400 font-semibold' : 'text-slate-200' }}">
                        Destinasi
                    </a>
                    <a href="{{ route('blog.index') }}" class="transition hover:text-sky-400 {{ request()->routeIs('blog.*') ? 'text-sky-400 font-semibold' : 'text-slate-200' }}">
                        Travel Guide
                    </a>
                </nav>

                <!-- Actions / Auth -->
                <div class="hidden md:flex items-center gap-3">
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-2.5 px-3 py-1.5 rounded-full bg-navy-800 border border-slate-700/60 hover:border-sky-500/50 transition">
                                <img src="{{ auth()->user()->avatar_url }}" 
                                     alt="{{ auth()->user()->name }}" class="w-7 h-7 rounded-full object-cover">
                                <span class="text-sm font-medium text-slate-200 max-w-[100px] truncate">{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <div x-show="open" @click.outside="open = false" x-cloak
                                 class="absolute right-0 mt-2 w-48 rounded-xl bg-navy-900 border border-slate-700/80 shadow-2xl py-2 z-50">
                                <div class="px-4 py-2 border-b border-slate-800 text-xs text-slate-400">
                                    Login sebagai: <strong class="text-sky-400 block uppercase font-mono">{{ auth()->user()->role }}</strong>
                                </div>
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-slate-200 hover:bg-navy-800 hover:text-sky-400">Dashboard</a>
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-sky-400 hover:bg-navy-800 font-bold border-b border-slate-800/80">
                                        🛡️ Panel Admin
                                    </a>
                                @endif
                                <a href="{{ route('booking.my') }}" class="block px-4 py-2 text-sm text-slate-200 hover:bg-navy-800 hover:text-sky-400">Pesanan Saya</a>
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-200 hover:bg-navy-800 hover:text-sky-400">Edit Profil</a>
                                <form method="POST" action="{{ route('logout') }}" class="border-t border-slate-800 mt-1">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-rose-400 hover:bg-navy-800">Keluar</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-slate-300 hover:text-white px-3 py-2 transition">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="text-sm font-medium px-4 py-2 rounded-xl bg-sky-500 hover:bg-sky-400 text-navy-950 font-semibold shadow-md shadow-sky-500/20 transition transform hover:-translate-y-0.5">
                            Daftar
                        </a>
                    @endauth

                    <!-- WhatsApp CTA Button -->
                    <a href="{{ $company->getWhatsappUrl('Halo ' . ($company->short_name ?? 'ALFITO') . ', saya ingin konsultasi paket wisata') }}" target="_blank"
                       class="flex items-center gap-2 text-xs font-semibold px-3.5 py-2 rounded-xl bg-sky-500/10 border border-sky-500/30 text-sky-400 hover:bg-sky-500 hover:text-navy-950 transition">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.299.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.144.39-.086.159.058 1.011.477 1.184.564.173.087.289.13.332.202.043.072.043.419-.101.824z"/></svg>
                        <span>Konsultasi</span>
                    </a>
                </div>

                <!-- Mobile Menu Hamburger -->
                <div class="flex md:hidden items-center gap-2">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 rounded-lg bg-navy-800 text-slate-200 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path x-show="mobileMenuOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div x-show="mobileMenuOpen" x-cloak class="md:hidden bg-navy-950/95 border-b border-slate-800 px-4 py-4 space-y-3">
            <a href="{{ route('home') }}" class="block text-slate-200 hover:text-sky-400 py-1.5 font-medium">Beranda</a>
            <a href="{{ route('tours.index') }}" class="block text-slate-200 hover:text-sky-400 py-1.5 font-medium">Paket Wisata</a>
            <a href="{{ route('tours.index', ['type' => 'open_trip']) }}" class="block text-sky-400 py-1.5 font-medium">Open Trip</a>
            <a href="{{ route('destinations.index') }}" class="block text-slate-200 hover:text-sky-400 py-1.5 font-medium">Destinasi</a>
            <a href="{{ route('blog.index') }}" class="block text-slate-200 hover:text-sky-400 py-1.5 font-medium">Travel Guide</a>
            <div class="pt-3 border-t border-slate-800 flex flex-col gap-2">
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="w-full text-center py-2.5 rounded-xl bg-gradient-to-r from-sky-500 to-sky-400 text-navy-950 font-bold text-sm shadow-md">
                            🛡️ Pusat Panel Admin
                        </a>
                    @endif
                    <a href="{{ route('dashboard') }}" class="w-full text-center py-2.5 rounded-xl bg-navy-800 text-slate-200 font-medium">Dashboard</a>
                    <a href="{{ route('booking.my') }}" class="w-full text-center py-2.5 rounded-xl bg-navy-800 text-slate-200 font-medium">Pesanan Saya</a>
                @else
                    <a href="{{ route('login') }}" class="w-full text-center py-2.5 rounded-xl bg-navy-800 text-slate-200 font-medium">Masuk</a>
                    <a href="{{ route('register') }}" class="w-full text-center py-2.5 rounded-xl bg-sky-500 text-navy-950 font-bold">Daftar Akun Baru</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-sky-500/10 border-b border-sky-500/30 text-sky-300 py-3 px-4">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Content Area -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Floating WhatsApp Widget -->
    <div class="fixed bottom-6 right-6 z-50 flex items-center gap-3">
        <a href="{{ $company->getWhatsappUrl('Halo ' . ($company->short_name ?? 'ALFITO') . ', saya tertarik dengan paket wisata') }}" 
           target="_blank" 
           class="relative group flex items-center gap-2.5 bg-gradient-to-r from-sky-400 to-blue-500 hover:from-sky-300 hover:to-blue-400 text-navy-950 font-bold text-sm px-4 py-3.5 rounded-full shadow-2xl shadow-sky-500/50 hover:scale-105 transition duration-300">
            <span class="absolute -inset-1 rounded-full bg-sky-400 opacity-30 animate-pulse-ring -z-10"></span>
            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.299.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.144.39-.086.159.058 1.011.477 1.184.564.173.087.289.13.332.202.043.072.043.419-.101.824z"/></svg>
            <span class="hidden sm:inline">Chat CS WhatsApp</span>
        </a>
    </div>

    <!-- Footer -->
    <footer class="bg-navy-950 border-t border-slate-800/80 pt-16 pb-12 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 pb-12 border-b border-slate-800">
                <!-- Col 1: About & Brand -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center gap-3">
                        @if(!empty($company->logo_url))
                            <img src="{{ $company->logo_url }}" alt="{{ $company->company_name }}" class="h-10 w-auto object-contain">
                        @else
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-sky-400 to-blue-600 flex items-center justify-center text-navy-950 font-black text-xl shadow-lg shadow-sky-500/25">
                                🧭
                            </div>
                        @endif
                        <span class="text-2xl font-black tracking-tight text-white">
                            {{ $company->short_name ?? 'ALFITO' }}
                        </span>
                    </div>
                    <p class="text-slate-400 text-sm leading-relaxed max-w-sm">
                        {{ $company->description ?? 'Platform tour operator resmi terkemuka di Indonesia. Mengkhususkan diri pada paket Open Trip dan Private Trip Bromo, Labuan Bajo Komodo, Raja Ampat, dan Nusa Penida dengan standar pelayanan bintang 5.' }}
                    </p>
                    <div class="flex flex-wrap items-center gap-3 pt-2">
                        @if(!empty($company->license_info))
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-navy-900 border border-slate-700/60 text-xs text-slate-300">
                                <span class="text-sky-400 font-bold">✓</span> {{ $company->license_info }}
                            </div>
                        @endif

                        <!-- Social Media Links -->
                        <div class="flex items-center gap-2 text-slate-400 text-xs">
                            @if(!empty($company->instagram_url))
                                <a href="{{ $company->instagram_url }}" target="_blank" class="p-1.5 rounded-lg bg-navy-900 hover:text-sky-400 hover:border-sky-500/40 border border-slate-800 transition" title="Instagram">
                                    📸
                                </a>
                            @endif
                            @if(!empty($company->threads_url))
                                <a href="{{ $company->threads_url }}" target="_blank" class="p-1.5 rounded-lg bg-navy-900 hover:text-sky-400 hover:border-sky-500/40 border border-slate-800 transition font-bold" title="Threads">
                                    🧵
                                </a>
                            @endif
                            @if(!empty($company->tiktok_url))
                                <a href="{{ $company->tiktok_url }}" target="_blank" class="p-1.5 rounded-lg bg-navy-900 hover:text-sky-400 hover:border-sky-500/40 border border-slate-800 transition" title="TikTok">
                                    🎵
                                </a>
                            @endif
                            @if(!empty($company->facebook_url))
                                <a href="{{ $company->facebook_url }}" target="_blank" class="p-1.5 rounded-lg bg-navy-900 hover:text-sky-400 hover:border-sky-500/40 border border-slate-800 transition" title="Facebook">
                                    📘
                                </a>
                            @endif
                            @if(!empty($company->youtube_url))
                                <a href="{{ $company->youtube_url }}" target="_blank" class="p-1.5 rounded-lg bg-navy-900 hover:text-sky-400 hover:border-sky-500/40 border border-slate-800 transition" title="YouTube">
                                    ▶️
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Col 2: Destinasi Populer -->
                <div>
                    <h4 class="text-white font-semibold text-sm uppercase tracking-wider mb-4">Destinasi Populer</h4>
                    <ul class="space-y-2.5 text-sm text-slate-400">
                        <li><a href="{{ route('tours.index', ['destination' => 'gunung-bromo-tengger']) }}" class="hover:text-sky-400 transition">Gunung Bromo Sunrise</a></li>
                        <li><a href="{{ route('tours.index', ['destination' => 'labuan-bajo-komodo']) }}" class="hover:text-sky-400 transition">Labuan Bajo & Komodo</a></li>
                        <li><a href="{{ route('tours.index', ['destination' => 'raja-ampat-papua']) }}" class="hover:text-sky-400 transition">Raja Ampat Papua</a></li>
                        <li><a href="{{ route('tours.index', ['destination' => 'nusa-penida-bali']) }}" class="hover:text-sky-400 transition">Nusa Penida & Bali</a></li>
                        <li><a href="{{ route('tours.index', ['destination' => 'yogyakarta-borobudur']) }}" class="hover:text-sky-400 transition">Jogja & Borobudur</a></li>
                    </ul>
                </div>

                <!-- Col 3: Informasi & Bantuan -->
                <div>
                    <h4 class="text-white font-semibold text-sm uppercase tracking-wider mb-4">Informasi Wisata</h4>
                    <ul class="space-y-2.5 text-sm text-slate-400">
                        <li><a href="{{ route('tours.index', ['type' => 'open_trip']) }}" class="hover:text-sky-400 transition">Jadwal Open Trip</a></li>
                        <li><a href="{{ route('tours.index', ['type' => 'private_trip']) }}" class="hover:text-sky-400 transition">Private Trip Keluarga</a></li>
                        <li><a href="{{ route('blog.index') }}" class="hover:text-sky-400 transition">Panduan & Tips Liburan</a></li>
                        <li><a href="{{ route('destinations.index') }}" class="hover:text-sky-400 transition">Peta Destinasi</a></li>
                    </ul>
                </div>

                <!-- Col 4: Kontak & Kantor -->
                <div>
                    <h4 class="text-white font-semibold text-sm uppercase tracking-wider mb-4">Hubungi Kami</h4>
                    <div class="space-y-3 text-sm text-slate-400">
                        @if(!empty($company->address))
                            <p class="flex items-start gap-2">
                                <span class="text-sky-400">📍</span>
                                <span>{{ $company->address }}</span>
                            </p>
                        @endif
                        <p class="flex items-center gap-2">
                            <span class="text-sky-400">📞</span>
                            <a href="{{ $company->getWhatsappUrl('Halo ' . ($company->short_name ?? 'ALFITO') . ', saya ingin konsultasi') }}" target="_blank" class="hover:text-sky-400 transition">
                                {{ $company->phone ?? $company->whatsapp_number ?? '0812-3456-7890' }}
                            </a>
                        </p>
                        @if(!empty($company->email))
                            <p class="flex items-center gap-2">
                                <span class="text-sky-400">✉️</span>
                                <a href="mailto:{{ $company->email }}" class="hover:text-sky-400 transition">
                                    {{ $company->email }}
                                </a>
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Bottom Disclaimer & Payment -->
            <div class="pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} {{ $company->company_name ?? 'ALFITO' }}. {{ $company->footer_text ?? 'All rights reserved. Platform Tour & Travel Terpercaya di Indonesia.' }}</p>
                <div class="flex items-center gap-4 text-slate-400">
                    <span>Metode Pembayaran:</span>
                    @if(!empty($company->bank_accounts) && is_array($company->bank_accounts))
                        @foreach($company->bank_accounts as $bank)
                            <span class="px-2 py-1 rounded bg-navy-900 border border-slate-800 font-semibold text-[11px]">{{ strtoupper($bank['bank'] ?? 'BANK') }}</span>
                        @endforeach
                    @else
                        <span class="px-2 py-1 rounded bg-navy-900 border border-slate-800 font-semibold text-[11px]">BCA</span>
                        <span class="px-2 py-1 rounded bg-navy-900 border border-slate-800 font-semibold text-[11px]">MANDIRI</span>
                        <span class="px-2 py-1 rounded bg-navy-900 border border-slate-800 font-semibold text-[11px]">BNI</span>
                        <span class="px-2 py-1 rounded bg-navy-900 border border-slate-800 font-semibold text-[11px]">QRIS</span>
                    @endif
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
