<?php

namespace App\Constants;

class ApplicationStatusConstants
{
    // Status IDs
    public const AWAITING_DOCUMENTS_ID = 1;
    public const AWAITING_FIRST_CHECK_ID = 2;
    public const FIRST_CHECK_REVISION_ID = 3;
    public const AWAITING_INDUSTRY_CHECK_ID = 4;
    public const INDUSTRY_CHECK_REVISION_ID = 5;
    public const AWAITING_CONTROL_CHECK_ID = 6;
    public const CONTROL_CHECK_REVISION_ID = 7;
    public const AWAITING_FINAL_DECISION_ID = 8;
    public const FULLY_APPROVED_ID = 9;
    public const PARTIALLY_APPROVED_ID = 10;
    public const REVOKED_ID = 11;
    public const REJECTED_ID = 12;

    // Status Values (slugs)
    public const AWAITING_DOCUMENTS_VALUE = 'awaiting-documents';
    public const AWAITING_FIRST_CHECK_VALUE = 'awaiting-first-check';
    public const FIRST_CHECK_REVISION_VALUE = 'first-check-revision';
    public const AWAITING_INDUSTRY_CHECK_VALUE = 'awaiting-industry-check';
    public const INDUSTRY_CHECK_REVISION_VALUE = 'industry-check-revision';
    public const AWAITING_CONTROL_CHECK_VALUE = 'awaiting-control-check';
    public const CONTROL_CHECK_REVISION_VALUE = 'control-check-revision';
    public const AWAITING_FINAL_DECISION_VALUE = 'awaiting-final-decision';
    public const FULLY_APPROVED_VALUE = 'fully-approved';
    public const PARTIALLY_APPROVED_VALUE = 'partially-approved';
    public const REVOKED_VALUE = 'revoked';
    public const REJECTED_VALUE = 'rejected';
}
