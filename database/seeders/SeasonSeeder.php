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
                'end' => '2025-12-01',
                'is_active' => true,
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
