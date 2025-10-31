<?php

namespace App\Constants;

class ApplicationStatusCategoryConstants
{
    // Category IDs
    public const DOCUMENT_SUBMISSION_ID = 1;
    public const FIRST_CHECK_ID = 2;
    public const INDUSTRY_CHECK_ID = 3;
    public const CONTROL_CHECK_ID = 4;
    public const FINAL_DECISION_ID = 5;
    public const APPROVED_ID = 6;
    public const REVOKED_ID = 7;
    public const REJECTED_ID = 8;

    // Category Values (slugs)
    public const DOCUMENT_SUBMISSION_VALUE = 'document-submission';
    public const FIRST_CHECK_VALUE = 'first-check';
    public const INDUSTRY_CHECK_VALUE = 'industry-check';
    public const CONTROL_CHECK_VALUE = 'control-check';
    public const FINAL_DECISION_VALUE = 'final-decision';
    public const APPROVED_VALUE = 'approved';
    public const REVOKED_VALUE = 'revoked';
    public const REJECTED_VALUE = 'rejected';
}
