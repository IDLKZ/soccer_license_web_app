<?php

namespace App\Livewire\Club;

use App\Constants\ApplicationStatusCategoryConstants;
use App\Constants\ApplicationStatusConstants;
use App\Models\Application;
use App\Models\ApplicationCriteriaDeadline;
use App\Models\ApplicationCriterion;
use App\Models\ApplicationDocument;
use App\Models\ApplicationStatus;
use App\Models\CategoryDocument;
use App\Models\LicenceRequirement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class MyApplicationDetail extends Component
{
    use WithFileUploads;

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
    public $canUpload = false;

    // Upload modal
    public $showUploadModal = false;

    public $showEditModal = false;

    public $selectedCriterion = null;

    public $selectedRequirement = null;

    public $editingDocument = null;

    // Document info modal
    public $showDocumentInfoModal = false;

    public $viewingDocument = null;

    // Upload form data
    #[Validate('required|file|max:10485760')] // Max 100MB as fallback
    public $uploadFile = null;

    #[Validate('required|string|max:255')]
    public $uploadTitle = '';

    #[Validate('nullable|string|max:1000')]
    public $uploadInfo = '';

    public function mount($application_id)
    {
        $this->applicationId = $application_id;
        $this->loadApplication();

        if (! $this->application) {
            abort(404);
        }

        $this->authorize('view-applications');
        $this->checkPermissions();
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
            \Log::error('Error loading application: '.$e->getMessage());
            $this->application = null;
        }
    }

    private function checkPermissions()
    {
        $authUser = Auth::user();
        // Check if user can view this specific application
        $userClubIds = $this->getUserClubIds();
        if (! in_array($this->application->club_id, $userClubIds)) {
            abort(403);
        }
    }

    private function getUserClubIds()
    {
        $user = Auth::user();
        $clubIds = [];

        if ($user->club_id) {
            $clubIds[] = $user->club_id;
        }

        // Get club teams for the user
        $clubTeams = \App\Models\ClubTeam::where('user_id', $user->id)->get();
        foreach ($clubTeams as $team) {
            if ($team->club_id && ! in_array($team->club_id, $clubIds)) {
                $clubIds[] = $team->club_id;
            }
        }

        return array_unique($clubIds);
    }

    private function loadTabsAndRequirements()
    {
        if (! $this->application) {
            return;
        }

        $user = Auth::user();
        $userRole = $user->role ? $user->role->value : null;

        // Get criteria that user can see based on roles
        $this->criteriaTabs = $this->application->application_criteria
            ->filter(function ($criterion) use ($userRole) {
                if (! $criterion->category_document) {
                    return false;
                }
                $category = $criterion->category_document;
                $categoryRoles = $category->roles ?? [];

                // Ensure categoryRoles is an array
                if (is_string($categoryRoles)) {
                    $categoryRoles = json_decode($categoryRoles, true) ?? [];
                }

                return empty($categoryRoles) || ($userRole && is_array($categoryRoles) && in_array($userRole, $categoryRoles));
            })
            ->groupBy('category_id')
            ->map(function ($criteria, $categoryId) {
                $category = CategoryDocument::find($categoryId);
                $firstCriterion = $criteria->first();

                return [
                    'category' => $category,
                    'criteria' => $criteria, // Keep as collection, not array
                    'title' => $category->title_ru ?? 'Категория',
                    'status' => $firstCriterion ? $firstCriterion->application_status : null,
                ];
            })
            ->values()
            ->toArray(); // Convert final collection to array

        // Set first tab as active if exists
        if (! empty($this->criteriaTabs)) {
            $firstTab = reset($this->criteriaTabs);
            $this->activeTab = $firstTab['category']->id;
            $this->loadLicenceRequirements();
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
                // Keep document as object, convert requirements to array
                return [
                    'document' => $requirements->first()->document,
                    'requirements' => $requirements->toArray(),
                ];
            })
            ->toArray(); // Convert final structure to array for Livewire

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

    public function canViewCategory($category)
    {
        if (! $category || ! $category->roles) {
            return true;
        }

        $user = Auth::user();
        $userRole = $user->role ? $user->role->value : null;

        $categoryRoles = $category->roles ?? [];

        // Ensure categoryRoles is an array
        if (is_string($categoryRoles)) {
            $categoryRoles = json_decode($categoryRoles, true) ?? [];
        }

        return $userRole && is_array($categoryRoles) && in_array($userRole, $categoryRoles);
    }

    public function canUploadDocuments($criterion)
    {
        if (! $criterion || ! $this->canUpload) {
            return false;
        }

        // Check if criterion status allows upload
        $applicationStatus = $criterion->application_status;
        if (! $applicationStatus) {
            return false;
        }

        $statusValue = $applicationStatus->value ?? null;
        $allowedStatuses = [
            'awaiting-documents',
            'first-check-revision',
            'industry-check-revision',
            'control-check-revision',
            'partially-approved',
        ];

        return in_array($statusValue, $allowedStatuses);
    }

    public function getDocumentsForRequirement($requirement)
    {
        if (! $this->application || ! $requirement) {
            return collect();
        }

        $documentId = is_object($requirement) ? $requirement->document_id : $requirement['document_id'];

        return $this->application->documents
            ->where('pivot.document_id', $documentId)
            ->where('pivot.category_id', $this->activeTab);
    }

    public function openUploadModal($criterionId, $requirementId)
    {
        // Check date restrictions first
        if (! $this->isWithinValidPeriod()) {
            toastr()->error($this->getDateRestrictionMessage());

            return;
        }

        // Find criterion by ID directly
        $criterion = ApplicationCriterion::with(['category_document', 'application_status', 'application_criteria_deadlines'])
            ->find($criterionId);

        if (! $criterion) {
            toastr()->error('Критерий не найден.');

            return;
        }

        // Check deadline restrictions
        $deadlineCheck = $this->checkCriterionDeadline($criterion);
        if (! $deadlineCheck['allowed']) {
            toastr()->error($deadlineCheck['message']);

            return;
        }

        // Find requirement by ID
        $requirement = LicenceRequirement::with(['document'])->find($requirementId);

        if (! $requirement) {
            toastr()->error('Требование не найдено.');

            return;
        }

        // Check if user can upload for this criterion and specific document
        if (! $this->canUploadForCriterion($criterion, $requirement->document_id)) {
            toastr()->error('Загрузка документов для этого критерия недоступна.');

            return;
        }

        $this->resetUploadForm();
        // Store as arrays for Livewire compatibility
        $this->selectedCriterion = $criterion->toArray();
        $this->selectedRequirement = $requirement->toArray();
        $this->showUploadModal = true;
    }

    public function closeUploadModal()
    {
        $this->showUploadModal = false;
        $this->resetUploadForm();
    }

    public function openEditModal($documentId)
    {
        $this->editingDocument = ApplicationDocument::find($documentId);

        if (! $this->editingDocument) {
            toastr()->error('Документ не найден.');

            return;
        }

        // Check if document can be edited
        if (! $this->canEditDocument($this->editingDocument)) {
            toastr()->error('Редактирование этого документа недоступно.');

            return;
        }

        $this->uploadTitle = $this->editingDocument->title;
        $this->uploadInfo = $this->editingDocument->info ?? '';
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->editingDocument = null;
        $this->resetUploadForm();
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

    private function resetUploadForm()
    {
        $this->uploadFile = null;
        $this->uploadTitle = '';
        $this->uploadInfo = '';
        $this->selectedCriterion = null;
        $this->selectedRequirement = null;
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

    public function uploadDocument()
    {
        // Check date restrictions first
        if (! $this->isWithinValidPeriod()) {
            toastr()->error($this->getDateRestrictionMessage());

            return;
        }

        // Validate basic fields
        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            toastr()->error('Пожалуйста, заполните все обязательные поля.');

            return;
        }

        if (! $this->selectedRequirement || ! $this->selectedCriterion) {
            toastr()->error('Выберите документ для загрузки.');

            return;
        }

        if (! $this->uploadFile) {
            toastr()->error('Пожалуйста, выберите файл для загрузки.');

            return;
        }

        // Get values from arrays
        $maxSizeMb = $this->selectedRequirement['max_file_size_mb'] ?? 10;
        $maxSizeKb = $maxSizeMb * 1024;

        if ($this->uploadFile->getSize() > ($maxSizeKb * 1024)) {
            toastr()->error("Размер файла превышает максимально допустимый ({$maxSizeMb} МБ).");

            return;
        }

        // Validate file extension
        $allowedExtensions = $this->selectedRequirement['allowed_extensions'] ?? [];

        // Handle if allowed_extensions is a string (JSON)
        if (is_string($allowedExtensions)) {
            $allowedExtensions = json_decode($allowedExtensions, true) ?? [];
        }

        $fileExtension = strtolower($this->uploadFile->getClientOriginalExtension());

        // Check extension (allowed extensions may have dots like ".pdf" or just "pdf")
        if (! empty($allowedExtensions) && is_array($allowedExtensions)) {
            $extensionValid = false;
            foreach ($allowedExtensions as $allowed) {
                $allowed = strtolower(ltrim($allowed, '.'));
                if ($fileExtension === $allowed) {
                    $extensionValid = true;
                    break;
                }
            }

            if (! $extensionValid) {
                toastr()->error('Недопустимый формат файла. Разрешенные форматы: '.implode(', ', $allowedExtensions));

                return;
            }
        }

        try {
            DB::beginTransaction();

            // Generate unique filename
            $filename = time().'_'.uniqid().'.'.$fileExtension;
            $path = 'applications/'.$this->application->id.'/'.$filename;

            // Store file
            $this->uploadFile->storeAs('applications/'.$this->application->id, $filename, 'public');

            // Get authenticated user info
            $user = Auth::user();
            $uploadedBy = trim(
                ($user->last_name ?? '').' '.
                ($user->first_name ?? '').' '.
                ($user->patronymic ?? '')
            );

            // Create application document
            ApplicationDocument::create([
                'application_id' => $this->application->id,
                'category_id' => $this->selectedCriterion['category_id'],
                'document_id' => $this->selectedRequirement['document_id'],
                'file_url' => $path,
                'uploaded_by_id' => $user->id,
                'uploaded_by' => $uploadedBy,
                'title' => $this->uploadTitle,
                'info' => $this->uploadInfo,
                'is_first_passed' => null,
                'is_industry_passed' => null,
                'is_final_passed' => null,
            ]);

            DB::commit();

            toastr()->success('Документ успешно загружен.');
            $this->closeUploadModal();
            $this->loadUploadedDocuments();
            $this->loadApplication();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error uploading document: '.$e->getMessage());
            Log::error('Stack trace: '.$e->getTraceAsString());
            toastr()->error('Ошибка при загрузке документа: '.$e->getMessage());
        }
    }

    public function updateDocument()
    {
        // Check date restrictions first
        if (! $this->isWithinValidPeriod()) {
            toastr()->error($this->getDateRestrictionMessage());

            return;
        }

        $this->validate([
            'uploadTitle' => 'required|string|max:255',
            'uploadInfo' => 'nullable|string|max:1000',
        ]);

        if (! $this->editingDocument) {
            toastr()->error('Документ не найден.');

            return;
        }

        if (! $this->canEditDocument($this->editingDocument)) {
            toastr()->error('Редактирование недоступно.');

            return;
        }

        try {
            DB::beginTransaction();

            // Update only title and info
            $this->editingDocument->update([
                'title' => $this->uploadTitle,
                'info' => $this->uploadInfo,
            ]);

            // If file is uploaded, replace it
            if ($this->uploadFile) {
                // Get requirement for validation
                $requirement = LicenceRequirement::where('licence_id', $this->application->license_id)
                    ->where('category_id', $this->editingDocument->category_id)
                    ->where('document_id', $this->editingDocument->document_id)
                    ->first();

                if ($requirement) {
                    // Validate new file
                    $maxSizeMb = $requirement->max_file_size_mb ?? 10;
                    $maxSizeKb = $maxSizeMb * 1024;

                    if ($this->uploadFile->getSize() > ($maxSizeKb * 1024)) {
                        session()->flash('error', "Размер файла превышает максимально допустимый ({$maxSizeMb} МБ).");

                        return;
                    }

                    $allowedExtensions = $requirement->allowed_extensions ?? [];
                    $fileExtension = strtolower($this->uploadFile->getClientOriginalExtension());

                    if (! empty($allowedExtensions) && ! in_array($fileExtension, $allowedExtensions)) {
                        toastr()->error('Недопустимый формат файла. Разрешенные форматы: '.implode(', ', $allowedExtensions));

                        return;
                    }

                    // Delete old file
                    if (Storage::disk('public')->exists($this->editingDocument->file_url)) {
                        Storage::disk('public')->delete($this->editingDocument->file_url);
                    }

                    // Upload new file
                    $filename = time().'_'.uniqid().'.'.$fileExtension;
                    $path = 'applications/'.$this->application->id.'/'.$filename;
                    $this->uploadFile->storeAs('applications/'.$this->application->id, $filename, 'public');

                    $this->editingDocument->update(['file_url' => $path]);
                }
            }

            DB::commit();

            toastr()->success('Документ успешно обновлен.');
            $this->closeEditModal();
            $this->loadUploadedDocuments();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating document: '.$e->getMessage());
            toastr()->error('Ошибка при обновлении документа.');
        }
    }

    public function deleteDocument($documentId)
    {
        // Check date restrictions first
        if (! $this->isWithinValidPeriod()) {
            toastr()->error($this->getDateRestrictionMessage());

            return;
        }

        $document = ApplicationDocument::find($documentId);

        if (! $document) {
            toastr()->error('Документ не найден.');

            return;
        }

        if (! $this->canEditDocument($document)) {
            toastr()->error('Удаление недоступно.');

            return;
        }

        try {
            DB::beginTransaction();

            // Delete file from storage
            if (Storage::disk('public')->exists($document->file_url)) {
                Storage::disk('public')->delete($document->file_url);
            }

            $document->delete();

            DB::commit();

            toastr()->success('Документ успешно удален.');
            $this->loadUploadedDocuments();
            $this->loadApplication();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting document: '.$e->getMessage());
            toastr()->error('Ошибка при удалении документа.');
        }
    }

    public function submitCriterionForCheck($criterionId, $checkType)
    {
        // Check date restrictions first
        if (! $this->isWithinValidPeriod()) {
            toastr()->error($this->getDateRestrictionMessage());

            return;
        }

        $criterion = ApplicationCriterion::with('application_criteria_deadlines')->find($criterionId);

        if (! $criterion) {
            toastr()->error('Критерий не найден.');

            return;
        }

        // Check deadline restrictions
        $deadlineCheck = $this->checkCriterionDeadline($criterion);
        if (! $deadlineCheck['allowed']) {
            toastr()->error($deadlineCheck['message']);

            return;
        }

        // Check if can submit
        if (! $this->canSubmitCriterion($criterion, $checkType)) {
            toastr()->error('Отправка на проверку недоступна.');

            return;
        }

        try {
            DB::beginTransaction();

            // Determine new status based on check type
            $newStatusValue = match ($checkType) {
                'first' => ApplicationStatusConstants::AWAITING_FIRST_CHECK_VALUE,
                'industry' => ApplicationStatusConstants::AWAITING_INDUSTRY_CHECK_VALUE,
                'control' => ApplicationStatusConstants::AWAITING_CONTROL_CHECK_VALUE,
                default => null
            };

            if (! $newStatusValue) {
                toastr()->error('Неверный тип проверки.');

                return;
            }

            // Find new status
            $newStatus = ApplicationStatus::where('value', $newStatusValue)->first();

            if (! $newStatus) {
                toastr()->error('Статус не найден.');

                return;
            }

            // Save old status_id before updating
            $oldStatusId = $criterion->status_id;

            // Update criterion status
            $criterion->update(['status_id' => $newStatus->id]);

            // If submitting for industry check, auto-approve first check documents with null status
            if ($checkType === 'industry') {
                ApplicationDocument::where('application_id', $this->application->id)
                    ->where('category_id', $criterion->category_id)
                    ->whereNull('is_first_passed')
                    ->update(['is_first_passed' => true]);
            }

            // If submitting for control check, auto-approve both checks for documents with null status
            if ($checkType === 'control') {
                ApplicationDocument::where('application_id', $this->application->id)
                    ->where('category_id', $criterion->category_id)
                    ->whereNull('is_first_passed')
                    ->update(['is_first_passed' => true]);

                ApplicationDocument::where('application_id', $this->application->id)
                    ->where('category_id', $criterion->category_id)
                    ->whereNull('is_industry_passed')
                    ->update(['is_industry_passed' => true]);
            }

            // Delete deadline record if exists for this criterion and old status
            ApplicationCriteriaDeadline::where('application_criteria_id', $criterionId)
                ->where('status_id', $oldStatusId)
                ->delete();

            DB::commit();

            toastr()->success('Критерий успешно отправлен на проверку.');
            $this->loadApplication();
            $this->loadTabsAndRequirements();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error submitting criterion: '.$e->getMessage());
            toastr()->error('Ошибка при отправке критерия на проверку.');
        }
    }

    // Helper methods for authorization and business logic

    /**
     * Check if current date is within licence period and club deadline
     */
    private function isWithinValidPeriod()
    {
        $now = now();

        // Check licence period
        if (! $this->licence || ! $this->licence->start_at || ! $this->licence->end_at) {
            return false;
        }

        if ($now->lt($this->licence->start_at) || $now->gt($this->licence->end_at)) {
            return false;
        }

        // Check club deadline if exists
        $deadline = $this->licence->licence_deadlines()
            ->where('club_id', $this->application->club_id)
            ->first();

        if ($deadline) {
            if (! $deadline->start_at || ! $deadline->end_at) {
                return false;
            }

            if ($now->lt($deadline->start_at) || $now->gt($deadline->end_at)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get human-readable message why upload is not available due to date restrictions
     */
    private function getDateRestrictionMessage()
    {
        $now = now();

        // Check licence period
        if (! $this->licence || ! $this->licence->start_at || ! $this->licence->end_at) {
            return 'Период действия лицензии не указан.';
        }

        if ($now->lt($this->licence->start_at)) {
            return 'Период действия лицензии еще не начался. Начало: '.$this->licence->start_at->format('d.m.Y');
        }

        if ($now->gt($this->licence->end_at)) {
            return 'Период действия лицензии истек. Окончание: '.$this->licence->end_at->format('d.m.Y');
        }

        // Check club deadline
        $deadline = $this->licence->licence_deadlines()
            ->where('club_id', $this->application->club_id)
            ->first();

        if ($deadline) {
            if (! $deadline->start_at || ! $deadline->end_at) {
                return 'Дедлайн для вашего клуба не указан.';
            }

            if ($now->lt($deadline->start_at)) {
                return 'Период подачи документов еще не начался. Начало: '.$deadline->start_at->format('d.m.Y H:i');
            }

            if ($now->gt($deadline->end_at)) {
                return 'Период подачи документов истек. Дедлайн: '.$deadline->end_at->format('d.m.Y H:i');
            }
        }

        return 'Загрузка документов недоступна в данный момент.';
    }

    private function canUploadForCriterion($criterion, $documentId = null)
    {
        if (! $criterion || ! $criterion->application_status) {
            return false;
        }

        // Check date restrictions (licence period and club deadline)
        if (! $this->isWithinValidPeriod()) {
            return false;
        }

        // Check role-based access (2.1 requirement)
        $user = auth()->user();
        if (! $user || ! $user->role) {
            return false;
        }

        // Get category document
        $categoryDocument = CategoryDocument::find($criterion->category_id);

        if (! $categoryDocument) {
            return false;
        }

        // Check if user's role value is in allowed roles for this category
        // roles is a JSON array of role values (slugs)
        $allowedRoleValues = $categoryDocument->roles ?? [];

        // Ensure allowedRoleValues is an array
        if (is_string($allowedRoleValues)) {
            $allowedRoleValues = json_decode($allowedRoleValues, true) ?? [];
        }

        if (! empty($allowedRoleValues) && is_array($allowedRoleValues) && ! in_array($user->role->value, $allowedRoleValues)) {
            return false;
        }

        // Check criterion status
        $statusValue = $criterion->application_status->value ?? null;

        // Standard statuses where upload is allowed
        $allowedStatuses = [
            ApplicationStatusConstants::AWAITING_DOCUMENTS_VALUE,
            ApplicationStatusConstants::FIRST_CHECK_REVISION_VALUE,
            ApplicationStatusConstants::INDUSTRY_CHECK_REVISION_VALUE,
            ApplicationStatusConstants::CONTROL_CHECK_REVISION_VALUE,
        ];

        // If status is in standard allowed statuses, return true
        if (in_array($statusValue, $allowedStatuses)) {
            return true;
        }

        // If status is partially-approved, check if specific document can be reuploaded
        if ($statusValue === ApplicationStatusConstants::PARTIALLY_APPROVED_VALUE) {
            // If no documentId provided, cannot upload (need to check specific document)
            if ($documentId === null) {
                return false;
            }

            // Check if document is in the reupload list
            $reuploadDocIds = $criterion->can_reupload_after_endings_doc_ids ?? [];

            // Decode if it's a string
            if (is_string($reuploadDocIds)) {
                $reuploadDocIds = json_decode($reuploadDocIds, true) ?? [];
            }

            return in_array($documentId, $reuploadDocIds);
        }

        return false;
    }

    private function canEditDocument($document, $criterion = null)
    {
        // Can only edit/delete if all check statuses are null
        $documentStatusOk = $document->is_first_passed === null &&
                           $document->is_industry_passed === null &&
                           $document->is_final_passed === null;

        if (! $documentStatusOk) {
            return false;
        }

        // If criterion is provided, check if we can upload for this criterion (same statuses)
        if ($criterion) {
            return $this->canUploadForCriterion($criterion);
        }

        // If no criterion provided, find it from document
        $appCriterion = ApplicationCriterion::with('application_status')
            ->where('application_id', $document->application_id)
            ->where('category_id', $document->category_id)
            ->first();

        if (! $appCriterion) {
            return false;
        }

        return $this->canUploadForCriterion($appCriterion);
    }

    private function canSubmitCriterion($criterion, $checkType)
    {
        if (! $criterion || ! $criterion->application_status) {
            return false;
        }

        // Check date restrictions (licence period and club deadline)
        if (! $this->isWithinValidPeriod()) {
            return false;
        }

        $statusValue = $criterion->application_status->value;

        // Get all required documents for this criterion
        $requiredDocuments = LicenceRequirement::where('licence_id', $this->application->license_id)
            ->where('category_id', $criterion->category_id)
            ->where('is_required', true)
            ->pluck('document_id')
            ->toArray();

        // Get uploaded documents
        $uploadedDocuments = ApplicationDocument::where('application_id', $this->application->id)
            ->where('category_id', $criterion->category_id)
            ->get();

        // Check if all required documents are uploaded
        foreach ($requiredDocuments as $docId) {
            $hasDocument = $uploadedDocuments->where('document_id', $docId)->isNotEmpty();
            if (! $hasDocument) {
                return false;
            }
        }

        // Check specific conditions based on check type
        switch ($checkType) {
            case 'first':
                // Can submit for first check from awaiting-documents or first-check-revision
                if (! in_array($statusValue, [
                    ApplicationStatusConstants::AWAITING_DOCUMENTS_VALUE,
                    ApplicationStatusConstants::FIRST_CHECK_REVISION_VALUE,
                ])) {
                    return false;
                }

                // For revision, check if at least one failed document has been re-uploaded (null status)
                if ($statusValue === ApplicationStatusConstants::FIRST_CHECK_REVISION_VALUE) {
                    $hasFailedDocuments = $uploadedDocuments->where('is_first_passed', false)->isNotEmpty();
                    $hasNewUploads = $uploadedDocuments->whereNull('is_first_passed')->isNotEmpty();

                    return $hasFailedDocuments && $hasNewUploads;
                }
                // For awaiting-documents, check that all documents have null status
                return $uploadedDocuments->every(function ($doc) {
                    return $doc->is_first_passed === null &&
                           $doc->is_industry_passed === null &&
                           $doc->is_final_passed === null;
                });

            case 'industry':
                // Can submit for industry check from industry-check-revision
                if ($statusValue !== ApplicationStatusConstants::INDUSTRY_CHECK_REVISION_VALUE) {
                    return false;
                }
                $hasFailedDocuments = $uploadedDocuments->where('is_industry_passed', false)->isNotEmpty();
                $hasNewUploads = $uploadedDocuments->whereNull("is_first_passed")->whereNull('is_industry_passed')->isNotEmpty();

                return $hasFailedDocuments && $hasNewUploads;

            case 'control':
                // Can submit for control check from control-check-revision
                if ($statusValue !== ApplicationStatusConstants::CONTROL_CHECK_REVISION_VALUE) {
                    return false;
                }

                $hasFailedDocuments = $uploadedDocuments->where('is_final_passed', false)->isNotEmpty();
                $hasNewUploads = $uploadedDocuments->whereNull("is_first_passed")->whereNull("is_industry_passed")->whereNull('is_final_passed')->isNotEmpty();

                return $hasFailedDocuments && $hasNewUploads;

            default:
                return false;
        }
    }

    public function getUploadedDocumentsForRequirement($documentId)
    {
        if (! isset($this->uploadedDocumentsByCategory[$documentId])) {
            return [];
        }

        return $this->uploadedDocumentsByCategory[$documentId];
    }

    public function canUploadDocument($criterion, $documentId)
    {
        return $this->canUploadForCriterion($criterion, $documentId);
    }

    public function canEditOrDeleteDocument($criterion, $doc)
    {
        // Convert to object if array
        $document = is_array($doc) ? (object) $doc : $doc;

        // Check if criterion allows upload for this document
        if (! $this->canUploadForCriterion($criterion, $document->document_id ?? null)) {
            return false;
        }

        // Get criterion status
        $statusValue = $criterion->application_status->value ?? null;

        // For partially-approved status, check if document is in reupload list
        if ($statusValue === ApplicationStatusConstants::PARTIALLY_APPROVED_VALUE) {
            $reuploadDocIds = $criterion->can_reupload_after_endings_doc_ids ?? [];

            // Decode if it's a string
            if (is_string($reuploadDocIds)) {
                $reuploadDocIds = json_decode($reuploadDocIds, true) ?? [];
            }

            // Can edit/delete if document is in reupload list
            return in_array($document->document_id ?? null, $reuploadDocIds);
        }

        // For other statuses, can only edit/delete if document hasn't been reviewed yet
        return $document->is_first_passed === null &&
               $document->is_industry_passed === null &&
               $document->is_final_passed === null;
    }

    /**
     * Check if criterion can be submitted based on deadline
     */
    public function checkCriterionDeadline($criterion)
    {
        if (! $criterion->application_criteria_deadlines || $criterion->application_criteria_deadlines->count() === 0) {
            return ['allowed' => true, 'message' => null];
        }

        $now = now();
        $statusId = $criterion->status_id;

        // Find active deadline for current status
        $activeDeadline = $criterion->application_criteria_deadlines
            ->where('status_id', $statusId)
            ->filter(function ($deadline) use ($now) {
                // Check if current time is within deadline range
                $startOk = is_null($deadline->deadline_start_at) || $deadline->deadline_start_at->lte($now);
                $endOk = $deadline->deadline_end_at->gte($now);

                return $startOk && $endOk;
            })
            ->first();

        if (! $activeDeadline) {
            // Check if there's an expired deadline
            $expiredDeadline = $criterion->application_criteria_deadlines
                ->where('status_id', $statusId)
                ->filter(function ($deadline) use ($now) {
                    return $deadline->deadline_end_at->lt($now);
                })
                ->sortByDesc('deadline_end_at')
                ->first();

            if ($expiredDeadline) {
                return [
                    'allowed' => false,
                    'message' => 'Дедлайн истек: '.$expiredDeadline->deadline_end_at->format('d.m.Y H:i').'. Невозможно отправить на проверку.',
                ];
            }

            // Check if deadline hasn't started yet
            $futureDeadline = $criterion->application_criteria_deadlines
                ->where('status_id', $statusId)
                ->filter(function ($deadline) use ($now) {
                    return ! is_null($deadline->deadline_start_at) && $deadline->deadline_start_at->gt($now);
                })
                ->sortBy('deadline_start_at')
                ->first();

            if ($futureDeadline) {
                return [
                    'allowed' => false,
                    'message' => 'Дедлайн еще не начался. Доступно с: '.$futureDeadline->deadline_start_at->format('d.m.Y H:i'),
                ];
            }
        }

        return ['allowed' => true, 'message' => null];
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

    public function render()
    {
        return view('livewire.club.my-application-detail')
            ->layout(get_user_layout());
    }
}
