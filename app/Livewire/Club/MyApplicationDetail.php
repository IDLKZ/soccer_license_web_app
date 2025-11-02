<?php

namespace App\Livewire\Club;

use App\Constants\ApplicationStatusCategoryConstants;
use App\Models\Application;
use App\Models\ApplicationCriterion;
use App\Models\ApplicationDocument;
use App\Models\CategoryDocument;
use App\Models\LicenceRequirement;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MyApplicationDetail extends Component
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

    // Permissions
    #[Locked]
    public $canView = false;
    #[Locked]
    public $canUpload = false;

    // Upload modal
    public $showUploadModal = false;
    public $selectedCriterion = null;
    public $selectedRequirement = null;

    public function mount($id)
    {
        $this->applicationId = $id;
        $this->loadApplication();

        if (!$this->application) {
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

    private function checkPermissions()
    {
        $authUser = Auth::user();
        // Check if user can view this specific application
        $userClubIds = $this->getUserClubIds();
        if (!in_array($this->application->club_id, $userClubIds)) {
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
            if ($team->club_id && !in_array($team->club_id, $clubIds)) {
                $clubIds[] = $team->club_id;
            }
        }

        return array_unique($clubIds);
    }

    private function loadTabsAndRequirements()
    {
        if (!$this->application) return;

        $user = Auth::user();
        $userRole = $user->role ? $user->role->value : null;

        // Get criteria that user can see based on roles
        $this->criteriaTabs = $this->application->application_criteria
            ->filter(function($criterion) use ($userRole) {
                if (!$criterion->category_document) {
                    return false;
                }
                $category = $criterion->category_document;
                $categoryRoles = $category->roles ?? [];
                return empty($categoryRoles) || ($userRole && in_array($userRole, $categoryRoles));
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
    }

    public function setActiveTab($categoryId)
    {
        $this->activeTab = $categoryId;
        $this->loadLicenceRequirements();
    }

    public function canViewCategory($category)
    {
        if (!$category || !$category->roles) {
            return true;
        }

        $user = Auth::user();
        $userRole = $user->role ? $user->role->value : null;
        return $userRole && in_array($userRole, $category->roles);
    }

    public function canUploadDocuments($criterion)
    {
        if (!$criterion || !$this->canUpload) {
            return false;
        }

        // Check if criterion status allows upload
        $applicationStatus = $criterion->application_status;
        if (!$applicationStatus) {
            return false;
        }

        $statusValue = $applicationStatus->value ?? null;
        $allowedStatuses = [
            'awaiting-documents',
            'first-check-revision',
            'industry-check-revision',
            'control-check-revision',
            'partially-approved'
        ];

        return in_array($statusValue, $allowedStatuses);
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

    public function openUploadModal($criterionId, $requirementId = null)
    {
        $this->selectedCriterion = $this->application->application_criteria
            ->where('id', $criterionId)
            ->first();

        if ($this->selectedRequirement) {
            $this->selectedRequirement = LicenceRequirement::find($requirementId);
        }

        $this->showUploadModal = true;
    }

    public function closeUploadModal()
    {
        $this->showUploadModal = false;
        $this->selectedCriterion = null;
        $this->selectedRequirement = null;
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

    public function render()
    {
        return view('livewire.club.my-application-detail')
            ->layout(get_user_layout());
    }
}
