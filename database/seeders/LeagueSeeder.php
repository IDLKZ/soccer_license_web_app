<?php

namespace Database\Seeders;

use App\Constants\LeagueConstants;
use App\Models\League;
use Illuminate\Database\Seeder;

class LeagueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $leagues = [
            [
                'id' => LeagueConstants::PREMIER_LEAGUE_ID,
                'image_url' => null,
                'title_ru' => 'Премьер-Лига',
                'title_kk' => 'Премьер-Лига',
                'title_en' => 'Premier League',
                'description_ru' => 'Высшая профессиональная футбольная лига Казахстана среди мужских клубов.',
                'description_kk' => 'Қазақстандағы кәсіби футбол клубтарының жоғарғы лигасы.',
                'description_en' => 'Top professional football league in Kazakhstan for men\'s clubs.',
                'value' => LeagueConstants::PREMIER_LEAGUE_VALUE,
                'is_active' => true,
                'level' => 1,
            ],
            [
                'id' => LeagueConstants::FIRST_LEAGUE_ID,
                'image_url' => null,
                'title_ru' => 'Первая лига',
                'title_kk' => 'Бірінші лига',
                'title_en' => 'First League',
                'description_ru' => 'Второй уровень профессионального футбола, подготовка клубов к участию в Премьер-Лиге.',
                'description_kk' => 'Премьер-лигаға қатысуға дайындық ретінде кәсіби футболдың екінші деңгейі.',
                'description_en' => 'Second level of professional football, preparation for Premier League.',
                'value' => LeagueConstants::FIRST_LEAGUE_VALUE,
                'is_active' => true,
                'level' => 2,
            ],
            [
                'id' => LeagueConstants::SECOND_LEAGUE_ID,
                'image_url' => null,
                'title_ru' => 'Вторая лига',
                'title_kk' => 'Екінші лига',
                'title_en' => 'Second League',
                'description_ru' => 'Третий профессиональный дивизион',
                'description_kk' => 'Футболдың үшінші деңгейі.',
                'description_en' => 'Third professional division',
                'value' => LeagueConstants::SECOND_LEAGUE_VALUE,
                'is_active' => true,
                'level' => 3,
            ],
            [
                'id' => LeagueConstants::WOMAN_LEAGUE_ID,
                'image_url' => null,
                'title_ru' => 'Женская лига',
                'title_kk' => 'Әйелдер лигасы',
                'title_en' => 'Women\'s League',
                'description_ru' => 'Высшая лига среди женских футбольных клубов Казахстана.',
                'description_kk' => 'Қазақстандағы әйелдер футбол клубтары арасындағы жоғарғы лига.',
                'description_en' => 'Top league for women\'s football clubs in Kazakhstan.',
                'value' => LeagueConstants::WOMAN_LEAGUE_VALUE,
                'is_active' => true,
                'level' => 4,
            ],
            [
                'id' => LeagueConstants::U21_LEAGUE_ID,
                'image_url' => null,
                'title_ru' => 'Молодёжная лига (U21)',
                'title_kk' => 'Жастар лигасы (U21)',
                'title_en' => 'Youth League (U21)',
                'description_ru' => 'Лига для молодых игроков до 21 года, при клубах Премьер-Лиги.',
                'description_kk' => 'Премьер-лига клубтары жанындағы 21 жасқа дейінгі жас ойыншылар лигасы.',
                'description_en' => 'League for U21 players attached to Premier League clubs.',
                'value' => LeagueConstants::U21_LEAGUE_VALUE,
                'is_active' => true,
                'level' => 5,
            ],
        ];

        foreach ($leagues as $league) {
            League::updateOrCreate(
                ['id' => $league['id']],
                $league
            );
        }
    }
}
