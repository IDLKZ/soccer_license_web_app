<?php

namespace App\Constants;

class ClubTypeConstants
{
    // Club Type IDs
    public const MAIN_MEN_ID = 1;
    public const MAIN_WOMEN_ID = 2;
    public const U21_MEN_ID = 3;
    public const U19_MEN_ID = 4;
    public const U19_WOMEN_ID = 5;
    public const U17_MEN_ID = 6;
    public const U16_MEN_ID = 7;
    public const U15_MEN_ID = 8;

    // Club Type Values (slugs)
    public const MAIN_MEN = 'main-men';
    public const MAIN_WOMEN = 'main-women';
    public const U21_MEN = 'u21-men';
    public const U19_MEN = 'u19-men';
    public const U19_WOMEN = 'u19-women';
    public const U17_MEN = 'u17-men';
    public const U16_MEN = 'u16-men';
    public const U15_MEN = 'u15-men';

    /**
     * Get all club types as array
     */
    public static function getAllClubTypes(): array
    {
        return [
            [
                'id' => self::MAIN_MEN_ID,
                'value' => self::MAIN_MEN,
                'title_ru' => 'Основная мужская команда',
                'title_kk' => 'Негізгі ерлер командасы',
                'title_en' => 'Main men team',
                'description_ru' => 'Основная мужская футбольная команда клуба',
                'description_kk' => 'Клубтың негізгі ерлер футбол командасы',
                'description_en' => 'Main men football team of the club',
            ],
            [
                'id' => self::MAIN_WOMEN_ID,
                'value' => self::MAIN_WOMEN,
                'title_ru' => 'Основная женская команда',
                'title_kk' => 'Негізгі әйелдер командасы',
                'title_en' => 'Main women team',
                'description_ru' => 'Основная женская футбольная команда клуба',
                'description_kk' => 'Клубтың негізгі әйелдер футбол командасы',
                'description_en' => 'Main women football team of the club',
            ],
            [
                'id' => self::U21_MEN_ID,
                'value' => self::U21_MEN,
                'title_ru' => 'Молодёжная команда (U21)',
                'title_kk' => 'Жастар командасы (U21)',
                'title_en' => 'Youth team (U21)',
                'description_ru' => 'Мужская команда игроков до 21 года',
                'description_kk' => '21 жасқа дейінгі ерлер ойыншылар командасы',
                'description_en' => 'Men team of players under 21 years old',
            ],
            [
                'id' => self::U19_MEN_ID,
                'value' => self::U19_MEN,
                'title_ru' => 'Юношеская команда (U19)',
                'title_kk' => 'Жасөспірімдер командасы (U19)',
                'title_en' => 'Junior team (U19)',
                'description_ru' => 'Мужская команда игроков до 19 лет',
                'description_kk' => '19 жасқа дейінгі ерлер ойыншылар командасы',
                'description_en' => 'Men team of players under 19 years old',
            ],
            [
                'id' => self::U19_WOMEN_ID,
                'value' => self::U19_WOMEN,
                'title_ru' => 'Юношеская женская команда (U19)',
                'title_kk' => 'Жасөспірімдер әйелдер командасы (U19)',
                'title_en' => 'Junior women team (U19)',
                'description_ru' => 'Женская команда игроков до 19 лет',
                'description_kk' => '19 жасқа дейінгі әйелдер ойыншылар командасы',
                'description_en' => 'Women team of players under 19 years old',
            ],
            [
                'id' => self::U17_MEN_ID,
                'value' => self::U17_MEN,
                'title_ru' => 'Юниорская команда (U17)',
                'title_kk' => 'Юниорлар командасы (U17)',
                'title_en' => 'Cadet team (U17)',
                'description_ru' => 'Мужская команда игроков до 17 лет',
                'description_kk' => '17 жасқа дейінгі ерлер ойыншылар командасы',
                'description_en' => 'Men team of players under 17 years old',
            ],
            [
                'id' => self::U16_MEN_ID,
                'value' => self::U16_MEN,
                'title_ru' => 'Кадетская команда (U16)',
                'title_kk' => 'Кадеттер командасы (U16)',
                'title_en' => 'Schoolboy team (U16)',
                'description_ru' => 'Мужская команда игроков до 16 лет',
                'description_kk' => '16 жасқа дейінгі ерлер ойыншылар командасы',
                'description_en' => 'Men team of players under 16 years old',
            ],
            [
                'id' => self::U15_MEN_ID,
                'value' => self::U15_MEN,
                'title_ru' => 'Детская команда (U15)',
                'title_kk' => 'Балалар командасы (U15)',
                'title_en' => 'Children team (U15)',
                'description_ru' => 'Мужская команда игроков до 15 лет',
                'description_kk' => '15 жасқа дейінгі ерлер ойыншылар командасы',
                'description_en' => 'Men team of players under 15 years old',
            ],
        ];
    }

    /**
     * Get club type by ID
     */
    public static function getClubTypeById(int $id): ?array
    {
        $types = self::getAllClubTypes();
        foreach ($types as $type) {
            if ($type['id'] === $id) {
                return $type;
            }
        }
        return null;
    }

    /**
     * Get club type by value
     */
    public static function getClubTypeByValue(string $value): ?array
    {
        $types = self::getAllClubTypes();
        foreach ($types as $type) {
            if ($type['value'] === $value) {
                return $type;
            }
        }
        return null;
    }

    /**
     * Get club type title by language
     */
    public static function getClubTypeTitle(int $id, string $language = 'ru'): ?string
    {
        $type = self::getClubTypeById($id);
        if (!$type) {
            return null;
        }

        return match($language) {
            'ru' => $type['title_ru'],
            'kk' => $type['title_kk'],
            'en' => $type['title_en'],
            default => $type['title_ru']
        };
    }
}
