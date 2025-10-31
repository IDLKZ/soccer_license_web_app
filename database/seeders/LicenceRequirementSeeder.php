<?php

namespace Database\Seeders;

use App\Constants\CategoryDocumentConstants;
use App\Constants\FileExtensionConstants;
use App\Models\LicenceRequirement;
use Illuminate\Database\Seeder;

class LicenceRequirementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $requirements = [
            // Licence 1 - Legal Documents (Category 1, Documents 1-4)
            [
                'licence_id' => 1,
                'category_id' => CategoryDocumentConstants::LEGAL_DOCUMENTS_ID,
                'document_id' => 1,
                'is_required' => true,
                'allowed_extensions' => json_encode(FileExtensionConstants::DOCUMENT_EXTENSIONS),
                'max_file_size_mb' => 10,
            ],
            [
                'licence_id' => 1,
                'category_id' => CategoryDocumentConstants::LEGAL_DOCUMENTS_ID,
                'document_id' => 2,
                'is_required' => true,
                'allowed_extensions' => json_encode(FileExtensionConstants::DOCUMENT_EXTENSIONS),
                'max_file_size_mb' => 10,
            ],
            [
                'licence_id' => 1,
                'category_id' => CategoryDocumentConstants::LEGAL_DOCUMENTS_ID,
                'document_id' => 3,
                'is_required' => true,
                'allowed_extensions' => json_encode(FileExtensionConstants::DOCUMENT_EXTENSIONS),
                'max_file_size_mb' => 10,
            ],
            [
                'licence_id' => 1,
                'category_id' => CategoryDocumentConstants::LEGAL_DOCUMENTS_ID,
                'document_id' => 4,
                'is_required' => true,
                'allowed_extensions' => json_encode(FileExtensionConstants::DOCUMENT_EXTENSIONS),
                'max_file_size_mb' => 10,
            ],

            // Licence 1 - Financial Documents (Category 2, Documents 5-7)
            [
                'licence_id' => 1,
                'category_id' => CategoryDocumentConstants::FINANCIAL_DOCUMENTS_ID,
                'document_id' => 5,
                'is_required' => true,
                'allowed_extensions' => json_encode(FileExtensionConstants::DOCUMENT_EXTENSIONS),
                'max_file_size_mb' => 10,
            ],
            [
                'licence_id' => 1,
                'category_id' => CategoryDocumentConstants::FINANCIAL_DOCUMENTS_ID,
                'document_id' => 6,
                'is_required' => true,
                'allowed_extensions' => json_encode(FileExtensionConstants::DOCUMENT_EXTENSIONS),
                'max_file_size_mb' => 10,
            ],
            [
                'licence_id' => 1,
                'category_id' => CategoryDocumentConstants::FINANCIAL_DOCUMENTS_ID,
                'document_id' => 7,
                'is_required' => true,
                'allowed_extensions' => json_encode(FileExtensionConstants::DOCUMENT_EXTENSIONS),
                'max_file_size_mb' => 10,
            ],

            // Licence 1 - Sport Documents (Category 3, Documents 8-10)
            [
                'licence_id' => 1,
                'category_id' => CategoryDocumentConstants::SPORT_DOCUMENTS_ID,
                'document_id' => 8,
                'is_required' => true,
                'allowed_extensions' => json_encode(FileExtensionConstants::DOCUMENT_EXTENSIONS),
                'max_file_size_mb' => 10,
            ],
            [
                'licence_id' => 1,
                'category_id' => CategoryDocumentConstants::SPORT_DOCUMENTS_ID,
                'document_id' => 9,
                'is_required' => true,
                'allowed_extensions' => json_encode(FileExtensionConstants::DOCUMENT_EXTENSIONS),
                'max_file_size_mb' => 10,
            ],
            [
                'licence_id' => 1,
                'category_id' => CategoryDocumentConstants::SPORT_DOCUMENTS_ID,
                'document_id' => 10,
                'is_required' => true,
                'allowed_extensions' => json_encode(FileExtensionConstants::DOCUMENT_EXTENSIONS),
                'max_file_size_mb' => 10,
            ],

            // Licence 1 - Infrastructure Documents (Category 4, Documents 11-12)
            [
                'licence_id' => 1,
                'category_id' => CategoryDocumentConstants::INFRASTRUCTURE_DOCUMENTS_ID,
                'document_id' => 11,
                'is_required' => true,
                'allowed_extensions' => json_encode(FileExtensionConstants::DOCUMENT_EXTENSIONS),
                'max_file_size_mb' => 10,
            ],
            [
                'licence_id' => 1,
                'category_id' => CategoryDocumentConstants::INFRASTRUCTURE_DOCUMENTS_ID,
                'document_id' => 12,
                'is_required' => true,
                'allowed_extensions' => json_encode(FileExtensionConstants::DOCUMENT_EXTENSIONS),
                'max_file_size_mb' => 10,
            ],

            // Licence 2 - Legal Documents (Category 1, Documents 1-4)
            [
                'licence_id' => 2,
                'category_id' => CategoryDocumentConstants::LEGAL_DOCUMENTS_ID,
                'document_id' => 1,
                'is_required' => true,
                'allowed_extensions' => json_encode(FileExtensionConstants::DOCUMENT_EXTENSIONS),
                'max_file_size_mb' => 10,
            ],
            [
                'licence_id' => 2,
                'category_id' => CategoryDocumentConstants::LEGAL_DOCUMENTS_ID,
                'document_id' => 2,
                'is_required' => true,
                'allowed_extensions' => json_encode(FileExtensionConstants::DOCUMENT_EXTENSIONS),
                'max_file_size_mb' => 10,
            ],
            [
                'licence_id' => 2,
                'category_id' => CategoryDocumentConstants::LEGAL_DOCUMENTS_ID,
                'document_id' => 3,
                'is_required' => true,
                'allowed_extensions' => json_encode(FileExtensionConstants::DOCUMENT_EXTENSIONS),
                'max_file_size_mb' => 10,
            ],
            [
                'licence_id' => 2,
                'category_id' => CategoryDocumentConstants::LEGAL_DOCUMENTS_ID,
                'document_id' => 4,
                'is_required' => true,
                'allowed_extensions' => json_encode(FileExtensionConstants::DOCUMENT_EXTENSIONS),
                'max_file_size_mb' => 10,
            ],

            // Licence 2 - Financial Documents (Category 2, Documents 5-7)
            [
                'licence_id' => 2,
                'category_id' => CategoryDocumentConstants::FINANCIAL_DOCUMENTS_ID,
                'document_id' => 5,
                'is_required' => true,
                'allowed_extensions' => json_encode(FileExtensionConstants::DOCUMENT_EXTENSIONS),
                'max_file_size_mb' => 10,
            ],
            [
                'licence_id' => 2,
                'category_id' => CategoryDocumentConstants::FINANCIAL_DOCUMENTS_ID,
                'document_id' => 6,
                'is_required' => true,
                'allowed_extensions' => json_encode(FileExtensionConstants::DOCUMENT_EXTENSIONS),
                'max_file_size_mb' => 10,
            ],
            [
                'licence_id' => 2,
                'category_id' => CategoryDocumentConstants::FINANCIAL_DOCUMENTS_ID,
                'document_id' => 7,
                'is_required' => true,
                'allowed_extensions' => json_encode(FileExtensionConstants::DOCUMENT_EXTENSIONS),
                'max_file_size_mb' => 10,
            ],

            // Licence 2 - Sport Documents (Category 3, Documents 8-10)
            [
                'licence_id' => 2,
                'category_id' => CategoryDocumentConstants::SPORT_DOCUMENTS_ID,
                'document_id' => 8,
                'is_required' => true,
                'allowed_extensions' => json_encode(FileExtensionConstants::DOCUMENT_EXTENSIONS),
                'max_file_size_mb' => 10,
            ],
            [
                'licence_id' => 2,
                'category_id' => CategoryDocumentConstants::SPORT_DOCUMENTS_ID,
                'document_id' => 9,
                'is_required' => true,
                'allowed_extensions' => json_encode(FileExtensionConstants::DOCUMENT_EXTENSIONS),
                'max_file_size_mb' => 10,
            ],
            [
                'licence_id' => 2,
                'category_id' => CategoryDocumentConstants::SPORT_DOCUMENTS_ID,
                'document_id' => 10,
                'is_required' => true,
                'allowed_extensions' => json_encode(FileExtensionConstants::DOCUMENT_EXTENSIONS),
                'max_file_size_mb' => 10,
            ],

            // Licence 2 - Infrastructure Documents (Category 4, Documents 11-12)
            [
                'licence_id' => 2,
                'category_id' => CategoryDocumentConstants::INFRASTRUCTURE_DOCUMENTS_ID,
                'document_id' => 11,
                'is_required' => true,
                'allowed_extensions' => json_encode(FileExtensionConstants::DOCUMENT_EXTENSIONS),
                'max_file_size_mb' => 10,
            ],
            [
                'licence_id' => 2,
                'category_id' => CategoryDocumentConstants::INFRASTRUCTURE_DOCUMENTS_ID,
                'document_id' => 12,
                'is_required' => true,
                'allowed_extensions' => json_encode(FileExtensionConstants::DOCUMENT_EXTENSIONS),
                'max_file_size_mb' => 10,
            ],
        ];

        foreach ($requirements as $requirement) {
            LicenceRequirement::updateOrCreate(
                [
                    'licence_id' => $requirement['licence_id'],
                    'category_id' => $requirement['category_id'],
                    'document_id' => $requirement['document_id'],
                ],
                $requirement
            );
        }
    }
}
