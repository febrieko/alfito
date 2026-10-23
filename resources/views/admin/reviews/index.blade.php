@extends('layouts.web')

@section('title', 'Moderasi Ulasan – Panel Admin ALFITO')

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
                    <span class="text-sky-400 font-bold">Moderasi Ulasan</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight flex items-center gap-3">
                    <span>Moderasi Ulasan & Rating</span>
                    <span class="px-2.5 py-1 text-xs rounded-full bg-sky-500/20 text-sky-400 font-mono border border-sky-500/30">
                        Admin Only
                    </span>
                </h1>
                <p class="text-sm text-slate-400 mt-1">
                    Setujui atau tolak ulasan dari pelanggan sebelum ulasan dan bintang tampil pada halaman publik paket wisata.
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

        <!-- Status Filter Tabs -->
        <div class="glass-card p-3 rounded-2xl border border-slate-700/60 shadow-lg flex items-center gap-2">
            <a href="{{ route('admin.reviews.index') }}"
               class="px-4 py-2 rounded-xl text-xs font-bold transition {{ !$status ? 'bg-sky-500 text-navy-950' : 'bg-navy-900 text-slate-300 hover:bg-navy-850' }}">
                Semua Ulasan ({{ $counts['all'] }})
            </a>
            <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}"
               class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $status === 'pending' ? 'bg-amber-500 text-navy-950' : 'bg-navy-900 text-amber-300 hover:bg-navy-850' }}">
                ⏳ Menunggu Persetujuan ({{ $counts['pending'] }})
            </a>
            <a href="{{ route('admin.reviews.index', ['status' => 'approved']) }}"
               class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $status === 'approved' ? 'bg-emerald-500 text-navy-950' : 'bg-navy-900 text-emerald-300 hover:bg-navy-850' }}">
                ✓ Disetujui ({{ $counts['approved'] }})
            </a>
        </div>

        <!-- Reviews List Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($reviews as $review)
                <div class="glass-card p-6 rounded-2xl border {{ $review->is_approved ? 'border-slate-700/60' : 'border-amber-500/40 bg-amber-500/5' }} shadow-xl flex flex-col justify-between space-y-4">
                    <div class="space-y-3">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-sky-500 to-indigo-500 flex items-center justify-center font-bold text-white text-xs flex-shrink-0">
                                    {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-bold text-white text-sm">{{ $review->user->name }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $review->created_at->format('d M Y, H:i') }}</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-1 bg-navy-900 px-2.5 py-1 rounded-lg border border-slate-700 text-xs">
                                <span class="text-amber-400 font-bold">★ {{ $review->rating }}</span>
                                <span class="text-slate-500">/ 5</span>
                            </div>
                        </div>

                        <!-- Tour Title -->
                        <div class="p-2.5 rounded-xl bg-navy-900/80 border border-slate-800 text-xs">
                            <span class="text-slate-400">Paket Wisata:</span>
                            <span class="font-bold text-sky-400 ml-1">{{ $review->tour->title }}</span>
                        </div>

                        <!-- Review Comment -->
                        <div class="text-xs text-slate-200 leading-relaxed italic bg-navy-950/60 p-3 rounded-xl border border-slate-800/60">
                            "{{ $review->comment }}"
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="pt-3 border-t border-slate-800 flex items-center justify-between">
                        <div>
                            @if($review->is_approved)
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                    ● Tampil di Web
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-500/20 text-amber-400 border border-amber-500/30">
                                    ⏳ Menunggu Moderasi
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center gap-2">
                            <form action="{{ route('admin.reviews.toggle-approval', $review) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="px-3 py-1.5 rounded-xl font-bold text-xs transition {{ $review->is_approved ? 'bg-navy-800 hover:bg-navy-700 text-amber-300 border border-slate-700' : 'bg-emerald-500 hover:bg-emerald-400 text-navy-950 shadow-md shadow-emerald-500/20' }}">
                                    {{ $review->is_approved ? 'Tangguhkan' : '✓ Setujui Sekarang' }}
                                </button>
                            </form>

                            <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST"
                                  onsubmit="return confirm('Hapus ulasan ini secara permanen?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 transition text-xs">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-2 text-center py-12 glass-card rounded-2xl border border-slate-700 text-slate-400">
                    Tidak ada ulasan pada kategori ini.
                </div>
            @endforelse
        </div>

        @if($reviews->hasPages())
            <div class="pt-4">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
