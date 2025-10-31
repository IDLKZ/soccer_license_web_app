<?php

namespace Database\Seeders;

use App\Constants\RoleConstants;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'id' => RoleConstants::ADMIN_ROLE_ID,
                'title_ru' => 'Администратор системы',
                'title_kk' => 'Жүйе әкімшісі',
                'title_en' => 'System Administrator',
                'description_ru' => 'Управление учетными записями пользователей, настройка прав доступа, контроль работы системы',
                'description_kk' => 'Пайдаланушылардың есептік жазбаларын басқару, қолжетімділікті орнату, жүйенің жұмысын бақылау',
                'description_en' => 'User account management, access rights configuration, system monitoring',
                'value' => RoleConstants::ADMIN_ROLE_VALUE,
                'is_active' => true,
                'can_register' => false,
                'is_system' => true,
                'is_administrative' => true,
            ],
            [
                'id' => RoleConstants::LICENSING_DEPARTMENT_ID,
                'title_ru' => 'Департамент лицензирования',
                'title_kk' => 'Лицензиялау бөлімі',
                'title_en' => 'Licensing Department',
                'description_ru' => 'Проверка полноты поданных документов, координация между отделами, вынесение предварительных решений',
                'description_kk' => 'Құжаттардың толықтығын тексеру, бөлімдер арасындағы үйлестіру, алдын ала шешім шығару',
                'description_en' => 'Document completeness check, inter-department coordination, preliminary decisions',
                'value' => RoleConstants::LICENSING_DEPARTMENT_VALUE,
                'is_active' => true,
                'can_register' => false,
                'is_system' => true,
                'is_administrative' => true,
            ],
            [
                'id' => RoleConstants::LEGAL_DEPARTMENT_ID,
                'title_ru' => 'Юридический департамент',
                'title_kk' => 'Заң бөлімі',
                'title_en' => 'Legal Department',
                'description_ru' => 'Проверка правовой информации клубов, соответствие уставных документов',
                'description_kk' => 'Клубтардың құқықтық ақпаратын тексеру, жарғы құжаттарының сәйкестігі',
                'description_en' => 'Legal information review, charter document compliance',
                'value' => RoleConstants::LEGAL_DEPARTMENT_VALUE,
                'is_active' => true,
                'can_register' => false,
                'is_system' => true,
                'is_administrative' => true,
            ],
            [
                'id' => RoleConstants::FINANCE_DEPARTMENT_ID,
                'title_ru' => 'Финансовый департамент',
                'title_kk' => 'Қаржы бөлімі',
                'title_en' => 'Finance Department',
                'description_ru' => 'Анализ финансовой устойчивости клубов, проверка долгов, отчетности',
                'description_kk' => 'Клубтардың қаржылық тұрақтылығын талдау, қарыздар мен есептілікті тексеру',
                'description_en' => 'Financial sustainability analysis, debt and reporting verification',
                'value' => RoleConstants::FINANCE_DEPARTMENT_VALUE,
                'is_active' => true,
                'can_register' => false,
                'is_system' => true,
                'is_administrative' => true,
            ],
            [
                'id' => RoleConstants::INFRASTRUCTURE_DEPARTMENT_ID,
                'title_ru' => 'Инфраструктурный отдел',
                'title_kk' => 'Инфрақұрылым бөлімі',
                'title_en' => 'Infrastructure Department',
                'description_ru' => 'Проверка стадионов и тренировочных баз, анализ технической документации',
                'description_kk' => 'Стадиондар мен жаттығу базаларын тексеру, техникалық құжаттарды талдау',
                'description_en' => 'Stadium and training base inspection, technical documentation analysis',
                'value' => RoleConstants::INFRASTRUCTURE_DEPARTMENT_VALUE,
                'is_active' => true,
                'can_register' => false,
                'is_system' => true,
                'is_administrative' => true,
            ],
            [
                'id' => RoleConstants::CONTROL_DEPARTMENT_ID,
                'title_ru' => 'Контрольный отдел',
                'title_kk' => 'Бақылау бөлімі',
                'title_en' => 'Control Department',
                'description_ru' => 'Мониторинг выполнения лицензионных требований клубами, выявление нарушений',
                'description_kk' => 'Клубтардың лицензиялық талаптарды орындауын бақылау, бұзушылықтарды анықтау',
                'description_en' => 'Monitoring club compliance with licensing requirements, identifying violations',
                'value' => RoleConstants::CONTROL_DEPARTMENT_VALUE,
                'is_active' => true,
                'can_register' => false,
                'is_system' => true,
                'is_administrative' => true,
            ],
            [
                'id' => RoleConstants::CLUB_ADMINISTRATOR_ID,
                'title_ru' => 'Администратор клуба',
                'title_kk' => 'Клуб әкімшісі',
                'title_en' => 'Club Administrator',
                'description_ru' => 'Управление учетными записями сотрудников клуба, подача заявки, загрузка документов, контроль статуса лицензии',
                'description_kk' => 'Клуб қызметкерлерінің есептік жазбаларын басқару, өтінім беру, құжаттарды жүктеу, лицензия мәртебесін бақылау',
                'description_en' => 'Managing club staff accounts, submitting applications, uploading documents, monitoring licence status',
                'value' => RoleConstants::CLUB_ADMINISTRATOR_VALUE,
                'is_active' => true,
                'can_register' => false,
                'is_system' => true,
                'is_administrative' => false,
            ],
            [
                'id' => RoleConstants::LEGAL_SPECIALIST_ID,
                'title_ru' => 'Юридический специалист',
                'title_kk' => 'Заңгер',
                'title_en' => 'Legal Specialist',
                'description_ru' => 'Загрузка юридических документов, ответы на запросы КФФ',
                'description_kk' => 'Құқықтық құжаттарды жүктеу, ҚФФ сұраныстарына жауап беру',
                'description_en' => 'Upload legal documents, respond to KFF requests',
                'value' => RoleConstants::LEGAL_SPECIALIST_VALUE,
                'is_active' => true,
                'can_register' => false,
                'is_system' => true,
                'is_administrative' => false,
            ],
            [
                'id' => RoleConstants::FINANCIAL_SPECIALIST_ID,
                'title_ru' => 'Финансовый специалист',
                'title_kk' => 'Қаржы маманы',
                'title_en' => 'Financial Specialist',
                'description_ru' => 'Загрузка финансовой отчетности, предоставление справок об отсутствии задолженностей',
                'description_kk' => 'Қаржылық есептілікті жүктеу, қарыздың жоқтығы туралы анықтамалар беру',
                'description_en' => 'Upload financial reports, provide debt-free certificates',
                'value' => RoleConstants::FINANCIAL_SPECIALIST_VALUE,
                'is_active' => true,
                'can_register' => false,
                'is_system' => true,
                'is_administrative' => false,
            ],
            [
                'id' => RoleConstants::SPORTING_DIRECTOR_ID,
                'title_ru' => 'Спортивный директор',
                'title_kk' => 'Спорт директоры',
                'title_en' => 'Sporting Director',
                'description_ru' => 'Загрузка информации о командах, тренерах, инфраструктуре, проверка наличия контрактов с игроками',
                'description_kk' => 'Командалар, жаттықтырушылар, инфрақұрылым туралы ақпаратты жүктеу, ойыншылармен келісімшарттардың бар-жоғын тексеру',
                'description_en' => 'Upload data on teams, coaches, infrastructure, verify player contracts',
                'value' => RoleConstants::SPORTING_DIRECTOR_VALUE,
                'is_active' => true,
                'can_register' => false,
                'is_system' => true,
                'is_administrative' => false,
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['id' => $role['id']],
                $role
            );
        }
    }
}
