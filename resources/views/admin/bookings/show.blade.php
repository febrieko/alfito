@extends('layouts.web')

@section('title', 'Detail Booking: ' . $booking->booking_code . ' – Panel Admin ALFITO')

@section('content')
<div class="py-12 bg-navy-950 min-h-screen text-slate-100">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-2">
                    <a href="{{ route('home') }}" class="hover:text-sky-400 transition">Beranda</a>
                    <span>/</span>
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-sky-400 transition">Admin</a>
                    <span>/</span>
                    <a href="{{ route('admin.bookings.index') }}" class="hover:text-sky-400 transition">Pesanan</a>
                    <span>/</span>
                    <span class="text-sky-400 font-bold">{{ $booking->booking_code }}</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight flex items-center gap-3">
                    <span>Detail Pesanan</span>
                    <span class="font-mono text-sky-400 text-xl font-bold bg-sky-500/10 px-3 py-1 rounded-xl border border-sky-500/20">
                        {{ $booking->booking_code }}
                    </span>
                </h1>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.bookings.index') }}"
                   class="px-4 py-2.5 rounded-xl bg-navy-900 hover:bg-navy-800 text-slate-300 font-semibold text-xs border border-slate-700 transition">
                    ← Kembali ke Daftar
                </a>
                <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST"
                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus booking {{ $booking->booking_code }}?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2.5 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 font-semibold text-xs border border-rose-500/30 transition">
                        Hapus Pesanan
                    </button>
                </form>
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

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Left 2 Cols: Details -->
            <div class="md:col-span-2 space-y-6">
                <!-- Paket Wisata Info -->
                <div class="glass-card p-6 rounded-2xl border border-slate-700/60 shadow-xl space-y-4">
                    <h2 class="text-sm font-bold text-slate-400 uppercase tracking-wider flex items-center gap-2">
                        <span>📦 Informasi Paket Wisata Dipesan</span>
                    </h2>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <img src="{{ $booking->tour->thumbnail }}" alt="{{ $booking->tour->title }}"
                             class="w-full sm:w-40 h-28 object-cover rounded-xl border border-slate-700 flex-shrink-0">
                        <div class="space-y-1.5 flex-1">
                            <h3 class="text-lg font-bold text-white hover:text-sky-400 transition">
                                <a href="{{ route('tours.show', $booking->tour->slug) }}" target="_blank">
                                    {{ $booking->tour->title }} ↗
                                </a>
                            </h3>
                            <div class="text-xs text-slate-400 flex flex-wrap items-center gap-3">
                                <span>📍 {{ $booking->tour->destination->name }}</span>
                                <span>•</span>
                                <span>⏱️ {{ $booking->tour->duration_days }}H{{ $booking->tour->duration_nights ? $booking->tour->duration_nights.'M' : '' }}</span>
                                @if($booking->tour->category)
                                    <span>•</span>
                                    <span class="text-sky-400">{{ $booking->tour->category->icon }} {{ $booking->tour->category->name }}</span>
                                @endif
                            </div>
                            <div class="pt-2 text-xs text-slate-300">
                                Dikelola oleh Agen: <strong>{{ $booking->tour->agen->name }}</strong> ({{ $booking->tour->agen->email }})
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer Details -->
                <div class="glass-card p-6 rounded-2xl border border-slate-700/60 shadow-xl space-y-4">
                    <h2 class="text-sm font-bold text-slate-400 uppercase tracking-wider flex items-center gap-2">
                        <span>👤 Data Pemesan / Kontak Pelanggan</span>
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div class="p-3 rounded-xl bg-navy-900 border border-slate-800">
                            <div class="text-slate-400">Nama Pelanggan</div>
                            <div class="text-base font-bold text-white mt-1">{{ $booking->user->name }}</div>
                        </div>
                        <div class="p-3 rounded-xl bg-navy-900 border border-slate-800">
                            <div class="text-slate-400">Email Akun</div>
                            <div class="text-sm font-semibold text-white mt-1">{{ $booking->user->email }}</div>
                        </div>
                        <div class="p-3 rounded-xl bg-navy-900 border border-slate-800">
                            <div class="text-slate-400">Nomor WhatsApp / HP</div>
                            <div class="text-base font-mono font-bold text-sky-400 mt-1">{{ $booking->contact_phone }}</div>
                        </div>
                        <div class="p-3 rounded-xl bg-navy-900 border border-slate-800 flex items-center justify-between">
                            <div>
                                <div class="text-slate-400">Hubungi Langsung</div>
                                <div class="text-xs text-slate-300 mt-0.5">Kirim pesan WhatsApp</div>
                            </div>
                            @php
                                $waPhone = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $booking->contact_phone));
                                $waText = urlencode("Halo {$booking->user->name}, kami dari Tim ALFITO mengenai pesanan {$booking->booking_code} ({$booking->tour->title}).");
                            @endphp
                            <a href="https://wa.me/{{ $waPhone }}?text={{ $waText }}" target="_blank"
                               class="px-3 py-1.5 rounded-lg bg-emerald-500 hover:bg-emerald-400 text-navy-950 font-bold text-xs transition flex items-center gap-1.5 shadow-md">
                                <span>Chat WA ↗</span>
                            </a>
                        </div>
                    </div>

                    @if($booking->special_request)
                        <div class="p-3 rounded-xl bg-navy-900 border border-slate-800 text-xs">
                            <div class="text-slate-400 font-semibold mb-1">Catatan Khusus dari Pelanggan:</div>
                            <div class="text-slate-200 italic whitespace-pre-line">{{ $booking->special_request }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right 1 Col: Status Update & Financials -->
            <div class="space-y-6">
                <!-- Status & Action Card -->
                <div class="glass-card p-6 rounded-2xl border border-slate-700/60 shadow-xl space-y-5">
                    <h2 class="text-sm font-bold text-white flex items-center gap-2">
                        <span>⚙ Perbarui Status Pesanan</span>
                    </h2>

                    <form action="{{ route('admin.bookings.status', $booking) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Pilih Status Baru</label>
                            <select name="status" class="glass-input w-full px-3 py-2.5 rounded-xl text-xs border border-slate-700 bg-navy-900 text-white font-bold cursor-pointer">
                                <option value="pending" {{ $booking->status === 'pending' ? 'selected' : '' }} class="bg-navy-900 text-amber-400">⏳ Pending (Menunggu Pembayaran)</option>
                                <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }} class="bg-navy-900 text-sky-400">✓ Confirmed (Terkonfirmasi)</option>
                                <option value="paid" {{ $booking->status === 'paid' ? 'selected' : '' }} class="bg-navy-900 text-emerald-400">💰 Paid (Lunas)</option>
                                <option value="completed" {{ $booking->status === 'completed' ? 'selected' : '' }} class="bg-navy-900 text-indigo-400">★ Completed (Trip Selesai)</option>
                                <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }} class="bg-navy-900 text-rose-400">✕ Cancelled (Dibatalkan)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Catatan Admin (Internal)</label>
                            <textarea name="admin_note" rows="3" placeholder="Contoh: Bukti transfer BCA sudah diverifikasi..."
                                      class="glass-input w-full px-3 py-2 rounded-xl text-xs border border-slate-700 bg-navy-900 text-white placeholder-slate-500">{{ old('admin_note', $booking->admin_note) }}</textarea>
                        </div>

                        <button type="submit" class="w-full py-2.5 rounded-xl bg-gradient-to-r from-sky-500 to-sky-400 hover:from-sky-400 hover:to-sky-300 text-navy-950 font-bold text-xs transition shadow-lg shadow-sky-500/20">
                            Simpan Perubahan Status
                        </button>
                    </form>
                </div>

                <!-- Financial Summary Card -->
                <div class="glass-card p-6 rounded-2xl border border-slate-700/60 shadow-xl space-y-3 text-xs">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Ringkasan Biaya</h3>
                    <div class="flex items-center justify-between text-slate-300">
                        <span>Harga per Pax</span>
                        <span>Rp {{ number_format($booking->tour->price, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between text-slate-300">
                        <span>Jumlah Pax</span>
                        <span>{{ $booking->pax_count }} orang</span>
                    </div>
                    <div class="pt-3 border-t border-slate-800 flex items-center justify-between text-sm font-black text-emerald-400">
                        <span>Total Pembayaran</span>
                        <span>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                    </div>

                    <div class="pt-4 border-t border-slate-800/80 space-y-1.5 text-[11px] text-slate-400">
                        <div>Waktu Booking: <span class="text-white">{{ $booking->created_at->format('d M Y, H:i') }}</span></div>
                        @if($booking->confirmed_at)
                            <div>Dikonfirmasi: <span class="text-sky-300">{{ $booking->confirmed_at->format('d M Y, H:i') }}</span></div>
                        @endif
                        @if($booking->paid_at)
                            <div>Pelunasan: <span class="text-emerald-300">{{ $booking->paid_at->format('d M Y, H:i') }}</span></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
