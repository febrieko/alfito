@extends('layouts.web')

@section('title', 'Kelola Pesanan & Transaksi – Panel Admin ALFITO')

@section('content')
<div class="py-12 bg-navy-950 min-h-screen text-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-2">
                    <a href="{{ route('home') }}" class="hover:text-sky-400 transition">Beranda</a>
                    <span>/</span>
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-sky-400 transition">Admin</a>
                    <span>/</span>
                    <span class="text-sky-400 font-bold">Kelola Pesanan</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight flex items-center gap-3">
                    <span>Manajemen Pesanan & Transaksi</span>
                    <span class="px-2.5 py-1 text-xs rounded-full bg-sky-500/20 text-sky-400 font-mono border border-sky-500/30">
                        Admin Only
                    </span>
                </h1>
                <p class="text-sm text-slate-400 mt-1">
                    Pantau konfirmasi pembayaran, tiket pesanan, kontak pemesan, dan riwayat status booking.
                </p>
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

        @include('admin.partials.nav')

        <!-- Filter Tabs & Search -->
        <div class="glass-card p-4 rounded-2xl border border-slate-700/60 shadow-lg flex flex-col md:flex-row md:items-center justify-between gap-4">
            <!-- Status Tabs -->
            <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-1 md:pb-0">
                <a href="{{ route('admin.bookings.index') }}"
                   class="px-3.5 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ !$statusFilter ? 'bg-sky-500 text-navy-950' : 'bg-navy-900 text-slate-300 hover:bg-navy-850' }}">
                    Semua ({{ $statusCounts['all'] }})
                </a>
                <a href="{{ route('admin.bookings.index', ['status' => 'pending']) }}"
                   class="px-3.5 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $statusFilter === 'pending' ? 'bg-amber-500 text-navy-950' : 'bg-navy-900 text-amber-300 hover:bg-navy-850' }}">
                    ⏳ Pending ({{ $statusCounts['pending'] }})
                </a>
                <a href="{{ route('admin.bookings.index', ['status' => 'confirmed']) }}"
                   class="px-3.5 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $statusFilter === 'confirmed' ? 'bg-sky-500 text-navy-950' : 'bg-navy-900 text-sky-300 hover:bg-navy-850' }}">
                    ✓ Terkonfirmasi ({{ $statusCounts['confirmed'] }})
                </a>
                <a href="{{ route('admin.bookings.index', ['status' => 'paid']) }}"
                   class="px-3.5 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $statusFilter === 'paid' ? 'bg-emerald-500 text-navy-950' : 'bg-navy-900 text-emerald-300 hover:bg-navy-850' }}">
                    💰 Lunas ({{ $statusCounts['paid'] }})
                </a>
                <a href="{{ route('admin.bookings.index', ['status' => 'completed']) }}"
                   class="px-3.5 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $statusFilter === 'completed' ? 'bg-indigo-500 text-white' : 'bg-navy-900 text-indigo-300 hover:bg-navy-850' }}">
                    ★ Selesai ({{ $statusCounts['completed'] }})
                </a>
                <a href="{{ route('admin.bookings.index', ['status' => 'cancelled']) }}"
                   class="px-3.5 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $statusFilter === 'cancelled' ? 'bg-rose-500 text-white' : 'bg-navy-900 text-rose-300 hover:bg-navy-850' }}">
                    ✕ Dibatalkan ({{ $statusCounts['cancelled'] }})
                </a>
            </div>

            <!-- Search Bar -->
            <form action="{{ route('admin.bookings.index') }}" method="GET" class="flex items-center gap-2">
                @if($statusFilter)
                    <input type="hidden" name="status" value="{{ $statusFilter }}">
                @endif
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari kode, nama, nomor HP..."
                       class="glass-input px-3.5 py-2 rounded-xl text-xs w-full sm:w-64 border border-slate-700 bg-navy-900 text-white placeholder-slate-500 focus:border-sky-500">
                <button type="submit" class="px-3 py-2 rounded-xl bg-sky-500 hover:bg-sky-400 text-navy-950 font-bold text-xs transition">
                    Cari
                </button>
                @if($search)
                    <a href="{{ route('admin.bookings.index', $statusFilter ? ['status' => $statusFilter] : []) }}" class="p-2 text-xs text-slate-400 hover:text-white">✕</a>
                @endif
            </form>
        </div>

        <!-- Bookings Table -->
        <div class="glass-card rounded-2xl border border-slate-700/60 shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-navy-900 text-slate-400 uppercase font-bold tracking-wider border-b border-slate-800">
                        <tr>
                            <th class="py-3.5 px-4">Kode Booking</th>
                            <th class="py-3.5 px-4">Pelanggan</th>
                            <th class="py-3.5 px-4">Paket Wisata</th>
                            <th class="py-3.5 px-4">Jadwal Keberangkatan</th>
                            <th class="py-3.5 px-4">Total Biaya</th>
                            <th class="py-3.5 px-4 text-center">Status</th>
                            <th class="py-3.5 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/80 font-medium">
                        @forelse($bookings as $booking)
                            <tr class="hover:bg-navy-900/40 transition">
                                <td class="py-3.5 px-4">
                                    <div class="font-mono font-bold text-sky-400 text-sm">{{ $booking->booking_code }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $booking->created_at->format('d M Y, H:i') }}</div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-white text-sm">{{ $booking->user->name }}</div>
                                    <div class="text-[11px] text-slate-400 font-mono">{{ $booking->contact_phone }}</div>
                                    <div class="text-[10px] text-slate-500">{{ $booking->user->email }}</div>
                                </td>
                                <td class="py-3.5 px-4 max-w-xs">
                                    <div class="font-bold text-slate-200 line-clamp-1">{{ $booking->tour->title }}</div>
                                    <div class="text-[10px] text-sky-400">📍 {{ $booking->tour->destination->name }}</div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-white">{{ $booking->travel_date->format('d M Y') }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $booking->pax_count }} Orang (Pax)</div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-emerald-400 text-sm">
                                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                    </div>
                                    @if($booking->paid_at)
                                        <div class="text-[10px] text-emerald-400">Lunas: {{ $booking->paid_at->format('d/m/Y') }}</div>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-center">
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
                                <td class="py-3.5 px-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('admin.bookings.show', $booking) }}"
                                           class="px-3 py-1.5 rounded-lg bg-navy-800 hover:bg-navy-700 text-sky-400 border border-slate-700 transition font-bold text-xs">
                                            Detail & Ubah Status
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-400">
                                    <div class="text-3xl mb-2">📑</div>
                                    <div>Tidak ada data pesanan yang sesuai dengan filter.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($bookings->hasPages())
                <div class="p-4 border-t border-slate-800">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
