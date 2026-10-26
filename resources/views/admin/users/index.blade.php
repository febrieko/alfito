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
                    Tambah pengguna baru, kelola perizinan role, pantau aktivitas reservasi, serta aktifkan atau nonaktifkan akun pengguna.
                </p>
            </div>

            <!-- Add User Button -->
            <div>
                <a href="{{ route('admin.users.create') }}"
                   class="px-5 py-3 rounded-xl bg-gradient-to-r from-sky-500 via-sky-400 to-blue-500 hover:from-sky-400 hover:to-blue-400 text-navy-950 font-black text-xs uppercase tracking-wider shadow-lg shadow-sky-500/25 transition transform hover:-translate-y-0.5 inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    <span>Tambah Pengguna Baru</span>
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
        <div class="glass-card p-4 rounded-2xl border border-slate-700/60 shadow-lg flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <!-- Filter Pills -->
            <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-1 lg:pb-0">
                <a href="{{ route('admin.users.index') }}"
                   class="px-3.5 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ !$roleFilter && !$statusFilter ? 'bg-sky-500 text-navy-950' : 'bg-navy-900 text-slate-300 hover:bg-navy-850' }}">
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

                <span class="text-slate-600 font-bold hidden sm:inline">|</span>

                <a href="{{ route('admin.users.index', ['status' => 'active']) }}"
                   class="px-3.5 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $statusFilter === 'active' ? 'bg-emerald-500 text-navy-950' : 'bg-navy-900 text-emerald-400 hover:bg-navy-850' }}">
                    ✓ Aktif ({{ $roleCounts['active'] }})
                </a>
                <a href="{{ route('admin.users.index', ['status' => 'inactive']) }}"
                   class="px-3.5 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $statusFilter === 'inactive' ? 'bg-rose-500 text-white' : 'bg-navy-900 text-rose-400 hover:bg-navy-850' }}">
                    ⛔ Nonaktif ({{ $roleCounts['inactive'] }})
                </a>
            </div>

            <!-- Search Input -->
            <form action="{{ route('admin.users.index') }}" method="GET" class="flex items-center gap-2">
                @if($roleFilter)
                    <input type="hidden" name="role" value="{{ $roleFilter }}">
                @endif
                @if($statusFilter)
                    <input type="hidden" name="status" value="{{ $statusFilter }}">
                @endif
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama, email, nomor HP..."
                       class="glass-input px-3.5 py-2 rounded-xl text-xs w-full sm:w-64 border border-slate-700 bg-navy-900 text-white placeholder-slate-500 focus:border-sky-500">
                <button type="submit" class="px-3.5 py-2 rounded-xl bg-sky-500 hover:bg-sky-400 text-navy-950 font-bold text-xs transition">
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
                            <th class="py-3.5 px-4 text-center">Status</th>
                            <th class="py-3.5 px-4 text-center">Aktivitas</th>
                            <th class="py-3.5 px-4">Bergabung</th>
                            <th class="py-3.5 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/80 font-medium">
                        @forelse($users as $user)
                            <tr class="hover:bg-navy-900/40 transition {{ !$user->isActive() ? 'opacity-70 bg-rose-950/10' : '' }}">
                                <!-- User Name & Avatar -->
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                                             class="w-9 h-9 rounded-full object-cover border border-slate-700/80 shadow shrink-0">
                                        <div>
                                            <div class="font-bold text-white text-sm flex items-center gap-1.5">
                                                <span>{{ $user->name }}</span>
                                                @if($user->id === auth()->id())
                                                    <span class="px-1.5 py-0.5 rounded bg-sky-500/20 text-sky-400 font-mono text-[9px] font-bold">Anda</span>
                                                @endif
                                            </div>
                                            <div class="text-[10px] text-slate-400 font-mono">ID: #{{ $user->id }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Email & Phone -->
                                <td class="py-3.5 px-4">
                                    <div class="text-slate-200">{{ $user->email }}</div>
                                    <div class="text-[11px] font-mono text-slate-400">{{ $user->phone ?: '-' }}</div>
                                </td>

                                <!-- Role -->
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

                                <!-- Status (Aktif / Nonaktif Toggle) -->
                                <td class="py-3.5 px-4 text-center">
                                    @if($user->isActive())
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-500/20 text-rose-400 border border-rose-500/30">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>

                                <!-- Activity Counts -->
                                <td class="py-3.5 px-4 text-center">
                                    <div class="text-slate-300">{{ $user->bookings_count }} Booking</div>
                                    @if($user->role !== 'user')
                                        <div class="text-[10px] text-sky-400 font-semibold">{{ $user->tours_count }} Paket Dibuat</div>
                                    @endif
                                </td>

                                <!-- Joined Date -->
                                <td class="py-3.5 px-4 text-slate-400">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>

                                <!-- Actions -->
                                <td class="py-3.5 px-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <!-- Quick Toggle Active Status Button -->
                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.toggle-active', $user) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        title="{{ $user->isActive() ? 'Klik untuk Nonaktifkan Akun' : 'Klik untuk Aktifkan Akun' }}"
                                                        class="p-1.5 rounded-lg border transition text-xs {{ $user->isActive() ? 'bg-amber-500/10 hover:bg-amber-500/20 text-amber-300 border-amber-500/30' : 'bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 border-emerald-500/30' }}">
                                                    @if($user->isActive())
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                                    @else
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                    @endif
                                                </button>
                                            </form>
                                        @endif

                                        <!-- Edit User Button -->
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                           title="Edit Pengguna"
                                           class="p-1.5 rounded-lg bg-sky-500/10 hover:bg-sky-500/20 text-sky-400 border border-sky-500/30 transition text-xs inline-flex items-center">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>

                                        <!-- Delete Button -->
                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna {{ $user->name }}?')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        title="Hapus Pengguna"
                                                        class="p-1.5 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 transition text-xs">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-400">
                                    Tidak ada data pengguna yang sesuai dengan filter atau pencarian.
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
