<?php

namespace App\Livewire\Department;

use App\Models\ApplicationCriterion;
use App\Models\ApplicationStatus;
use App\Models\CategoryDocument;
use Livewire\Component;
use Livewire\Attributes\Locked;

class DepartmentCriterias extends Component
{
    // Tab states
    public $activeTab = 'active';

    // Search and pagination
    public $search = '';
    public $perPage = 12;

    // Data collections
    public $criterias;
    public $activeCriterias;
    public $revisionCriterias;
    public $approvedCriterias;
    public $rejectedCriterias;

    // Pagination
    public $currentPage = 1;

    // Statistics
    public $stats = [
        'active' => 0,
        'revision' => 0,
        'approved' => 0,
        'rejected' => 0
    ];

    // Status groups for department (different from club)
    private $revisionStatuses = [
        'first-check-revision',
        'industry-check-revision',
        'control-check-revision'
    ];

    private $approvedStatuses = [
        'fully-approved',
        'partially-approved'
    ];

    private $rejectedStatuses = [
        'revoked',
        'rejected'
    ];

    // Permissions
    #[Locked]
    public $canView = false;

    public function mount()
    {
        $user = auth()->user();
        $this->canView = $user->can('view-applications');

        // Initialize collections
        $this->criterias = collect();
        $this->activeCriterias = collect();
        $this->revisionCriterias = collect();
        $this->approvedCriterias = collect();
        $this->rejectedCriterias = collect();

        $this->loadCriterias();
        $this->calculateStats();
    }

    public function loadCriterias()
    {
        $user = auth()->user();

        // Get category IDs where user's role is allowed (пункт 2)
        $categoryIds = $this->getUserCategoryIds();

        // Get status IDs where user's role is allowed (пункт 3)
        $activeStatusIds = $this->getUserActiveStatusIds();

        if (empty($categoryIds)) {
            $this->setEmptyCollections();
            return;
        }

        // Base query with access control (пункт 2)
        $baseQuery = ApplicationCriterion::with([
            'application.licence.season',
            'application.licence.league',
            'application.club',
            'category_document',
            'application_status'
        ])
        ->whereIn('category_id', $categoryIds); // Обязательный пункт 2

        // Apply search filter
        if ($this->search) {
            $baseQuery->where(function($q) {
                $q->whereHas('application', function($subQuery) {
                    $subQuery->whereHas('licence', function($licenceQuery) {
                        $licenceQuery->where('title_ru', 'like', '%' . $this->search . '%')
                                ->orWhere('title_kk', 'like', '%' . $this->search . '%')
                                ->orWhere('title_en', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('club', function($clubQuery) {
                        $clubQuery->where('short_name_ru', 'like', '%' . $this->search . '%')
                                ->orWhere('short_name_kk', 'like', '%' . $this->search . '%')
                                ->orWhere('short_name_en', 'like', '%' . $this->search . '%');
                    });
                })
                ->orWhereHas('category_document', function($categoryQuery) {
                    $categoryQuery->where('title_ru', 'like', '%' . $this->search . '%')
                            ->orWhere('title_kk', 'like', '%' . $this->search . '%')
                            ->orWhere('title_en', 'like', '%' . $this->search . '%');
                });
            });
        }

        // Get all criteria
        $allCriterias = $baseQuery->get();

        // Вкладка "Активные" (пункт 4)
        $this->activeCriterias = $allCriterias->filter(function($criteria) use ($activeStatusIds) {
            return $criteria->status_id && in_array($criteria->status_id, $activeStatusIds);
        });

        // Вкладка "На доработке" (пункт 5)
        $this->revisionCriterias = $allCriterias->filter(function($criteria) {
            return $criteria->application_status &&
                   in_array($criteria->application_status->value, $this->revisionStatuses);
        });

        // Вкладка "Одобрено" (пункт 6)
        $this->approvedCriterias = $allCriterias->filter(function($criteria) {
            return $criteria->application_status &&
                   in_array($criteria->application_status->value, $this->approvedStatuses);
        });

        // Вкладка "Отказано" (пункт 7)
        $this->rejectedCriterias = $allCriterias->filter(function($criteria) {
            return $criteria->application_status &&
                   in_array($criteria->application_status->value, $this->rejectedStatuses);
        });

        // Set current criterias based on active tab
        $this->setActiveCriterias();
    }

    /**
     * Get category IDs where user's role is in category_documents.roles (пункт 2)
     */
    private function getUserCategoryIds()
    {
        $user = auth()->user();

        if (!$user->role) {
            return [];
        }

        return CategoryDocument::whereJsonContains('roles', $user->role->value)
            ->pluck('id')
            ->toArray();
    }

    /**
     * Get status IDs where user's role is in application_statuses.role_values (пункт 3 & 4)
     */
    private function getUserActiveStatusIds()
    {
        $user = auth()->user();

        if (!$user->role) {
            return [];
        }

        return ApplicationStatus::whereJsonContains('role_values', $user->role->value)
            ->pluck('id')
            ->toArray();
    }

    private function setEmptyCollections()
    {
        $this->activeCriterias = collect();
        $this->revisionCriterias = collect();
        $this->approvedCriterias = collect();
        $this->rejectedCriterias = collect();
        $this->setActiveCriterias();
    }

    private function setActiveCriterias()
    {
        switch ($this->activeTab) {
            case 'active':
                $this->criterias = $this->activeCriterias;
                break;
            case 'revision':
                $this->criterias = $this->revisionCriterias;
                break;
            case 'approved':
                $this->criterias = $this->approvedCriterias;
                break;
            case 'rejected':
                $this->criterias = $this->rejectedCriterias;
                break;
            default:
                $this->criterias = collect();
        }

        // Apply pagination
        $this->criterias = $this->criterias->slice(($this->currentPage - 1) * $this->perPage, $this->perPage);
    }

    public function getCurrentPageCriterias()
    {
        return $this->criterias;
    }

    public function getPaginatedCriterias()
    {
        return new \Illuminate\Pagination\LengthAwarePaginator(
            $this->getCurrentPageCriterias(),
            $this->getTotalCriterias(),
            $this->perPage,
            $this->currentPage,
            [
                'path' => request()->url(),
                'pageName' => 'page',
            ]
        );
    }

    public function getTotalCriterias()
    {
        switch ($this->activeTab) {
            case 'active':
                return $this->activeCriterias->count();
            case 'revision':
                return $this->revisionCriterias->count();
            case 'approved':
                return $this->approvedCriterias->count();
            case 'rejected':
                return $this->rejectedCriterias->count();
            default:
                return 0;
        }
    }

    public function goToPage($page)
    {
        $this->currentPage = $page;
        $this->setActiveCriterias();
    }

    public function nextPage()
    {
        if ($this->currentPage < $this->getLastPage()) {
            $this->currentPage++;
            $this->setActiveCriterias();
        }
    }

    public function previousPage()
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
            $this->setActiveCriterias();
        }
    }

    public function getLastPage()
    {
        return (int) ceil($this->getTotalCriterias() / $this->perPage);
    }

    private function calculateStats()
    {
        $this->stats = [
            'active' => $this->activeCriterias->count(),
            'revision' => $this->revisionCriterias->count(),
            'approved' => $this->approvedCriterias->count(),
            'rejected' => $this->rejectedCriterias->count()
        ];
    }

    public function updatedSearch()
    {
        $this->loadCriterias();
        $this->calculateStats();
        $this->currentPage = 1;
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->currentPage = 1;
        $this->setActiveCriterias();
    }

    public function getCriteriaStatusColor($statusValue)
    {
        if (in_array($statusValue, $this->revisionStatuses)) {
            return 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200';
        } elseif (in_array($statusValue, $this->approvedStatuses)) {
            return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
        } elseif (in_array($statusValue, $this->rejectedStatuses)) {
            return 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
        } else {
            return 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200';
        }
    }

    public function getCriteriaStatusIcon($statusValue)
    {
        return match($statusValue) {
            'awaiting-documents' => 'fas fa-upload',
            'first-check-revision' => 'fas fa-exclamation-triangle',
            'industry-check-revision' => 'fas fa-exclamation-triangle',
            'control-check-revision' => 'fas fa-exclamation-triangle',
            'partially-approved' => 'fas fa-check-circle-half',
            'awaiting-first-check' => 'fas fa-search',
            'awaiting-industry-check' => 'fas fa-industry',
            'awaiting-control-check' => 'fas fa-clipboard-check',
            'awaiting-final-decision' => 'fas fa-gavel',
            'fully-approved' => 'fas fa-check-circle',
            'revoked' => 'fas fa-times-circle',
            'rejected' => 'fas fa-ban',
            default => 'fas fa-question-circle'
        };
    }

    /**
     * Get count of criteria for badge (пункт 3)
     */
    public static function getCriteriaCheckCount()
    {
        $user = auth()->user();

        if (!$user || !$user->role) {
            return 0;
        }

        // Get category IDs where user's role is allowed
        $categoryIds = CategoryDocument::whereJsonContains('roles', $user->role->value)
            ->pluck('id')
            ->toArray();

        if (empty($categoryIds)) {
            return 0;
        }

        // Get status IDs where user's role is allowed
        $statusIds = ApplicationStatus::whereJsonContains('role_values', $user->role->value)
            ->pluck('id')
            ->toArray();

        if (empty($statusIds)) {
            return 0;
        }

        // Count criteria that match both conditions (пункт 3)
        return ApplicationCriterion::whereIn('category_id', $categoryIds)
            ->whereIn('status_id', $statusIds)
            ->count();
    }

    public function render()
    {
        return view('livewire.department.department-criterias')
            ->layout(get_user_layout());
    }
}
