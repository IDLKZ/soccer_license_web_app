<?php

namespace App\Livewire\Department;

use App\Constants\ApplicationStatusCategoryConstants;
use App\Constants\ApplicationStatusConstants;
use App\Models\Application;
use App\Models\ApplicationCriterion;
use App\Models\ApplicationDocument;
use App\Models\ApplicationStatus;
use App\Models\ApplicationStep;
use App\Models\CategoryDocument;
use App\Models\LicenceRequirement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

    public function mount($application_id)
    {
        $this->applicationId = $application_id;
        $this->loadApplication();

        if (!$this->application) {
            abort(404);
        }
        $this->loadTabsAndRequirements();
    }

    private function loadApplication()
    {
        try {
            $this->application = Application::find($this->applicationId);

            if (!$this->application) {
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
                'documents'
            ]);

            $this->licence = $this->application->licence;
            $this->club = $this->application->club;
            $this->user = $this->application->user;
        } catch (\Exception $e) {
            Log::error('Error loading application: ' . $e->getMessage());
            $this->application = null;
        }
    }

    private function loadTabsAndRequirements()
    {
        if (!$this->application) return;

        $user = Auth::user();
        $userRole = $user->role ? $user->role->value : null;

        // Get criteria that user can see based on roles (3.1 requirement)
        $this->criteriaTabs = $this->application->application_criteria
            ->filter(function($criterion) use ($userRole) {
                if (!$criterion->category_document) {
                    return false;
                }
                $category = $criterion->category_document;
                $categoryRoles = $category->roles ?? [];

                // Department users must have their role in the allowed roles
                return !empty($categoryRoles) && $userRole && in_array($userRole, $categoryRoles);
            })
            ->groupBy('category_id')
            ->map(function($criteria, $categoryId) {
                $category = CategoryDocument::find($categoryId);
                return [
                    'category' => $category,
                    'criteria' => $criteria,
                    'title' => $category->title_ru ?? 'Категория'
                ];
            })
            ->values()
            ->toArray();

        // Set first tab as active if exists
        if (!empty($this->criteriaTabs)) {
            $firstTab = reset($this->criteriaTabs);
            $this->activeTab = $firstTab['category']->id;
            $this->loadLicenceRequirements();
        }
    }

    private function loadLicenceRequirements()
    {
        if (!$this->activeTab) return;

        $this->licenceRequirementsByCategory = LicenceRequirement::with(['document'])
            ->where('licence_id', $this->application->license_id)
            ->where('category_id', $this->activeTab)
            ->get()
            ->groupBy('document_id')
            ->map(function($requirements) {
                return [
                    'document' => $requirements->first()->document,
                    'requirements' => $requirements->toArray()
                ];
            })
            ->toArray();

        // Load uploaded documents for current category
        $this->loadUploadedDocuments();
    }

    private function loadUploadedDocuments()
    {
        if (!$this->activeTab || !$this->application) return;

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
        if (!$criterion || !$criterion->application_status) {
            return false;
        }

        $user = Auth::user();
        $userRole = $user->role ? $user->role->value : null;

        $status = $criterion->application_status;
        $roleValues = $status->role_values ?? [];

        return $userRole && in_array($userRole, $roleValues);
    }

    // Set temporary review decision for a document
    public function setReviewDecision($documentId, $decision, $comment = '')
    {
        $this->reviewDecisions[$documentId] = [
            'decision' => $decision, // true = accept, false = reject
            'comment' => $comment
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
        if (!$this->rejectComment) {
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
        if (!$criterion) {
            return false;
        }

        $documents = ApplicationDocument::where('application_id', $this->application->id)
            ->where('category_id', $criterion->category_id)
            ->get();

        $statusValue = $criterion->application_status->value ?? null;

        // Filter documents based on stage requirements
        if ($statusValue === 'awaiting-first-check') {
            // 2.1: Check documents where is_first_passed == null AND is_industry_passed == null AND is_final_passed == null
            $documentsToReview = $documents->filter(function($doc) {
                return $doc->is_first_passed === null &&
                       $doc->is_industry_passed === null &&
                       $doc->is_final_passed === null;
            });
        } elseif ($statusValue === 'awaiting-industry-check') {
            // 2.2: Check documents where is_first_passed == true AND is_industry_passed == null AND is_final_passed == null
            $documentsToReview = $documents->filter(function($doc) {
                return $doc->is_first_passed === true &&
                       $doc->is_industry_passed === null &&
                       $doc->is_final_passed === null;
            });
        } elseif ($statusValue === 'awaiting-control-check') {
            // 2.3: Check documents where is_first_passed == true AND is_industry_passed == true AND is_final_passed == null
            $documentsToReview = $documents->filter(function($doc) {
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
            if (!isset($this->reviewDecisions[$doc->id])) {
                return false;
            }
        }

        return true;
    }

    // Submit First Check (2.1)
    public function submitFirstCheck($criterionId, $decision)
    {
        $criterion = ApplicationCriterion::with('application_status')->find($criterionId);

        if (!$criterion || !$this->canReviewCriterion($criterion)) {
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
        $documentsToReview = $documents->filter(function($doc) {
            return $doc->is_first_passed === null &&
                   $doc->is_industry_passed === null &&
                   $doc->is_final_passed === null;
        });

        foreach ($documentsToReview as $doc) {
            if (!isset($this->reviewDecisions[$doc->id])) {
                toastr()->error('Необходимо принять решение по всем новым документам.');
                return;
            }
        }

        try {
            DB::beginTransaction();

            $user = Auth::user();
            $userName = trim(($user->last_name ?? '') . ' ' . ($user->first_name ?? '') . ' ' . ($user->patronymic ?? ''));

            $allPassed = true;

            // Update ONLY documents that need first check (is_first_passed === null)
            foreach ($documentsToReview as $doc) {
                if (isset($this->reviewDecisions[$doc->id])) {
                    $reviewDecision = $this->reviewDecisions[$doc->id];

                    $doc->update([
                        'is_first_passed' => $reviewDecision['decision'],
                        'first_comment' => $reviewDecision['comment'] ?? null,
                        'first_checked_by_id' => $user->id,
                        'first_checked_by' => $userName
                    ]);

                    if (!$reviewDecision['decision']) {
                        $allPassed = false;
                    }
                }
            }

            // Check if there are any previously failed documents
            $previouslyFailed = $documents->filter(function($doc) {
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
            } else {
                // Move to industry check
                $newStatus = ApplicationStatus::where('value', ApplicationStatusConstants::AWAITING_INDUSTRY_CHECK_VALUE)->first();
                $passed = $allPassed;
            }

            // Update criterion
            $criterion->update([
                'status_id' => $newStatus->id,
                'is_first_passed' => $passed,
                'first_comment' => $this->criterionComment,
                'first_checked_by_id' => $user->id,
                'first_checked_by' => $userName
            ]);

            // Log to application_steps
            ApplicationStep::create([
                'application_id' => $this->application->id,
                'application_criteria_id' => $criterion->id,
                'status_id' => $newStatus->id,
                'responsible_id' => $user->id,
                'responsible_by' => $userName,
                'is_passed' => $passed,
                'result' => $this->criterionComment
            ]);

            DB::commit();

            toastr()->success('Первичная проверка завершена.');
            $this->reviewDecisions = [];
            $this->criterionComment = '';
            $this->loadApplication();
            $this->loadTabsAndRequirements();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error submitting first check: ' . $e->getMessage());
            toastr()->error('Ошибка при сохранении проверки.');
        }
    }

    // Submit Industry Check (2.2)
    public function submitIndustryCheck($criterionId, $decision)
    {
        $criterion = ApplicationCriterion::with('application_status')->find($criterionId);

        if (!$criterion || !$this->canReviewCriterion($criterion)) {
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
        $documentsToReview = $documents->filter(function($doc) {
            return $doc->is_first_passed === true &&
                   $doc->is_industry_passed === null &&
                   $doc->is_final_passed === null;
        });

        foreach ($documentsToReview as $doc) {
            if (!isset($this->reviewDecisions[$doc->id])) {
                toastr()->error('Необходимо принять решение по всем новым документам.');
                return;
            }
        }

        try {
            DB::beginTransaction();

            $user = Auth::user();
            $userName = trim(($user->last_name ?? '') . ' ' . ($user->first_name ?? '') . ' ' . ($user->patronymic ?? ''));

            $allPassed = true;

            // Update ONLY documents that need industry check
            foreach ($documentsToReview as $doc) {
                if (isset($this->reviewDecisions[$doc->id])) {
                    $reviewDecision = $this->reviewDecisions[$doc->id];

                    $doc->update([
                        'is_industry_passed' => $reviewDecision['decision'],
                        'industry_comment' => $reviewDecision['comment'] ?? null,
                        'checked_by_id' => $user->id,
                        'checked_by' => $userName
                    ]);

                    if (!$reviewDecision['decision']) {
                        $allPassed = false;
                    }
                }
            }

            // Check if there are any previously failed documents
            $previouslyFailed = $documents->filter(function($doc) {
                return $doc->is_industry_passed === false;
            });

            if ($previouslyFailed->count() > 0) {
                $allPassed = false;
            }

            if ($decision === 'revision') {
                $newStatus = ApplicationStatus::where('value', ApplicationStatusConstants::INDUSTRY_CHECK_REVISION_VALUE)->first();
                $passed = false;
            } else {
                $newStatus = ApplicationStatus::where('value', ApplicationStatusConstants::AWAITING_CONTROL_CHECK_VALUE)->first();
                $passed = $allPassed;
            }

            $criterion->update([
                'status_id' => $newStatus->id,
                'is_industry_passed' => $passed,
                'industry_comment' => $this->criterionComment,
                'checked_by_id' => $user->id,
                'checked_by' => $userName
            ]);

            ApplicationStep::create([
                'application_id' => $this->application->id,
                'application_criteria_id' => $criterion->id,
                'status_id' => $newStatus->id,
                'responsible_id' => $user->id,
                'responsible_by' => $userName,
                'is_passed' => $passed,
                'result' => $this->criterionComment
            ]);

            DB::commit();

            toastr()->success('Отраслевая проверка завершена.');
            $this->reviewDecisions = [];
            $this->criterionComment = '';
            $this->loadApplication();
            $this->loadTabsAndRequirements();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error submitting industry check: ' . $e->getMessage());
            toastr()->error('Ошибка при сохранении проверки.');
        }
    }

    // Submit Control Check (2.3)
    public function submitControlCheck($criterionId, $decision)
    {
        $criterion = ApplicationCriterion::with('application_status')->find($criterionId);

        if (!$criterion || !$this->canReviewCriterion($criterion)) {
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
        $documentsToReview = $documents->filter(function($doc) {
            return $doc->is_first_passed === true &&
                   $doc->is_industry_passed === true &&
                   $doc->is_final_passed === null;
        });

        foreach ($documentsToReview as $doc) {
            if (!isset($this->reviewDecisions[$doc->id])) {
                toastr()->error('Необходимо принять решение по всем новым документам.');
                return;
            }
        }

        try {
            DB::beginTransaction();

            $user = Auth::user();
            $userName = trim(($user->last_name ?? '') . ' ' . ($user->first_name ?? '') . ' ' . ($user->patronymic ?? ''));

            $allPassed = true;

            // Update ONLY documents that need control check
            foreach ($documentsToReview as $doc) {
                if (isset($this->reviewDecisions[$doc->id])) {
                    $reviewDecision = $this->reviewDecisions[$doc->id];

                    $doc->update([
                        'is_final_passed' => $reviewDecision['decision'],
                        'control_comment' => $reviewDecision['comment'] ?? null,
                        'control_checked_by_id' => $user->id,
                        'control_checked_by' => $userName
                    ]);

                    if (!$reviewDecision['decision']) {
                        $allPassed = false;
                    }
                }
            }

            // Check if there are any previously failed documents
            $previouslyFailed = $documents->filter(function($doc) {
                return $doc->is_final_passed === false;
            });

            if ($previouslyFailed->count() > 0) {
                $allPassed = false;
            }

            if ($decision === 'revision') {
                $newStatus = ApplicationStatus::where('value', ApplicationStatusConstants::CONTROL_CHECK_REVISION_VALUE)->first();
                $passed = false;
            } else {
                $newStatus = ApplicationStatus::where('value', ApplicationStatusConstants::AWAITING_FINAL_DECISION_VALUE)->first();
                $passed = $allPassed;
            }

            $criterion->update([
                'status_id' => $newStatus->id,
                'is_final_passed' => $passed,
                'final_comment' => $this->criterionComment,
                'control_checked_by_id' => $user->id,
                'control_checked_by' => $userName
            ]);

            ApplicationStep::create([
                'application_id' => $this->application->id,
                'application_criteria_id' => $criterion->id,
                'status_id' => $newStatus->id,
                'responsible_id' => $user->id,
                'responsible_by' => $userName,
                'is_passed' => $passed,
                'result' => $this->criterionComment
            ]);

            DB::commit();

            toastr()->success('Контрольная проверка завершена.');
            $this->reviewDecisions = [];
            $this->criterionComment = '';
            $this->loadApplication();
            $this->loadTabsAndRequirements();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error submitting control check: ' . $e->getMessage());
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

        $awaitingFinal = $allCriteria->filter(function($c) {
            return $c->application_status && $c->application_status->value === ApplicationStatusConstants::AWAITING_FINAL_DECISION_VALUE;
        })->count();

        $total = $allCriteria->count();

        return ['awaiting' => $awaitingFinal, 'total' => $total];
    }

    // Open final decision modal (2.4.2)
    public function openFinalDecisionModal()
    {
        if (!$this->canMakeFinalDecision()) {
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
            if (!isset($this->finalCommentsByCriterion[$criterion['id']])) {
                $this->finalCommentsByCriterion[$criterion['id']] = '';
            }
            if (!isset($this->finalDecisionsByCriterion[$criterion['id']])) {
                $this->finalDecisionsByCriterion[$criterion['id']] = '';
            }
            if (!isset($this->reuploadDocumentIdsByCriterion[$criterion['id']])) {
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

            // Check comment
            if (empty($this->finalCommentsByCriterion[$criterion->id])) {
                toastr()->error('Необходимо указать комментарий по каждому критерию.');
                return;
            }

            // Check reupload documents for partially-approved
            if ($this->finalDecisionsByCriterion[$criterion->id] === 'partially-approved') {
                if (empty($this->reuploadDocumentIdsByCriterion[$criterion->id])) {
                    toastr()->error('Для частичного одобрения необходимо указать документы для повторной загрузки.');
                    return;
                }
            }
        }

        try {
            DB::beginTransaction();

            $user = Auth::user();
            $userName = trim(($user->last_name ?? '') . ' ' . ($user->first_name ?? '') . ' ' . ($user->patronymic ?? ''));

            // Update each criterion with its individual decision
            foreach ($allCriteria as $criterion) {
                $criterionDecision = $this->finalDecisionsByCriterion[$criterion->id];
                $criterionComment = $this->finalCommentsByCriterion[$criterion->id] ?? '';

                $statusValue = match($criterionDecision) {
                    'fully-approved' => ApplicationStatusConstants::FULLY_APPROVED_VALUE,
                    'partially-approved' => ApplicationStatusConstants::PARTIALLY_APPROVED_VALUE,
                    'revoked' => ApplicationStatusConstants::REVOKED_VALUE,
                    default => null
                };

                if (!$statusValue) {
                    toastr()->error('Неверное решение для критерия ' . $criterion->category_document->title_ru);
                    return;
                }

                $newStatus = ApplicationStatus::where('value', $statusValue)->first();

                $updateData = [
                    'status_id' => $newStatus->id,
                    'last_comment' => $criterionComment
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
                    'result' => $criterionComment
                ]);
            }

            DB::commit();

            toastr()->success('Финальное решение принято.');
            $this->closeFinalDecisionModal();
            $this->loadApplication();
            $this->loadTabsAndRequirements();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error submitting final decision: ' . $e->getMessage());
            toastr()->error('Ошибка при сохранении решения.');
        }
    }

    // Check if all criteria reached final status (2.4.3 condition)
    public function canChangeApplicationStatus()
    {
        $allCriteria = ApplicationCriterion::with('application_status')->where('application_id', $this->application->id)->get();

        return $allCriteria->every(function($c) {
            return $c->application_status && in_array($c->application_status->value, [
                ApplicationStatusConstants::FULLY_APPROVED_VALUE,
                ApplicationStatusConstants::PARTIALLY_APPROVED_VALUE,
                ApplicationStatusConstants::REVOKED_VALUE
            ]);
        });
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
        if (!$this->canChangeApplicationStatus()) {
            toastr()->error('Не все критерии имеют финальное решение.');
            return;
        }

        if (!$this->applicationFinalDecision) {
            toastr()->error('Необходимо выбрать решение.');
            return;
        }

        
        try {
            DB::beginTransaction();

            $categoryValue = match($this->applicationFinalDecision) {
                'approved' => ApplicationStatusCategoryConstants::APPROVED_VALUE,
                'revoked' => ApplicationStatusCategoryConstants::REVOKED_VALUE,
                default => null
            };

            if (!$categoryValue) {
                toastr()->error('Неверный статус.');
                return;
            }

            $category = \App\Models\ApplicationStatusCategory::where('value', $categoryValue)->first();

            // Update application
            $this->application->update([
                'category_id' => $category->id
            ]);

            DB::commit();

            toastr()->success('Статус заявки изменен.');
            $this->applicationFinalDecision = '';
            $this->loadApplication();
            $this->loadTabsAndRequirements();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error changing application status: ' . $e->getMessage());
            toastr()->error('Ошибка при изменении статуса.');
        }
    }

    // Upgrade criterion from partially-approved to fully-approved (2.4.4)
    public function upgradeCriterionToFullyApproved($criterionId)
    {
        $criterion = ApplicationCriterion::with('application_status')->find($criterionId);

        if (!$criterion) {
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
            $userName = trim(($user->last_name ?? '') . ' ' . ($user->first_name ?? '') . ' ' . ($user->patronymic ?? ''));

            $newStatus = ApplicationStatus::where('value', ApplicationStatusConstants::FULLY_APPROVED_VALUE)->first();

            $criterion->update([
                'status_id' => $newStatus->id,
                'can_reupload_after_ending' => false,
                'can_reupload_after_endings_doc_ids' => null
            ]);

            // Log step
            ApplicationStep::create([
                'application_id' => $this->application->id,
                'application_criteria_id' => $criterion->id,
                'status_id' => $newStatus->id,
                'responsible_id' => $user->id,
                'responsible_by' => $userName,
                'is_passed' => true,
                'result' => 'Критерий изменен с частично одобренного на полностью одобренный'
            ]);

            DB::commit();

            toastr()->success('Критерий изменен на полностью одобренный.');
            $this->loadApplication();
            $this->loadTabsAndRequirements();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error upgrading criterion: ' . $e->getMessage());
            toastr()->error('Ошибка при изменении статуса критерия.');
        }
    }

    public function getApplicationStatusColor($statusValue)
    {
        return match($statusValue) {
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
        if (!$criterion->is_ready) {
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
        } elseif ($criterion->is_industry_passed === null && !$hasFailures) {
            $hasPending = true;
        }

        if ($criterion->is_final_passed === false) {
            $hasFailures = true;
        } elseif ($criterion->is_final_passed === null && !$hasFailures) {
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

    public function getUploadedDocumentsForRequirement($documentId)
    {
        if (!isset($this->uploadedDocumentsByCategory[$documentId])) {
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
            'application.licence'
        ])->find($documentId);

        if (!$this->viewingDocument) {
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
        if (!$this->application || !$this->application->application_status_category) {
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

        if (!in_array($categoryValue, $allowedStatuses)) {
            return false;
        }

        // Check if user's role is in the category's role_values
        $user = auth()->user();
        if (!$user || !$user->role) {
            return false;
        }

        $categoryRoleValues = $this->application->application_status_category->role_values ?? [];

        // Decode if it's a string
        if (is_string($categoryRoleValues)) {
            $categoryRoleValues = json_decode($categoryRoleValues, true) ?? [];
        }

        return in_array($user->role->value, $categoryRoleValues);
    }

    public function openRejectApplicationModal()
    {
        if (!$this->canRejectApplication()) {
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
        if (!$this->canRejectApplication()) {
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
                    'last_comment' => $this->rejectApplicationComment ?: 'Заявка отклонена'
                ]);

            DB::commit();

            toastr()->success('Заявка успешно отклонена.');

            $this->closeRejectApplicationModal();

            // Reload application
            $this->loadApplication();
            $this->loadTabsAndRequirements();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error rejecting application: ' . $e->getMessage());
            toastr()->error('Произошла ошибка при отказе заявки.');
        }
    }

    public function render()
    {
        return view('livewire.department.department-application-detail')
            ->layout(get_user_layout());
    }
}
