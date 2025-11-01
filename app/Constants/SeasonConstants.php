<?php

namespace App\Constants;

class SeasonConstants
{
    // Season IDs
    public const SEASON_2025_2026_ID = 1;
    public const SEASON_2024_2025_ID = 2;
    public const SEASON_2023_2024_ID = 3;
    public const SEASON_2022_2023_ID = 4;
    public const SEASON_2021_2022_ID = 5;

    // Season Values
    public const SEASON_2025_2026_VALUE = '2025-2026';
    public const SEASON_2024_2025_VALUE = '2024-2025';
    public const SEASON_2023_2024_VALUE = '2023-2024';
    public const SEASON_2022_2023_VALUE = '2022-2023';
    public const SEASON_2021_2022_VALUE = '2021-2022';

    // Season Names (Russian)
    public const SEASON_2025_2026_NAME_RU = '2025-2026';
    public const SEASON_2024_2025_NAME_RU = '2024-2025';
    public const SEASON_2023_2024_NAME_RU = '2023-2024';
    public const SEASON_2022_2023_NAME_RU = '2022-2023';
    public const SEASON_2021_2022_NAME_RU = '2021-2022';

    // Season Names (Kazakh)
    public const SEASON_2025_2026_NAME_KK = '2025-2026';
    public const SEASON_2024_2025_NAME_KK = '2024-2025';
    public const SEASON_2023_2024_NAME_KK = '2023-2024';
    public const SEASON_2022_2023_NAME_KK = '2022-2023';
    public const SEASON_2021_2022_NAME_KK = '2021-2022';

    // Season Names (English)
    public const SEASON_2025_2026_NAME_EN = '2025-2026';
    public const SEASON_2024_2025_NAME_EN = '2024-2025';
    public const SEASON_2023_2024_NAME_EN = '2023-2024';
    public const SEASON_2022_2023_NAME_EN = '2022-2023';
    public const SEASON_2021_2022_NAME_EN = '2021-2022';

    // All Seasons Array
    public static function getAllSeasons(): array
    {
        return [
            self::SEASON_2025_2026_ID => [
                'id' => self::SEASON_2025_2026_ID,
                'value' => self::SEASON_2025_2026_VALUE,
                'title_ru' => self::SEASON_2025_2026_NAME_RU,
                'title_kk' => self::SEASON_2025_2026_NAME_KK,
                'title_en' => self::SEASON_2025_2026_NAME_EN,
            ],
            self::SEASON_2024_2025_ID => [
                'id' => self::SEASON_2024_2025_ID,
                'value' => self::SEASON_2024_2025_VALUE,
                'title_ru' => self::SEASON_2024_2025_NAME_RU,
                'title_kk' => self::SEASON_2024_2025_NAME_KK,
                'title_en' => self::SEASON_2024_2025_NAME_EN,
            ],
            self::SEASON_2023_2024_ID => [
                'id' => self::SEASON_2023_2024_ID,
                'value' => self::SEASON_2023_2024_VALUE,
                'title_ru' => self::SEASON_2023_2024_NAME_RU,
                'title_kk' => self::SEASON_2023_2024_NAME_KK,
                'title_en' => self::SEASON_2023_2024_NAME_EN,
            ],
            self::SEASON_2022_2023_ID => [
                'id' => self::SEASON_2022_2023_ID,
                'value' => self::SEASON_2022_2023_VALUE,
                'title_ru' => self::SEASON_2022_2023_NAME_RU,
                'title_kk' => self::SEASON_2022_2023_NAME_KK,
                'title_en' => self::SEASON_2022_2023_NAME_EN,
            ],
            self::SEASON_2021_2022_ID => [
                'id' => self::SEASON_2021_2022_ID,
                'value' => self::SEASON_2021_2022_VALUE,
                'title_ru' => self::SEASON_2021_2022_NAME_RU,
                'title_kk' => self::SEASON_2021_2022_NAME_KK,
                'title_en' => self::SEASON_2021_2022_NAME_EN,
            ],
        ];
    }

    // Get season name by ID
    public static function getSeasonName(int $seasonId, string $language = 'ru'): ?string
    {
        $seasons = self::getAllSeasons();
        $season = $seasons[$seasonId] ?? null;

        if (!$season) {
            return null;
        }

        return match($language) {
            'kk' => $season['title_kk'],
            'en' => $season['title_en'],
            default => $season['title_ru'],
        };
    }

    // Get season value by ID
    public static function getSeasonValue(int $seasonId): ?string
    {
        $seasons = self::getAllSeasons();
        return $seasons[$seasonId]['value'] ?? null;
    }

    // Get season ID by value
    public static function getSeasonId(string $seasonValue): ?int
    {
        $seasons = self::getAllSeasons();

        foreach ($seasons as $id => $season) {
            if ($season['value'] === $seasonValue) {
                return $id;
            }
        }

        return null;
    }
}