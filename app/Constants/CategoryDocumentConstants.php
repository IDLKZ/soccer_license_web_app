<?php

namespace App\Constants;

class CategoryDocumentConstants
{
    // Category IDs
    public const LEGAL_DOCUMENTS_ID = 1;
    public const FINANCIAL_DOCUMENTS_ID = 2;
    public const SPORT_DOCUMENTS_ID = 3;
    public const INFRASTRUCTURE_DOCUMENTS_ID = 4;
    public const SOCIAL_DOCUMENTS_ID = 5;
    public const HR_DOCUMENTS_ID = 6;

    // Category Values (slugs)
    public const LEGAL_DOCUMENTS_VALUE = 'legal-documents';
    public const FINANCIAL_DOCUMENTS_VALUE = 'financial-documents';
    public const SPORT_DOCUMENTS_VALUE = 'sport-documents';
    public const INFRASTRUCTURE_DOCUMENTS_VALUE = 'infrastructure-documents';
    public const SOCIAL_DOCUMENTS_VALUE = 'social-documents';
    public const HR_DOCUMENTS_VALUE = 'hr-documents';

    // Role arrays for each category
    public const LEGAL_DOCUMENTS_ROLES = [
        RoleConstants::CLUB_ADMINISTRATOR_VALUE,
        RoleConstants::LICENSING_DEPARTMENT_VALUE,
        RoleConstants::LEGAL_DEPARTMENT_VALUE,
        RoleConstants::LEGAL_SPECIALIST_VALUE,
        RoleConstants::CONTROL_DEPARTMENT_VALUE,
    ];

    public const FINANCIAL_DOCUMENTS_ROLES = [
        RoleConstants::CLUB_ADMINISTRATOR_VALUE,
        RoleConstants::LICENSING_DEPARTMENT_VALUE,
        RoleConstants::FINANCE_DEPARTMENT_VALUE,
        RoleConstants::FINANCIAL_SPECIALIST_VALUE,
        RoleConstants::CONTROL_DEPARTMENT_VALUE,
    ];

    public const SPORT_DOCUMENTS_ROLES = [
        RoleConstants::CLUB_ADMINISTRATOR_VALUE,
        RoleConstants::LICENSING_DEPARTMENT_VALUE,
        RoleConstants::SPORTING_DIRECTOR_VALUE,
        RoleConstants::INFRASTRUCTURE_DEPARTMENT_VALUE,
        RoleConstants::CONTROL_DEPARTMENT_VALUE,
    ];

    public const INFRASTRUCTURE_DOCUMENTS_ROLES = [
        RoleConstants::CLUB_ADMINISTRATOR_VALUE,
        RoleConstants::LICENSING_DEPARTMENT_VALUE,
        RoleConstants::INFRASTRUCTURE_DEPARTMENT_VALUE,
        RoleConstants::CONTROL_DEPARTMENT_VALUE,
        RoleConstants::LEGAL_SPECIALIST_VALUE,
    ];

    public const SOCIAL_DOCUMENTS_ROLES = [
        RoleConstants::CLUB_ADMINISTRATOR_VALUE,
        RoleConstants::LICENSING_DEPARTMENT_VALUE,
        RoleConstants::CONTROL_DEPARTMENT_VALUE,
        RoleConstants::LEGAL_SPECIALIST_VALUE,
    ];

    public const HR_DOCUMENTS_ROLES = [
        RoleConstants::CLUB_ADMINISTRATOR_VALUE,
        RoleConstants::LICENSING_DEPARTMENT_VALUE,
        RoleConstants::CONTROL_DEPARTMENT_VALUE,
        RoleConstants::SPORTING_DIRECTOR_VALUE,
        RoleConstants::INFRASTRUCTURE_DEPARTMENT_VALUE,
    ];
}
