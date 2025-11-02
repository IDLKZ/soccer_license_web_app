<?php

namespace App\Constants;

class LeagueConstants
{
    // League IDs
    public const PREMIER_LEAGUE_ID = 1;
    public const FIRST_LEAGUE_ID = 2;
    public const SECOND_LEAGUE_ID = 3;
    public const WOMAN_LEAGUE_ID = 4;
    public const YOUTH_LEAGUE_ID = 5;
    public const U21_LEAGUE_ID = 5;

    // League Values (slugs)
    public const PREMIER_LEAGUE_VALUE = 'premier-league';
    public const FIRST_LEAGUE_VALUE = 'first-league';
    public const SECOND_LEAGUE_VALUE = 'second-league';
    public const WOMAN_LEAGUE_VALUE = 'women-league';
    public const YOUTH_LEAGUE_VALUE = 'youth-league';
    public const U21_LEAGUE_VALUE = 'youth-league';

    /**
     * Get all leagues as array
     */
    public static function getAllLeagues(): array
    {
        return [
            [
                'id' => self::PREMIER_LEAGUE_ID,
                'value' => self::PREMIER_LEAGUE_VALUE,
                'title_ru' => 'Премьер-Лига',
                'title_kk' => 'Премьер-Лигасы',
                'title_en' => 'Premier League',
                'description_ru' => 'Высший дивизион профессионального футбола',
                'description_kk' => 'Кәсібионал футболдың жоғарғы дивизионы',
                'description_en' => 'Highest division of professional football',
            ],
            [
                'id' => self::FIRST_LEAGUE_ID,
                'value' => self::FIRST_LEAGUE_VALUE,
                'title_ru' => 'Первая лига',
                'title_kk' => 'Бірінші лига',
                'title_en' => 'First League',
                'description_ru' => 'Второй по значимости дивизион профессионального футбола',
                'description_kk' => 'Кәшілмді маңыздыдағы футбол дивизионы',
                'description_en' => 'Second most important division of professional football',
            ],
            [
                'id' => self::SECOND_LEAGUE_ID,
                'value' => self::SECOND_LEAGUE_VALUE,
                'title_ru' => 'Вторая лига',
                'title_kk' => 'Екінші лига',
                'title_en' => 'Second League',
                'description_ru' => 'Третий по значимости дивизион профессионального футбола',
                'description_kk' => 'Үшінші маңыздыдағы футбол дивизионы',
                'description_en' => 'Third most important division of professional football',
            ],
            [
                'id' => self::WOMAN_LEAGUE_ID,
                'value' => self::WOMEN_LEAGUE_VALUE,
                'title_ru' => 'Женская лига',
                'title_kk' => 'Әйелдер лигасы',
                'title_en' => 'Women League',
                'description_ru' => 'Высший дивизион женского футбола',
                'description_kk' => 'Әйелдер футболының жоғарғы дивизионы',
                'description_en' => 'Highest division of women\'s football',
            ],
            [
                'id' => self::YOUTH_LEAGUE_ID,
                'value' => self::YOUTH_LEAGUE_VALUE,
                'title_ru' => 'Молодёжная лига (U21)',
                'title_kk' => 'Жастар лигасы (U21)',
                'title_en' => 'Youth League (U21)',
                'description_ru' => 'Лига для молодых футболистов до 21 года',
                'description_kk' => '21 жасқа дейінгі жас футболшылар үшін лигасы',
                'description_en' => 'League for young football players under 21',
            ],
        ];
    }

    /**
     * Get league by ID
     */
    public static function getLeagueById(int $id): ?array
    {
        $leagues = self::getAllLeagues();
        foreach ($leagues as $league) {
            if ($league['id'] === $id) {
                return $league;
            }
        }
        return null;
    }

    /**
     * Get league by value
     */
    public static function getLeagueByValue(string $value): ?array
    {
        $leagues = self::getAllLeagues();
        foreach ($leagues as $league) {
            if ($league['value'] === $value) {
                return $league;
            }
        }
        return null;
    }

    /**
     * Get league title by language
     */
    public static function getLeagueTitle(int $id, string $language = 'ru'): ?string
    {
        $league = self::getLeagueById($id);
        if (!$league) {
            return null;
        }

        return match($language) {
            'ru' => $league['title_ru'],
            'kk' => $league['title_kk'],
            'en' => $league['title_en'],
            default => $league['title_ru']
        };
    }

    /**
     * Get league description by language
     */
    public static function getLeagueDescription(int $id, string $language = 'ru'): ?string
    {
        $league = self::getLeagueById($id);
        if (!$league) {
            return null;
        }

        return match($language) {
            'ru' => $league['description_ru'],
            'kk' => $league['description_kk'],
            'en' => $league['description_en'],
            default => $league['description_ru']
        };
    }
}
