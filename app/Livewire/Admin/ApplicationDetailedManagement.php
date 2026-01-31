<?php

namespace App\Livewire\Admin;

use App\Constants\ApplicationStatusCategoryConstants;
use App\Models\Application;
use App\Models\ApplicationCriteriaDeadline;
use App\Models\ApplicationCriterion;
use App\Models\ApplicationDocument;
use App\Models\ApplicationInitialReport;
use App\Models\ApplicationReport;
use App\Models\ApplicationSolution;
use App\Models\ApplicationStatus;
use App\Models\ApplicationStatusCategory;
use App\Models\CategoryDocument;
use App\Models\LicenceRequirement;
use App\Models\LicenseCertificate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ApplicationDetailedManagement extends Component
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
    public $reportsByCriteria = [];

    public $initialReportsByCriteria = [];

    public $departmentReports = [];

    public $solutions = [];

    public $licenseCertificates = [];

    // Permissions
    #[Locked]
    public $canView = false;

    #[Locked]
    public $canManage = false;

    // Document info/edit modal
    public $showDocumentEditModal = false;
    public $editingDocument = null;
    public $editDocTitle = '';
    public $editDocInfo = '';
    public $editDocIsFirstPassed = null;
    public $editDocIsIndustryPassed = null;
    public $editDocIsFinalPassed = null;
    public $editDocFirstComment = '';
    public $editDocIndustryComment = '';
    public $editDocControlComment = '';

    // Criterion edit modal
    public $showCriterionEditModal = false;
    public $editingCriterion = null;
    public $editCriterionStatusId = null;
    public $editCriterionIsFirstPassed = null;
    public $editCriterionIsIndustryPassed = null;
    public $editCriterionIsFinalPassed = null;
    public $editCriterionFirstComment = '';
    public $editCriterionIndustryComment = '';
    public $editCriterionFinalComment = '';
    public $editCriterionLastComment = '';

    // Application status edit modal
    public $showApplicationEditModal = false;
    public $editApplicationCategoryId = null;

    // Reference data
    public $applicationStatuses = [];
    public $applicationStatusCategories = [];

    public function mount($application_id)
    {
        $this->authorize('view-full-application');

        $user = Auth::user();
        $this->canView = $user->can('view-full-application');
        $this->canManage = $user->can('manage-full-application');

        $this->applicationId = $application_id;
        $this->loadApplication();

        if (! $this->application) {
            abort(404);
        }

        $this->loadTabsAndRequirements();
        $this->loadReferenceData();
    }

    private function loadReferenceData()
    {
        $this->applicationStatuses = ApplicationStatus::orderBy('id')->get();
        $this->applicationStatusCategories = ApplicationStatusCategory::orderBy('id')->get();
    }

    private function loadApplication()
    {
        try {
            $this->application = Application::find($this->applicationId);

            if (! $this->application) {
                return;
            }

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

        // Admin sees ALL criteria (not filtered by role like department)
        $this->criteriaTabs = $this->application->application_criteria
            ->filter(function ($criterion) {
                return $criterion->category_document !== null;
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

            // Load license certificates
            $this->loadLicenseCertificates();
        }
    }

    private function loadReportsForAllCriteria()
    {
        if (! $this->application) {
            return;
        }

        $criteriaIds = [];
        foreach ($this->criteriaTabs as $tab) {
            foreach ($tab['criteria'] as $criterion) {
                $criteriaIds[] = $criterion->id;
            }
        }

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

    private function loadInitialReportsForAllCriteria()
    {
        if (! $this->application) {
            return;
        }

        $criteriaIds = [];
        foreach ($this->criteriaTabs as $tab) {
            foreach ($tab['criteria'] as $criterion) {
                $criteriaIds[] = $criterion->id;
            }
        }

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

    private function loadLicenseCertificates()
    {
        if (! $this->application) {
            return;
        }

        if ($this->application->category_id == ApplicationStatusCategoryConstants::APPROVED_ID) {
            $this->licenseCertificates = LicenseCertificate::where('application_id', $this->application->id)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $this->licenseCertificates = [];
        }
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
        $this->loadLicenceRequirements();
    }

    public function getUploadedDocumentsForRequirement($documentId)
    {
        try {
            if (! $this->application || ! $this->activeTab) {
                return collect();
            }

            $documents = ApplicationDocument::with(['document', 'user'])
                ->where('application_id', $this->application->id)
                ->where('document_id', $documentId)
                ->where('category_id', $this->activeTab)
                ->orderBy('created_at', 'desc')
                ->get();

            return $documents;
        } catch (\Exception $e) {
            return collect();
        }
    }

    // ==================== DOCUMENT EDIT ====================

    public function openDocumentEditModal($documentId)
    {
        if (!$this->canManage) {
            toastr()->error('У вас нет прав для редактирования.');
            return;
        }

        $this->editingDocument = ApplicationDocument::find($documentId);
        if (!$this->editingDocument) {
            toastr()->error('Документ не найден.');
            return;
        }

        $this->editDocTitle = $this->editingDocument->title ?? '';
        $this->editDocInfo = $this->editingDocument->info ?? '';
        $this->editDocIsFirstPassed = $this->editingDocument->is_first_passed;
        $this->editDocIsIndustryPassed = $this->editingDocument->is_industry_passed;
        $this->editDocIsFinalPassed = $this->editingDocument->is_final_passed;
        $this->editDocFirstComment = $this->editingDocument->first_comment ?? '';
        $this->editDocIndustryComment = $this->editingDocument->industry_comment ?? '';
        $this->editDocControlComment = $this->editingDocument->control_comment ?? '';

        $this->showDocumentEditModal = true;
    }

    public function closeDocumentEditModal()
    {
        $this->showDocumentEditModal = false;
        $this->editingDocument = null;
        $this->reset([
            'editDocTitle', 'editDocInfo', 'editDocIsFirstPassed',
            'editDocIsIndustryPassed', 'editDocIsFinalPassed',
            'editDocFirstComment', 'editDocIndustryComment', 'editDocControlComment'
        ]);
    }

    public function saveDocument()
    {
        if (!$this->canManage) {
            toastr()->error('У вас нет прав для редактирования.');
            return;
        }

        if (!$this->editingDocument) {
            toastr()->error('Документ не найден.');
            return;
        }

        try {
            // Convert empty strings to null for nullable fields
            $isFirstPassed = $this->editDocIsFirstPassed === '' || $this->editDocIsFirstPassed === null
                ? null
                : (bool) $this->editDocIsFirstPassed;
            $isIndustryPassed = $this->editDocIsIndustryPassed === '' || $this->editDocIsIndustryPassed === null
                ? null
                : (bool) $this->editDocIsIndustryPassed;
            $isFinalPassed = $this->editDocIsFinalPassed === '' || $this->editDocIsFinalPassed === null
                ? null
                : (bool) $this->editDocIsFinalPassed;

            $this->editingDocument->update([
                'title' => $this->editDocTitle ?: null,
                'info' => $this->editDocInfo ?: null,
                'is_first_passed' => $isFirstPassed,
                'is_industry_passed' => $isIndustryPassed,
                'is_final_passed' => $isFinalPassed,
                'first_comment' => $this->editDocFirstComment ?: null,
                'industry_comment' => $this->editDocIndustryComment ?: null,
                'control_comment' => $this->editDocControlComment ?: null,
            ]);

            Log::info("Admin updated document #{$this->editingDocument->id}", [
                'admin_id' => Auth::id(),
                'document_id' => $this->editingDocument->id,
            ]);

            $this->closeDocumentEditModal();
            $this->loadUploadedDocuments();

            toastr()->success('Документ успешно обновлен.');
        } catch (\Exception $e) {
            Log::error('Error updating document: ' . $e->getMessage());
            toastr()->error('Ошибка при обновлении документа: ' . $e->getMessage());
        }
    }

    // ==================== CRITERION EDIT ====================

    public function openCriterionEditModal($criterionId)
    {
        if (!$this->canManage) {
            toastr()->error('У вас нет прав для редактирования.');
            return;
        }

        $this->editingCriterion = ApplicationCriterion::with('application_status')->find($criterionId);
        if (!$this->editingCriterion) {
            toastr()->error('Критерий не найден.');
            return;
        }

        $this->editCriterionStatusId = $this->editingCriterion->status_id;
        $this->editCriterionIsFirstPassed = $this->editingCriterion->is_first_passed;
        $this->editCriterionIsIndustryPassed = $this->editingCriterion->is_industry_passed;
        $this->editCriterionIsFinalPassed = $this->editingCriterion->is_final_passed;
        $this->editCriterionFirstComment = $this->editingCriterion->first_comment ?? '';
        $this->editCriterionIndustryComment = $this->editingCriterion->industry_comment ?? '';
        $this->editCriterionFinalComment = $this->editingCriterion->final_comment ?? '';
        $this->editCriterionLastComment = $this->editingCriterion->last_comment ?? '';

        $this->showCriterionEditModal = true;
    }

    public function closeCriterionEditModal()
    {
        $this->showCriterionEditModal = false;
        $this->editingCriterion = null;
        $this->reset([
            'editCriterionStatusId', 'editCriterionIsFirstPassed',
            'editCriterionIsIndustryPassed', 'editCriterionIsFinalPassed',
            'editCriterionFirstComment', 'editCriterionIndustryComment',
            'editCriterionFinalComment', 'editCriterionLastComment'
        ]);
    }

    public function saveCriterion()
    {
        if (!$this->canManage) {
            toastr()->error('У вас нет прав для редактирования.');
            return;
        }

        if (!$this->editingCriterion) {
            toastr()->error('Критерий не найден.');
            return;
        }

        try {
            // Convert empty strings to null for nullable fields
            $isFirstPassed = $this->editCriterionIsFirstPassed === '' || $this->editCriterionIsFirstPassed === null
                ? null
                : (bool) $this->editCriterionIsFirstPassed;
            $isIndustryPassed = $this->editCriterionIsIndustryPassed === '' || $this->editCriterionIsIndustryPassed === null
                ? null
                : (bool) $this->editCriterionIsIndustryPassed;
            $isFinalPassed = $this->editCriterionIsFinalPassed === '' || $this->editCriterionIsFinalPassed === null
                ? null
                : (bool) $this->editCriterionIsFinalPassed;

            $this->editingCriterion->update([
                'status_id' => $this->editCriterionStatusId ?: null,
                'is_first_passed' => $isFirstPassed,
                'is_industry_passed' => $isIndustryPassed,
                'is_final_passed' => $isFinalPassed,
                'first_comment' => $this->editCriterionFirstComment ?: null,
                'industry_comment' => $this->editCriterionIndustryComment ?: null,
                'final_comment' => $this->editCriterionFinalComment ?: null,
                'last_comment' => $this->editCriterionLastComment ?: null,
            ]);

            Log::info("Admin updated criterion #{$this->editingCriterion->id}", [
                'admin_id' => Auth::id(),
                'criterion_id' => $this->editingCriterion->id,
                'new_status_id' => $this->editCriterionStatusId,
            ]);

            $this->closeCriterionEditModal();
            $this->loadApplication();
            $this->loadTabsAndRequirements();

            toastr()->success('Критерий успешно обновлен.');
        } catch (\Exception $e) {
            Log::error('Error updating criterion: ' . $e->getMessage());
            toastr()->error('Ошибка при обновлении критерия: ' . $e->getMessage());
        }
    }

    // ==================== APPLICATION STATUS EDIT ====================

    public function openApplicationEditModal()
    {
        if (!$this->canManage) {
            toastr()->error('У вас нет прав для редактирования.');
            return;
        }

        $this->editApplicationCategoryId = $this->application->category_id;
        $this->showApplicationEditModal = true;
    }

    public function closeApplicationEditModal()
    {
        $this->showApplicationEditModal = false;
        $this->editApplicationCategoryId = null;
    }

    public function saveApplication()
    {
        if (!$this->canManage) {
            toastr()->error('У вас нет прав для редактирования.');
            return;
        }

        try {
            $this->application->update([
                'category_id' => $this->editApplicationCategoryId,
            ]);

            Log::info("Admin updated application #{$this->application->id} category", [
                'admin_id' => Auth::id(),
                'application_id' => $this->application->id,
                'new_category_id' => $this->editApplicationCategoryId,
            ]);

            $this->closeApplicationEditModal();
            $this->loadApplication();

            toastr()->success('Статус заявки успешно обновлен.');
        } catch (\Exception $e) {
            Log::error('Error updating application: ' . $e->getMessage());
            toastr()->error('Ошибка при обновлении заявки: ' . $e->getMessage());
        }
    }

    // ==================== HELPER METHODS ====================

    public function downloadDocument($fileUrl)
    {
        try {
            if (Storage::disk('public')->exists($fileUrl)) {
                return Storage::disk('public')->download($fileUrl);
            }

            toastr()->error('Файл не найден.');
        } catch (\Exception $e) {
            toastr()->error('Ошибка при скачивании файла: '.$e->getMessage());
        }
    }

    public function getApplicationStatusColor($statusValue)
    {
        $colors = [
            'draft' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
            'in-review' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
            'revision' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
            'approved' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
            'partially-approved' => 'bg-lime-100 text-lime-800 dark:bg-lime-900 dark:text-lime-300',
            'revoked' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
            'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
        ];

        return $colors[$statusValue] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
    }

    public function getCriterionStatusColor($criterion)
    {
        if ($criterion->is_first_passed === false || $criterion->is_industry_passed === false || $criterion->is_final_passed === false) {
            return 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
        } elseif ($criterion->is_first_passed === null || $criterion->is_industry_passed === null || $criterion->is_final_passed === null) {
            return 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300';
        } else {
            return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
        }
    }

    public function getCriterionStatusColorByValue($statusValue)
    {
        $colors = [
            'draft' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
            'awaiting-first-check' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
            'first-check-revision' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
            'awaiting-industry-check' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
            'industry-check-revision' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300',
            'awaiting-control-check' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300',
            'control-check-revision' => 'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-300',
            'awaiting-final-decision' => 'bg-cyan-100 text-cyan-800 dark:bg-cyan-900 dark:text-cyan-300',
            'fully-approved' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
            'partially-approved' => 'bg-lime-100 text-lime-800 dark:bg-lime-900 dark:text-lime-300',
            'revoked' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
        ];

        return $colors[$statusValue] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
    }

    public function getFinalDecisionStats()
    {
        if (! $this->application) {
            return ['total' => 0, 'awaiting' => 0, 'decided' => 0];
        }

        $allCriteria = ApplicationCriterion::with('application_status')
            ->where('application_id', $this->application->id)
            ->get();

        $total = $allCriteria->count();
        $awaiting = $allCriteria->filter(function ($c) {
            return $c->application_status && $c->application_status->value === 'awaiting-final-decision';
        })->count();

        $decided = $allCriteria->filter(function ($c) {
            return $c->application_status && in_array($c->application_status->value, [
                'fully-approved', 'partially-approved', 'revoked'
            ]);
        })->count();

        return ['total' => $total, 'awaiting' => $awaiting, 'decided' => $decided];
    }

    public function render()
    {
        return view('livewire.admin.application-detailed-management')->layout(get_user_layout());
    }
}
