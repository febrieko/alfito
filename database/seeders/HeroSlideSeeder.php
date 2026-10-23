<?php

namespace Database\Seeders;

use App\Models\HeroSlide;
use Illuminate\Database\Seeder;

class HeroSlideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $slides = [
            [
                'title' => 'Eksplorasi Keajaiban Danau & Pura Beratan Bali',
                'subtitle' => 'Nikmati harmoni spiritual dan keindahan danau berkabut yang magis di Pulau Dewata dengan jaminan 100% Pasti Berangkat.',
                'badge' => 'Pilihan Favorit Wisatawan',
                'image' => 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=2000&q=85',
                'cta_text' => 'Lihat Paket Bali',
                'cta_link' => '/tours?destination=nusa-penida-bali',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Sailing Phinisi Mewah di Labuan Bajo & Komodo',
                'subtitle' => 'Berlayar melintasi perairan toska, trekking Pulau Padar saat sunrise, dan berenang bersama pari manta raksasa.',
                'badge' => 'Liveaboard Phinisi Berlisensi',
                'image' => 'https://images.unsplash.com/photo-1544644181-1484b3fdfc62?auto=format&fit=crop&w=2000&q=85',
                'cta_text' => 'Eksplor Komodo',
                'cta_link' => '/tours?destination=labuan-bajo-komodo',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Kemegahan Golden Sunrise Gunung Bromo',
                'subtitle' => 'Petualangan jeep 4x4 melintasi lautan pasir berbisik, kawah aktif spektakuler, dan panorama fajar di Penanjakan.',
                'badge' => 'Midnight Jeep Lava Tour',
                'image' => 'https://images.unsplash.com/photo-1605649487212-47bdab064df7?auto=format&fit=crop&w=2000&q=85',
                'cta_text' => 'Open Trip Bromo',
                'cta_link' => '/tours?destination=gunung-bromo-tengger',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Surga Karst & Laut Terkaya di Raja Ampat',
                'subtitle' => 'Menyelami pesona laguna Wayag & Piaynemo dengan keanekaragaman biota laut terlengkap di segitiga terumbu karang dunia.',
                'badge' => 'The Last Paradise on Earth',
                'image' => 'https://images.unsplash.com/photo-1516690561799-46d8f74f9abf?auto=format&fit=crop&w=2000&q=85',
                'cta_text' => 'Jelajahi Raja Ampat',
                'cta_link' => '/tours?destination=raja-ampat-papua',
                'order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($slides as $slide) {
            HeroSlide::updateOrCreate(
                ['title' => $slide['title']],
                $slide
            );
        }
    }
}
