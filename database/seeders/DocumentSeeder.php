<?php

namespace Database\Seeders;

use App\Constants\CategoryDocumentConstants;
use App\Models\Document;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $documents = [
            // Legal Documents (Category 1)
            [
                'id' => 1,
                'category_id' => CategoryDocumentConstants::LEGAL_DOCUMENTS_ID,
                'title_ru' => 'Статья 63. Заявление на участие в соревнованиях, проводимых КФФ',
                'title_kk' => 'Клубтың тіркеу құжаттары',
                'title_en' => 'Club registration documents',
                'description_ru' => 'Статья 63. Заявление на участие в соревнованиях, проводимых КФФ',
                'description_kk' => 'Клубтың тіркелгенін растайтын құжаттар.',
                'description_en' => 'Documents confirming the club\'s registration.',
                'value' => 'club_registration_docs',
                'level' => 1,
            ],
            [
                'id' => 2,
                'category_id' => CategoryDocumentConstants::LEGAL_DOCUMENTS_ID,
                'title_ru' => 'Статья 64. Минимальная юридическая информация',
                'title_kk' => 'Клуб жарғысы',
                'title_en' => 'Club charter',
                'description_ru' => 'Статья 64. Минимальная юридическая информация',
                'description_kk' => 'Клубтың жарғылық құжаты.',
                'description_en' => 'Primary charter document of the club.',
                'value' => 'club_charter',
                'level' => 1,
            ],
            [
                'id' => 3,
                'category_id' => CategoryDocumentConstants::LEGAL_DOCUMENTS_ID,
                'title_ru' => 'Статья 64/1. Личность, история и наследие соискателя лицензии',
                'title_kk' => 'Басқару құрылымы туралы мәлімет',
                'title_en' => 'Management structure details',
                'description_ru' => 'Статья 64/1. Личность, история и наследие соискателя лицензии',
                'description_kk' => 'Клубтың басқару құрамының ақпараты.',
                'description_en' => 'Information about the club\'s management structure.',
                'value' => 'management_structure',
                'level' => 1,
            ],
            [
                'id' => 4,
                'category_id' => CategoryDocumentConstants::LEGAL_DOCUMENTS_ID,
                'title_ru' => 'Статья 65. Структура управления и собственности группы',
                'title_kk' => 'ПФЛК және МФФ мүшелігін растау',
                'title_en' => 'Confirmation of membership in PFLK and MFF',
                'description_ru' => 'Статья 65. Структура управления и собственности группы',
                'description_kk' => 'Клубтың ПФЛК мен МФФ мүшесі екенін растайтын құжаттар.',
                'description_en' => 'Documents confirming club membership in PFLK and MFF.',
                'value' => 'membership_confirmation',
                'level' => 1,
            ],

            // Financial Documents (Category 2)
            [
                'id' => 5,
                'category_id' => CategoryDocumentConstants::FINANCIAL_DOCUMENTS_ID,
                'title_ru' => 'Годовая финансовая отчетность',
                'title_kk' => 'Жылдық қаржылық есеп',
                'title_en' => 'Annual financial report',
                'description_ru' => 'Финансовая отчетность за год.',
                'description_kk' => 'Бір жылға арналған қаржылық есеп.',
                'description_en' => 'Financial report for the year.',
                'value' => 'annual_financial_report',
                'level' => 2,
            ],
            [
                'id' => 6,
                'category_id' => CategoryDocumentConstants::FINANCIAL_DOCUMENTS_ID,
                'title_ru' => 'Справка об отсутствии задолженностей',
                'title_kk' => 'Қарыздардың жоқтығы туралы анықтама',
                'title_en' => 'No-debt certificate',
                'description_ru' => 'Справка о финансовой добросовестности клуба.',
                'description_kk' => 'Клубтың қаржылық міндеттемелері жоқ екенін растайтын құжат.',
                'description_en' => 'Certificate confirming no debts to the state, KFF or UEFA.',
                'value' => 'no_debt_certificate',
                'level' => 2,
            ],
            [
                'id' => 7,
                'category_id' => CategoryDocumentConstants::FINANCIAL_DOCUMENTS_ID,
                'title_ru' => 'Промежуточная отчетность',
                'title_kk' => 'Аралық есеп',
                'title_en' => 'Interim report',
                'description_ru' => 'Финансовый отчет за определённый период года.',
                'description_kk' => 'Жылдың белгілі бір кезеңіне арналған қаржылық есеп.',
                'description_en' => 'Interim financial report.',
                'value' => 'interim_financial_report',
                'level' => 2,
            ],

            // Sport Documents (Category 3)
            [
                'id' => 8,
                'category_id' => CategoryDocumentConstants::SPORT_DOCUMENTS_ID,
                'title_ru' => 'Списки игроков основной и молодежных команд',
                'title_kk' => 'Негізгі және жастар командаларының ойыншылар тізімі',
                'title_en' => 'List of main and youth team players',
                'description_ru' => 'Списки всех зарегистрированных игроков клуба.',
                'description_kk' => 'Клубтың тіркелген ойыншыларының толық тізімі.',
                'description_en' => 'Roster of all registered players in the club.',
                'value' => 'player_lists',
                'level' => 3,
            ],
            [
                'id' => 9,
                'category_id' => CategoryDocumentConstants::SPORT_DOCUMENTS_ID,
                'title_ru' => 'Контракты с игроками и тренерами',
                'title_kk' => 'Ойыншылар мен жаттықтырушылармен келісімшарттар',
                'title_en' => 'Contracts with players and coaches',
                'description_ru' => 'Действующие контракты с ключевым персоналом.',
                'description_kk' => 'Негізгі қызметкерлермен жасалған келісімшарттар.',
                'description_en' => 'Active contracts with players and coaching staff.',
                'value' => 'contracts_players_coaches',
                'level' => 3,
            ],
            [
                'id' => 10,
                'category_id' => CategoryDocumentConstants::SPORT_DOCUMENTS_ID,
                'title_ru' => 'Программа развития ДЮФ',
                'title_kk' => 'ЖСФ дамыту бағдарламасы',
                'title_en' => 'Youth football development program',
                'description_ru' => 'Программа развития детско-юношеского футбола.',
                'description_kk' => 'Жасөспірімдер футболын дамыту бағдарламасы.',
                'description_en' => 'Program for youth football development.',
                'value' => 'youth_football_program',
                'level' => 3,
            ],

            // Infrastructure Documents (Category 4)
            [
                'id' => 11,
                'category_id' => CategoryDocumentConstants::INFRASTRUCTURE_DOCUMENTS_ID,
                'title_ru' => 'Сертификат безопасности стадиона',
                'title_kk' => 'Стадион қауіпсіздігі сертификаты',
                'title_en' => 'Stadium safety certificate',
                'description_ru' => 'Сертификат, подтверждающий соответствие стадиона нормам безопасности.',
                'description_kk' => 'Стадионның қауіпсіздік талаптарына сәйкес келетінін растайтын құжат.',
                'description_en' => 'Certificate confirming stadium safety standards compliance.',
                'value' => 'stadium_safety_certificate',
                'level' => 4,
            ],
            [
                'id' => 12,
                'category_id' => CategoryDocumentConstants::INFRASTRUCTURE_DOCUMENTS_ID,
                'title_ru' => 'Документы о состоянии объектов',
                'title_kk' => 'Нысандардың техникалық жағдайы туралы құжаттар',
                'title_en' => 'Facility condition documents',
                'description_ru' => 'Документы о техническом состоянии спортивных объектов.',
                'description_kk' => 'Спорт нысандарының техникалық жағдайы туралы құжаттар.',
                'description_en' => 'Documents on the technical condition of sports facilities.',
                'value' => 'facility_condition_docs',
                'level' => 4,
            ],
        ];

        foreach ($documents as $document) {
            Document::updateOrCreate(
                ['id' => $document['id']],
                $document
            );
        }
    }
}
