<?php

namespace Database\Seeders;

use App\Constants\ApplicationStatusCategoryConstants;
use App\Models\ApplicationStatusCategory;
use Illuminate\Database\Seeder;

class ApplicationStatusCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'id' => ApplicationStatusCategoryConstants::DOCUMENT_SUBMISSION_ID,
                'cat_previous_id' => null,
                'cat_next_id' => ApplicationStatusCategoryConstants::FIRST_CHECK_ID,
                'title_ru' => 'Заявка создана, отправка документов на первичную проверку',
                'title_kk' => 'Өтінім жасалды, құжаттарды алғашқы тексеруге жіберу',
                'title_en' => 'Application created, document submission for initial review',
                'description_ru' => null,
                'description_kk' => null,
                'description_en' => null,
                'value' => ApplicationStatusCategoryConstants::DOCUMENT_SUBMISSION_VALUE,
                'role_values' => null,
                'is_active' => true,
                'is_first' => true,
                'is_last' => false,
                'result' => 0,
            ],
            [
                'id' => ApplicationStatusCategoryConstants::FIRST_CHECK_ID,
                'cat_previous_id' => ApplicationStatusCategoryConstants::DOCUMENT_SUBMISSION_ID,
                'cat_next_id' => ApplicationStatusCategoryConstants::INDUSTRY_CHECK_ID,
                'title_ru' => 'Первичная проверка',
                'title_kk' => 'Алғашқы тексеру',
                'title_en' => 'Initial review',
                'description_ru' => null,
                'description_kk' => null,
                'description_en' => null,
                'value' => ApplicationStatusCategoryConstants::FIRST_CHECK_VALUE,
                'role_values' => null,
                'is_active' => true,
                'is_first' => false,
                'is_last' => false,
                'result' => 0,
            ],
            [
                'id' => ApplicationStatusCategoryConstants::INDUSTRY_CHECK_ID,
                'cat_previous_id' => ApplicationStatusCategoryConstants::FIRST_CHECK_ID,
                'cat_next_id' => ApplicationStatusCategoryConstants::CONTROL_CHECK_ID,
                'title_ru' => 'Отраслевая проверка',
                'title_kk' => 'Салалық тексеру',
                'title_en' => 'Industry review',
                'description_ru' => null,
                'description_kk' => null,
                'description_en' => null,
                'value' => ApplicationStatusCategoryConstants::INDUSTRY_CHECK_VALUE,
                'role_values' => null,
                'is_active' => true,
                'is_first' => false,
                'is_last' => false,
                'result' => 0,
            ],
            [
                'id' => ApplicationStatusCategoryConstants::CONTROL_CHECK_ID,
                'cat_previous_id' => ApplicationStatusCategoryConstants::INDUSTRY_CHECK_ID,
                'cat_next_id' => ApplicationStatusCategoryConstants::FINAL_DECISION_ID,
                'title_ru' => 'Контрольная проверка',
                'title_kk' => 'Бақылау тексеруі',
                'title_en' => 'Control review',
                'description_ru' => null,
                'description_kk' => null,
                'description_en' => null,
                'value' => ApplicationStatusCategoryConstants::CONTROL_CHECK_VALUE,
                'role_values' => null,
                'is_active' => true,
                'is_first' => false,
                'is_last' => false,
                'result' => 0,
            ],
            [
                'id' => ApplicationStatusCategoryConstants::FINAL_DECISION_ID,
                'cat_previous_id' => ApplicationStatusCategoryConstants::CONTROL_CHECK_ID,
                'cat_next_id' => ApplicationStatusCategoryConstants::APPROVED_ID,
                'title_ru' => 'Принятие финального решения',
                'title_kk' => 'Түпкілікті шешім қабылдау',
                'title_en' => 'Final decision',
                'description_ru' => null,
                'description_kk' => null,
                'description_en' => null,
                'value' => ApplicationStatusCategoryConstants::FINAL_DECISION_VALUE,
                'role_values' => null,
                'is_active' => true,
                'is_first' => false,
                'is_last' => false,
                'result' => 0,
            ],
            [
                'id' => ApplicationStatusCategoryConstants::APPROVED_ID,
                'cat_previous_id' => ApplicationStatusCategoryConstants::FINAL_DECISION_ID,
                'cat_next_id' => null,
                'title_ru' => 'Лицензия одобрена',
                'title_kk' => 'Лицензия мақұлданды',
                'title_en' => 'License approved',
                'description_ru' => null,
                'description_kk' => null,
                'description_en' => null,
                'value' => ApplicationStatusCategoryConstants::APPROVED_VALUE,
                'role_values' => null,
                'is_active' => true,
                'is_first' => false,
                'is_last' => true,
                'result' => 1,
            ],
            [
                'id' => ApplicationStatusCategoryConstants::REVOKED_ID,
                'cat_previous_id' => null,
                'cat_next_id' => null,
                'title_ru' => 'Лицензия отозвана',
                'title_kk' => 'Лицензия кері қайтарылды',
                'title_en' => 'License revoked',
                'description_ru' => null,
                'description_kk' => null,
                'description_en' => null,
                'value' => ApplicationStatusCategoryConstants::REVOKED_VALUE,
                'role_values' => null,
                'is_active' => true,
                'is_first' => false,
                'is_last' => true,
                'result' => -1,
            ],
            [
                'id' => ApplicationStatusCategoryConstants::REJECTED_ID,
                'cat_previous_id' => null,
                'cat_next_id' => null,
                'title_ru' => 'Отказано',
                'title_kk' => 'Бас тартылды',
                'title_en' => 'Rejected',
                'description_ru' => null,
                'description_kk' => null,
                'description_en' => null,
                'value' => ApplicationStatusCategoryConstants::REJECTED_VALUE,
                'role_values' => null,
                'is_active' => true,
                'is_first' => false,
                'is_last' => true,
                'result' => -1,
            ],
        ];

        // First pass: create categories without references
        foreach ($categories as $category) {
            ApplicationStatusCategory::updateOrCreate(
                ['id' => $category['id']],
                array_merge($category, [
                    'cat_previous_id' => null,
                    'cat_next_id' => null,
                ])
            );
        }

        // Second pass: update references
        foreach ($categories as $category) {
            if ($category['cat_previous_id'] !== null || $category['cat_next_id'] !== null) {
                ApplicationStatusCategory::where('id', $category['id'])->update([
                    'cat_previous_id' => $category['cat_previous_id'],
                    'cat_next_id' => $category['cat_next_id'],
                ]);
            }
        }
    }
}
