<?php

namespace App\Livewire\Club;

use App\Models\ApplicationCriterion;
use App\Models\ClubTeam;
use App\Models\CategoryDocument;
use App\Models\Application;
use Livewire\Component;
use Livewire\Attributes\Locked;

class MyCriterias extends Component
{
    // Tab states
    public $activeTab = 'active';

    // Search and pagination
    public $search = '';
    public $perPage = 12;

    // Data collections
    public $criterias;
    public $activeCriterias;
    public $inReviewCriterias;
    public $approvedCriterias;
    public $rejectedCriterias;

    // Pagination
    public $currentPage = 1;

    // Statistics
    public $stats = [
        'active' => 0,
        'in_review' => 0,
        'approved' => 0,
        'rejected' => 0
    ];

    // Status groups
    private $activeStatuses = [
        'awaiting-documents',
        'first-check-revision',
        'industry-check-revision',
        'control-check-revision',
        'partially-approved'
    ];

    private $inReviewStatuses = [
        'awaiting-first-check',
        'awaiting-industry-check',
        'awaiting-control-check',
        'awaiting-final-decision'
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
        $this->authorize('view-applications');

        $user = auth()->user();
        $this->canView = $user->can('view-applications');

        // Initialize collections
        $this->criterias = collect();
        $this->activeCriterias = collect();
        $this->inReviewCriterias = collect();
        $this->approvedCriterias = collect();
        $this->rejectedCriterias = collect();

        $this->loadCriterias();
        $this->calculateStats();
    }

    public function loadCriterias()
    {
        $user = auth()->user();

        // Get application and category IDs for user
        $clubIds = $this->getUserClubIds();
        $categoryIds = $this->getUserCategoryIds();

        if (empty($clubIds) || empty($categoryIds)) {
            $this->setEmptyCollections();
            return;
        }

        // Get application IDs for user's clubs
        $applicationIds = Application::whereIn('club_id', $clubIds)
            ->pluck('id')
            ->toArray();

        if (empty($applicationIds)) {
            $this->setEmptyCollections();
            return;
        }

        // Base query
        $baseQuery = ApplicationCriterion::with([
            'application.licence.season',
            'application.licence.league',
            'application.club',
            'category_document',
            'application_status'
        ])
        ->whereIn('application_id', $applicationIds)
        ->whereIn('category_id', $categoryIds);

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

        // Filter by status groups
        $this->activeCriterias = $allCriterias->filter(function($criteria) {
            return $criteria->application_status &&
                   in_array($criteria->application_status->value, $this->activeStatuses);
        });

        $this->inReviewCriterias = $allCriterias->filter(function($criteria) {
            return $criteria->application_status &&
                   in_array($criteria->application_status->value, $this->inReviewStatuses);
        });

        $this->approvedCriterias = $allCriterias->filter(function($criteria) {
            return $criteria->application_status &&
                   in_array($criteria->application_status->value, $this->approvedStatuses);
        });

        $this->rejectedCriterias = $allCriterias->filter(function($criteria) {
            return $criteria->application_status &&
                   in_array($criteria->application_status->value, $this->rejectedStatuses);
        });

        // Set current criterias based on active tab
        $this->setActiveCriterias();
    }

    private function getUserClubIds()
    {
        $user = auth()->user();
        $clubIds = [];

        // Get clubs directly associated with user
        if ($user->club_id) {
            $clubIds[] = $user->club_id;
        }

        // Get clubs through club teams
        $clubTeams = ClubTeam::where('user_id', $user->id)->get();
        foreach ($clubTeams as $team) {
            if ($team->club_id && !in_array($team->club_id, $clubIds)) {
                $clubIds[] = $team->club_id;
            }
        }

        return array_unique($clubIds);
    }

    private function getUserCategoryIds()
    {
        $user = auth()->user();

        return CategoryDocument::whereJsonContains('roles', $user->role->value)
            ->pluck('id')
            ->toArray();
    }

    private function setEmptyCollections()
    {
        $this->activeCriterias = collect();
        $this->inReviewCriterias = collect();
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
            case 'in_review':
                $this->criterias = $this->inReviewCriterias;
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
            case 'in_review':
                return $this->inReviewCriterias->count();
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
            'in_review' => $this->inReviewCriterias->count(),
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
        if (in_array($statusValue, $this->activeStatuses)) {
            return 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200';
        } elseif (in_array($statusValue, $this->inReviewStatuses)) {
            return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200';
        } elseif (in_array($statusValue, $this->approvedStatuses)) {
            return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
        } elseif (in_array($statusValue, $this->rejectedStatuses)) {
            return 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
        } else {
            return 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
        }
    }

    public function getCriteriaStatusIcon($statusValue)
    {
        return match($statusValue) {
            'awaiting-documents' => 'fas fa-upload',
            'first-check-revision' => 'fas fa-exclamation-triangle',
            'industry-check-revision' => 'fas fa-exclamation-triangle',
            'control-check-revision' => 'fas fa-exclamation-triangle',
            'partially-approved' => 'fas fa-check-half',
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
     * Get count of criteria checks for badge
     */
    public static function getCriteriaCheckCount()
    {
        $userId = auth()->id();

        if (!$userId) {
            return 0;
        }

        $user = auth()->user();

        // Get club IDs where user is a member
        $clubIds = ClubTeam::where('user_id', $userId)->pluck('club_id')->toArray();

        if (empty($clubIds)) {
            return 0;
        }

        // Get category IDs where user's role is allowed
        $categoryIds = CategoryDocument::whereJsonContains('roles', $user->role->value)
            ->pluck('id')
            ->toArray();

        if (empty($categoryIds)) {
            return 0;
        }

        // Get application IDs for user's clubs
        $applicationIds = Application::whereIn('club_id', $clubIds)
            ->pluck('id')
            ->toArray();

        if (empty($applicationIds)) {
            return 0;
        }

        $activeStatuses = [
            'awaiting-documents',
            'first-check-revision',
            'industry-check-revision',
            'control-check-revision',
            'partially-approved'
        ];

        // Count criteria that match all conditions
        return ApplicationCriterion::whereIn('application_id', $applicationIds)
            ->whereIn('category_id', $categoryIds)
            ->whereHas('application_status', function ($query) use ($activeStatuses) {
                $query->whereIn('value', $activeStatuses);
            })
            ->count();
    }

    public function render()
    {
        return view('livewire.club.my-criterias')
            ->layout(get_user_layout());
    }
}
