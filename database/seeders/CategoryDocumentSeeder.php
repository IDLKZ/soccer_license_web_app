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
                'title_kk' => 'Құқықтық критерийлер',
                'title_en' => 'Legal criteria',
                'value' => CategoryDocumentConstants::LEGAL_DOCUMENTS_VALUE,
                'level' => 1,
                'roles' => json_encode(CategoryDocumentConstants::LEGAL_DOCUMENTS_ROLES),
            ],
            [
                'id' => CategoryDocumentConstants::FINANCIAL_DOCUMENTS_ID,
                'title_ru' => 'Финансовые критерии',
                'title_kk' => 'Қаржылық критерийлер',
                'title_en' => 'Financial criteria',
                'value' => CategoryDocumentConstants::FINANCIAL_DOCUMENTS_VALUE,
                'level' => 2,
                'roles' => json_encode(CategoryDocumentConstants::FINANCIAL_DOCUMENTS_ROLES),
            ],
            [
                'id' => CategoryDocumentConstants::SPORT_DOCUMENTS_ID,
                'title_ru' => 'Спортивные критерии',
                'title_kk' => 'Спорттық критерийлер',
                'title_en' => 'Sport criteria',
                'value' => CategoryDocumentConstants::SPORT_DOCUMENTS_VALUE,
                'level' => 3,
                'roles' => json_encode(CategoryDocumentConstants::SPORT_DOCUMENTS_ROLES),
            ],
            [
                'id' => CategoryDocumentConstants::INFRASTRUCTURE_DOCUMENTS_ID,
                'title_ru' => 'Инфраструктурные критерии',
                'title_kk' => 'Инфрақұрылым критерийлері',
                'title_en' => 'Infrastructure criteria',
                'value' => CategoryDocumentConstants::INFRASTRUCTURE_DOCUMENTS_VALUE,
                'level' => 4,
                'roles' => json_encode(CategoryDocumentConstants::INFRASTRUCTURE_DOCUMENTS_ROLES),
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
