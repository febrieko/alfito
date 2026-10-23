<?php

namespace Database\Seeders;

use App\Models\Tour;
use App\Models\TourCategory;
use Illuminate\Database\Seeder;

class TourCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Parent Category: Gathering Kantor
        $gathering = TourCategory::updateOrCreate(
            ['slug' => 'gathering-kantor'],
            [
                'parent_id' => null,
                'name' => 'Gathering Kantor',
                'icon' => '🏢',
                'description' => 'Paket wisata korporat, outbound, rapat kerja, dan kegiatan kebersamaan eksklusif untuk instansi perusahaan dan kantor.',
                'color' => 'sky',
                'order' => 1,
                'is_active' => true,
            ]
        );

        // Sub categories under Gathering Kantor
        $subCategories = [
            [
                'name' => 'Outbound & Team Building',
                'slug' => 'outbound-team-building',
                'icon' => '🎯',
                'description' => 'Aktivitas membangun sinergi dan kekompakan tim kerja dengan instruktur profesional serta fun games di alam terbuka.',
                'color' => 'emerald',
                'order' => 1,
            ],
            [
                'name' => 'Rapat Kerja (Raker) & Workshop',
                'slug' => 'raker-workshop',
                'icon' => '📊',
                'description' => 'Kombinasi fokus rapat kerja tahunan dengan kenyamanan resort berpemandangan asri dan fasilitas meeting berstandar tinggi.',
                'color' => 'indigo',
                'order' => 2,
            ],
            [
                'name' => 'Family Gathering Perusahaan',
                'slug' => 'family-gathering',
                'icon' => '👨‍👩‍👧‍👦',
                'description' => 'Momen keakraban dan apresiasi bagi seluruh karyawan beserta keluarga dengan hiburan ramah anak dan games ceria.',
                'color' => 'amber',
                'order' => 3,
            ],
            [
                'name' => 'Gala Dinner & Awarding Night',
                'slug' => 'gala-dinner-awarding',
                'icon' => '✨',
                'description' => 'Perayaan pencapaian tahunan dengan tata panggung, hiburan live music, dan hidangan buffet istimewa.',
                'color' => 'purple',
                'order' => 4,
            ],
            [
                'name' => 'Capacity Building & Leadership',
                'slug' => 'capacity-building-leadership',
                'icon' => '🚀',
                'description' => 'Program pelatihan kepemimpinan dan ketahanan mental tim dengan metode experiential learning terpadu.',
                'color' => 'rose',
                'order' => 5,
            ],
        ];

        foreach ($subCategories as $sub) {
            TourCategory::updateOrCreate(
                ['slug' => $sub['slug']],
                array_merge($sub, [
                    'parent_id' => $gathering->id,
                    'is_active' => true,
                ])
            );
        }

        // Parent Category: Wisata Liburan & Petualangan (for existing general tours)
        $wisata = TourCategory::updateOrCreate(
            ['slug' => 'wisata-liburan'],
            [
                'parent_id' => null,
                'name' => 'Wisata Liburan & Adventure',
                'icon' => '🏖️',
                'description' => 'Paket open trip & private trip untuk liburan keluarga, pasangan, maupun backpacker.',
                'color' => 'cyan',
                'order' => 2,
                'is_active' => true,
            ]
        );

        // Assign some existing tours to categories if empty
        $tours = Tour::whereNull('category_id')->get();
        if ($tours->isNotEmpty()) {
            $subOutbound = TourCategory::where('slug', 'outbound-team-building')->first();
            $subFamily = TourCategory::where('slug', 'family-gathering')->first();

            foreach ($tours as $index => $tour) {
                // If title contains bromo or outbound or gathering
                if ($index % 3 === 0 && $subOutbound) {
                    $tour->update(['category_id' => $subOutbound->id]);
                } elseif ($index % 3 === 1 && $subFamily) {
                    $tour->update(['category_id' => $subFamily->id]);
                } else {
                    $tour->update(['category_id' => $wisata->id]);
                }
            }
        }
    }
}
