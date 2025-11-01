<?php

namespace Database\Seeders;

use App\Models\Season;
use Illuminate\Database\Seeder;

class SeasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $seasons = [
            [
                'id' => 1,
                'title_ru' => '2025-2026',
                'title_kk' => '2025-2026',
                'title_en' => '2025-2026',
                'value' => '2025-2026',
                'start' => '2025-06-01',
                'end' => '2026-05-31',
                'is_active' => true,
            ],
            [
                'id' => 2,
                'title_ru' => '2024-2025',
                'title_kk' => '2024-2025',
                'title_en' => '2024-2025',
                'value' => '2024-2025',
                'start' => '2024-06-01',
                'end' => '2025-05-31',
                'is_active' => false,
            ],
            [
                'id' => 3,
                'title_ru' => '2023-2024',
                'title_kk' => '2023-2024',
                'title_en' => '2023-2024',
                'value' => '2023-2024',
                'start' => '2023-06-01',
                'end' => '2024-05-31',
                'is_active' => false,
            ],
            [
                'id' => 4,
                'title_ru' => '2022-2023',
                'title_kk' => '2022-2023',
                'title_en' => '2022-2023',
                'value' => '2022-2023',
                'start' => '2022-06-01',
                'end' => '2023-05-31',
                'is_active' => false,
            ],
            [
                'id' => 5,
                'title_ru' => '2021-2022',
                'title_kk' => '2021-2022',
                'title_en' => '2021-2022',
                'value' => '2021-2022',
                'start' => '2021-06-01',
                'end' => '2022-05-31',
                'is_active' => false,
            ],
        ];

        foreach ($seasons as $season) {
            Season::updateOrCreate(
                ['id' => $season['id']],
                $season
            );
        }
    }
}
