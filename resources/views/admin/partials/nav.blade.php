@php
    $pendingBookingsCount = \App\Models\Booking::where('status', 'pending')->count();
    $pendingReviewsCount = \App\Models\Review::where('is_approved', false)->count();

    $navItems = [
        [
            'route' => 'admin.dashboard',
            'pattern' => 'admin.dashboard',
            'icon' => '📊',
            'label' => 'Dashboard',
            'badge' => null,
            'badgeColor' => null,
        ],
        [
            'route' => 'admin.bookings.index',
            'pattern' => 'admin.bookings.*',
            'icon' => '📑',
            'label' => 'Booking & Pesanan',
            'badge' => $pendingBookingsCount > 0 ? $pendingBookingsCount : null,
            'badgeColor' => 'bg-amber-500 text-navy-950',
        ],
        [
            'route' => 'admin.tours.index',
            'pattern' => 'admin.tours.*',
            'icon' => '📦',
            'label' => 'Paket Wisata',
            'badge' => null,
            'badgeColor' => null,
        ],
        [
            'route' => 'admin.categories.index',
            'pattern' => 'admin.categories.*',
            'icon' => '🏢',
            'label' => 'Kategori & Sub',
            'badge' => null,
            'badgeColor' => null,
        ],
        [
            'route' => 'admin.destinations.index',
            'pattern' => 'admin.destinations.*',
            'icon' => '📍',
            'label' => 'Destinasi',
            'badge' => null,
            'badgeColor' => null,
        ],
        [
            'route' => 'admin.users.index',
            'pattern' => 'admin.users.*',
            'icon' => '👥',
            'label' => 'Pengguna & Agen',
            'badge' => null,
            'badgeColor' => null,
        ],
        [
            'route' => 'admin.reviews.index',
            'pattern' => 'admin.reviews.*',
            'icon' => '⭐',
            'label' => 'Ulasan Review',
            'badge' => $pendingReviewsCount > 0 ? $pendingReviewsCount : null,
            'badgeColor' => 'bg-rose-500 text-white',
        ],
        [
            'route' => 'admin.posts.index',
            'pattern' => 'admin.posts.*',
            'icon' => '📰',
            'label' => 'Blog Guide',
            'badge' => null,
            'badgeColor' => null,
        ],
        [
            'route' => 'admin.hero-slides.index',
            'pattern' => 'admin.hero-slides.*',
            'icon' => '🖼️',
            'label' => 'Slider Hero',
            'badge' => null,
            'badgeColor' => null,
        ],
        [
            'route' => 'admin.settings.edit',
            'pattern' => 'admin.settings.*',
            'icon' => '⚙️',
            'label' => 'Profil Perusahaan',
            'badge' => null,
            'badgeColor' => null,
        ],
    ];
@endphp

<div class="mb-8">
    <div class="glass-card p-2 rounded-2xl border border-slate-700/60 shadow-lg overflow-x-auto no-scrollbar">
        <nav class="flex items-center gap-1.5 min-w-max">
            @foreach($navItems as $item)
                @php
                    $isActive = request()->routeIs($item['pattern']);
                @endphp
                <a href="{{ route($item['route']) }}"
                   class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 whitespace-nowrap {{ $isActive ? 'bg-gradient-to-r from-sky-500 to-sky-400 text-navy-950 shadow-md shadow-sky-500/25' : 'text-slate-300 hover:text-white hover:bg-navy-850' }}">
                    <span>{{ $item['icon'] }}</span>
                    <span>{{ $item['label'] }}</span>
                    @if($item['badge'])
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] font-black {{ $item['badgeColor'] }}">
                            {{ $item['badge'] }}
                        </span>
                    @endif
                </a>
            @endforeach
        </nav>
    </div>
</div>
