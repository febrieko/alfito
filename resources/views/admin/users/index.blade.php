@extends('layouts.web')

@section('title', 'Kelola Pengguna & Agen – Panel Admin ALFITO')

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
                    <span class="text-sky-400 font-bold">Kelola Pengguna</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight flex items-center gap-3">
                    <span>Manajemen Pengguna & Agen</span>
                    <span class="px-2.5 py-1 text-xs rounded-full bg-sky-500/20 text-sky-400 font-mono border border-sky-500/30">
                        Admin Only
                    </span>
                </h1>
                <p class="text-sm text-slate-400 mt-1">
                    Kelola hak akses role (Admin, Agen Tur, Member), pantau aktivitas pesanan, dan verifikasi mitra agen.
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

        @if(session('error'))
            <div class="p-4 rounded-xl bg-rose-500/15 border border-rose-500/30 text-rose-300 text-sm flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-lg">✕</span>
                    <span>{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-xs hover:text-white">✕</button>
            </div>
        @endif

        @include('admin.partials.nav')

        <!-- Filter & Search Bar -->
        <div class="glass-card p-4 rounded-2xl border border-slate-700/60 shadow-lg flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-2 overflow-x-auto no-scrollbar">
                <a href="{{ route('admin.users.index') }}"
                   class="px-3.5 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ !$roleFilter ? 'bg-sky-500 text-navy-950' : 'bg-navy-900 text-slate-300 hover:bg-navy-850' }}">
                    Semua ({{ $roleCounts['all'] }})
                </a>
                <a href="{{ route('admin.users.index', ['role' => 'admin']) }}"
                   class="px-3.5 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $roleFilter === 'admin' ? 'bg-sky-500 text-navy-950' : 'bg-navy-900 text-sky-400 hover:bg-navy-850' }}">
                    🛡️ Admin ({{ $roleCounts['admin'] }})
                </a>
                <a href="{{ route('admin.users.index', ['role' => 'agen']) }}"
                   class="px-3.5 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $roleFilter === 'agen' ? 'bg-purple-500 text-white' : 'bg-navy-900 text-purple-300 hover:bg-navy-850' }}">
                    💼 Agen Tur ({{ $roleCounts['agen'] }})
                </a>
                <a href="{{ route('admin.users.index', ['role' => 'user']) }}"
                   class="px-3.5 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $roleFilter === 'user' ? 'bg-emerald-500 text-navy-950' : 'bg-navy-900 text-emerald-300 hover:bg-navy-850' }}">
                    👤 Member ({{ $roleCounts['user'] }})
                </a>
            </div>

            <form action="{{ route('admin.users.index') }}" method="GET" class="flex items-center gap-2">
                @if($roleFilter)
                    <input type="hidden" name="role" value="{{ $roleFilter }}">
                @endif
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama, email, nomor HP..."
                       class="glass-input px-3.5 py-2 rounded-xl text-xs w-full sm:w-64 border border-slate-700 bg-navy-900 text-white placeholder-slate-500 focus:border-sky-500">
                <button type="submit" class="px-3 py-2 rounded-xl bg-sky-500 hover:bg-sky-400 text-navy-950 font-bold text-xs transition">
                    Cari
                </button>
            </form>
        </div>

        <!-- Users Table -->
        <div class="glass-card rounded-2xl border border-slate-700/60 shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-navy-900 text-slate-400 uppercase font-bold tracking-wider border-b border-slate-800">
                        <tr>
                            <th class="py-3.5 px-4">Pengguna</th>
                            <th class="py-3.5 px-4">Email & Kontak</th>
                            <th class="py-3.5 px-4 text-center">Role Akses</th>
                            <th class="py-3.5 px-4 text-center">Aktivitas</th>
                            <th class="py-3.5 px-4">Bergabung</th>
                            <th class="py-3.5 px-4 text-right">Ubah Role / Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/80 font-medium">
                        @forelse($users as $user)
                            <tr class="hover:bg-navy-900/40 transition">
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-sky-500 to-indigo-500 flex items-center justify-center font-bold text-white text-xs">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-white text-sm flex items-center gap-1.5">
                                                <span>{{ $user->name }}</span>
                                                @if($user->id === auth()->id())
                                                    <span class="px-1.5 py-0.2 rounded bg-sky-500/20 text-sky-400 font-mono text-[9px]">Anda</span>
                                                @endif
                                            </div>
                                            <div class="text-[10px] text-slate-400">ID: #{{ $user->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="text-slate-200">{{ $user->email }}</div>
                                    <div class="text-[11px] font-mono text-slate-400">{{ $user->phone ?: '-' }}</div>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    @php
                                        $roleBadges = [
                                            'admin' => 'bg-sky-500/20 text-sky-400 border-sky-500/30',
                                            'agen' => 'bg-purple-500/20 text-purple-300 border-purple-500/30',
                                            'user' => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30',
                                        ];
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase border {{ $roleBadges[$user->role] ?? 'bg-slate-700 text-slate-300' }}">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <div class="text-slate-300">{{ $user->bookings_count }} Booking</div>
                                    @if($user->role !== 'user')
                                        <div class="text-[10px] text-sky-400 font-semibold">{{ $user->tours_count }} Paket Dibuat</div>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-slate-400">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Role Changer Dropdown -->
                                        <form action="{{ route('admin.users.role', $user) }}" method="POST" class="inline-flex items-center">
                                            @csrf
                                            @method('PATCH')
                                            <select name="role" onchange="this.form.submit()"
                                                    class="glass-input px-2.5 py-1 rounded-lg text-xs border border-slate-700 bg-navy-900 text-slate-200 cursor-pointer {{ $user->id === auth()->id() ? 'opacity-50 pointer-events-none' : '' }}">
                                                <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>Member</option>
                                                <option value="agen" {{ $user->role === 'agen' ? 'selected' : '' }}>Agen Tur</option>
                                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                            </select>
                                        </form>

                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                                  onsubmit="return confirm('Hapus pengguna {{ $user->name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 transition text-xs">
                                                    ✕
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-400">
                                    Tidak ada data pengguna yang sesuai.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="p-4 border-t border-slate-800">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
