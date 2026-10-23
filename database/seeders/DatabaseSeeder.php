<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Destination;
use App\Models\Post;
use App\Models\Review;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ── 1. Create Users ──────────────────────────────────────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@alfito.test'],
            [
                'name' => 'Admin ALFITO',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '081234567890',
                'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=300&q=80',
                'bio' => 'Head Administrator ALFITO',
            ]
        );

        $agenBromo = User::firstOrCreate(
            ['email' => 'agen@alfito.test'],
            [
                'name' => 'Bromo Adventure Organizer',
                'password' => Hash::make('password'),
                'role' => 'agen',
                'phone' => '081298765432',
                'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=300&q=80',
                'bio' => 'Spesialis trip Jawa Timur & Bromo sejak 2018 berlisensi resmi HPI.',
            ]
        );

        $agenFlores = User::firstOrCreate(
            ['email' => 'agen.flores@alfito.test'],
            [
                'name' => 'Flores Phinisi Cruise',
                'password' => Hash::make('password'),
                'role' => 'agen',
                'phone' => '081377889900',
                'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=300&q=80',
                'bio' => 'Operator kapal phinisi liveaboard berstandar internasional di Labuan Bajo & Komodo.',
            ]
        );

        $traveler = User::firstOrCreate(
            ['email' => 'traveler@alfito.test'],
            [
                'name' => 'Dimas Surya Pratama',
                'password' => Hash::make('password'),
                'role' => 'user',
                'phone' => '082155443322',
                'avatar' => 'https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?auto=format&fit=crop&w=300&q=80',
                'bio' => 'Pencinta alam, fotografer lanskap & pemburu sunset.',
            ]
        );

        $traveler2 = User::firstOrCreate(
            ['email' => 'anisa@alfito.test'],
            [
                'name' => 'Anisa Rahmawati',
                'password' => Hash::make('password'),
                'role' => 'user',
                'phone' => '081999887766',
                'avatar' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=300&q=80',
                'bio' => 'Travel blogger & coffee enthusiast.',
            ]
        );

        // ── 2. Create Destinations ───────────────────────────────────
        $destBromo = Destination::firstOrCreate(
            ['slug' => 'gunung-bromo-tengger'],
            [
                'name' => 'Gunung Bromo & Tengger',
                'region' => 'Jawa Timur',
                'country' => 'Indonesia',
                'description' => 'Ikon pariwisata Jawa Timur dengan keajaiban lautan pasir berbisik, kawah aktif spektakuler, bukit teletubbies yang hijau, serta panorama golden sunrise terbaik di Penanjakan.',
                'thumbnail' => 'https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?auto=format&fit=crop&w=1200&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1609137144822-4985f8f53767?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1518548419970-58e3b4079ab2?auto=format&fit=crop&w=1200&q=80',
                ],
                'map_lat' => -7.9424936,
                'map_lng' => 112.9530122,
                'is_featured' => true,
                'view_count' => 1520,
            ]
        );

        $destBajo = Destination::firstOrCreate(
            ['slug' => 'labuan-bajo-komodo'],
            [
                'name' => 'Labuan Bajo & Taman Nasional Komodo',
                'region' => 'Nusa Tenggara Timur',
                'country' => 'Indonesia',
                'description' => 'Surga wisata bahari kelas dunia, habitat kadal purba komodo, trekking Pulau Padar dengan 3 pantai berlainan warna, snorkeling bersama pari Manta, dan sunset romantis di Pulau Kalong.',
                'thumbnail' => 'https://images.unsplash.com/photo-1596401057633-54a8fe8ef647?auto=format&fit=crop&w=1200&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1596401057633-54a8fe8ef647?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1516690561799-46d8f74f9abf?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1570789210967-2cac24afeb00?auto=format&fit=crop&w=1200&q=80',
                ],
                'map_lat' => -8.4539958,
                'map_lng' => 119.8893946,
                'is_featured' => true,
                'view_count' => 2840,
            ]
        );

        $destRajaAmpat = Destination::firstOrCreate(
            ['slug' => 'raja-ampat-papua'],
            [
                'name' => 'Raja Ampat',
                'region' => 'Papua Barat Daya',
                'country' => 'Indonesia',
                'description' => 'Kepulauan karst paling memukau di bumi dengan keanekaragaman hayati laut terkaya di segitiga terumbu karang dunia. Gugusan pulau Wayag, laguna Piaynemo, dan pasir timbul Pasir Putih.',
                'thumbnail' => 'https://images.unsplash.com/photo-1516690561799-46d8f74f9abf?auto=format&fit=crop&w=1200&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1516690561799-46d8f74f9abf?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=1200&q=80',
                ],
                'map_lat' => -0.233333,
                'map_lng' => 130.516667,
                'is_featured' => true,
                'view_count' => 3100,
            ]
        );

        $destBali = Destination::firstOrCreate(
            ['slug' => 'nusa-penida-bali'],
            [
                'name' => 'Nusa Penida & Bali Selatan',
                'region' => 'Bali',
                'country' => 'Indonesia',
                'description' => 'Tebing eksotis Kelingking Beach berbentuk T-Rex, kolam alami Angel Billabong, Broken Beach yang dramatis, serta pemandangan bawah laut jernih bersama Manta Ray.',
                'thumbnail' => 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=1200&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1555400038-63f5ba517a47?auto=format&fit=crop&w=1200&q=80',
                ],
                'map_lat' => -8.7278,
                'map_lng' => 115.5444,
                'is_featured' => true,
                'view_count' => 1950,
            ]
        );

        $destJogja = Destination::firstOrCreate(
            ['slug' => 'yogyakarta-borobudur'],
            [
                'name' => 'Yogyakarta & Candi Borobudur',
                'region' => 'DI Yogyakarta & Jawa Tengah',
                'country' => 'Indonesia',
                'description' => 'Pusat kebudayaan Jawa yang memikat hati: Kemegahan candi Buddha terbesar di dunia Borobudur, candi Prambanan yang anggun, petualangan jeep lava tour Merapi, dan kuliner legendaris Malioboro.',
                'thumbnail' => 'https://images.unsplash.com/photo-1600172454520-134a542a2255?auto=format&fit=crop&w=1200&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1600172454520-134a542a2255?auto=format&fit=crop&w=1200&q=80',
                ],
                'map_lat' => -7.7956,
                'map_lng' => 110.3695,
                'is_featured' => false,
                'view_count' => 1400,
            ]
        );

        $destDerawan = Destination::firstOrCreate(
            ['slug' => 'kepulauan-derawan'],
            [
                'name' => 'Kepulauan Derawan & Maratua',
                'region' => 'Kalimantan Timur',
                'country' => 'Indonesia',
                'description' => 'Berenang bersama ubur-ubur tanpa sengat di Danau Kakaban, menyaksikan hiu paus jinak di Talisayan, dan menjelajahi surga gua laut dan penyu raksasa di Pulau Sangalaki.',
                'thumbnail' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=1200&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=1200&q=80',
                ],
                'map_lat' => 2.2858,
                'map_lng' => 118.2436,
                'is_featured' => false,
                'view_count' => 980,
            ]
        );

        // ── 3. Create Tours ──────────────────────────────────────────
        $tourBromoMidnight = Tour::firstOrCreate(
            ['slug' => 'open-trip-bromo-midnight-sunrise'],
            [
                'user_id' => $agenBromo->id,
                'destination_id' => $destBromo->id,
                'title' => 'Open Trip Bromo Midnight Sunrise & Kawah Bromo',
                'type' => 'open_trip',
                'duration_days' => 1,
                'duration_nights' => 0,
                'price' => 350000,
                'price_private' => 1450000,
                'min_pax' => 1,
                'max_pax' => 20,
                'description' => 'Paket open trip hemat paling populer untuk menikmati keindahan sunrise spektakuler Gunung Bromo dari spot Penanjakan 1 / Kingkong Hill. Ditemani armada Jeep 4x4 profesional dan pemandu berpengalaman.',
                'itinerary' => [
                    [
                        'day' => 1,
                        'title' => 'Eksplorasi Bromo Golden Sunrise & 5 Destinasi Utama',
                        'activities' => [
                            '00:00 - 01:00 : Penjemputan peserta di Malang / Surabaya (Stasiun/Hotel)',
                            '01:00 - 03:00 : Perjalanan menuju Rest Area Sukapura / Tosari',
                            '03:00 - 03:30 : Transit Jeep 4x4 menuju Sunrise Point Penanjakan 1 / Kingkong Hill',
                            '04:30 - 06:00 : Menikmati Golden Sunrise Bromo dan sesi foto berlatar Gunung Batok & Semeru',
                            '06:30 - 08:00 : Menuju Kawah Bromo & Pura Luhur Poten (jalan kaki / opsi sewa kuda)',
                            '08:30 - 09:30 : Mengunjungi Pasir Berbisik dan Padang Savana Bukit Teletubbies',
                            '10:00 - 11:00 : Kembali ke meeting point Sukapura & bersih-bersih',
                            '11:30 - 14:00 : Pengantaran kembali ke Malang / Surabaya. Trip selesai!',
                        ],
                    ],
                ],
                'includes' => [
                    'Transportasi AC Malang/Surabaya PP',
                    'Jeep 4x4 Bromo 5 Spot Resmi',
                    'Tiket Masuk TNBTS (Wisnus)',
                    'Driver & BBM',
                    'Tour Leader / Guide Ramah',
                    'Air Mineral selama perjalanan',
                    'Dokumentasi Foto Standar',
                    'Asuransi perjalanan TNBTS',
                ],
                'excludes' => [
                    'Sewa kuda di Kawah Bromo (opsional)',
                    'Pengeluaran pribadi & makan di luar program',
                    'Sewa jaket / sarung tangan',
                    'Tip sukarela driver & tour leader',
                ],
                'thumbnail' => 'https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?auto=format&fit=crop&w=1200&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1609137144822-4985f8f53767?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1518548419970-58e3b4079ab2?auto=format&fit=crop&w=1200&q=80',
                ],
                'is_featured' => true,
                'is_active' => true,
                'view_count' => 1240,
            ]
        );

        $tourBajoLiveaboard = Tour::firstOrCreate(
            ['slug' => 'sailing-komodo-3d2n-deluxe-phinisi'],
            [
                'user_id' => $agenFlores->id,
                'destination_id' => $destBajo->id,
                'title' => 'Sailing Komodo 3D2N Liveaboard Phinisi Deluxe',
                'type' => 'open_trip',
                'duration_days' => 3,
                'duration_nights' => 2,
                'price' => 2850000,
                'price_private' => 18500000,
                'min_pax' => 1,
                'max_pax' => 14,
                'description' => 'Sensasi liburan mewah menginap di atas kapal phinisi modern berkeliling Taman Nasional Komodo. Trekking Pulau Padar, bertemu Komodo Dragon, snorkeling di Pink Beach dan Manta Point!',
                'itinerary' => [
                    [
                        'day' => 1,
                        'title' => 'Kelor Island - Manjarite - Kalong Island Sunset',
                        'activities' => [
                            '09:30 - 10:30 : Penjemputan di hotel / Bandara Komodo Labuan Bajo menuju pelabuhan',
                            '11:00 - 12:30 : Berlayar ke Pulau Kelor, trekking bukit & lunch on board',
                            '13:30 - 15:30 : Snorkeling bersama ribuan ikan di Manjarite Island yang jernih',
                            '17:00 - 18:30 : Menikmati sunset spektakuler jutaan kelelawar terbang di Pulau Kalong',
                            '19:00 - 21:00 : Dinner istimewa hidangan laut segar & istirahat di kapal',
                        ],
                    ],
                    [
                        'day' => 2,
                        'title' => 'Padar Island Sunrise - Pink Beach - Komodo Island - Manta Point',
                        'activities' => [
                            '05:00 - 07:30 : Trekking pagi ke puncak ikonik Pulau Padar untuk sunrise panorama 3 teluk',
                            '08:30 - 10:30 : Berenang & foto estetik di pasir merah muda Pink Beach',
                            '11:00 - 13:00 : Eksplorasi habitat asli komodo bersama Ranger profesional di Pulau Komodo',
                            '14:00 - 16:30 : Snorkeling bersama kawanan Pari Manta raksasa di Manta Point & Taka Makassar',
                            '19:00 - 21:00 : BBQ Dinner on boat di perairan tenang',
                        ],
                    ],
                    [
                        'day' => 3,
                        'title' => 'Kanawa Island Snorkeling - Kembali ke Labuan Bajo',
                        'activities' => [
                            '07:30 - 09:30 : Snorkeling di terumbu karang warna-warni dermaga Pulau Kanawa',
                            '10:30 - 12:00 : Berlayar kembali menuju Pelabuhan Labuan Bajo',
                            '12:30 - 13:30 : Drop off kembali ke Bandara Komodo / Hotel pilihan Anda',
                        ],
                    ],
                ],
                'includes' => [
                    'Menginap 2 malam di Kapal Phinisi Deluxe Ber-AC',
                    'Antar jemput Bandara Komodo / Hotel di Labuan Bajo',
                    'Makan 7x Chef on board (Breakfast, Lunch, Dinner, Snack)',
                    'Air mineral, teh, kopi sepuasnya',
                    'Peralatan snorkeling lengkap & pelampung',
                    'Dokumentasi Drone DJI, Mirrorless & Go-Pro underwater',
                    'Tour Guide berlisensi & Kru kapal ramah',
                    'P3K standar di kapal',
                ],
                'excludes' => [
                    'Tiket masuk TN Komodo & Ranger fee',
                    'Tiket pesawat dari kota asal ke Labuan Bajo',
                    'Pengeluaran pribadi & tipping kru/guide',
                ],
                'thumbnail' => 'https://images.unsplash.com/photo-1596401057633-54a8fe8ef647?auto=format&fit=crop&w=1200&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1596401057633-54a8fe8ef647?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1516690561799-46d8f74f9abf?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1570789210967-2cac24afeb00?auto=format&fit=crop&w=1200&q=80',
                ],
                'is_featured' => true,
                'is_active' => true,
                'view_count' => 3120,
            ]
        );

        $tourRajaAmpat = Tour::firstOrCreate(
            ['slug' => 'raja-ampat-wayag-piaynemo-4d3n'],
            [
                'user_id' => $agenFlores->id,
                'destination_id' => $destRajaAmpat->id,
                'title' => 'Exotic Raja Ampat Wayag & Piaynemo 4D3N Homestay Resort',
                'type' => 'private_trip',
                'duration_days' => 4,
                'duration_nights' => 3,
                'price' => 5950000,
                'price_private' => 5950000,
                'min_pax' => 2,
                'max_pax' => 12,
                'description' => 'Jelajahi surga terakhir di bumi. Mendaki puncak karst Wayag yang magis, menikmati laguna bintang Telaga Bintang, snorkeling di Sawinggrai, dan santai di pasir timbul Mansuar.',
                'itinerary' => [
                    [
                        'day' => 1,
                        'title' => 'Sorong - Waisai - Teluk Kabui - Batu Pensil',
                        'activities' => [
                            '08:00 - 09:00 : Penjemputan di Bandara DEO Sorong & menuju Pelabuhan Rakyat',
                            '09:00 - 11:30 : Kapal Cepat Bahari Express Sorong - Waisai',
                            '12:00 - 14:00 : Check-in water cottage & makan siang',
                            '14:30 - 17:30 : Mengunjungi Teluk Kabui, formasi karst Batu Pensil & snorkeling',
                            '19:00 : Makan malam santai di dermaga resort',
                        ],
                    ],
                    [
                        'day' => 2,
                        'title' => 'Wayag Karst Peak 1 & 2 - Pos Konservasi Shark Point',
                        'activities' => [
                            '06:30 - 09:30 : Berlayar dengan Speedboat menuju Kepulauan Wayag',
                            '10:00 - 12:30 : Mendaki Puncak Wayag 1 & 2 dengan view ikonik panorama dunia',
                            '13:00 - 15:00 : Berenang bersama bayi hiu karang (blacktip shark) di Pos Konservasi',
                            '17:30 : Kembali ke penginapan & makan malam',
                        ],
                    ],
                    [
                        'day' => 3,
                        'title' => 'Piaynemo - Telaga Bintang - Yenbuba Jetty - Pasir Timbul',
                        'activities' => [
                            '07:30 - 09:30 : Menuju Piaynemo (Mini Wayag) & foto di gardu pandang kayu',
                            '10:00 - 11:30 : Trekking Telaga Bintang dengan laguna bentuk bintang alami',
                            '12:30 - 14:30 : Snorkeling melihat jutaan ikan di bawah dermaga Yenbuba',
                            '15:00 - 16:30 : Mampir ke Pasir Timbul di tengah laut berpasir putih selembut tepung',
                        ],
                    ],
                    [
                        'day' => 4,
                        'title' => 'Waisai - Sorong Belanja Oleh-Oleh - Kepulangan',
                        'activities' => [
                            '07:00 - 08:30 : Breakfast & check out dari penginapan',
                            '09:00 - 11:00 : Ferry Express Waisai kembali ke Sorong',
                            '11:30 - 13:00 : Singgah beli roti abon gulung legendaris & drop off bandara',
                        ],
                    ],
                ],
                'includes' => [
                    'Penginapan Water Cottage tepi pantai 3 malam (AC)',
                    'Speedboat private selama tour pulau',
                    'Tiket Ferry Express PP Sorong - Waisai (VIP Class)',
                    'Makan lengkap 3x sehari kuliner nusantara & seafood',
                    'Alat snorkeling & pelampung',
                    'Guide lokal tersertifikasi',
                    'Dokumentasi Mirrorless & Drone',
                ],
                'excludes' => [
                    'Kartu PIN Wisata / Tarif Konservasi Raja Ampat (KJL)',
                    'Tiket pesawat ke Sorong',
                    'Tipping sukarela',
                ],
                'thumbnail' => 'https://images.unsplash.com/photo-1516690561799-46d8f74f9abf?auto=format&fit=crop&w=1200&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1516690561799-46d8f74f9abf?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=1200&q=80',
                ],
                'is_featured' => true,
                'is_active' => true,
                'view_count' => 2450,
            ]
        );

        $tourPenida = Tour::firstOrCreate(
            ['slug' => 'one-day-tour-nusa-penida-west-snorkeling'],
            [
                'user_id' => $agenBromo->id,
                'destination_id' => $destBali->id,
                'title' => 'One Day Tour Nusa Penida West & Manta Bay Snorkeling',
                'type' => 'open_trip',
                'duration_days' => 1,
                'duration_nights' => 0,
                'price' => 485000,
                'price_private' => 950000,
                'min_pax' => 1,
                'max_pax' => 15,
                'description' => 'Paket harian lengkap mengeksplor tebing T-Rex Pantai Kelingking, Broken Beach, Angel Billabong, serta snorkeling di 3 spot menawan termasuk Manta Bay.',
                'itinerary' => [
                    [
                        'day' => 1,
                        'title' => 'Sanur - Nusa Penida West Coast Tour',
                        'activities' => [
                            '07:00 - 07:30 : Kumpul di Pelabuhan Sanur Denpasar',
                            '08:00 - 08:45 : Fastboat menuju Pulau Nusa Penida',
                            '09:00 - 11:30 : Boat snorkeling ke Manta Bay, Crystal Bay, Gamat Bay',
                            '12:00 - 13:00 : Makan siang di restoran lokal',
                            '13:30 - 14:30 : Mengunjungi Kelingking Cliff & foto panorama T-Rex',
                            '15:00 - 16:00 : Mengunjungi Broken Beach & Angel Billabong',
                            '16:30 - 17:30 : Fastboat kembali ke Sanur Bali',
                        ],
                    ],
                ],
                'includes' => [
                    'Tiket Fastboat PP Sanur - Nusa Penida',
                    'Mobil AC & Driver lokal ramah merangkap fotografer',
                    'Boat snorkeling + perlengkapan alat',
                    'Tiket masuk semua objek wisata',
                    'Makan siang di restoran',
                ],
                'excludes' => [
                    'Antar jemput hotel di Bali ke Sanur (opsional)',
                    'Pengeluaran pribadi',
                ],
                'thumbnail' => 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=1200&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1555400038-63f5ba517a47?auto=format&fit=crop&w=1200&q=80',
                ],
                'is_featured' => true,
                'is_active' => true,
                'view_count' => 1890,
            ]
        );

        $tourJogja = Tour::firstOrCreate(
            ['slug' => 'heritage-jogja-3d2n-borobudur-merapi-lava'],
            [
                'user_id' => $agenBromo->id,
                'destination_id' => $destJogja->id,
                'title' => 'Heritage Jogja 3D2N Borobudur Sunrise & Merapi Lava Jeep',
                'type' => 'private_trip',
                'duration_days' => 3,
                'duration_nights' => 2,
                'price' => 1650000,
                'price_private' => 1650000,
                'min_pax' => 2,
                'max_pax' => 10,
                'description' => 'Paket liburan keluarga eksklusif di Yogyakarta. Menikmati sunrise Borobudur dari Punthuk Setumbu, petualangan jeep lava tour lereng Merapi, Candi Prambanan, dan hunting kuliner malam Malioboro.',
                'itinerary' => [
                    [
                        'day' => 1,
                        'title' => 'Kedatangan - Candi Prambanan - Tebing Breksi Sunset',
                        'activities' => [
                            'Penjemputan di Bandara YIA / Stasiun Tugu',
                            'Wisata sejarah ke Candi Prambanan',
                            'Menikmati sunset di Tebing Breksi',
                            'Kuliner malam & check in hotel bintang 3/4',
                        ],
                    ],
                    [
                        'day' => 2,
                        'title' => 'Sunrise Punthuk Setumbu - Candi Borobudur - Jeep Merapi',
                        'activities' => [
                            '04:00 Sunrise Punthuk Setumbu berlatar Borobudur & Merapi',
                            'Kunjungan Candi Borobudur',
                            'Jeep Lava Tour Kaliurang Merapi rute seru basah-basahan Kali Kuning',
                            'Belanja bakpia kukus tugu',
                        ],
                    ],
                    [
                        'day' => 3,
                        'title' => 'Keraton Yogyakarta - Tamansari Water Castle - Kepulangan',
                        'activities' => [
                            'Mengunjungi Keraton Kasultanan Yogyakarta',
                            'Eksplor lorong bawah tanah Tamansari',
                            'Oleh-oleh Malioboro & pengantaran ke bandara/stasiun',
                        ],
                    ],
                ],
                'includes' => [
                    'Hotel Bintang 3 di pusat kota Jogja (2 malam + breakfast)',
                    'Mobil All New Avanza/Innova AC + Driver + BBM',
                    'Sewa Jeep Merapi Lava Tour',
                    'Tiket masuk semua objek wisata',
                    'Air mineral & parkir',
                ],
                'excludes' => [
                    'Tiket pesawat/kereta api ke Jogja',
                    'Makan siang & malam di luar paket',
                ],
                'thumbnail' => 'https://images.unsplash.com/photo-1600172454520-134a542a2255?auto=format&fit=crop&w=1200&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1600172454520-134a542a2255?auto=format&fit=crop&w=1200&q=80',
                ],
                'is_featured' => false,
                'is_active' => true,
                'view_count' => 840,
            ]
        );

        // ── 4. Create Bookings & Reviews ─────────────────────────────
        $booking1 = Booking::firstOrCreate(
            ['booking_code' => 'WK-202410-001'],
            [
                'user_id' => $traveler->id,
                'tour_id' => $tourBromoMidnight->id,
                'travel_date' => now()->addDays(7)->format('Y-m-d'),
                'pax_count' => 2,
                'total_price' => 700000,
                'status' => 'confirmed',
                'contact_phone' => '082155443322',
                'special_request' => 'Mohon penjemputan di Stasiun Malang Kota Baru pukul 00:30 WIB.',
                'confirmed_at' => now()->subDay(),
            ]
        );

        $booking2 = Booking::firstOrCreate(
            ['booking_code' => 'WK-202410-002'],
            [
                'user_id' => $traveler2->id,
                'tour_id' => $tourBajoLiveaboard->id,
                'travel_date' => now()->addDays(14)->format('Y-m-d'),
                'pax_count' => 2,
                'total_price' => 5700000,
                'status' => 'paid',
                'contact_phone' => '081999887766',
                'special_request' => 'Minta kamar private double bed sea view ya kak.',
                'confirmed_at' => now()->subDays(2),
                'paid_at' => now()->subDay(),
            ]
        );

        Review::firstOrCreate(
            ['booking_id' => $booking1->id],
            [
                'user_id' => $traveler->id,
                'tour_id' => $tourBromoMidnight->id,
                'rating' => 5,
                'title' => 'Pengalaman Bromo Pertama Kali yang Luar Biasa!',
                'comment' => 'Driver Jeep sangat handal, ramah dan jago ambil foto di spot-spot tersembunyi. Sunrise di Kingkong Hill bener-bener magis! Sukses terus untuk ALFITO.',
                'is_approved' => true,
            ]
        );

        Review::firstOrCreate(
            ['booking_id' => $booking2->id],
            [
                'user_id' => $traveler2->id,
                'tour_id' => $tourBajoLiveaboard->id,
                'rating' => 5,
                'title' => 'Sailing Phinisi Terbaik, Makanan Chef Hotel Bintang 5!',
                'comment' => 'Kapalnya bersih banget, AC dingin, dan makanannya melimpah ruah! Pas di Manta Point dapet kesempatan berenang bareng 4 pari manta sekaligus. Pengalaman sekali seumur hidup yang tak terlupakan.',
                'is_approved' => true,
            ]
        );

        // Additional testimonials
        Review::firstOrCreate(
            ['comment' => 'Trip ke Nusa Penida sangat terorganisir. Fastboat tepat waktu, tour guidenya Mas Wayan super sabar bantuin kami trekking turun dan foto di Kelingking. Recommended banget!'],
            [
                'user_id' => $traveler->id,
                'tour_id' => $tourPenida->id,
                'rating' => 5,
                'title' => 'Pelayanan ramah, foto-fotonya cinematic!',
                'is_approved' => true,
            ]
        );

        // ── 5. Create Posts (Travel Guide & Blog) ─────────────────────
        Post::firstOrCreate(
            ['slug' => 'panduan-lengkap-pertama-kali-sailing-komodo-labuan-bajo'],
            [
                'user_id' => $admin->id,
                'destination_id' => $destBajo->id,
                'title' => 'Panduan Lengkap Pertama Kali Sailing Komodo di Labuan Bajo',
                'excerpt' => 'Semua yang perlu Anda ketahui sebelum naik kapal phinisi di Labuan Bajo: tips memilih kapal, pakaian yang tepat, hingga waktu terbaik melihat manta ray.',
                'content' => 'Labuan Bajo telah bertransformasi menjadi salah satu destinasi wisata premium kebanggaan Indonesia. Bagi Anda yang baru pertama kali merencanakan sailing liveaboard di Taman Nasional Komodo, ada beberapa hal penting yang harus dipersiapkan...'."\n\n".
                    '### 1. Kapan Waktu Terbaik ke Labuan Bajo?'."\n".
                    'Musim kemarau antara bulan April hingga Oktober adalah waktu paling ideal. Langit cerah, ombak tenang, dan perairan sangat jernih untuk snorkeling.'."\n\n".
                    '### 2. Barang Wajib Bawa'."\n".
                    '- Sepatu trekking atau sandal gunung anti-slip untuk mendaki Pulau Padar.'."\n".
                    '- Dry bag untuk melindungi gadget Anda selama transfer sekoci.'."\n".
                    '- Sunscreen ramah terumbu karang (reef-safe) dan kacamata polaroid.'."\n\n".
                    '### 3. Pilihan Trip: Open Trip vs Private Trip'."\n".
                    'Open trip berangkat setiap hari Jumat siang dan kembali hari Minggu sore, sangat cocok untuk solo traveler atau pasangan yang ingin hemat budget.',
                'thumbnail' => 'https://images.unsplash.com/photo-1596401057633-54a8fe8ef647?auto=format&fit=crop&w=1200&q=80',
                'category' => 'guide',
                'tags' => ['Labuan Bajo', 'Tips Wisata', 'Komodo', 'Phinisi'],
                'is_published' => true,
                'published_at' => now()->subDays(5),
                'view_count' => 640,
            ]
        );

        Post::firstOrCreate(
            ['slug' => 'tips-berpakaian-dan-persiapan-golden-sunrise-bromo'],
            [
                'user_id' => $admin->id,
                'destination_id' => $destBromo->id,
                'title' => 'Tips Berpakaian & Persiapan Menikmati Golden Sunrise Gunung Bromo',
                'excerpt' => 'Suhu di Bromo bisa mencapai 3°C hingga 10°C di pagi dini hari. Simak panduan layering pakaian dan perlengkapan penting agar liburan Anda tetap hangat dan nyaman.',
                'content' => 'Berada di ketinggian 2.329 mdpl, suhu udara di kawasan Penanjakan Gunung Bromo saat dini hari bisa sangat menusuk tulang. Berikut adalah panduan busana layering yang tepat...'."\n\n".
                    '1. Lapisan Pertama (Base Layer): Gunakan thermal inner wear / long johns yang menyerap keringat.'."\n".
                    '2. Lapisan Kedua (Mid Layer): Sweater fleece atau rajut tebal.'."\n".
                    '3. Lapisan Luar (Outer): Jaket windbreaker tebal atau down jacket anti-angin.'."\n".
                    '4. Aksesori Wajib: Kupluk/beanie menutupi telinga, sarung tangan fleece, dan syal tebal.'."\n".
                    'Jangan lupa membawa masker kain untuk menyaring debu pasir vulkanik di lautan pasir!',
                'thumbnail' => 'https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?auto=format&fit=crop&w=1200&q=80',
                'category' => 'tips',
                'tags' => ['Bromo', 'Outfit Wisata', 'Tips Liburan'],
                'is_published' => true,
                'published_at' => now()->subDays(2),
                'view_count' => 480,
            ]
        );
        // ── 6. Create Hero Slides ────────────────────────────────────
        $this->call(HeroSlideSeeder::class);
    }
}
