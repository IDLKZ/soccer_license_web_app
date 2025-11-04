<?php

namespace Database\Seeders;

use App\Constants\ApplicationStatusCategoryConstants;
use App\Constants\ApplicationStatusConstants;
use App\Constants\RoleConstants;
use App\Models\ApplicationStatus;
use Illuminate\Database\Seeder;

class ApplicationStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = $this->getStatuses();

        // First pass: create statuses without references
        foreach ($statuses as $status) {
            ApplicationStatus::updateOrCreate(
                ['id' => $status['id']],
                array_merge($status, [
                    'previous_id' => null,
                    'next_id' => null,
                ])
            );
        }

        // Second pass: update references
        foreach ($statuses as $status) {
            if ($status['previous_id'] !== null || $status['next_id'] !== null) {
                ApplicationStatus::where('id', $status['id'])->update([
                    'previous_id' => $status['previous_id'],
                    'next_id' => $status['next_id'],
                ]);
            }
        }
    }

    private function getStatuses(): array
    {
        return [
            // 1. Ожидание загрузки пакета документов
            [
                'id' => ApplicationStatusConstants::AWAITING_DOCUMENTS_ID,
                'category_id' => ApplicationStatusCategoryConstants::DOCUMENT_SUBMISSION_ID,
                'previous_id' => null,
                'next_id' => ApplicationStatusConstants::AWAITING_FIRST_CHECK_ID,
                'title_ru' => 'Ожидание загрузки пакета документов',
                'title_kk' => 'Құжаттар пакетін жүктеуді күту',
                'title_en' => 'Awaiting document package upload',
                'description_ru' => null,
                'description_kk' => null,
                'description_en' => null,
                'value' => ApplicationStatusConstants::AWAITING_DOCUMENTS_VALUE,
                'role_values' => [
                    RoleConstants::CLUB_ADMINISTRATOR_VALUE,
                    RoleConstants::LEGAL_SPECIALIST_VALUE,
                    RoleConstants::FINANCIAL_SPECIALIST_VALUE,
                    RoleConstants::SPORTING_DIRECTOR_VALUE,
                ],
                'is_active' => true,
                'is_first' => true,
                'is_last' => false,
                'result' => 0,
            ],
            // 2. Ожидание первичной проверки
            [
                'id' => ApplicationStatusConstants::AWAITING_FIRST_CHECK_ID,
                'category_id' => ApplicationStatusCategoryConstants::FIRST_CHECK_ID,
                'previous_id' => ApplicationStatusConstants::AWAITING_DOCUMENTS_ID,
                'next_id' => ApplicationStatusConstants::AWAITING_INDUSTRY_CHECK_ID,
                'title_ru' => 'Ожидание первичной проверки',
                'title_kk' => 'Алғашқы тексеруді күту',
                'title_en' => 'Awaiting initial review',
                'description_ru' => null,
                'description_kk' => null,
                'description_en' => null,
                'value' => ApplicationStatusConstants::AWAITING_FIRST_CHECK_VALUE,
                'role_values' => [
                    RoleConstants::LICENSING_DEPARTMENT_VALUE,
                ],
                'is_active' => true,
                'is_first' => false,
                'is_last' => false,
                'result' => 0,
            ],
            // 3. Доработка первичной проверки
            [
                'id' => ApplicationStatusConstants::FIRST_CHECK_REVISION_ID,
                'category_id' => ApplicationStatusCategoryConstants::FIRST_CHECK_ID,
                'previous_id' => ApplicationStatusConstants::AWAITING_FIRST_CHECK_ID,
                'next_id' => ApplicationStatusConstants::AWAITING_FIRST_CHECK_ID,
                'title_ru' => 'Доработка первичной проверки',
                'title_kk' => 'Алғашқы тексеруді өңдеу',
                'title_en' => 'Initial review revision',
                'description_ru' => null,
                'description_kk' => null,
                'description_en' => null,
                'value' => ApplicationStatusConstants::FIRST_CHECK_REVISION_VALUE,
                'role_values' => [
                    RoleConstants::CLUB_ADMINISTRATOR_VALUE,
                    RoleConstants::LEGAL_SPECIALIST_VALUE,
                    RoleConstants::FINANCIAL_SPECIALIST_VALUE,
                    RoleConstants::SPORTING_DIRECTOR_VALUE,
                ],
                'is_active' => true,
                'is_first' => false,
                'is_last' => false,
                'result' => 0,
            ],
            // 4. Ожидание отраслевой проверки
            [
                'id' => ApplicationStatusConstants::AWAITING_INDUSTRY_CHECK_ID,
                'category_id' => ApplicationStatusCategoryConstants::INDUSTRY_CHECK_ID,
                'previous_id' => ApplicationStatusConstants::AWAITING_FIRST_CHECK_ID,
                'next_id' => ApplicationStatusConstants::AWAITING_CONTROL_CHECK_ID,
                'title_ru' => 'Ожидание отраслевой проверки',
                'title_kk' => 'Салалық тексеруді күту',
                'title_en' => 'Awaiting industry review',
                'description_ru' => null,
                'description_kk' => null,
                'description_en' => null,
                'value' => ApplicationStatusConstants::AWAITING_INDUSTRY_CHECK_VALUE,
                'role_values' => [
                    RoleConstants::LEGAL_DEPARTMENT_VALUE,
                    RoleConstants::FINANCE_DEPARTMENT_VALUE,
                    RoleConstants::INFRASTRUCTURE_DEPARTMENT_VALUE,
                ],
                'is_active' => true,
                'is_first' => false,
                'is_last' => false,
                'result' => 0,
            ],
            // 5. Доработка отраслевой проверки
            [
                'id' => ApplicationStatusConstants::INDUSTRY_CHECK_REVISION_ID,
                'category_id' => ApplicationStatusCategoryConstants::INDUSTRY_CHECK_ID,
                'previous_id' => ApplicationStatusConstants::AWAITING_INDUSTRY_CHECK_ID,
                'next_id' => ApplicationStatusConstants::AWAITING_INDUSTRY_CHECK_ID,
                'title_ru' => 'Доработка отраслевой проверки',
                'title_kk' => 'Салалық тексеруді өңдеу',
                'title_en' => 'Industry review revision',
                'description_ru' => null,
                'description_kk' => null,
                'description_en' => null,
                'value' => ApplicationStatusConstants::INDUSTRY_CHECK_REVISION_VALUE,
                'role_values' => [
                    RoleConstants::LEGAL_SPECIALIST_VALUE,
                    RoleConstants::FINANCIAL_SPECIALIST_VALUE,
                    RoleConstants::SPORTING_DIRECTOR_VALUE,
                ],
                'is_active' => true,
                'is_first' => false,
                'is_last' => false,
                'result' => 0,
            ],
            // 6. Ожидание контрольной проверки
            [
                'id' => ApplicationStatusConstants::AWAITING_CONTROL_CHECK_ID,
                'category_id' => ApplicationStatusCategoryConstants::CONTROL_CHECK_ID,
                'previous_id' => ApplicationStatusConstants::AWAITING_INDUSTRY_CHECK_ID,
                'next_id' => ApplicationStatusConstants::AWAITING_FINAL_DECISION_ID,
                'title_ru' => 'Ожидание контрольной проверки',
                'title_kk' => 'Бақылау тексеруін күту',
                'title_en' => 'Awaiting control review',
                'description_ru' => null,
                'description_kk' => null,
                'description_en' => null,
                'value' => ApplicationStatusConstants::AWAITING_CONTROL_CHECK_VALUE,
                'role_values' => [
                    RoleConstants::CONTROL_DEPARTMENT_VALUE,
                ],
                'is_active' => true,
                'is_first' => false,
                'is_last' => false,
                'result' => 0,
            ],
            // 7. Доработка контрольной проверки
            [
                'id' => ApplicationStatusConstants::CONTROL_CHECK_REVISION_ID,
                'category_id' => ApplicationStatusCategoryConstants::CONTROL_CHECK_ID,
                'previous_id' => ApplicationStatusConstants::AWAITING_CONTROL_CHECK_ID,
                'next_id' => ApplicationStatusConstants::AWAITING_CONTROL_CHECK_ID,
                'title_ru' => 'Доработка контрольной проверки',
                'title_kk' => 'Бақылау тексеруін өңдеу',
                'title_en' => 'Control review revision',
                'description_ru' => null,
                'description_kk' => null,
                'description_en' => null,
                'value' => ApplicationStatusConstants::CONTROL_CHECK_REVISION_VALUE,
                'role_values' => [
                    RoleConstants::LEGAL_SPECIALIST_VALUE,
                    RoleConstants::FINANCIAL_SPECIALIST_VALUE,
                    RoleConstants::SPORTING_DIRECTOR_VALUE,
                ],
                'is_active' => true,
                'is_first' => false,
                'is_last' => false,
                'result' => 0,
            ],
            // 8. Ожидание финального решения
            [
                'id' => ApplicationStatusConstants::AWAITING_FINAL_DECISION_ID,
                'category_id' => ApplicationStatusCategoryConstants::FINAL_DECISION_ID,
                'previous_id' => ApplicationStatusConstants::AWAITING_CONTROL_CHECK_ID,
                'next_id' => ApplicationStatusConstants::FULLY_APPROVED_ID,
                'title_ru' => 'Ожидание финального решения',
                'title_kk' => 'Түпкілікті шешімді күту',
                'title_en' => 'Awaiting final decision',
                'description_ru' => null,
                'description_kk' => null,
                'description_en' => null,
                'value' => ApplicationStatusConstants::AWAITING_FINAL_DECISION_VALUE,
                'role_values' => [
                    RoleConstants::CONTROL_DEPARTMENT_VALUE,
                ],
                'is_active' => true,
                'is_first' => false,
                'is_last' => false,
                'result' => 0,
            ],
            // 9. Полностью одобрено
            [
                'id' => ApplicationStatusConstants::FULLY_APPROVED_ID,
                'category_id' => ApplicationStatusCategoryConstants::APPROVED_ID,
                'previous_id' => ApplicationStatusConstants::AWAITING_FINAL_DECISION_ID,
                'next_id' => null,
                'title_ru' => 'Полностью одобрено',
                'title_kk' => 'Толығымен мақұлданды',
                'title_en' => 'Fully approved',
                'description_ru' => null,
                'description_kk' => null,
                'description_en' => null,
                'value' => ApplicationStatusConstants::FULLY_APPROVED_VALUE,
                'role_values' => [
                    RoleConstants::CONTROL_DEPARTMENT_VALUE,
                ],
                'is_active' => true,
                'is_first' => false,
                'is_last' => true,
                'result' => 1,
            ],
            // 10. Одобрено частично
            [
                'id' => ApplicationStatusConstants::PARTIALLY_APPROVED_ID,
                'category_id' => ApplicationStatusCategoryConstants::APPROVED_ID,
                'previous_id' => ApplicationStatusConstants::AWAITING_FINAL_DECISION_ID,
                'next_id' => null,
                'title_ru' => 'Одобрено частично, с доработкой',
                'title_kk' => 'Ішінара мақұлданды, өңдеумен',
                'title_en' => 'Partially approved with revision',
                'description_ru' => null,
                'description_kk' => null,
                'description_en' => null,
                'value' => ApplicationStatusConstants::PARTIALLY_APPROVED_VALUE,
                'role_values' => [
                    RoleConstants::CONTROL_DEPARTMENT_VALUE,
                ],
                'is_active' => true,
                'is_first' => false,
                'is_last' => true,
                'result' => 1,
            ],
            // 11. Отозванная лицензия
            [
                'id' => ApplicationStatusConstants::REVOKED_ID,
                'category_id' => ApplicationStatusCategoryConstants::REVOKED_ID,
                'previous_id' => null,
                'next_id' => null,
                'title_ru' => 'Отозванная лицензия',
                'title_kk' => 'Кері қайтарылған лицензия',
                'title_en' => 'Revoked license',
                'description_ru' => null,
                'description_kk' => null,
                'description_en' => null,
                'value' => ApplicationStatusConstants::REVOKED_VALUE,
                'role_values' => [
                    RoleConstants::CONTROL_DEPARTMENT_VALUE,
                ],
                'is_active' => true,
                'is_first' => false,
                'is_last' => true,
                'result' => -1,
            ],
            // 12. Отказано
            [
                'id' => ApplicationStatusConstants::REJECTED_ID,
                'category_id' => ApplicationStatusCategoryConstants::REJECTED_ID,
                'previous_id' => null,
                'next_id' => null,
                'title_ru' => 'Отказано',
                'title_kk' => 'Бас тартылды',
                'title_en' => 'Rejected',
                'description_ru' => null,
                'description_kk' => null,
                'description_en' => null,
                'value' => ApplicationStatusConstants::REJECTED_VALUE,
                'role_values' => [
                    RoleConstants::CONTROL_DEPARTMENT_VALUE,
                    RoleConstants::LICENSING_DEPARTMENT_VALUE,
                    RoleConstants::CLUB_ADMINISTRATOR_VALUE,
                ],
                'is_active' => true,
                'is_first' => false,
                'is_last' => true,
                'result' => -1,
            ],
        ];
    }
}
