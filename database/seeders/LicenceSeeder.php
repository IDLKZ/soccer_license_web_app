<?php

namespace Database\Seeders;

use App\Constants\LeagueConstants;
use App\Models\Licence;
use Illuminate\Database\Seeder;

class LicenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $licences = [
            [
                'id' => 1,
                'season_id' => 1,
                'league_id' => LeagueConstants::PREMIER_LEAGUE_ID,
                'title_ru' => 'Лицензия 1',
                'title_kk' => 'Лицензия 1',
                'title_en' => 'Licence 1',
                'description_ru' => 'Описание лицензии 1',
                'description_kk' => 'Описание лицензии 1',
                'description_en' => 'Licence description 1',
                'start_at' => '2025-06-01',
                'end_at' => '2025-12-31',
                'is_active' => true,
            ],
            [
                'id' => 2,
                'season_id' => 1,
                'league_id' => LeagueConstants::PREMIER_LEAGUE_ID,
                'title_ru' => 'Лицензия 2',
                'title_kk' => 'Лицензия 2',
                'title_en' => 'Licence 2',
                'description_ru' => 'Описание лицензии 2',
                'description_kk' => 'Описание лицензии 2',
                'description_en' => 'Licence description 2',
                'start_at' => '2025-06-01',
                'end_at' => '2025-12-31',
                'is_active' => true,
            ],
        ];

        foreach ($licences as $licence) {
            Licence::updateOrCreate(
                ['id' => $licence['id']],
                $licence
            );
        }
    }
}
