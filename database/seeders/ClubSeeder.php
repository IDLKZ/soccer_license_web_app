<?php

namespace Database\Seeders;

use App\Models\Club;
use App\Models\ClubType;
use Illuminate\Database\Seeder;

class ClubSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get main men's team type
        $mainMenType = ClubType::where('value', 'main-men')->first();

        $clubs = [
            [
                'short_name_ru' => 'Кайрат',
                'short_name_kk' => 'Қайрат',
                'short_name_en' => 'Kairat',
                'full_name_ru' => 'Футбольный клуб Кайрат',
                'full_name_kk' => 'Қайрат футбол клубы',
                'full_name_en' => 'Football Club Kairat',
                'bin' => '940140000001',
                'foundation_date' => '1954-01-01',
                'legal_address' => 'г. Алматы, ул. Абая, 44',
                'actual_address' => 'г. Алматы, ул. Абая, 44',
                'website' => 'https://fckairat.kz',
                'email' => 'info@fckairat.kz',
                'phone_number' => '+77272501234',
                'type_id' => $mainMenType?->id,
            ],
            [
                'short_name_ru' => 'Астана',
                'short_name_kk' => 'Астана',
                'short_name_en' => 'Astana',
                'full_name_ru' => 'Футбольный клуб Астана',
                'full_name_kk' => 'Астана футбол клубы',
                'full_name_en' => 'Football Club Astana',
                'bin' => '090540000002',
                'foundation_date' => '2009-01-01',
                'legal_address' => 'г. Астана, пр. Кабанбай батыра, 15',
                'actual_address' => 'г. Астана, пр. Кабанбай батыра, 15',
                'website' => 'https://fcastana.kz',
                'email' => 'info@fcastana.kz',
                'phone_number' => '+77172501234',
                'type_id' => $mainMenType?->id,
            ],
            [
                'short_name_ru' => 'Тобол',
                'short_name_kk' => 'Тобыл',
                'short_name_en' => 'Tobol',
                'full_name_ru' => 'Футбольный клуб Тобол',
                'full_name_kk' => 'Тобыл футбол клубы',
                'full_name_en' => 'Football Club Tobol',
                'bin' => '970440000003',
                'foundation_date' => '1997-01-01',
                'legal_address' => 'г. Костанай, ул. Байтурсынова, 88',
                'actual_address' => 'г. Костанай, ул. Байтурсынова, 88',
                'website' => 'https://fctobol.kz',
                'email' => 'info@fctobol.kz',
                'phone_number' => '+77142501234',
                'type_id' => $mainMenType?->id,
            ],
            [
                'short_name_ru' => 'Актобе',
                'short_name_kk' => 'Ақтөбе',
                'short_name_en' => 'Aktobe',
                'full_name_ru' => 'Футбольный клуб Актобе',
                'full_name_kk' => 'Ақтөбе футбол клубы',
                'full_name_en' => 'Football Club Aktobe',
                'bin' => '970640000004',
                'foundation_date' => '1967-01-01',
                'legal_address' => 'г. Актобе, пр. Абилкайыр хана, 67',
                'actual_address' => 'г. Актобе, пр. Абилкайыр хана, 67',
                'website' => 'https://fcaktobe.kz',
                'email' => 'info@fcaktobe.kz',
                'phone_number' => '+77132501234',
                'type_id' => $mainMenType?->id,
            ],
            [
                'short_name_ru' => 'Ордабасы',
                'short_name_kk' => 'Ордабасы',
                'short_name_en' => 'Ordabasy',
                'full_name_ru' => 'Футбольный клуб Ордабасы',
                'full_name_kk' => 'Ордабасы футбол клубы',
                'full_name_en' => 'Football Club Ordabasy',
                'bin' => '990340000005',
                'foundation_date' => '1999-01-01',
                'legal_address' => 'г. Шымкент, пр. Тауке хана, 4',
                'actual_address' => 'г. Шымкент, пр. Тауке хана, 4',
                'website' => 'https://fcordabasy.kz',
                'email' => 'info@fcordabasy.kz',
                'phone_number' => '+77252501234',
                'type_id' => $mainMenType?->id,
            ],
        ];

        foreach ($clubs as $clubData) {
            Club::updateOrCreate(
                ['bin' => $clubData['bin']],
                $clubData
            );
        }
    }
}
