<?php

namespace Database\Seeders;

use App\Constants\CategoryDocumentConstants;
use App\Models\CategoryDocument;
use Illuminate\Database\Seeder;

class CategoryDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'id' => CategoryDocumentConstants::LEGAL_DOCUMENTS_ID,
                'title_ru' => 'Правовые критерии',
                'title_kk' => 'Құқықтық құжаттар',
                'title_en' => 'Legal criteries',
                'value' => CategoryDocumentConstants::LEGAL_DOCUMENTS_VALUE,
                'level' => 1,
                'roles' => CategoryDocumentConstants::LEGAL_DOCUMENTS_ROLES,
            ],
            [
                'id' => CategoryDocumentConstants::FINANCIAL_DOCUMENTS_ID,
                'title_ru' => 'Финансовые критерии',
                'title_kk' => 'Қаржылық құжаттар',
                'title_en' => 'Financial criteries',
                'value' => CategoryDocumentConstants::FINANCIAL_DOCUMENTS_VALUE,
                'level' => 1,
                'roles' => CategoryDocumentConstants::FINANCIAL_DOCUMENTS_ROLES,
            ],
            [
                'id' => CategoryDocumentConstants::SPORT_DOCUMENTS_ID,
                'title_ru' => 'Спортивные критерии',
                'title_kk' => 'Спорттық құжаттар',
                'title_en' => 'Sport criteries',
                'value' => CategoryDocumentConstants::SPORT_DOCUMENTS_VALUE,
                'level' => 1,
                'roles' => CategoryDocumentConstants::SPORT_DOCUMENTS_ROLES,
            ],
            [
                'id' => CategoryDocumentConstants::INFRASTRUCTURE_DOCUMENTS_ID,
                'title_ru' => 'Инфраструктурные критерии',
                'title_kk' => 'Инфрақұрылым құжаттары',
                'title_en' => 'Infrastructure criteries',
                'value' => CategoryDocumentConstants::INFRASTRUCTURE_DOCUMENTS_VALUE,
                'level' => 1,
                'roles' => CategoryDocumentConstants::INFRASTRUCTURE_DOCUMENTS_ROLES,
            ],
            [
                'id' => CategoryDocumentConstants::SOCIAL_DOCUMENTS_ID,
                'title_ru' => 'Критерии социальной и экологической ответственности',
                'title_kk' => 'Әлеуметтік және экологиялық жауапкершілік критерийлері',
                'title_en' => 'Social and environmental responsibility criteria',
                'value' => CategoryDocumentConstants::SOCIAL_DOCUMENTS_VALUE,
                'level' => 1,
                'roles' => CategoryDocumentConstants::SOCIAL_DOCUMENTS_ROLES,
            ],
            [
                'id' => CategoryDocumentConstants::HR_DOCUMENTS_ID,
                'title_ru' => 'Кадровые и административные критерии',
                'title_kk' => 'Кадрлық және әкімшілік критерийлері',
                'title_en' => 'Personnel and administrative criteria',
                'value' => CategoryDocumentConstants::HR_DOCUMENTS_VALUE,
                'level' => 1,
                'roles' => CategoryDocumentConstants::HR_DOCUMENTS_ROLES,
            ],
        ];

        foreach ($categories as $category) {
            CategoryDocument::updateOrCreate(
                ['id' => $category['id']],
                $category
            );
        }
    }
}
