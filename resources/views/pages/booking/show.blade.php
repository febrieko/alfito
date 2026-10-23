@extends('layouts.web')

@section('title', 'Invoice Pesanan ' . $booking->booking_code . ' – ALFITO')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-12">
        <!-- Success Alert Header -->
        <div class="text-center mb-10">
            <div class="w-16 h-16 rounded-full bg-sky-500/20 text-sky-400 border border-sky-500/40 flex items-center justify-center text-3xl mx-auto mb-4 animate-bounce">
                ✓
            </div>
            <span class="text-sky-400 text-xs font-bold uppercase tracking-widest block mb-1">Pesanan Berhasil Dibuat!</span>
            <h1 class="text-3xl sm:text-4xl font-black text-white">Invoice & Konfirmasi Pesanan</h1>
            <p class="text-slate-400 text-sm mt-2">Simpan kode pesanan Anda atau langsung hubungi CS WhatsApp kami untuk pembayaran.</p>
        </div>

        <!-- Invoice Card -->
        <div class="glass-card-static rounded-3xl border border-slate-700/80 overflow-hidden shadow-2xl">
            <!-- Header Banner -->
            <div class="bg-navy-950 p-6 sm:p-8 border-b border-slate-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <span class="text-xs text-slate-400 block">Kode Pesanan (Booking Code)</span>
                    <span class="text-2xl font-black text-sky-400 tracking-wider font-mono">{{ $booking->booking_code }}</span>
                </div>
                <div class="text-left sm:text-right">
                    <span class="text-xs text-slate-400 block">Status Pembayaran</span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide
                        {{ $booking->status === 'paid' ? 'bg-sky-500/20 text-sky-400 border border-sky-500/30' : 'bg-amber-500/20 text-amber-400 border border-amber-500/30' }}">
                        {{ $booking->status === 'paid' ? 'LUNAS (PAID)' : 'MENUNGGU KONFIRMASI (PENDING)' }}
                    </span>
                </div>
            </div>

            <!-- Invoice Details -->
            <div class="p-6 sm:p-8 space-y-6">
                <!-- Tour Information -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pb-6 border-b border-slate-800 text-sm">
                    <div>
                        <h4 class="text-xs font-semibold text-slate-400 uppercase mb-2">Detail Perjalanan</h4>
                        <h3 class="text-base font-bold text-white mb-1">{{ $booking->tour->title }}</h3>
                        <p class="text-xs text-slate-300">Destinasi: {{ $booking->tour->destination->name }}</p>
                        <p class="text-xs text-slate-300 mt-1">
                            Durasi: {{ $booking->tour->duration_days }} Hari {{ $booking->tour->duration_nights ? $booking->tour->duration_nights.' Malam' : '' }}
                        </p>
                    </div>
                    <div>
                        <h4 class="text-xs font-semibold text-slate-400 uppercase mb-2">Data Pemesan</h4>
                        <p class="text-white font-medium">{{ $booking->user->name }}</p>
                        <p class="text-xs text-slate-300">{{ $booking->user->email }}</p>
                        <p class="text-xs text-sky-400 font-mono mt-1">WA: {{ $booking->contact_phone }}</p>
                    </div>
                </div>

                <!-- Schedule & Breakdown Table -->
                <div class="space-y-3">
                    <h4 class="text-xs font-semibold text-slate-400 uppercase">Rincian Biaya</h4>
                    <div class="rounded-xl bg-navy-950/70 border border-slate-800 p-4 space-y-2.5 text-xs sm:text-sm">
                        <div class="flex justify-between text-slate-300">
                            <span>Tanggal Keberangkatan</span>
                            <span class="font-bold text-white">{{ $booking->travel_date->format('d F Y') }}</span>
                        </div>
                        <div class="flex justify-between text-slate-300">
                            <span>Jumlah Peserta</span>
                            <span class="font-bold text-white">{{ $booking->pax_count }} Orang</span>
                        </div>
                        @if($booking->special_request)
                            <div class="flex justify-between text-slate-300 pt-2 border-t border-slate-800/80">
                                <span>Catatan / Titik Jemput</span>
                                <span class="text-slate-400 text-right max-w-xs">{{ $booking->special_request }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between items-center text-base sm:text-lg font-black text-white pt-3 border-t border-slate-800">
                            <span>Total Tagihan</span>
                            <span class="text-sky-400 font-extrabold">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Next Steps Instructions -->
                <div class="p-5 rounded-2xl bg-sky-950/30 border border-sky-500/30 space-y-3">
                    <h4 class="text-sm font-bold text-sky-400 flex items-center gap-2">
                        <span>💬</span> Langkah Konfirmasi Cepat:
                    </h4>
                    <ol class="list-decimal list-inside space-y-1.5 text-xs text-slate-300 leading-relaxed">
                        <li>Klik tombol <strong>"Konfirmasi ke WhatsApp CS"</strong> di bawah ini.</li>
                        <li>CS kami akan mengecek jadwal dan memberikan instruksi pembayaran rekening resmi BCA/Mandiri atau QRIS.</li>
                        <li>Kirimkan bukti transfer, dan e-voucher tiket beserta kontak driver akan diterbitkan.</li>
                    </ol>
                </div>

                <!-- WhatsApp CTA Action -->
                @php
                    $waText = urlencode(
                        "Halo Customer Service ALFITO,\n" .
                        "Saya ingin konfirmasi pesanan saya:\n" .
                        "• Kode Booking: {$booking->booking_code}\n" .
                        "• Paket: {$booking->tour->title}\n" .
                        "• Tanggal: {$booking->travel_date->format('d M Y')}\n" .
                        "• Peserta: {$booking->pax_count} orang\n" .
                        "• Total: Rp " . number_format($booking->total_price, 0, ',', '.') . "\n\n" .
                        "Mohon dibantu nomor rekening resmi untuk pembayaran ya. Terima kasih!"
                    );
                    $waPhone = $company->clean_whatsapp ?? '6281234567890';
                    $waUrl = "https://wa.me/{$waPhone}?text={$waText}";
                @endphp

                <div class="pt-4 flex flex-col sm:flex-row gap-4 items-center justify-between">
                    <a href="{{ route('home') }}" class="text-xs text-slate-400 hover:text-white transition">
                        ← Kembali ke Beranda
                    </a>

                    <a href="{{ $waUrl }}" target="_blank" 
                       class="w-full sm:w-auto px-8 py-4 rounded-xl bg-gradient-to-r from-sky-400 to-blue-500 hover:from-sky-300 hover:to-blue-400 text-navy-950 font-bold text-sm shadow-xl shadow-sky-500/30 flex items-center justify-center gap-2 transition transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.299.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.144.39-.086.159.058 1.011.477 1.184.564.173.087.289.13.332.202.043.072.043.419-.101.824z"/></svg>
                        <span>Konfirmasi via WhatsApp Sekarang</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
