@extends('layouts.web')

@section('title', 'Riwayat Pesanan Saya – ALFITO')

@section('content')
    <div class="bg-navy-900 border-b border-slate-800 py-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <span class="text-sky-400 text-xs font-bold uppercase tracking-widest block mb-1">Akun Saya</span>
            <h1 class="text-2xl sm:text-3xl font-black text-white">Riwayat Pemesanan Trip</h1>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-10">
        <div class="space-y-4">
            @forelse($bookings as $b)
                <div class="glass-card-static p-6 rounded-2xl border border-slate-800 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                    <div class="flex items-start gap-4">
                        <img src="{{ $b->tour->thumbnail }}" alt="{{ $b->tour->title }}" class="w-24 h-20 rounded-xl object-cover">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-mono text-xs font-bold text-sky-400">{{ $b->booking_code }}</span>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase
                                    {{ $b->status === 'paid' ? 'bg-sky-500/20 text-sky-400' : 'bg-amber-500/20 text-amber-400' }}">
                                    {{ $b->status }}
                                </span>
                            </div>
                            <h3 class="text-base font-bold text-white">{{ $b->tour->title }}</h3>
                            <p class="text-xs text-slate-400 mt-1">
                                Berangkat: <strong class="text-slate-200">{{ $b->travel_date->format('d M Y') }}</strong> &bull; {{ $b->pax_count }} Orang
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between w-full md:w-auto md:justify-end gap-6 pt-4 md:pt-0 border-t md:border-t-0 border-slate-800">
                        <div class="text-left md:text-right">
                            <span class="text-[11px] text-slate-400 block">Total Tagihan</span>
                            <span class="text-base font-extrabold text-white">Rp {{ number_format($b->total_price, 0, ',', '.') }}</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('booking.show', $b->booking_code) }}" class="px-4 py-2 rounded-xl bg-navy-800 hover:bg-sky-500 hover:text-navy-950 text-sky-400 text-xs font-bold transition border border-sky-500/30">
                                Invoice
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="glass-card-static p-12 rounded-3xl border border-slate-800 text-center space-y-4">
                    <span class="text-4xl block">🎒</span>
                    <h3 class="text-lg font-bold text-white">Belum Ada Riwayat Pesanan</h3>
                    <p class="text-xs text-slate-400 max-w-sm mx-auto">
                        Anda belum memesan paket wisata apapun. Jelajahi katalog dan temukan liburan impian Anda sekarang!
                    </p>
                    <a href="{{ route('tours.index') }}" class="inline-block px-5 py-2.5 rounded-xl bg-sky-500 hover:bg-sky-400 text-navy-950 font-bold text-xs transition">
                        Jelajahi Paket Wisata
                    </a>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $bookings->links() }}
        </div>
    </div>
@endsection
