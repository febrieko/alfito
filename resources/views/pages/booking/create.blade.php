@extends('layouts.web')

@section('title', 'Form Pemesanan – ' . $tour->title)

@section('content')
    <div class="bg-navy-900 border-b border-slate-800 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6">
            <span class="text-sky-400 text-xs font-bold uppercase tracking-widest block mb-1">Langkah Terakhir</span>
            <h1 class="text-2xl sm:text-3xl font-black text-white">Formulir Data Pemesanan</h1>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
        <form action="{{ route('booking.store') }}" method="POST" class="space-y-8">
            @csrf
            <input type="hidden" name="tour_id" value="{{ $tour->id }}">

            <!-- Tour Summary Card -->
            <div class="glass-card-static p-6 rounded-2xl border border-slate-800 flex flex-col sm:flex-row gap-5 items-start">
                <img src="{{ $tour->thumbnail }}" alt="{{ $tour->title }}" class="w-full sm:w-36 h-28 rounded-xl object-cover">
                <div class="flex-1">
                    <span class="text-[11px] font-bold text-sky-400 uppercase tracking-wide block mb-1">
                        {{ $tour->type === 'open_trip' ? 'Open Trip' : 'Private Trip' }} &bull; {{ $tour->duration_days }} Hari
                    </span>
                    <h3 class="text-lg font-bold text-white">{{ $tour->title }}</h3>
                    <p class="text-xs text-slate-400 mt-1">Destinasi: {{ $tour->destination->name }}</p>
                    <div class="mt-2 text-xs font-semibold text-slate-300">
                        Harga: <span class="text-sky-400">Rp {{ number_format($tour->price, 0, ',', '.') }}</span> / orang
                    </div>
                </div>
            </div>

            <!-- Form Fields -->
            <div class="glass-card-static p-6 sm:p-8 rounded-2xl border border-slate-800 space-y-6">
                <h3 class="text-lg font-bold text-white border-b border-slate-800 pb-3">Informasi Kontak Pemesan</h3>

                @if(!auth()->check())
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Nama Lengkap *</label>
                            <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Budi Santoso" 
                                   class="glass-input w-full px-4 py-2.5 rounded-xl text-xs sm:text-sm">
                            @error('name') <span class="text-xs text-rose-400">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Alamat Email *</label>
                            <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com" 
                                   class="glass-input w-full px-4 py-2.5 rounded-xl text-xs sm:text-sm">
                            @error('email') <span class="text-xs text-rose-400">{{ $message }}</span> @enderror
                        </div>
                    </div>
                @else
                    <div class="p-4 rounded-xl bg-navy-950/60 border border-slate-800 text-xs text-slate-300 flex items-center justify-between">
                        <div>
                            Login sebagai: <strong class="text-white">{{ auth()->user()->name }}</strong> ({{ auth()->user()->email }})
                        </div>
                        <span class="text-sky-400 font-semibold">Akun Terverifikasi</span>
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">Nomor WhatsApp Aktif *</label>
                        <input type="text" name="contact_phone" value="{{ old('contact_phone', auth()->user()?->phone) }}" required placeholder="081234567890" 
                               class="glass-input w-full px-4 py-2.5 rounded-xl text-xs sm:text-sm">
                        <span class="text-[10px] text-slate-400 mt-1 block">Konfirmasi trip akan dikirim ke nomor ini</span>
                        @error('contact_phone') <span class="text-xs text-rose-400">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">Tanggal Berangkat *</label>
                        <input type="date" name="travel_date" value="{{ old('travel_date', $travelDate) }}" required min="{{ now()->addDay()->format('Y-m-d') }}"
                               class="glass-input w-full px-4 py-2.5 rounded-xl text-xs sm:text-sm">
                        @error('travel_date') <span class="text-xs text-rose-400">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">Jumlah Peserta (Pax) *</label>
                        <input type="number" name="pax_count" value="{{ old('pax_count', $pax) }}" min="1" max="50" required 
                               class="glass-input w-full px-4 py-2.5 rounded-xl text-xs sm:text-sm">
                        @error('pax_count') <span class="text-xs text-rose-400">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">Catatan Tambahan / Titik Penjemputan (Opsional)</label>
                    <textarea name="special_request" rows="3" placeholder="Contoh: Minta dijemput di Stasiun Malang / Hotel Santika pukul 00:30..." 
                              class="glass-input w-full px-4 py-2.5 rounded-xl text-xs sm:text-sm">{{ old('special_request') }}</textarea>
                </div>
            </div>

            <!-- Submit Button & Trust -->
            <div class="glass-card-static p-6 rounded-2xl border border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-xs text-slate-400">
                    Dengan menekan tombol pesan, Anda menyetujui syarat & ketentuan perjalanan kami.
                </div>

                <button type="submit" class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-gradient-to-r from-sky-400 to-blue-500 hover:from-sky-300 hover:to-blue-400 text-navy-950 font-bold text-sm shadow-xl shadow-sky-500/25 transition transform hover:-translate-y-0.5">
                    Konfirmasi & Buat Pesanan →
                </button>
            </div>
        </form>
    </div>
@endsection
