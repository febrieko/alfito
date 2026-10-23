@extends('layouts.web')

@section('title', 'Dashboard Member – ' . ($company->short_name ?? 'ALFITO'))

@section('content')
<div class="py-10 bg-navy-950 min-h-screen text-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Welcome Hero Banner with Official Logo -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-navy-900 via-navy-850 to-navy-900 border border-slate-800 p-6 sm:p-8 shadow-2xl">
            <!-- Ambient Glow -->
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-sky-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                <div class="space-y-3 max-w-2xl">
                    <div class="flex flex-wrap items-center gap-3">
                        @if(!empty($company->logo_url))
                            <div class="px-3 py-1.5 rounded-xl bg-navy-950/90 border border-slate-700/80 shadow-md inline-flex items-center">
                                <img src="{{ $company->logo_url }}" alt="{{ $company->company_name }}" class="h-8 w-auto object-contain">
                            </div>
                        @else
                            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-sky-400 to-blue-600 flex items-center justify-center text-navy-950 font-black text-lg shadow-md shadow-sky-500/25">
                                🧭
                            </div>
                        @endif

                        <span class="px-2.5 py-1 rounded-lg bg-sky-500/20 text-sky-400 font-mono text-xs uppercase font-bold border border-sky-500/30">
                            Member Area &bull; Role: {{ auth()->user()->role }}
                        </span>

                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-500/20 text-emerald-400 text-xs font-semibold border border-emerald-500/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            Akun Aktif
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                        Selamat Datang Kembali, {{ auth()->user()->name }}! 👋
                    </h1>
                    <p class="text-sm text-slate-300 leading-relaxed font-light">
                        Kelola seluruh reservasi trip, cetak invoice resmi, dan pantau status pemesanan liburan Anda bersama <span class="text-white font-semibold">{{ $company->company_name }}</span>.
                    </p>
                </div>

                <!-- Quick Action Buttons -->
                <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                    <a href="{{ route('tours.index') }}" 
                       class="flex-1 lg:flex-initial px-5 py-3 rounded-xl bg-gradient-to-r from-sky-500 to-sky-400 hover:from-sky-400 hover:to-sky-300 text-navy-950 font-bold text-xs shadow-lg shadow-sky-500/25 transition text-center flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        <span>Pesan Paket Wisata</span>
                    </a>
                    <a href="{{ route('booking.my') }}" 
                       class="flex-1 lg:flex-initial px-4 py-3 rounded-xl bg-navy-800 hover:bg-navy-750 text-slate-200 font-bold text-xs border border-slate-700/80 transition text-center flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        <span>Semua Pesanan</span>
                    </a>
                </div>
            </div>
        </div>

        @if(auth()->user()->isAdmin())
            <!-- Admin Quick Tools Banner -->
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-navy-900 to-sky-950 border border-sky-500/30 p-6 shadow-xl">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-sky-500/20 text-sky-400 flex items-center justify-center text-2xl font-bold border border-sky-400/30 shrink-0">
                            🛡️
                        </div>
                        <div>
                            <h2 class="text-lg font-black text-white flex items-center gap-2">
                                <span>Pusat Kendali Administrator</span>
                                <span class="text-[10px] px-2 py-0.5 rounded bg-sky-500 text-navy-950 font-bold uppercase">Super Admin</span>
                            </h2>
                            <p class="text-xs text-slate-300 mt-0.5">
                                Anda memiliki akses penuh ke manajemen pesanan, katalog trip, review, hero slide, dan pengaturan profil perusahaan.
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-2.5">
                        <a href="{{ route('admin.dashboard') }}" 
                           class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-sky-500 to-sky-400 hover:from-sky-400 hover:to-sky-300 text-navy-950 font-black text-xs shadow-md shadow-sky-500/25 transition flex items-center gap-1.5">
                            <span>Buka Panel Admin</span>
                            <span>→</span>
                        </a>
                        <a href="{{ route('admin.bookings.index') }}" 
                           class="px-3.5 py-2.5 rounded-xl bg-navy-800 hover:bg-navy-700 text-slate-200 font-semibold text-xs border border-slate-700 transition">
                            Booking
                        </a>
                        <a href="{{ route('admin.tours.index') }}" 
                           class="px-3.5 py-2.5 rounded-xl bg-navy-800 hover:bg-navy-700 text-slate-200 font-semibold text-xs border border-slate-700 transition">
                            Paket Wisata
                        </a>
                        <a href="{{ route('admin.settings.edit') }}" 
                           class="px-3.5 py-2.5 rounded-xl bg-navy-800 hover:bg-navy-700 text-slate-200 font-semibold text-xs border border-slate-700 transition">
                            ⚙️ Pengaturan
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Member Stat Overview Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Total Bookings -->
            <div class="glass-card p-6 rounded-2xl border border-slate-800/80 shadow-lg relative overflow-hidden group">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Pesanan</span>
                    <span class="w-10 h-10 rounded-xl bg-sky-500/20 text-sky-400 flex items-center justify-center text-lg border border-sky-400/30">
                        📑
                    </span>
                </div>
                <div class="text-3xl font-black text-white">
                    {{ auth()->user()->bookings()->count() }}
                </div>
                <div class="mt-2 text-xs text-slate-400 flex items-center justify-between">
                    <span>Trip terdaftar</span>
                    <a href="{{ route('booking.my') }}" class="text-sky-400 hover:underline font-semibold">Lihat &rarr;</a>
                </div>
            </div>

            <!-- Active / Paid Status -->
            <div class="glass-card p-6 rounded-2xl border border-slate-800/80 shadow-lg relative overflow-hidden group">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pesanan Lunas</span>
                    <span class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-lg border border-emerald-400/30">
                        ✓
                    </span>
                </div>
                <div class="text-3xl font-black text-white">
                    {{ auth()->user()->bookings()->where('status', 'paid')->count() }}
                </div>
                <div class="mt-2 text-xs text-slate-400">
                    <span class="text-emerald-400 font-semibold">{{ auth()->user()->bookings()->where('status', 'pending')->count() }}</span> menunggu konfirmasi
                </div>
            </div>

            <!-- Total Expenditure -->
            <div class="glass-card p-6 rounded-2xl border border-slate-800/80 shadow-lg relative overflow-hidden group">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Transaksi</span>
                    <span class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-lg border border-amber-400/30">
                        💳
                    </span>
                </div>
                @php
                    $totalSpent = auth()->user()->bookings()->where('status', 'paid')->sum('total_price');
                @endphp
                <div class="text-2xl font-black text-white truncate" title="Rp {{ number_format($totalSpent, 0, ',', '.') }}">
                    Rp {{ number_format($totalSpent, 0, ',', '.') }}
                </div>
                <div class="mt-2 text-xs text-slate-400">
                    Nilai transaksi liburan lunas
                </div>
            </div>

            <!-- Official WhatsApp Support -->
            <div class="glass-card p-6 rounded-2xl border border-slate-800/80 shadow-lg relative overflow-hidden group">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Bantuan Customer Care</span>
                    <span class="w-10 h-10 rounded-xl bg-sky-500/20 text-sky-400 flex items-center justify-center text-lg border border-sky-400/30">
                        💬
                    </span>
                </div>
                <div class="text-base font-bold text-white truncate">
                    {{ $company->phone ?? $company->whatsapp_number ?? '0812-3456-7890' }}
                </div>
                <div class="mt-2 text-xs text-slate-400">
                    <a href="{{ $company->getWhatsappUrl('Halo ' . ($company->short_name ?? 'ALFITO') . ', saya butuh bantuan terkait akun member') }}" 
                       target="_blank" 
                       class="text-sky-400 hover:text-sky-300 font-bold inline-flex items-center gap-1">
                        <span>Chat WhatsApp Resmi</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Bookings Section -->
        <div class="glass-card rounded-3xl border border-slate-800/80 shadow-xl overflow-hidden">
            <div class="p-6 border-b border-slate-800 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h2 class="font-black text-white text-lg">Pesanan Wisata Terakhir</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Daftar trip terbaru yang telah Anda daftarkan di platform ini.</p>
                </div>
                <a href="{{ route('booking.my') }}" class="text-xs font-bold text-sky-400 hover:text-sky-300 flex items-center gap-1 transition">
                    <span>Lihat Semua Pesanan</span>
                    <span>&rarr;</span>
                </a>
            </div>

            <div class="divide-y divide-slate-800/60">
                @forelse($recentBookings as $item)
                    <div class="p-6 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6 hover:bg-navy-850/40 transition">
                        <div class="flex items-start gap-4">
                            @if(!empty($item->tour->thumbnail))
                                <img src="{{ $item->tour->thumbnail }}" alt="{{ $item->tour->title }}" class="w-20 h-16 sm:w-24 sm:h-20 rounded-xl object-cover border border-slate-700/60 shadow-md shrink-0">
                            @else
                                <div class="w-20 h-16 sm:w-24 sm:h-20 rounded-xl bg-navy-800 border border-slate-700/60 flex items-center justify-center text-2xl text-slate-500 shrink-0">
                                    🏞️
                                </div>
                            @endif

                            <div class="space-y-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="font-mono text-xs font-bold text-sky-400 bg-sky-500/10 px-2 py-0.5 rounded border border-sky-500/20">
                                        {{ $item->booking_code }}
                                    </span>
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider
                                        @if($item->status === 'paid') bg-emerald-500/20 text-emerald-400 border border-emerald-500/30
                                        @elseif($item->status === 'confirmed') bg-sky-500/20 text-sky-400 border border-sky-500/30
                                        @elseif($item->status === 'pending') bg-amber-500/20 text-amber-400 border border-amber-500/30
                                        @else bg-rose-500/20 text-rose-400 border border-rose-500/30 @endif">
                                        {{ $item->status }}
                                    </span>
                                </div>
                                <h3 class="font-bold text-white text-base">
                                    {{ $item->tour->title ?? 'Paket Wisata' }}
                                </h3>
                                <p class="text-xs text-slate-400">
                                    📅 Keberangkatan: <strong class="text-slate-200">{{ $item->travel_date ? $item->travel_date->format('d M Y') : '-' }}</strong> &bull; 
                                    👥 {{ $item->pax_count }} Peserta
                                    @if(!empty($item->tour->destination))
                                        &bull; 📍 {{ $item->tour->destination->name }}
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between lg:justify-end gap-6 w-full lg:w-auto pt-4 lg:pt-0 border-t lg:border-t-0 border-slate-800/80">
                            <div class="text-left lg:text-right">
                                <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 block">Total Tagihan</span>
                                <span class="text-lg font-black text-white">Rp {{ number_format($item->total_price, 0, ',', '.') }}</span>
                            </div>

                            <a href="{{ route('booking.show', $item->booking_code) }}" 
                               class="px-4 py-2.5 rounded-xl bg-navy-800 hover:bg-navy-750 text-sky-400 hover:text-sky-300 font-bold text-xs border border-slate-700/80 hover:border-sky-500/50 transition flex items-center gap-1.5 shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>Detail Invoice</span>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center space-y-4">
                        <div class="w-16 h-16 mx-auto rounded-2xl bg-navy-800 border border-slate-700/60 flex items-center justify-center text-3xl">
                            🧭
                        </div>
                        <div class="max-w-md mx-auto">
                            <h3 class="text-base font-bold text-white">Belum Ada Riwayat Pesanan</h3>
                            <p class="text-xs text-slate-400 mt-1">
                                Anda belum memiliki pesanan paket trip. Mulai rencanakan liburan impian Anda ke Bromo, Labuan Bajo, atau destinasi Nusantara lainnya.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('tours.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-sky-500 hover:bg-sky-400 text-navy-950 font-bold text-xs shadow-lg shadow-sky-500/25 transition">
                                <span>Eksplor Katalog Paket Wisata</span>
                                <span>&rarr;</span>
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Official Brand Guarantee Footer Snippet -->
        <div class="rounded-2xl bg-gradient-to-r from-navy-900 to-navy-850 border border-slate-800/80 p-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400">
            <div class="flex items-center gap-3">
                @if(!empty($company->logo_url))
                    <img src="{{ $company->logo_url }}" alt="{{ $company->company_name }}" class="h-6 w-auto object-contain opacity-80">
                @endif
                <span>{{ $company->license_info ?? 'Tour Operator Berlisensi Resmi ASITA & HPI' }} &bull; {{ $company->operational_hours ?? 'Layanan 08:00 - 22:00 WIB' }}</span>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('profile.edit') }}" class="hover:text-sky-400 transition">Pengaturan Akun & Sandi</a>
                <span>&bull;</span>
                <a href="{{ $company->getWhatsappUrl('Halo ' . ($company->short_name ?? 'ALFITO') . ', saya ingin bertanya tentang paket wisata custom') }}" target="_blank" class="hover:text-sky-400 text-sky-400 font-semibold transition">Bantuan CS</a>
            </div>
        </div>

    </div>
</div>
@endsection
