@extends('layouts.web')

@section('title', 'Pusat Kendali Admin – ALFITO')

@section('content')
<div class="py-12 bg-navy-950 min-h-screen text-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-start sm:items-center gap-4">
                @if(!empty($company->logo_url))
                    <div class="p-2.5 rounded-2xl bg-navy-900 border border-slate-700/80 shadow-md shrink-0">
                        <img src="{{ $company->logo_url }}" alt="{{ $company->company_name }}" class="h-11 w-auto object-contain">
                    </div>
                @else
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-sky-400 to-blue-600 flex items-center justify-center text-navy-950 font-black text-2xl shadow-lg shadow-sky-500/25 shrink-0">
                        🧭
                    </div>
                @endif
                <div>
                    <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-1">
                        <a href="{{ route('home') }}" class="hover:text-sky-400 transition">Beranda</a>
                        <span>/</span>
                        <span class="text-sky-400 font-bold">Panel Administrator</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight flex items-center gap-3">
                        <span>Pusat Kendali Administrator</span>
                        <span class="px-2.5 py-1 text-xs rounded-full bg-sky-500/20 text-sky-400 font-mono border border-sky-500/30">
                            Super Admin
                        </span>
                    </h1>
                    <p class="text-sm text-slate-400 mt-0.5">
                        Kelola seluruh data paket wisata, transaksi pesanan, destinasi, artikel, ulasan, dan pengguna {{ $company->short_name ?? 'ALFITO' }}.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" target="_blank"
                   class="px-4 py-2.5 rounded-xl bg-navy-800 hover:bg-navy-700 text-slate-200 text-xs font-semibold border border-slate-700/70 transition flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <span>Kunjungi Website</span>
                </a>
                <a href="{{ route('admin.tours.create') }}" 
                   class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-sky-500 to-sky-400 hover:from-sky-400 hover:to-sky-300 text-navy-950 font-bold text-xs shadow-lg shadow-sky-500/25 transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>+ Paket Baru</span>
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 text-sm flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-lg">✓</span>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-xs hover:text-white">✕</button>
            </div>
        @endif

        <!-- Admin Nav Bar Component -->
        @include('admin.partials.nav')

        <!-- KPI Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Revenue Card -->
            <div class="glass-card p-6 rounded-2xl border border-slate-700/60 shadow-xl relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-sky-500/10 rounded-full blur-xl group-hover:bg-sky-500/20 transition"></div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Omset Sukses</span>
                    <span class="w-10 h-10 rounded-xl bg-sky-500/20 text-sky-400 flex items-center justify-center text-lg border border-sky-400/30">
                        💰
                    </span>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-white">
                    Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}
                </div>
                <div class="mt-2 text-xs text-slate-400 flex items-center gap-1.5">
                    <span class="text-emerald-400 font-bold">●</span>
                    <span>Dari pesanan terkonfirmasi & lunas</span>
                </div>
            </div>

            <!-- Total Bookings Card -->
            <div class="glass-card p-6 rounded-2xl border border-slate-700/60 shadow-xl relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-amber-500/10 rounded-full blur-xl group-hover:bg-amber-500/20 transition"></div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Booking</span>
                    <span class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-lg border border-amber-400/30">
                        📑
                    </span>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-white">
                    {{ $stats['total_bookings'] }}
                </div>
                <div class="mt-2 text-xs text-slate-400 flex items-center gap-2">
                    <span class="text-amber-400 font-bold">{{ $stats['pending_bookings'] }} pending</span>
                    <span>•</span>
                    <span class="text-emerald-400 font-bold">{{ $stats['confirmed_bookings'] }} terbayar</span>
                </div>
            </div>

            <!-- Tours & Destinations -->
            <div class="glass-card p-6 rounded-2xl border border-slate-700/60 shadow-xl relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-emerald-500/10 rounded-full blur-xl group-hover:bg-emerald-500/20 transition"></div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Katalog Wisata</span>
                    <span class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-lg border border-emerald-400/30">
                        📦
                    </span>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-white">
                    {{ $stats['total_tours'] }} <span class="text-sm font-normal text-slate-400">Paket</span>
                </div>
                <div class="mt-2 text-xs text-slate-400 flex items-center gap-2">
                    <span class="text-sky-400 font-bold">{{ $stats['total_destinations'] }} Destinasi</span>
                    <span>•</span>
                    <span class="text-emerald-400 font-bold">{{ $stats['total_categories'] }} Kategori</span>
                </div>
            </div>

            <!-- Users & Agents -->
            <div class="glass-card p-6 rounded-2xl border border-slate-700/60 shadow-xl relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-purple-500/10 rounded-full blur-xl group-hover:bg-purple-500/20 transition"></div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Pengguna</span>
                    <span class="w-10 h-10 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center text-lg border border-purple-400/30">
                        👥
                    </span>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-white">
                    {{ $stats['total_users'] }} <span class="text-sm font-normal text-slate-400">Akun</span>
                </div>
                <div class="mt-2 text-xs text-slate-400 flex items-center gap-2">
                    <span class="text-purple-400 font-bold">{{ $stats['total_agents'] }} Agen Resmi</span>
                    <span>•</span>
                    <span>{{ $stats['total_posts'] }} Blog</span>
                </div>
            </div>
        </div>

        <!-- Quick Access Shortcuts Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <a href="{{ route('admin.bookings.index', ['status' => 'pending']) }}"
               class="glass-card p-4 rounded-xl border border-amber-500/30 hover:border-amber-400 transition flex items-center gap-3">
                <span class="text-2xl">⏳</span>
                <div>
                    <div class="text-xs text-slate-400">Perlu Konfirmasi</div>
                    <div class="text-sm font-bold text-amber-300">{{ $stats['pending_bookings'] }} Pesanan Baru</div>
                </div>
            </a>

            <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}"
               class="glass-card p-4 rounded-xl border border-rose-500/30 hover:border-rose-400 transition flex items-center gap-3">
                <span class="text-2xl">⭐</span>
                <div>
                    <div class="text-xs text-slate-400">Perlu Moderasi</div>
                    <div class="text-sm font-bold text-rose-300">{{ $stats['pending_reviews'] }} Ulasan Tertunda</div>
                </div>
            </a>

            <a href="{{ route('admin.categories.index') }}"
               class="glass-card p-4 rounded-xl border border-emerald-500/30 hover:border-emerald-400 transition flex items-center gap-3">
                <span class="text-2xl">🏢</span>
                <div>
                    <div class="text-xs text-slate-400">Gathering & Wisata</div>
                    <div class="text-sm font-bold text-emerald-300">Kelola Kategori & Sub</div>
                </div>
            </a>

            <a href="{{ route('admin.hero-slides.index') }}"
               class="glass-card p-4 rounded-xl border border-sky-500/30 hover:border-sky-400 transition flex items-center gap-3">
                <span class="text-2xl">🖼️</span>
                <div>
                    <div class="text-xs text-slate-400">Tampilan Beranda</div>
                    <div class="text-sm font-bold text-sky-300">Pengaturan Slider</div>
                </div>
            </a>
        </div>

        <!-- Two Columns: Recent Bookings & Reviews Moderation -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Recent Bookings Table (2 cols) -->
            <div class="lg:col-span-2 glass-card rounded-2xl border border-slate-700/60 shadow-xl overflow-hidden flex flex-col">
                <div class="p-6 border-b border-slate-800 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-white flex items-center gap-2">
                            <span>📑 Pesanan Terbaru Masuk</span>
                        </h2>
                        <p class="text-xs text-slate-400 mt-0.5">Daftar transaksi dan booking terbaru yang dibuat oleh pelanggan.</p>
                    </div>
                    <a href="{{ route('admin.bookings.index') }}" class="text-xs font-semibold text-sky-400 hover:text-sky-300 transition">
                        Lihat Semua Pesanan →
                    </a>
                </div>

                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-navy-900 text-slate-400 uppercase font-bold tracking-wider border-b border-slate-800">
                            <tr>
                                <th class="py-3 px-4">Kode Booking</th>
                                <th class="py-3 px-4">Pemesan</th>
                                <th class="py-3 px-4">Paket Wisata</th>
                                <th class="py-3 px-4">Total Biaya</th>
                                <th class="py-3 px-4 text-center">Status</th>
                                <th class="py-3 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/80 font-medium">
                            @forelse($recentBookings as $booking)
                                <tr class="hover:bg-navy-900/40 transition">
                                    <td class="py-3 px-4">
                                        <div class="font-mono font-bold text-sky-400">{{ $booking->booking_code }}</div>
                                        <div class="text-[10px] text-slate-500">{{ $booking->created_at->format('d M Y H:i') }}</div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="font-bold text-white">{{ $booking->user->name }}</div>
                                        <div class="text-[10px] text-slate-400">{{ $booking->contact_phone }}</div>
                                    </td>
                                    <td class="py-3 px-4 max-w-xs truncate" title="{{ $booking->tour->title }}">
                                        <div class="text-slate-200 truncate">{{ $booking->tour->title }}</div>
                                        <div class="text-[10px] text-slate-400">{{ $booking->pax_count }} Pax • {{ $booking->travel_date->format('d M Y') }}</div>
                                    </td>
                                    <td class="py-3 px-4 font-bold text-white">
                                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        @php
                                            $statusClasses = [
                                                'pending' => 'bg-amber-500/20 text-amber-400 border-amber-500/30',
                                                'confirmed' => 'bg-sky-500/20 text-sky-400 border-sky-500/30',
                                                'paid' => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30',
                                                'completed' => 'bg-indigo-500/20 text-indigo-400 border-indigo-500/30',
                                                'cancelled' => 'bg-rose-500/20 text-rose-400 border-rose-500/30',
                                            ];
                                        @endphp
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase border {{ $statusClasses[$booking->status] ?? 'bg-slate-700 text-slate-300' }}">
                                            {{ $booking->status }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-right">
                                        <a href="{{ route('admin.bookings.show', $booking) }}"
                                           class="px-2.5 py-1 rounded-lg bg-navy-800 hover:bg-navy-700 text-sky-400 border border-slate-700 transition font-bold text-[11px]">
                                            Kelola
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-slate-400">Belum ada data pesanan booking.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Right Column: Pending Reviews & Top Tours (1 col) -->
            <div class="space-y-8">
                <!-- Review Moderation Queue -->
                <div class="glass-card rounded-2xl border border-slate-700/60 shadow-xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-sm font-bold text-white flex items-center gap-2">
                            <span>⭐ Moderasi Ulasan</span>
                            @if($stats['pending_reviews'] > 0)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-500 text-white">
                                    {{ $stats['pending_reviews'] }} baru
                                </span>
                            @endif
                        </h2>
                        <a href="{{ route('admin.reviews.index') }}" class="text-xs text-sky-400 hover:underline">
                            Semua →
                        </a>
                    </div>

                    <div class="space-y-3">
                        @forelse($pendingReviews as $review)
                            <div class="p-3 rounded-xl bg-navy-900 border border-slate-800 space-y-2">
                                <div class="flex items-center justify-between">
                                    <div class="font-bold text-white text-xs">{{ $review->user->name }}</div>
                                    <div class="text-amber-400 text-xs">★ {{ $review->rating }}/5</div>
                                </div>
                                <div class="text-[11px] text-slate-400 font-semibold truncate">{{ $review->tour->title }}</div>
                                <p class="text-xs text-slate-300 italic line-clamp-2">"{{ $review->comment }}"</p>
                                <div class="flex items-center justify-end gap-2 pt-1 border-t border-slate-800/80">
                                    <form action="{{ route('admin.reviews.toggle-approval', $review) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-2 py-1 rounded bg-emerald-500/20 text-emerald-300 hover:bg-emerald-500/30 text-[10px] font-bold border border-emerald-500/30 transition">
                                            ✓ Setujui
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Hapus ulasan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2 py-1 rounded bg-rose-500/20 text-rose-300 hover:bg-rose-500/30 text-[10px] font-bold border border-rose-500/30 transition">
                                            ✕ Tolak
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-6 text-xs text-slate-400">
                                <div>✓</div>
                                <div class="mt-1">Tidak ada ulasan tertunda. Semua ulasan telah dimoderasi!</div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Top Tours Mini List -->
                <div class="glass-card rounded-2xl border border-slate-700/60 shadow-xl p-6">
                    <h2 class="text-sm font-bold text-white mb-4 flex items-center gap-2">
                        <span>🏆 Paket Wisata Terpopuler</span>
                    </h2>
                    <div class="space-y-3">
                        @foreach($topTours as $index => $tour)
                            <div class="flex items-center gap-3">
                                <span class="w-6 h-6 rounded-lg bg-navy-800 border border-slate-700 flex items-center justify-center text-xs font-bold font-mono {{ $index === 0 ? 'text-amber-400 border-amber-400/30' : 'text-slate-400' }}">
                                    {{ $index + 1 }}
                                </span>
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs font-bold text-slate-200 truncate">{{ $tour->title }}</div>
                                    <div class="text-[10px] text-slate-400">
                                        {{ $tour->destination->name }} • Rp {{ number_format($tour->price, 0, ',', '.') }}
                                    </div>
                                </div>
                                <div class="text-xs font-bold text-sky-400 whitespace-nowrap">
                                    {{ $tour->bookings_count }} order
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
