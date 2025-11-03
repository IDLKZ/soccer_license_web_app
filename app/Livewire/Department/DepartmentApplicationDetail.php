<?php

namespace App\Livewire\Department;

use App\Constants\ApplicationStatusCategoryConstants;
use App\Models\Application;
use App\Models\ApplicationCriterion;
use App\Models\ApplicationDocument;
use App\Models\CategoryDocument;
use App\Models\LicenceRequirement;
use Illuminate\Support\Facades\Auth;
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
            \Log::error('Error loading application: ' . $e->getMessage());
            $this->application = null;
        }
    }

    private function loadTabsAndRequirements()
    {
        if (!$this->application) return;

        $user = Auth::user();
        $userRole = $user->role ? $user->role->value : null;

        // Get criteria that user can see based on roles (3.1 requirement)
        // Department users can only see criteria where their role is in category_document.roles
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
                    'criteria' => $criteria, // Keep as collection, not array
                    'title' => $category->title_ru ?? 'Категория'
                ];
            })
            ->values()
            ->toArray(); // Convert final collection to array

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

        // Filter requirements based on user role (3.1 requirement)
        $user = Auth::user();
        $userRole = $user->role ? $user->role->value : null;

        $this->licenceRequirementsByCategory = LicenceRequirement::with(['document'])
            ->where('licence_id', $this->application->license_id)
            ->where('category_id', $this->activeTab)
            ->get()
            ->groupBy('document_id')
            ->map(function($requirements) {
                // Keep document as object, convert requirements to array
                return [
                    'document' => $requirements->first()->document,
                    'requirements' => $requirements->toArray()
                ];
            })
            ->toArray(); // Convert final structure to array for Livewire

        // Load uploaded documents for current category
        $this->loadUploadedDocuments();
    }

    private function loadUploadedDocuments()
    {
        if (!$this->activeTab || !$this->application) return;

        // Department users can only see documents that belong to categories they have access to (3.2 requirement)
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
        if (!$category || !$category->roles) {
            return false;
        }

        $user = Auth::user();
        $userRole = $user->role ? $user->role->value : null;

        // Department users must be in the allowed roles list
        return $userRole && in_array($userRole, $category->roles);
    }

    public function getDocumentsForRequirement($requirement)
    {
        if (!$this->application || !$requirement) {
            return collect();
        }

        $documentId = is_object($requirement) ? $requirement->document_id : $requirement['document_id'];

        return $this->application->documents
            ->where('pivot.document_id', $documentId)
            ->where('pivot.category_id', $this->activeTab);
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

    public function render()
    {
        return view('livewire.department.department-application-detail')
            ->layout(get_user_layout());
    }
}
