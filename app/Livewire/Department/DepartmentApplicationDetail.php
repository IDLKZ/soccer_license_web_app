<?php

namespace App\Livewire\Department;

use App\Constants\ApplicationStatusCategoryConstants;
use App\Constants\ApplicationStatusConstants;
use App\Models\Application;
use App\Models\ApplicationCriteriaDeadline;
use App\Models\ApplicationCriterion;
use App\Models\ApplicationDocument;
use App\Models\ApplicationInitialReport;
use App\Models\ApplicationReport;
use App\Models\ApplicationSolution;
use App\Models\ApplicationStatus;
use App\Models\ApplicationStep;
use App\Models\CategoryDocument;
use App\Models\LicenceRequirement;
use App\Models\LicenseCertificate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Attributes\Locked;
use Livewire\Component;

class DepartmentApplicationDetail extends Component
{
    public $applicationId;

    public $application;

    public $licence;

    public $club;

    public $user;

    // Tab management
    public $activeTab = null;

    public $criteriaTabs = [];

    public $licenceRequirementsByCategory = [];

    public $uploadedDocumentsByCategory = [];

    // Reports by criteria
    public $reportsByCriteria = []; // ['criteria_id' => ['reports' => [], 'count' => 0]]

    public $initialReportsByCriteria = []; // ['criteria_id' => [reports]]

    public $departmentReports = []; // General department reports (criteria_id = null)

    public $solutions = []; // Commission solutions from application_solutions table

    public $licenseCertificates = []; // License certificates for approved applications

    public $downloadingReports = []; // Track which reports are being downloaded

    // Permissions
    #[Locked]
    public $canView = false;

    #[Locked]
    public $canReview = false;

    // Temporary review decisions (before submitting)
    public $reviewDecisions = []; // ['document_id' => ['decision' => true/false, 'comment' => '...']]

    // Criterion comment
    public $criterionComment = '';

    // Accept/Reject modals
    public $showAcceptModal = false;

    public $showRejectModal = false;

    public $currentDocumentId = null;

    public $currentDocumentTitle = '';

    public $rejectComment = '';

    // Final decision
    public $showFinalDecisionModal = false;

    public $finalDecision = ''; // fully-approved, partially-approved, revoked (removed - now per criterion)

    public $finalComment = '';

    public $finalCommentsByCriterion = []; // ['criterion_id' => 'comment']

    public $finalDecisionsByCriterion = []; // ['criterion_id' => 'fully-approved|partially-approved|revoked']

    public $reuploadDocumentIdsByCriterion = []; // ['criterion_id' => [doc_ids]]

    public $availableDocumentsForReupload = [];

    public $allCriteriaForFinalDecision = [];

    // Application level final decision (2.4.3)
    public $applicationFinalDecision = ''; // approved, partially-approved, revoked

    public $applicationReuploadDocIds = []; // For partially-approved application

    // Document info modal
    public $showDocumentInfoModal = false;

    public $viewingDocument = null;

    // Reject application modal
    public $showRejectApplicationModal = false;

    public $rejectApplicationComment = '';

    // Revision deadline modal
    public $showRevisionDeadlineModal = false;

    public $revisionCriterionId = null;

    public $revisionType = null; // 'first', 'industry', 'control'

    public $deadlineStartAt = null;

    public $deadlineEndAt = null;

    // Change to partially approved modal
    public $showChangeToPartialModal = false;

    public $changeCriterionId = null;

    public $changeComment = '';

    public $changeReuploadDocumentIds = [];

    // Generate report modal
    public $showGenerateReportModal = false;

    public $reportCriterionId = null;

    public $selectedDocumentIds = [];

    public $availableDocumentsForReport = [];

    // Edit Solution Modal
    public $showEditSolutionModal = false;

    public $editingSolutionId = null;

    public $secretaryName = '';

    public $secretaryPosition = '';

    public $directorPosition = '';

    public $directorName = '';

    public $solutionType = '';

    public $meetingDate = '';

    public $meetingPlace = '';

    public $departmentName = '';

    public $listCriteria = []; // Array of criteria items

    public $availableCriteriaForSolution = []; // Available criteria with last_comment != null

    public $newCriteriaTitle = '';

    public $newCriteriaType = '';

    public $newCriteriaDeadline = '';

    // Edit Certificate Modal
    public $showEditCertificateModal = false;

    public $editingCertificateId = null;

    public $certificateTypeRu = '';

    public $certificateTypeKk = '';

    public function mount($application_id)
    {
        $this->applicationId = $application_id;
        $this->loadApplication();

        if (! $this->application) {
            abort(404);
        }
        $this->loadTabsAndRequirements();
    }

    private function loadApplication()
    {
        try {
            $this->application = Application::find($this->applicationId);

            if (! $this->application) {
                return;
            }

            // Load relations separately to avoid issues
            $this->application->load([
                'application_status_category',
                'licence.season',
                'licence.league',
                'club',
                'user.role',
                'application_criteria.category_document',
                'application_criteria.application_status',
                'application_criteria.application_criteria_deadlines.application_status',
                'documents',
            ]);

            $this->licence = $this->application->licence;
            $this->club = $this->application->club;
            $this->user = $this->application->user;
        } catch (\Exception $e) {
            Log::error('Error loading application: '.$e->getMessage());
            $this->application = null;
        }
    }

    private function loadTabsAndRequirements()
    {
        if (! $this->application) {
            return;
        }

        $user = Auth::user();
        $userRole = $user->role ? $user->role->value : null;

        // Get criteria that user can see based on roles (3.1 requirement)
        $this->criteriaTabs = $this->application->application_criteria
            ->filter(function ($criterion) use ($userRole) {
                if (! $criterion->category_document) {
                    return false;
                }
                $category = $criterion->category_document;
                $categoryRoles = $category->roles ?? [];

                // Ensure categoryRoles is always an array
                if (is_string($categoryRoles)) {
                    $categoryRoles = json_decode($categoryRoles, true) ?? [];
                } elseif (! is_array($categoryRoles)) {
                    $categoryRoles = [];
                }

                // Department users must have their role in the allowed roles
                return ! empty($categoryRoles) && $userRole && in_array($userRole, $categoryRoles);
            })
            ->groupBy('category_id')
            ->map(function ($criteria, $categoryId) {
                $category = CategoryDocument::find($categoryId);
                $firstCriterion = $criteria->first();

                return [
                    'category' => $category,
                    'criteria' => $criteria,
                    'title' => $category->title_ru ?? 'Категория',
                    'status' => $firstCriterion ? $firstCriterion->application_status : null,
                ];
            })
            ->values()
            ->toArray();

        // Set first tab as active if exists
        if (! empty($this->criteriaTabs)) {
            $firstTab = reset($this->criteriaTabs);
            $this->activeTab = $firstTab['category']->id;
            $this->loadLicenceRequirements();

            // Load reports for all criteria
            $this->loadReportsForAllCriteria();

            // Load initial reports for all criteria
            $this->loadInitialReportsForAllCriteria();

            // Load general department reports
            $this->loadDepartmentReports();

            // Load commission solutions
            $this->loadSolutions();

            // Load license certificates (only for approved applications)
            $this->loadLicenseCertificates();
        }
    }

    private function loadReportsForAllCriteria()
    {
        if (! $this->application) {
            return;
        }

        // Get all criteria IDs from tabs
        $criteriaIds = [];
        foreach ($this->criteriaTabs as $tab) {
            foreach ($tab['criteria'] as $criterion) {
                $criteriaIds[] = $criterion->id;
            }
        }

        // Load reports for each criteria
        $reports = ApplicationReport::with('application_criterion')
            ->where('application_id', $this->application->id)
            ->whereIn('criteria_id', $criteriaIds)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('criteria_id');

        $this->reportsByCriteria = [];
        foreach ($criteriaIds as $criteriaId) {
            $this->reportsByCriteria[$criteriaId] = $reports->get($criteriaId, collect());
        }
    }

    /**
     * Load general department reports (criteria_id = null)
     */
    private function loadDepartmentReports()
    {
        if (! $this->application) {
            return;
        }

        $this->departmentReports = ApplicationReport::with('application.user')
            ->where('application_id', $this->application->id)
            ->whereNull('criteria_id')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Load initial reports for all criteria
     */
    private function loadInitialReportsForAllCriteria()
    {
        if (! $this->application) {
            return;
        }

        // Get all criteria IDs from tabs
        $criteriaIds = [];
        foreach ($this->criteriaTabs as $tab) {
            foreach ($tab['criteria'] as $criterion) {
                $criteriaIds[] = $criterion->id;
            }
        }

        // Load initial reports for each criteria
        $initialReports = ApplicationInitialReport::with('application_criterion')
            ->where('application_id', $this->application->id)
            ->whereIn('criteria_id', $criteriaIds)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('criteria_id');

        $this->initialReportsByCriteria = [];
        foreach ($criteriaIds as $criteriaId) {
            $this->initialReportsByCriteria[$criteriaId] = $initialReports->get($criteriaId, collect());
        }
    }

    /**
     * Load commission solutions from application_solutions table
     */
    private function loadSolutions()
    {
        if (! $this->application) {
            return;
        }

        $this->solutions = ApplicationSolution::with('user')
            ->where('application_id', $this->application->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Load license certificates for approved applications
     */
    private function loadLicenseCertificates()
    {
        if (! $this->application) {
            return;
        }

        // Only load certificates if application is approved (category_id == 6)
        if ($this->application->category_id == ApplicationStatusCategoryConstants::APPROVED_ID) {
            $this->licenseCertificates = LicenseCertificate::where('application_id', $this->application->id)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $this->licenseCertificates = [];
        }
    }

    public function getReportsForCriterion($criterionId)
    {
        if (! $this->application || ! $criterionId) {
            return collect();
        }

        return ApplicationReport::with('application_criterion')
            ->where('application_id', $this->application->id)
            ->where('criteria_id', $criterionId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    private function loadLicenceRequirements()
    {
        if (! $this->activeTab) {
            return;
        }

        $this->licenceRequirementsByCategory = LicenceRequirement::with(['document'])
            ->where('licence_id', $this->application->license_id)
            ->where('category_id', $this->activeTab)
            ->get()
            ->groupBy('document_id')
            ->map(function ($requirements) {
                return [
                    'document' => $requirements->first()->document,
                    'requirements' => $requirements->toArray(),
                ];
            })
            ->toArray();

        // Load uploaded documents for current category
        $this->loadUploadedDocuments();
    }

    private function loadUploadedDocuments()
    {
        if (! $this->activeTab || ! $this->application) {
            return;
        }

        $this->uploadedDocumentsByCategory = ApplicationDocument::with(['document', 'user'])
            ->where('application_id', $this->application->id)
            ->where('category_id', $this->activeTab)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('document_id')
            ->toArray();
    }

    public function setActiveTab($categoryId)
    {
        $this->activeTab = $categoryId;
        $this->reviewDecisions = []; // Reset decisions when changing tabs
        $this->criterionComment = '';
        $this->loadLicenceRequirements();
    }

    // Check if current user can review based on status role_values (п. 1.1)
    public function canReviewCriterion($criterion)
    {
        if (! $criterion || ! $criterion->application_status) {
            return false;
        }

        $user = Auth::user();
        $userRole = $user->role ? $user->role->value : null;

        $status = $criterion->application_status;
        $roleValues = $status->role_values ?? [];

        // Ensure role_values is an array
        if (is_string($roleValues)) {
            $roleValues = json_decode($roleValues, true) ?? [];
        }

        return $userRole && is_array($roleValues) && in_array($userRole, $roleValues);
    }

    // Set temporary review decision for a document
    public function setReviewDecision($documentId, $decision, $comment = '')
    {
        $this->reviewDecisions[$documentId] = [
            'decision' => $decision, // true = accept, false = reject
            'comment' => $comment,
        ];
    }

    // Open accept modal
    public function openAcceptModal($documentId, $documentTitle)
    {
        $this->currentDocumentId = $documentId;
        $this->currentDocumentTitle = $documentTitle;
        $this->showAcceptModal = true;
    }

    // Close accept modal
    public function closeAcceptModal()
    {
        $this->showAcceptModal = false;
        $this->currentDocumentId = null;
        $this->currentDocumentTitle = '';
    }

    // Confirm accept
    public function confirmAccept()
    {
        if ($this->currentDocumentId) {
            $this->setReviewDecision($this->currentDocumentId, true, '');
        }
        $this->closeAcceptModal();
    }

    // Accept all documents for current criterion
    public function acceptAllDocuments($criterionId)
    {
        $criterion = ApplicationCriterion::with('application_status')->find($criterionId);

        if (! $criterion || ! $this->canReviewCriterion($criterion)) {
            toastr()->error('У вас нет прав для проверки этого критерия.');

            return;
        }

        $statusValue = $criterion->application_status->value ?? null;

        // Get all documents for this category
        $documents = ApplicationDocument::where('application_id', $this->application->id)
            ->where('category_id', $criterion->category_id)
            ->get();

        // Filter documents that need review based on status
        $documentsToAccept = collect();

        if ($statusValue === ApplicationStatusConstants::AWAITING_FIRST_CHECK_VALUE) {
            // Accept documents where is_first_passed == null AND is_industry_passed == null AND is_final_passed == null
            $documentsToAccept = $documents->filter(function ($doc) {
                return $doc->is_first_passed === null &&
                       $doc->is_industry_passed === null &&
                       $doc->is_final_passed === null;
            });
        } elseif ($statusValue === ApplicationStatusConstants::AWAITING_CONTROL_CHECK_VALUE) {
            // Accept documents where is_first_passed == true AND is_industry_passed == true AND is_final_passed == null
            $documentsToAccept = $documents->filter(function ($doc) {
                return $doc->is_first_passed === true &&
                       $doc->is_industry_passed === true &&
                       $doc->is_final_passed === null;
            });
        }

        if ($documentsToAccept->isEmpty()) {
            toastr()->info('Нет документов для принятия на данном этапе.');

            return;
        }

        // Set review decision (accept) for all documents
        foreach ($documentsToAccept as $doc) {
            $this->setReviewDecision($doc->id, true, '');
        }

        toastr()->success('Все документы ('.$documentsToAccept->count().') отмечены как принятые.');
    }

    // Open reject modal
    public function openRejectModal($documentId, $documentTitle)
    {
        $this->currentDocumentId = $documentId;
        $this->currentDocumentTitle = $documentTitle;
        $this->rejectComment = '';
        $this->showRejectModal = true;
    }

    // Close reject modal
    public function closeRejectModal()
    {
        $this->showRejectModal = false;
        $this->currentDocumentId = null;
        $this->currentDocumentTitle = '';
        $this->rejectComment = '';
    }

    // Confirm reject
    public function confirmReject()
    {
        if (! $this->rejectComment) {
            toastr()->error('Необходимо указать причину отклонения.');

            return;
        }

        if ($this->currentDocumentId) {
            $this->setReviewDecision($this->currentDocumentId, false, $this->rejectComment);
        }
        $this->closeRejectModal();
    }

    // Check if all documents in current category have been reviewed
    public function allDocumentsReviewed($criterionId)
    {
        $criterion = ApplicationCriterion::find($criterionId);
        if (! $criterion) {
            return false;
        }

        $documents = ApplicationDocument::where('application_id', $this->application->id)
            ->where('category_id', $criterion->category_id)
            ->get();

        $statusValue = $criterion->application_status->value ?? null;

        // Filter documents based on stage requirements
        if ($statusValue === 'awaiting-first-check') {
            // 2.1: Check documents where is_first_passed == null AND is_industry_passed == null AND is_final_passed == null
            $documentsToReview = $documents->filter(function ($doc) {
                return $doc->is_first_passed === null &&
                       $doc->is_industry_passed === null &&
                       $doc->is_final_passed === null;
            });
        } elseif ($statusValue === 'awaiting-industry-check') {
            // 2.2: Check documents where is_first_passed == true AND is_industry_passed == null AND is_final_passed == null
            $documentsToReview = $documents->filter(function ($doc) {
                return $doc->is_first_passed === true &&
                       $doc->is_industry_passed === null &&
                       $doc->is_final_passed === null;
            });
        } elseif ($statusValue === 'awaiting-control-check') {
            // 2.3: Check documents where is_first_passed == true AND is_industry_passed == true AND is_final_passed == null
            $documentsToReview = $documents->filter(function ($doc) {
                return $doc->is_first_passed === true &&
                       $doc->is_industry_passed === true &&
                       $doc->is_final_passed === null;
            });
        } else {
            // For other statuses, no documents need review
            return true;
        }

        // Check if all documents that need review have been reviewed
        foreach ($documentsToReview as $doc) {
            if (! isset($this->reviewDecisions[$doc->id])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if criterion has at least one rejected document
     */
    public function hasRejectedDocuments($criterionId)
    {
        $criterion = ApplicationCriterion::find($criterionId);
        if (! $criterion) {
            return false;
        }

        $statusValue = $criterion->application_status->value ?? null;

        // Get documents for this category
        $documents = ApplicationDocument::where('application_id', $this->application->id)
            ->where('category_id', $criterion->category_id)
            ->get();

        // Check if any document has been rejected in current review decisions
        foreach ($documents as $doc) {
            if (isset($this->reviewDecisions[$doc->id]) && $this->reviewDecisions[$doc->id]['decision'] === false) {
                return true;
            }
        }

        return false;
    }

    // Submit First Check (2.1)
    public function submitFirstCheck($criterionId, $decision)
    {
        $criterion = ApplicationCriterion::with('application_status')->find($criterionId);

        if (! $criterion || ! $this->canReviewCriterion($criterion)) {
            toastr()->error('У вас нет прав для проверки этого критерия.');

            return;
        }

        if ($criterion->application_status->value !== ApplicationStatusConstants::AWAITING_FIRST_CHECK_VALUE) {
            toastr()->error('Критерий не находится на этапе первичной проверки.');

            return;
        }

        // Get all documents for this category
        $documents = ApplicationDocument::where('application_id', $this->application->id)
            ->where('category_id', $criterion->category_id)
            ->get();

        // Validate that all documents with null is_first_passed have been reviewed
        $documentsToReview = $documents->filter(function ($doc) {
            return $doc->is_first_passed === null &&
                   $doc->is_industry_passed === null &&
                   $doc->is_final_passed === null;
        });

        foreach ($documentsToReview as $doc) {
            if (! isset($this->reviewDecisions[$doc->id])) {
                toastr()->error('Необходимо принять решение по всем новым документам.');

                return;
            }
        }

        try {
            DB::beginTransaction();

            $user = Auth::user();
            $userName = trim(($user->last_name ?? '').' '.($user->first_name ?? '').' '.($user->patronymic ?? ''));

            $allPassed = true;

            // Update ONLY documents that need first check (is_first_passed === null)
            foreach ($documentsToReview as $doc) {
                if (isset($this->reviewDecisions[$doc->id])) {
                    $reviewDecision = $this->reviewDecisions[$doc->id];

                    $doc->update([
                        'is_first_passed' => $reviewDecision['decision'],
                        'first_comment' => $reviewDecision['comment'] ?? null,
                        'first_checked_by_id' => $user->id,
                        'first_checked_by' => $userName,
                    ]);

                    if (! $reviewDecision['decision']) {
                        $allPassed = false;
                    }
                }
            }

            // Check if there are any previously failed documents
            $previouslyFailed = $documents->filter(function ($doc) {
                return $doc->is_first_passed === false;
            });

            if ($previouslyFailed->count() > 0) {
                $allPassed = false;
            }

            // Determine new status based on decision
            if ($decision === 'revision') {
                // Send back for revision
                $newStatus = ApplicationStatus::where('value', ApplicationStatusConstants::FIRST_CHECK_REVISION_VALUE)->first();
                $passed = false;
            } elseif ($decision === 'approve') {
                // Approve and move to industry check
                $newStatus = ApplicationStatus::where('value', ApplicationStatusConstants::AWAITING_INDUSTRY_CHECK_VALUE)->first();
                $passed = $allPassed;
            } else {
                // Move to industry check
                $newStatus = ApplicationStatus::where('value', ApplicationStatusConstants::AWAITING_INDUSTRY_CHECK_VALUE)->first();
                $passed = $allPassed;
            }

            // Update criterion - set is_first_passed = true when approve action
            $updateData = [
                'status_id' => $newStatus->id,
                'is_first_passed' => $decision === 'approve' ? true : $passed,
                'first_comment' => $this->criterionComment,
                'first_checked_by_id' => $user->id,
                'first_checked_by' => $userName,
            ];

            $criterion->update($updateData);

            // Log to application_steps
            ApplicationStep::create([
                'application_id' => $this->application->id,
                'application_criteria_id' => $criterion->id,
                'status_id' => $newStatus->id,
                'responsible_id' => $user->id,
                'responsible_by' => $userName,
                'is_passed' => $passed,
                'result' => $this->criterionComment,
            ]);

            // Save deadline if sending for revision and deadline is set
            if ($decision === 'revision' && $this->deadlineEndAt) {
                ApplicationCriteriaDeadline::create([
                    'application_id' => $this->application->id,
                    'application_criteria_id' => $criterion->id,
                    'deadline_start_at' => $this->deadlineStartAt,
                    'deadline_end_at' => $this->deadlineEndAt,
                    'status_id' => $newStatus->id,
                ]);
            }

            DB::commit();

            toastr()->success('Первичная проверка завершена.');
            $this->reviewDecisions = [];
            $this->criterionComment = '';
            $this->loadApplication();
            $this->loadTabsAndRequirements();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error submitting first check: '.$e->getMessage());
            toastr()->error('Ошибка при сохранении проверки.');
        }
    }

    // Submit Industry Check (2.2)
    public function submitIndustryCheck($criterionId, $decision)
    {
        $criterion = ApplicationCriterion::with('application_status')->find($criterionId);

        if (! $criterion || ! $this->canReviewCriterion($criterion)) {
            toastr()->error('У вас нет прав для проверки этого критерия.');

            return;
        }

        if ($criterion->application_status->value !== ApplicationStatusConstants::AWAITING_INDUSTRY_CHECK_VALUE) {
            toastr()->error('Критерий не находится на этапе отраслевой проверки.');

            return;
        }

        $documents = ApplicationDocument::where('application_id', $this->application->id)
            ->where('category_id', $criterion->category_id)
            ->get();

        // Validate that all documents with is_first_passed=true and is_industry_passed=null have been reviewed
        $documentsToReview = $documents->filter(function ($doc) {
            return $doc->is_first_passed === true &&
                   $doc->is_industry_passed === null &&
                   $doc->is_final_passed === null;
        });

        foreach ($documentsToReview as $doc) {
            if (! isset($this->reviewDecisions[$doc->id])) {
                toastr()->error('Необходимо принять решение по всем новым документам.');

                return;
            }
        }

        try {
            DB::beginTransaction();

            $user = Auth::user();
            $userName = trim(($user->last_name ?? '').' '.($user->first_name ?? '').' '.($user->patronymic ?? ''));

            $allPassed = true;

            // Update ONLY documents that need industry check
            foreach ($documentsToReview as $doc) {
                if (isset($this->reviewDecisions[$doc->id])) {
                    $reviewDecision = $this->reviewDecisions[$doc->id];

                    $doc->update([
                        'is_industry_passed' => $reviewDecision['decision'],
                        'industry_comment' => $reviewDecision['comment'] ?? null,
                        'checked_by_id' => $user->id,
                        'checked_by' => $userName,
                    ]);

                    if (! $reviewDecision['decision']) {
                        $allPassed = false;
                    }
                }
            }

            // Check if there are any previously failed documents
            $previouslyFailed = $documents->filter(function ($doc) {
                return $doc->is_industry_passed === false;
            });

            if ($previouslyFailed->count() > 0) {
                $allPassed = false;
            }

            if ($decision === 'revision') {
                $newStatus = ApplicationStatus::where('value', ApplicationStatusConstants::INDUSTRY_CHECK_REVISION_VALUE)->first();
                $passed = false;
            } elseif ($decision === 'approve') {
                $newStatus = ApplicationStatus::where('value', ApplicationStatusConstants::AWAITING_CONTROL_CHECK_VALUE)->first();
                $passed = $allPassed;
            } else {
                $newStatus = ApplicationStatus::where('value', ApplicationStatusConstants::AWAITING_CONTROL_CHECK_VALUE)->first();
                $passed = $allPassed;
            }

            // Update criterion - set is_first_passed and is_industry_passed = true when approve action
            $updateData = [
                'status_id' => $newStatus->id,
                'is_first_passed' => $decision === 'approve' ? true : $criterion->is_first_passed,
                'is_industry_passed' => $decision === 'approve' ? true : $passed,
                'industry_comment' => $this->criterionComment,
                'checked_by_id' => $user->id,
                'checked_by' => $userName,
            ];

            $criterion->update($updateData);

            ApplicationStep::create([
                'application_id' => $this->application->id,
                'application_criteria_id' => $criterion->id,
                'status_id' => $newStatus->id,
                'responsible_id' => $user->id,
                'responsible_by' => $userName,
                'is_passed' => $passed,
                'result' => $this->criterionComment,
            ]);

            // Save deadline if sending for revision and deadline is set
            if ($decision === 'revision' && $this->deadlineEndAt) {
                ApplicationCriteriaDeadline::create([
                    'application_id' => $this->application->id,
                    'application_criteria_id' => $criterion->id,
                    'deadline_start_at' => $this->deadlineStartAt,
                    'deadline_end_at' => $this->deadlineEndAt,
                    'status_id' => $newStatus->id,
                ]);
            }

            DB::commit();

            toastr()->success('Отраслевая проверка завершена.');
            $this->reviewDecisions = [];
            $this->criterionComment = '';
            $this->loadApplication();
            $this->loadTabsAndRequirements();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error submitting industry check: '.$e->getMessage());
            toastr()->error('Ошибка при сохранении проверки.');
        }
    }

    // Submit Control Check (2.3)
    public function submitControlCheck($criterionId, $decision)
    {
        $criterion = ApplicationCriterion::with('application_status')->find($criterionId);

        if (! $criterion || ! $this->canReviewCriterion($criterion)) {
            toastr()->error('У вас нет прав для проверки этого критерия.');

            return;
        }

        if ($criterion->application_status->value !== ApplicationStatusConstants::AWAITING_CONTROL_CHECK_VALUE) {
            toastr()->error('Критерий не находится на этапе контрольной проверки.');

            return;
        }

        $documents = ApplicationDocument::where('application_id', $this->application->id)
            ->where('category_id', $criterion->category_id)
            ->get();

        // Validate that all documents with is_first_passed=true, is_industry_passed=true and is_final_passed=null have been reviewed
        $documentsToReview = $documents->filter(function ($doc) {
            return $doc->is_first_passed === true &&
                   $doc->is_industry_passed === true &&
                   $doc->is_final_passed === null;
        });

        foreach ($documentsToReview as $doc) {
            if (! isset($this->reviewDecisions[$doc->id])) {
                toastr()->error('Необходимо принять решение по всем новым документам.');

                return;
            }
        }

        try {
            DB::beginTransaction();

            $user = Auth::user();
            $userName = trim(($user->last_name ?? '').' '.($user->first_name ?? '').' '.($user->patronymic ?? ''));

            $allPassed = true;

            // Update ONLY documents that need control check
            foreach ($documentsToReview as $doc) {
                if (isset($this->reviewDecisions[$doc->id])) {
                    $reviewDecision = $this->reviewDecisions[$doc->id];

                    $doc->update([
                        'is_final_passed' => $reviewDecision['decision'],
                        'control_comment' => $reviewDecision['comment'] ?? null,
                        'control_checked_by_id' => $user->id,
                        'control_checked_by' => $userName,
                    ]);

                    if (! $reviewDecision['decision']) {
                        $allPassed = false;
                    }
                }
            }

            // Check if there are any previously failed documents
            $previouslyFailed = $documents->filter(function ($doc) {
                return $doc->is_final_passed === false;
            });

            if ($previouslyFailed->count() > 0) {
                $allPassed = false;
            }

            if ($decision === 'revision') {
                $newStatus = ApplicationStatus::where('value', ApplicationStatusConstants::CONTROL_CHECK_REVISION_VALUE)->first();
                $passed = false;
            } elseif ($decision === 'approve') {
                $newStatus = ApplicationStatus::where('value', ApplicationStatusConstants::AWAITING_FINAL_DECISION_VALUE)->first();
                $passed = $allPassed;
            } else {
                $newStatus = ApplicationStatus::where('value', ApplicationStatusConstants::AWAITING_FINAL_DECISION_VALUE)->first();
                $passed = $allPassed;
            }

            // Update criterion - set all flags = true when approve action
            $updateData = [
                'status_id' => $newStatus->id,
                'is_first_passed' => $decision === 'approve' ? true : $criterion->is_first_passed,
                'is_industry_passed' => $decision === 'approve' ? true : $criterion->is_industry_passed,
                'is_final_passed' => $decision === 'approve' ? true : $passed,
                'final_comment' => $this->criterionComment,
                'control_checked_by_id' => $user->id,
                'control_checked_by' => $userName,
            ];

            $criterion->update($updateData);

            ApplicationStep::create([
                'application_id' => $this->application->id,
                'application_criteria_id' => $criterion->id,
                'status_id' => $newStatus->id,
                'responsible_id' => $user->id,
                'responsible_by' => $userName,
                'is_passed' => $passed,
                'result' => $this->criterionComment,
            ]);

            // Save deadline if sending for revision and deadline is set
            if ($decision === 'revision' && $this->deadlineEndAt) {
                ApplicationCriteriaDeadline::create([
                    'application_id' => $this->application->id,
                    'application_criteria_id' => $criterion->id,
                    'deadline_start_at' => $this->deadlineStartAt,
                    'deadline_end_at' => $this->deadlineEndAt,
                    'status_id' => $newStatus->id,
                ]);
            }

            DB::commit();

            toastr()->success('Контрольная проверка завершена.');
            $this->reviewDecisions = [];
            $this->criterionComment = '';
            $this->loadApplication();
            $this->loadTabsAndRequirements();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error submitting control check: '.$e->getMessage());
            toastr()->error('Ошибка при сохранении проверки.');
        }
    }

    // Check if all criteria are at awaiting-final-decision stage (2.4.1)
    public function canMakeFinalDecision()
    {
        $allCriteria = ApplicationCriterion::where('application_id', $this->application->id)->get();

        foreach ($allCriteria as $criterion) {
            if ($criterion->application_status->value !== ApplicationStatusConstants::AWAITING_FINAL_DECISION_VALUE) {
                return false;
            }
        }

        return true;
    }

    public function getFinalDecisionStats()
    {
        $allCriteria = ApplicationCriterion::with('application_status')->where('application_id', $this->application->id)->get();

        $awaitingFinal = $allCriteria->filter(function ($c) {
            return $c->application_status && $c->application_status->value === ApplicationStatusConstants::AWAITING_FINAL_DECISION_VALUE;
        })->count();

        $total = $allCriteria->count();

        return ['awaiting' => $awaitingFinal, 'total' => $total];
    }

    // Open final decision modal (2.4.2)
    public function openFinalDecisionModal()
    {
        if (! $this->canMakeFinalDecision()) {
            toastr()->error('Не все критерии находятся на этапе финального решения.');

            return;
        }

        // Load all criteria for final decision
        $this->allCriteriaForFinalDecision = ApplicationCriterion::with(['category_document', 'application_status'])
            ->where('application_id', $this->application->id)
            ->get()
            ->toArray();

        // Initialize arrays for each criterion
        foreach ($this->allCriteriaForFinalDecision as $criterion) {
            if (! isset($this->finalCommentsByCriterion[$criterion['id']])) {
                $this->finalCommentsByCriterion[$criterion['id']] = '';
            }
            if (! isset($this->finalDecisionsByCriterion[$criterion['id']])) {
                $this->finalDecisionsByCriterion[$criterion['id']] = '';
            }
            if (! isset($this->reuploadDocumentIdsByCriterion[$criterion['id']])) {
                $this->reuploadDocumentIdsByCriterion[$criterion['id']] = [];
            }
        }

        // Load available documents for partially-approved option
        $this->availableDocumentsForReupload = LicenceRequirement::with('document')
            ->where('licence_id', $this->application->license_id)
            ->get()
            ->pluck('document')
            ->unique('id')
            ->toArray();

        $this->showFinalDecisionModal = true;
    }

    public function closeFinalDecisionModal()
    {
        $this->showFinalDecisionModal = false;
        $this->finalDecision = '';
        $this->finalComment = '';
        $this->finalCommentsByCriterion = [];
        $this->finalDecisionsByCriterion = [];
        $this->reuploadDocumentIdsByCriterion = [];
        $this->availableDocumentsForReupload = [];
        $this->allCriteriaForFinalDecision = [];
    }

    // Submit final decision for all criteria (2.4.2)
    public function submitFinalDecision()
    {
        // Validate that all criteria have decisions and comments
        $allCriteria = ApplicationCriterion::where('application_id', $this->application->id)->get();

        foreach ($allCriteria as $criterion) {
            // Check decision
            if (empty($this->finalDecisionsByCriterion[$criterion->id])) {
                toastr()->error('Необходимо выбрать решение по каждому критерию.');

                return;
            }

            // Check comment - required only for partially-approved and revoked
            $decision = $this->finalDecisionsByCriterion[$criterion->id];
            if (in_array($decision, ['partially-approved', 'revoked'])) {
                if (empty($this->finalCommentsByCriterion[$criterion->id])) {
                    toastr()->error('Необходимо указать комментарий для решений "Одобрено частично" и "Отозвано".');

                    return;
                }
            }

            // Check reupload documents for partially-approved
            if ($decision === 'partially-approved') {
                if (empty($this->reuploadDocumentIdsByCriterion[$criterion->id])) {
                    toastr()->error('Для частичного одобрения необходимо указать документы для повторной загрузки.');

                    return;
                }
            }
        }

        try {
            DB::beginTransaction();

            $user = Auth::user();
            $userName = trim(($user->last_name ?? '').' '.($user->first_name ?? '').' '.($user->patronymic ?? ''));

            // Update each criterion with its individual decision
            foreach ($allCriteria as $criterion) {
                $criterionDecision = $this->finalDecisionsByCriterion[$criterion->id];
                $criterionComment = $this->finalCommentsByCriterion[$criterion->id] ?? '';

                $statusValue = match ($criterionDecision) {
                    'fully-approved' => ApplicationStatusConstants::FULLY_APPROVED_VALUE,
                    'partially-approved' => ApplicationStatusConstants::PARTIALLY_APPROVED_VALUE,
                    'revoked' => ApplicationStatusConstants::REVOKED_VALUE,
                    default => null
                };

                if (! $statusValue) {
                    toastr()->error('Неверное решение для критерия '.$criterion->category_document->title_ru);

                    return;
                }

                $newStatus = ApplicationStatus::where('value', $statusValue)->first();

                $updateData = [
                    'status_id' => $newStatus->id,
                    'last_comment' => $criterionComment,
                ];

                if ($criterionDecision === 'partially-approved') {
                    $updateData['can_reupload_after_ending'] = true;
                    $updateData['can_reupload_after_endings_doc_ids'] = $this->reuploadDocumentIdsByCriterion[$criterion->id] ?? [];
                }

                $criterion->update($updateData);

                // Log step with individual criterion comment
                ApplicationStep::create([
                    'application_id' => $this->application->id,
                    'application_criteria_id' => $criterion->id,
                    'status_id' => $newStatus->id,
                    'responsible_id' => $user->id,
                    'responsible_by' => $userName,
                    'is_passed' => $criterionDecision === 'fully-approved',
                    'result' => $criterionComment,
                ]);
            }

            DB::commit();

            toastr()->success('Финальное решение принято.');
            $this->closeFinalDecisionModal();
            $this->loadApplication();
            $this->loadTabsAndRequirements();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error submitting final decision: '.$e->getMessage());
            toastr()->error('Ошибка при сохранении решения.');
        }
    }

    // Check if all criteria reached final status (2.4.3 condition)
    public function canChangeApplicationStatus()
    {
        $allCriteria = ApplicationCriterion::with('application_status')->where('application_id', $this->application->id)->get();

        return $allCriteria->every(function ($c) {
            return $c->application_status && in_array($c->application_status->value, [
                ApplicationStatusConstants::FULLY_APPROVED_VALUE,
                ApplicationStatusConstants::PARTIALLY_APPROVED_VALUE,
                ApplicationStatusConstants::REVOKED_VALUE,
            ]);
        });
    }

    // Check if current user has permission to apply final decision at application level (2.4.3)
    public function canApplyApplicationFinalDecision()
    {
        $user = auth()->user();
        if (! $user || ! $user->role) {
            return false;
        }

        // Get final-decision category
        $finalDecisionCategory = \App\Models\ApplicationStatusCategory::where('value', ApplicationStatusCategoryConstants::FINAL_DECISION_VALUE)->first();

        if (! $finalDecisionCategory) {
            return false;
        }

        $roleValues = $finalDecisionCategory->role_values ?? [];

        // Ensure role_values is an array
        if (is_string($roleValues)) {
            $roleValues = json_decode($roleValues, true) ?? [];
        }

        return is_array($roleValues) && in_array($user->role->value, $roleValues);
    }

    // Get available final decisions based on criteria statuses
    public function getAvailableFinalDecisions()
    {
        // Regardless of criteria statuses, both options are always available
        // The decision is up to the responsible person
        return ['approved', 'revoked'];
    }

    // Change application status (2.4.3)
    public function changeApplicationStatus()
    {
        if (! $this->canChangeApplicationStatus()) {
            toastr()->error('Не все критерии имеют финальное решение.');

            return;
        }

        if (! $this->applicationFinalDecision) {
            toastr()->error('Необходимо выбрать решение.');

            return;
        }

        if (! $this->canApplyApplicationFinalDecision()) {
            toastr()->error('У вас нет прав для применения финального решения.');

            return;
        }

        try {
            DB::beginTransaction();

            $categoryValue = match ($this->applicationFinalDecision) {
                'approved' => ApplicationStatusCategoryConstants::APPROVED_VALUE,
                'revoked' => ApplicationStatusCategoryConstants::REVOKED_VALUE,
                default => null
            };

            if (! $categoryValue) {
                toastr()->error('Неверный статус.');

                return;
            }

            $category = \App\Models\ApplicationStatusCategory::where('value', $categoryValue)->first();

            // Update application
            $this->application->update([
                'category_id' => $category->id,
            ]);

            DB::commit();

            toastr()->success('Статус заявки изменен.');
            $this->applicationFinalDecision = '';
            $this->loadApplication();
            $this->loadTabsAndRequirements();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error changing application status: '.$e->getMessage());
            toastr()->error('Ошибка при изменении статуса.');
        }
    }

    // Upgrade criterion from partially-approved to fully-approved (2.4.4)
    public function upgradeCriterionToFullyApproved($criterionId)
    {
        $criterion = ApplicationCriterion::with('application_status')->find($criterionId);

        if (! $criterion) {
            toastr()->error('Критерий не найден.');

            return;
        }

        // Check that application is approved and criterion is partially-approved
        if ($this->application->application_status_category->value !== ApplicationStatusCategoryConstants::APPROVED_VALUE) {
            toastr()->error('Заявка должна быть одобрена для изменения статуса критерия.');

            return;
        }

        if ($criterion->application_status->value !== ApplicationStatusConstants::PARTIALLY_APPROVED_VALUE) {
            toastr()->error('Только частично одобренные критерии могут быть изменены на полностью одобренные.');

            return;
        }

        try {
            DB::beginTransaction();

            $user = Auth::user();
            $userName = trim(($user->last_name ?? '').' '.($user->first_name ?? '').' '.($user->patronymic ?? ''));

            $newStatus = ApplicationStatus::where('value', ApplicationStatusConstants::FULLY_APPROVED_VALUE)->first();

            $criterion->update([
                'status_id' => $newStatus->id,
                'can_reupload_after_ending' => false,
                'can_reupload_after_endings_doc_ids' => null,
            ]);

            // Log step
            ApplicationStep::create([
                'application_id' => $this->application->id,
                'application_criteria_id' => $criterion->id,
                'status_id' => $newStatus->id,
                'responsible_id' => $user->id,
                'responsible_by' => $userName,
                'is_passed' => true,
                'result' => 'Критерий изменен с частично одобренного на полностью одобренный',
            ]);

            DB::commit();

            toastr()->success('Критерий изменен на полностью одобренный.');
            $this->loadApplication();
            $this->loadTabsAndRequirements();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error upgrading criterion: '.$e->getMessage());
            toastr()->error('Ошибка при изменении статуса критерия.');
        }
    }

    public function getApplicationStatusColor($statusValue)
    {
        return match ($statusValue) {
            ApplicationStatusCategoryConstants::DOCUMENT_SUBMISSION_VALUE => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
            ApplicationStatusCategoryConstants::FIRST_CHECK_VALUE => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
            ApplicationStatusCategoryConstants::INDUSTRY_CHECK_VALUE => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
            ApplicationStatusCategoryConstants::CONTROL_CHECK_VALUE => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
            ApplicationStatusCategoryConstants::FINAL_DECISION_VALUE => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200',
            ApplicationStatusCategoryConstants::APPROVED_VALUE => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            ApplicationStatusCategoryConstants::REVOKED_VALUE => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
            ApplicationStatusCategoryConstants::REJECTED_VALUE => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200'
        };
    }

    public function getCriterionStatusColor($criterion)
    {
        if (! $criterion->is_ready) {
            return 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
        }

        $hasFailures = false;
        $hasPending = false;

        if ($criterion->is_first_passed === false) {
            $hasFailures = true;
        } elseif ($criterion->is_first_passed === null) {
            $hasPending = true;
        }

        if ($criterion->is_industry_passed === false) {
            $hasFailures = true;
        } elseif ($criterion->is_industry_passed === null && ! $hasFailures) {
            $hasPending = true;
        }

        if ($criterion->is_final_passed === false) {
            $hasFailures = true;
        } elseif ($criterion->is_final_passed === null && ! $hasFailures) {
            $hasPending = true;
        }

        if ($hasFailures) {
            return 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
        } elseif ($hasPending) {
            return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200';
        } else {
            return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
        }
    }

    public function getCriterionStatusColorByValue($statusValue)
    {
        return match ($statusValue) {
            ApplicationStatusConstants::AWAITING_DOCUMENTS_VALUE => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
            ApplicationStatusConstants::AWAITING_FIRST_CHECK_VALUE => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
            ApplicationStatusConstants::FIRST_CHECK_REVISION_VALUE => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
            ApplicationStatusConstants::AWAITING_INDUSTRY_CHECK_VALUE => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
            ApplicationStatusConstants::INDUSTRY_CHECK_REVISION_VALUE => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
            ApplicationStatusConstants::AWAITING_CONTROL_CHECK_VALUE => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
            ApplicationStatusConstants::CONTROL_CHECK_REVISION_VALUE => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
            ApplicationStatusConstants::AWAITING_FINAL_DECISION_VALUE => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200',
            ApplicationStatusConstants::FULLY_APPROVED_VALUE => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            ApplicationStatusConstants::PARTIALLY_APPROVED_VALUE => 'bg-teal-100 text-teal-800 dark:bg-teal-900 dark:text-teal-200',
            ApplicationStatusConstants::REVOKED_VALUE => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
            ApplicationStatusConstants::REJECTED_VALUE => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200'
        };
    }

    public function getUploadedDocumentsForRequirement($documentId)
    {
        if (! isset($this->uploadedDocumentsByCategory[$documentId])) {
            return [];
        }

        return $this->uploadedDocumentsByCategory[$documentId];
    }

    public function openDocumentInfoModal($documentId)
    {
        $this->viewingDocument = ApplicationDocument::with([
            'document',
            'user',
            'application.club',
            'application.licence',
        ])->find($documentId);

        if (! $this->viewingDocument) {
            toastr()->error('Документ не найден.');

            return;
        }

        $this->showDocumentInfoModal = true;
    }

    public function closeDocumentInfoModal()
    {
        $this->showDocumentInfoModal = false;
        $this->viewingDocument = null;
    }

    public function canRejectApplication()
    {
        if (! $this->application || ! $this->application->application_status_category) {
            return false;
        }

        $categoryValue = $this->application->application_status_category->value;

        // Check if application is in one of the allowed statuses
        $allowedStatuses = [
            ApplicationStatusCategoryConstants::FIRST_CHECK_VALUE,
            ApplicationStatusCategoryConstants::INDUSTRY_CHECK_VALUE,
            ApplicationStatusCategoryConstants::CONTROL_CHECK_VALUE,
            ApplicationStatusCategoryConstants::FINAL_DECISION_VALUE,
        ];

        if (! in_array($categoryValue, $allowedStatuses)) {
            return false;
        }

        // Check if user's role is in the category's role_values
        $user = auth()->user();
        if (! $user || ! $user->role) {
            return false;
        }

        $categoryRoleValues = $this->application->application_status_category->role_values ?? [];

        // Decode if it's a string
        if (is_string($categoryRoleValues)) {
            $categoryRoleValues = json_decode($categoryRoleValues, true) ?? [];
        }

        return is_array($categoryRoleValues) && in_array($user->role->value, $categoryRoleValues);
    }

    public function openRejectApplicationModal()
    {
        if (! $this->canRejectApplication()) {
            toastr()->error('У вас нет прав для отказа этой заявки.');

            return;
        }

        $this->rejectApplicationComment = '';
        $this->showRejectApplicationModal = true;
    }

    public function closeRejectApplicationModal()
    {
        $this->showRejectApplicationModal = false;
        $this->rejectApplicationComment = '';
    }

    public function rejectApplication()
    {
        if (! $this->canRejectApplication()) {
            toastr()->error('У вас нет прав для отказа этой заявки.');

            return;
        }

        try {
            DB::beginTransaction();

            // Update application category to rejected
            $this->application->category_id = ApplicationStatusCategoryConstants::REJECTED_ID;
            $this->application->save();

            // Update all application criteria to rejected status
            $rejectedStatusId = ApplicationStatusConstants::REJECTED_ID;

            ApplicationCriterion::where('application_id', $this->application->id)
                ->update([
                    'status_id' => $rejectedStatusId,
                    'last_comment' => $this->rejectApplicationComment ?: 'Заявка отклонена',
                ]);

            DB::commit();

            toastr()->success('Заявка успешно отклонена.');

            $this->closeRejectApplicationModal();

            // Reload application
            $this->loadApplication();
            $this->loadTabsAndRequirements();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error rejecting application: '.$e->getMessage());
            toastr()->error('Произошла ошибка при отказе заявки.');
        }
    }

    /**
     * Download initial report from external service
     */
    public function downloadInitialReport($reportId)
    {
        // Set loading state
        $this->downloadingReports[$reportId] = true;

        try {
            $reportServiceUrl = config('app.initial_report_service_url', env('INITIAL_REPORT_SERVICE_URL'));

            if (! $reportServiceUrl) {
                toastr()->error('URL сервиса генерации первичных отчетов не настроен');
                $this->downloadingReports[$reportId] = false;

                return;
            }

            // Send POST request to report service
            $response = Http::timeout(30)
                ->post($reportServiceUrl, [
                    'report_id' => $reportId,
                ]);

            if (! $response->successful()) {
                Log::error('Initial report service returned error: '.$response->status().' - '.$response->body());
                toastr()->error('Ошибка при получении первичного отчета от сервиса');
                $this->downloadingReports[$reportId] = false;

                return;
            }

            // Get the file content
            $fileContent = $response->body();

            // Get the report from database for filename
            $report = ApplicationInitialReport::find($reportId);
            $filename = 'initial_report_'.$reportId.'_'.now()->format('Y-m-d_H-i-s').'.pdf';

            if ($report && $report->application_criterion && $report->application_criterion->category_document) {
                $categoryName = Str::slug($report->application_criterion->category_document->title_ru ?? 'report');
                $filename = 'initial_'.$categoryName.'_report_'.$reportId.'_'.now()->format('Y-m-d_H-i-s').'.pdf';
            }

            // Clear loading state before returning file
            $this->downloadingReports[$reportId] = false;

            // Return file download response
            return response()->streamDownload(function () use ($fileContent) {
                echo $fileContent;
            }, $filename, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            ]);

        } catch (\Exception $e) {
            Log::error('Error downloading initial report: '.$e->getMessage());
            toastr()->error('Произошла ошибка при скачивании первичного отчета');
            $this->downloadingReports[$reportId] = false;
        }
    }

    /**
     * Download report from external service
     */
    public function downloadReport($reportId)
    {
        // Set loading state
        $this->downloadingReports[$reportId] = true;

        try {
            $reportServiceUrl = config('app.report_service_url', env('REPORT_SERVICE_URL'));

            if (! $reportServiceUrl) {
                toastr()->error('URL сервиса генерации отчетов не настроен');
                $this->downloadingReports[$reportId] = false;

                return;
            }

            // Send POST request to report service
            $response = Http::timeout(30)
                ->post($reportServiceUrl, [
                    'report_id' => $reportId,
                ]);

            if (! $response->successful()) {
                Log::error('Report service returned error: '.$response->status().' - '.$response->body());
                toastr()->error('Ошибка при получении отчета от сервиса');
                $this->downloadingReports[$reportId] = false;

                return;
            }

            // Get the file content
            $fileContent = $response->body();

            // Get the report from database for filename
            $report = ApplicationReport::find($reportId);
            $filename = 'report_'.$reportId.'_'.now()->format('Y-m-d_H-i-s').'.pdf';

            if ($report && $report->application_criterion && $report->application_criterion->category_document) {
                $categoryName = Str::slug($report->application_criterion->category_document->title_ru ?? 'report');
                $filename = $categoryName.'_report_'.$reportId.'_'.now()->format('Y-m-d_H-i-s').'.pdf';
            }

            // Clear loading state before returning file
            $this->downloadingReports[$reportId] = false;

            // Return file download response
            return response()->streamDownload(function () use ($fileContent) {
                echo $fileContent;
            }, $filename, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            ]);

        } catch (\Exception $e) {
            Log::error('Error downloading report: '.$e->getMessage());
            toastr()->error('Произошла ошибка при скачивании отчета');
            $this->downloadingReports[$reportId] = false;
        }
    }

    /**
     * Download department report from external service
     */
    public function downloadDepartmentReport($reportId)
    {
        // Set loading state
        $this->downloadingReports['dept_'.$reportId] = true;

        try {
            $reportServiceUrl = config('app.department_report_service_url', env('DEPARTMENT_REPORT_SERVICE_URL'));

            if (! $reportServiceUrl) {
                toastr()->error('URL сервиса генерации отчетов департамента не настроен');
                $this->downloadingReports['dept_'.$reportId] = false;

                return;
            }

            // Send POST request to report service
            $response = Http::timeout(30)
                ->post($reportServiceUrl, [
                    'report_id' => $reportId,
                ]);

            if (! $response->successful()) {
                Log::error('Department report service returned error: '.$response->status().' - '.$response->body());
                toastr()->error('Ошибка при получении отчета от сервиса');
                $this->downloadingReports['dept_'.$reportId] = false;

                return;
            }

            // Get the file content
            $fileContent = $response->body();

            // Get the report from database for filename
            $report = ApplicationReport::find($reportId);
            $filename = 'department_report_'.$reportId.'_'.now()->format('Y-m-d_H-i-s').'.pdf';

            if ($report) {
                $appName = Str::slug(config('app.name', 'KFF'));
                $filename = $appName.'_department_report_'.$reportId.'_'.now()->format('Y-m-d_H-i-s').'.pdf';
            }

            // Clear loading state before returning file
            $this->downloadingReports['dept_'.$reportId] = false;

            // Return file download response
            return response()->streamDownload(function () use ($fileContent) {
                echo $fileContent;
            }, $filename, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            ]);

        } catch (\Exception $e) {
            Log::error('Error downloading department report: '.$e->getMessage());
            toastr()->error('Произошла ошибка при скачивании отчета');
            $this->downloadingReports['dept_'.$reportId] = false;
        }
    }

    /**
     * Download commission solution from external service
     */
    public function downloadSolution($solutionId)
    {
        // Set loading state
        $this->downloadingReports['solution_'.$solutionId] = true;

        try {
            $solutionServiceUrl = config('app.solution_service_url', env('SOLUTION_SERVICE_URL'));

            if (! $solutionServiceUrl) {
                toastr()->error('URL сервиса генерации решений комиссии не настроен');
                $this->downloadingReports['solution_'.$solutionId] = false;

                return;
            }

            // Send POST request to solution service
            $response = Http::timeout(30)
                ->post($solutionServiceUrl, [
                    'solution_id' => $solutionId,
                ]);

            if (! $response->successful()) {
                Log::error('Solution service returned error: '.$response->status().' - '.$response->body());
                toastr()->error('Ошибка при получении решения от сервиса');
                $this->downloadingReports['solution_'.$solutionId] = false;

                return;
            }

            // Get the file content
            $fileContent = $response->body();

            // Get the solution from database for filename
            $solution = ApplicationSolution::find($solutionId);
            $filename = 'commission_solution_'.$solutionId.'_'.now()->format('Y-m-d_H-i-s').'.pdf';

            if ($solution) {
                $appName = Str::slug(config('app.name', 'KFF'));
                $filename = $appName.'_commission_solution_'.$solutionId.'_'.now()->format('Y-m-d_H-i-s').'.pdf';
            }

            // Clear loading state before returning file
            $this->downloadingReports['solution_'.$solutionId] = false;

            // Return file download response
            return response()->streamDownload(function () use ($fileContent) {
                echo $fileContent;
            }, $filename, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            ]);

        } catch (\Exception $e) {
            Log::error('Error downloading solution: '.$e->getMessage());
            toastr()->error('Произошла ошибка при скачивании решения');
            $this->downloadingReports['solution_'.$solutionId] = false;
        }
    }

    /**
     * Download license certificate from external service
     */
    public function downloadLicenseCertificate($certificateId)
    {
        // Set loading state
        $this->downloadingReports['certificate_'.$certificateId] = true;

        try {
            $certificateServiceUrl = config('app.certificate_service_url', env('CERTIFICATE_SERVICE_URL'));

            if (! $certificateServiceUrl) {
                toastr()->error('URL сервиса генерации лицензий не настроен');
                $this->downloadingReports['certificate_'.$certificateId] = false;

                return;
            }

            // Send POST request to certificate service
            $response = Http::timeout(30)
                ->post($certificateServiceUrl, [
                    'certificate_id' => $certificateId,
                ]);

            if (! $response->successful()) {
                Log::error('Certificate service returned error: '.$response->status().' - '.$response->body());
                toastr()->error('Ошибка при получении лицензии от сервиса');
                $this->downloadingReports['certificate_'.$certificateId] = false;

                return;
            }

            // Get the file content
            $fileContent = $response->body();

            // Get the certificate from database for filename
            $certificate = LicenseCertificate::find($certificateId);
            $filename = 'license_certificate_'.$certificateId.'_'.now()->format('Y-m-d_H-i-s').'.pdf';

            if ($certificate && $certificate->application && $certificate->application->club) {
                $clubName = Str::slug($certificate->application->club->name_ru ?? 'club');
                $seasonYear = $certificate->application->licence->season->year ?? date('Y');
                $filename = $clubName.'_license_'.$seasonYear.'_'.$certificateId.'_'.now()->format('Y-m-d_H-i-s').'.pdf';
            }

            // Clear loading state before returning file
            $this->downloadingReports['certificate_'.$certificateId] = false;

            // Return file download response
            return response()->streamDownload(function () use ($fileContent) {
                echo $fileContent;
            }, $filename, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            ]);

        } catch (\Exception $e) {
            Log::error('Error downloading certificate: '.$e->getMessage());
            toastr()->error('Произошла ошибка при скачивании лицензии');
            $this->downloadingReports['certificate_'.$certificateId] = false;
        }
    }

    public function openRevisionDeadlineModal($criterionId, $revisionType)
    {
        $this->revisionCriterionId = $criterionId;
        $this->revisionType = $revisionType;
        $this->deadlineStartAt = null;
        $this->deadlineEndAt = null;
        $this->showRevisionDeadlineModal = true;
    }

    public function closeRevisionDeadlineModal()
    {
        $this->showRevisionDeadlineModal = false;
        $this->revisionCriterionId = null;
        $this->revisionType = null;
        $this->deadlineStartAt = null;
        $this->deadlineEndAt = null;
        $this->resetValidation(['deadlineEndAt']);
    }

    public function confirmRevisionWithDeadline()
    {
        // Validate deadline (optional, but if provided must be valid)
        $this->validate([
            'deadlineEndAt' => 'nullable|date|after:now',
            'deadlineStartAt' => 'nullable|date|before:deadlineEndAt',
        ], [
            'deadlineEndAt.date' => 'Неверный формат даты',
            'deadlineEndAt.after' => 'Дедлайн должен быть в будущем',
            'deadlineStartAt.date' => 'Неверный формат даты начала',
            'deadlineStartAt.before' => 'Дата начала должна быть раньше даты окончания',
        ]);

        // Determine which submit method to call based on revision type
        switch ($this->revisionType) {
            case 'first':
                $this->submitFirstCheck($this->revisionCriterionId, 'revision');
                break;
            case 'industry':
                $this->submitIndustryCheck($this->revisionCriterionId, 'revision');
                break;
            case 'control':
                $this->submitControlCheck($this->revisionCriterionId, 'revision');
                break;
        }

        $this->closeRevisionDeadlineModal();
    }

    /**
     * Check if user can change fully-approved to partially-approved
     */
    public function canChangeToPartiallyApproved($criterion)
    {
        $user = auth()->user();

        if (! $user || ! $user->role) {
            return false;
        }

        // Only licensing-department and control-department can change
        if (! in_array($user->role->value, ['licensing-department', 'control-department'])) {
            return false;
        }

        // Check if criterion is fully-approved
        if (! $criterion->application_status || $criterion->application_status->value !== ApplicationStatusConstants::FULLY_APPROVED_VALUE) {
            return false;
        }

        return true;
    }

    /**
     * Open modal to change fully-approved to partially-approved
     */
    public function openChangeToPartialModal($criterionId)
    {
        $criterion = ApplicationCriterion::with(['application_status', 'application'])->find($criterionId);

        if (! $criterion || ! $this->canChangeToPartiallyApproved($criterion)) {
            toastr()->error('Изменение статуса недоступно.');

            return;
        }

        $this->changeCriterionId = $criterionId;
        $this->changeComment = '';
        $this->changeReuploadDocumentIds = [];

        // Load available documents for this criterion from licence_requirements
        // using license_id from application and category_id from criterion
        $licenseId = $criterion->application->license_id;
        $categoryId = $criterion->category_id;
        $requirements = LicenceRequirement::with('document')
            ->where('licence_id', $licenseId)
            ->where('category_id', $categoryId)
            ->get();
        $this->availableDocumentsForReupload = $requirements
            ->map(function ($requirement) {
                return [
                    'id' => $requirement->id,
                    'document' => [
                        'id' => $requirement->document->id,
                        'title_ru' => $requirement->document->title_ru,
                        'title_kk' => $requirement->document->title_kk,
                        'title_en' => $requirement->document->title_en,
                        'description_ru' => $requirement->document->description_ru ?? null,
                    ],
                ];
            })
            ->toArray();

        $this->showChangeToPartialModal = true;
    }

    /**
     * Close modal
     */
    public function closeChangeToPartialModal()
    {
        $this->showChangeToPartialModal = false;
        $this->changeCriterionId = null;
        $this->changeComment = '';
        $this->changeReuploadDocumentIds = [];
        $this->availableDocumentsForReupload = [];
    }

    /**
     * Change fully-approved to partially-approved
     */
    public function changeToPartiallyApproved()
    {
        $criterion = ApplicationCriterion::with('application_status')->find($this->changeCriterionId);

        if (! $criterion || ! $this->canChangeToPartiallyApproved($criterion)) {
            toastr()->error('Изменение статуса недоступно.');

            return;
        }

        if (empty($this->changeReuploadDocumentIds)) {
            toastr()->error('Необходимо выбрать хотя бы один документ для повторной загрузки.');

            return;
        }

        try {
            DB::beginTransaction();

            // Get partially-approved status
            $partiallyApprovedStatus = ApplicationStatus::where('value', ApplicationStatusConstants::PARTIALLY_APPROVED_VALUE)->first();

            if (! $partiallyApprovedStatus) {
                toastr()->error('Статус "Одобрено частично" не найден.');

                return;
            }

            $user = auth()->user();
            $userName = $user->name ?? 'Неизвестный пользователь';

            // Update criterion
            $criterion->update([
                'status_id' => $partiallyApprovedStatus->id,
                'last_comment' => $this->changeComment,
                'can_reupload_after_ending' => true,
                'can_reupload_after_endings_doc_ids' => $this->changeReuploadDocumentIds,
            ]);

            // Log step
            ApplicationStep::create([
                'application_id' => $this->application->id,
                'application_criteria_id' => $criterion->id,
                'status_id' => $partiallyApprovedStatus->id,
                'responsible_id' => $user->id,
                'responsible_by' => $userName,
                'is_passed' => false,
                'result' => $this->changeComment ?: 'Изменен статус с "Полностью одобрено" на "Одобрено частично"',
            ]);

            DB::commit();

            toastr()->success('Статус успешно изменен на "Одобрено частично".');
            $this->closeChangeToPartialModal();
            $this->loadApplication();
            $this->loadTabsAndRequirements();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error changing to partially approved: '.$e->getMessage());
            toastr()->error('Ошибка при изменении статуса.');
        }
    }

    /**
     * Open generate report modal for a criterion
     */
    public function openGenerateReportModal($criterionId)
    {
        $criterion = ApplicationCriterion::with(['application', 'category_document', 'application_status'])
            ->find($criterionId);

        if (! $criterion) {
            toastr()->error('Критерий не найден.');

            return;
        }

        // Check if criterion is at awaiting-control-check status
        if ($criterion->application_status->value !== ApplicationStatusConstants::AWAITING_CONTROL_CHECK_VALUE || $criterion->application_status->value !== ApplicationStatusConstants::AWAITING_FINAL_DECISION_VALUE) {
            toastr()->error('Отчет можно сгенерировать только для критериев на этапе контрольной проверки.');

            return;
        }

        // Check if report already exists for this criterion
        $existingReport = ApplicationReport::where('application_id', $criterion->application_id)
            ->where('criteria_id', $criterionId)
            ->first();

        if ($existingReport) {
            toastr()->error('Отчет для этого критерия уже существует.');

            return;
        }

        $this->reportCriterionId = $criterionId;
        $this->selectedDocumentIds = [];

        // Load documents for this criterion's category
        $documents = ApplicationDocument::with('document')
            ->where('application_id', $criterion->application_id)
            ->where('category_id', $criterion->category_id)
            ->get();

        $this->availableDocumentsForReport = $documents->map(function ($appDoc) {
            return [
                'id' => $appDoc->id,
                'document_id' => $appDoc->document_id,
                'title_ru' => $appDoc->document->title_ru ?? 'Документ',
                'title_kk' => $appDoc->document->title_kk ?? 'Құжат',
                'title_en' => $appDoc->document->title_en ?? 'Document',
                'file_url' => $appDoc->file_url,
                'status' => $appDoc->is_industry_passed,
                'comment' => $appDoc->industry_comment,
            ];
        })->toArray();

        $this->showGenerateReportModal = true;
    }

    /**
     * Close generate report modal
     */
    public function closeGenerateReportModal()
    {
        $this->showGenerateReportModal = false;
        $this->reportCriterionId = null;
        $this->selectedDocumentIds = [];
        $this->availableDocumentsForReport = [];
    }

    /**
     * Create general report if all criteria reports exist
     */
    private function createGeneralReportIfAllCriteriaReportsExist($applicationId)
    {
        // Check if general report already exists
        $existingGeneralReport = ApplicationReport::where('application_id', $applicationId)
            ->whereNull('criteria_id')
            ->first();

        if ($existingGeneralReport) {
            Log::info("General report already exists for application #{$applicationId}");

            return;
        }

        // Get awaiting-control-check status ID
        $awaitingControlCheckStatusId = ApplicationStatus::where('value', ApplicationStatusConstants::AWAITING_CONTROL_CHECK_VALUE)->value('id');

        // Get ALL criteria for this application
        $allCriteria = ApplicationCriterion::where('application_id', $applicationId)
            ->pluck('id')
            ->toArray();

        if (empty($allCriteria)) {
            Log::info("No criteria found for application #{$applicationId}");

            return;
        }

        // Get criteria with awaiting-control-check status
        $criteriaWithAwaitingControlCheck = ApplicationCriterion::where('application_id', $applicationId)
            ->where('status_id', $awaitingControlCheckStatusId)
            ->pluck('id')
            ->toArray();

        // IMPORTANT: Check if ALL criteria have reached awaiting-control-check status
        if (count($allCriteria) !== count($criteriaWithAwaitingControlCheck)) {
            Log::info("Not all criteria have reached awaiting-control-check status for application #{$applicationId}. Total criteria: ".count($allCriteria).', Criteria with awaiting-control-check: '.count($criteriaWithAwaitingControlCheck));

            return;
        }

        // Now check if all these criteria have reports
        $criteriaWithReports = ApplicationReport::where('application_id', $applicationId)
            ->whereNotNull('criteria_id')
            ->whereIn('criteria_id', $criteriaWithAwaitingControlCheck)
            ->pluck('criteria_id')
            ->unique()
            ->toArray();

        // Compare: do all criteria have reports?
        $allCriteriaHaveReports = count($criteriaWithAwaitingControlCheck) === count($criteriaWithReports);

        if (! $allCriteriaHaveReports) {
            Log::info("Not all criteria have reports yet for application #{$applicationId}. Criteria with awaiting-control-check: ".count($criteriaWithAwaitingControlCheck).', Reports count: '.count($criteriaWithReports));

            return;
        }

        // All criteria have reports! Create general report
        Log::info("All criteria reports exist for application #{$applicationId}, creating general report");

        // Collect all list_documents from criteria reports
        $criteriaReports = ApplicationReport::where('application_id', $applicationId)
            ->whereNotNull('criteria_id')
            ->get();

        $allDocuments = [];
        foreach ($criteriaReports as $criteriaReport) {
            if (! empty($criteriaReport->list_documents) && is_array($criteriaReport->list_documents)) {
                $allDocuments = array_merge($allDocuments, $criteriaReport->list_documents);
            }
        }

        // Remove duplicates and reindex
        $allDocuments = array_values(array_unique($allDocuments));

        // Sort in ascending order
        sort($allDocuments, SORT_NUMERIC);

        // Create general ApplicationReport
        ApplicationReport::create([
            'application_id' => $applicationId,
            'criteria_id' => null,
            'status' => 1,
            'list_documents' => $allDocuments,
        ]);

        Log::info("General ApplicationReport created for application #{$applicationId} with ".count($allDocuments).' documents from '.count($criteriaReports).' criteria reports');
    }

    /**
     * Generate report with selected documents
     */
    public function generateReport()
    {
        if (empty($this->selectedDocumentIds)) {
            toastr()->error('Необходимо выбрать хотя бы один документ для отчета.');

            return;
        }

        $criterion = ApplicationCriterion::find($this->reportCriterionId);

        if (! $criterion) {
            toastr()->error('Критерий не найден.');

            return;
        }

        try {
            DB::beginTransaction();

            // Sort selected document IDs in ascending order
            $sortedDocumentIds = $this->selectedDocumentIds;
            sort($sortedDocumentIds, SORT_NUMERIC);

            // Create ApplicationReport with selected document IDs
            ApplicationReport::create([
                'application_id' => $criterion->application_id,
                'criteria_id' => $criterion->id,
                'status' => true,
                'list_documents' => $sortedDocumentIds,
            ]);

            // Check if all criteria reports are now created and create general report if needed
            $this->createGeneralReportIfAllCriteriaReportsExist($criterion->application_id);

            DB::commit();

            toastr()->success('Отчет успешно сгенерирован.');
            $this->closeGenerateReportModal();

            // Reload reports
            $this->loadReportsForAllCriteria();
            $this->loadDepartmentReports();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error generating report: '.$e->getMessage());
            toastr()->error('Ошибка при генерации отчета.');
        }
    }

    public function openEditSolutionModal($solutionId)
    {
        $solution = ApplicationSolution::find($solutionId);

        if (!$solution) {
            toastr()->error('Решение не найдено.');
            return;
        }

        $this->editingSolutionId = $solution->id;
        $this->secretaryName = $solution->secretary_name ?? '';
        $this->secretaryPosition = $solution->secretary_position ?? '';
        $this->directorPosition = $solution->director_position ?? '';
        $this->directorName = $solution->director_name ?? '';
        $this->solutionType = $solution->type ?? '';
        $this->meetingDate = $solution->meeting_date ? $solution->meeting_date->format('Y-m-d') : '';
        $this->meetingPlace = $solution->meeting_place ?? '';
        $this->departmentName = $solution->department_name ?? '';
        $this->listCriteria = $solution->list_criteria ?? [];

        $this->loadAvailableCriteria();

        $this->showEditSolutionModal = true;
    }

    private function loadAvailableCriteria()
    {
        $this->availableCriteriaForSolution = ApplicationCriterion::with('category_document')
            ->where('application_id', $this->applicationId)
            ->where('status_id', 10)
            ->get()
            ->toArray();
    }

    public function addCriteriaToList()
    {
        // Validate new criteria fields
        if (empty($this->newCriteriaTitle)) {
            toastr()->error('Выберите критерий.');
            return;
        }

        if (empty($this->newCriteriaDeadline)) {
            toastr()->error('Укажите срок.');
            return;
        }

        // Check if criteria already exists in list
        foreach ($this->listCriteria as $item) {
            if ($item['title'] === $this->newCriteriaTitle) {
                toastr()->error('Этот критерий уже добавлен в список.');
                return;
            }
        }

        // Add to list
        $this->listCriteria[] = [
            'title' => $this->newCriteriaTitle,
            'type' => $this->newCriteriaType ?? '',
            'deadline' => $this->newCriteriaDeadline,
        ];

        // Reset fields
        $this->newCriteriaTitle = '';
        $this->newCriteriaType = '';
        $this->newCriteriaDeadline = '';

        toastr()->success('Критерий добавлен.');
    }

    public function removeCriteriaFromList($index)
    {
        if (isset($this->listCriteria[$index])) {
            unset($this->listCriteria[$index]);
            $this->listCriteria = array_values($this->listCriteria); // Re-index array
            toastr()->success('Критерий удален.');
        }
    }

    public function updateSolution()
    {
        $solution = ApplicationSolution::find($this->editingSolutionId);

        if (!$solution) {
            toastr()->error('Решение не найдено.');
            return;
        }

        $solution->update([
            'secretary_name' => $this->secretaryName,
            'secretary_position' => $this->secretaryPosition,
            'director_position' => $this->directorPosition,
            'director_name' => $this->directorName,
            'type' => $this->solutionType,
            'meeting_date' => $this->meetingDate ? \Carbon\Carbon::parse($this->meetingDate) : null,
            'meeting_place' => $this->meetingPlace,
            'department_name' => $this->departmentName,
            'list_criteria' => $this->listCriteria,
        ]);

        toastr()->success('Решение успешно обновлено.');
        $this->closeEditSolutionModal();

        // Reload solutions
        $this->loadApplication();
    }

    public function closeEditSolutionModal()
    {
        $this->showEditSolutionModal = false;
        $this->editingSolutionId = null;
        $this->secretaryName = '';
        $this->secretaryPosition = '';
        $this->directorPosition = '';
        $this->directorName = '';
        $this->solutionType = '';
        $this->meetingDate = '';
        $this->meetingPlace = '';
        $this->departmentName = '';
        $this->listCriteria = [];
        $this->availableCriteriaForSolution = [];
        $this->newCriteriaTitle = '';
        $this->newCriteriaType = '';
        $this->newCriteriaDeadline = '';
        $this->dispatch('closeEditSolutionModal');
    }

    public function openEditCertificateModal($certificateId)
    {
        $certificate = LicenseCertificate::find($certificateId);

        if (!$certificate) {
            toastr()->error('Сертификат не найден.');
            return;
        }

        $this->editingCertificateId = $certificate->id;
        $this->certificateTypeRu = $certificate->type_ru ?? '';
        $this->certificateTypeKk = $certificate->type_kk ?? '';

        $this->showEditCertificateModal = true;
    }

    public function updateCertificate()
    {
        $certificate = LicenseCertificate::find($this->editingCertificateId);

        if (!$certificate) {
            toastr()->error('Сертификат не найден.');
            return;
        }

        $certificate->update([
            'type_ru' => $this->certificateTypeRu,
            'type_kk' => $this->certificateTypeKk,
        ]);

        toastr()->success('Сертификат успешно обновлен.');
        $this->closeEditCertificateModal();

        // Reload application
        $this->loadApplication();
    }

    public function closeEditCertificateModal()
    {
        $this->showEditCertificateModal = false;
        $this->editingCertificateId = null;
        $this->certificateTypeRu = '';
        $this->certificateTypeKk = '';
        $this->dispatch('closeEditCertificateModal');
    }

    public function render()
    {
        return view('livewire.department.department-application-detail')
            ->layout(get_user_layout());
    }
}
