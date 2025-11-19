<?php

namespace App\Livewire\Club;

use App\Constants\ApplicationStatusCategoryConstants;
use App\Models\Application;
use App\Models\Club;
use App\Models\ClubTeam;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MyApplications extends Component
{
    // Tab states
    public $activeTab = 'pending';

    // Search and pagination
    public $search = '';
    public $perPage = 10;

    // Data collections
    public $applications;
    public $pendingApplications;
    public $inReviewApplications;
    public $completedApplications;
    public $cancelledApplications;

    // Statistics
    public $stats = [
        'pending' => 0,
        'in_review' => 0,
        'completed' => 0,
        'cancelled' => 0
    ];

    // Permissions
    #[Locked]
    public $canView = false;

    public function mount()
    {
        $this->authorize('view-applications');

        $user = Auth::user();

        // Initialize collections
        $this->applications = collect();
        $this->pendingApplications = collect();
        $this->inReviewApplications = collect();
        $this->completedApplications = collect();
        $this->cancelledApplications = collect();

        $this->loadApplications();
        $this->calculateStats();
    }

    public function loadApplications()
    {
        $user = Auth::user();

        // Get applications for user's clubs and club teams
        $clubIds = $this->getUserClubIds();

        if (empty($clubIds)) {
            $this->pendingApplications = collect();
            $this->inReviewApplications = collect();
            $this->completedApplications = collect();
            $this->cancelledApplications = collect();
            $this->setActiveApplications();
            return;
        }

        $query = Application::with([
            'application_status_category',
            'licence.season',
            'licence.league',
            'club',
            'application_criteria.category_document',
            'application_criteria.application_status'
        ])
        ->whereIn('club_id', $clubIds)
        ->whereNotNull('license_id')
        ->orderBy('created_at', 'desc');

        // Apply search filter
        if ($this->search) {
            $query->where(function($q) {
                $q->whereHas('licence', function($subQuery) {
                    $subQuery->where('title_ru', 'like', '%' . $this->search . '%')
                            ->orWhere('title_kk', 'like', '%' . $this->search . '%')
                            ->orWhere('title_en', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('club', function($subQuery) {
                    $subQuery->where('short_name_ru', 'like', '%' . $this->search . '%')
                            ->orWhere('short_name_kk', 'like', '%' . $this->search . '%')
                            ->orWhere('short_name_en', 'like', '%' . $this->search . '%')
                            ->orWhere('full_name_ru', 'like', '%' . $this->search . '%')
                            ->orWhere('full_name_kk', 'like', '%' . $this->search . '%')
                            ->orWhere('full_name_en', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('application_status_category', function($subQuery) {
                    $subQuery->where('title_ru', 'like', '%' . $this->search . '%')
                            ->orWhere('title_kk', 'like', '%' . $this->search . '%')
                            ->orWhere('title_en', 'like', '%' . $this->search . '%');
                });
            });
        }

        $allApplications = $query->get();

        // Filter by status categories
        $this->pendingApplications = $allApplications->filter(function($app) {
            return $app->application_status_category &&
                   in_array($app->application_status_category->value, [
                       ApplicationStatusCategoryConstants::DOCUMENT_SUBMISSION_VALUE
                   ]);
        });

        $this->inReviewApplications = $allApplications->filter(function($app) {
            return $app->application_status_category &&
                   in_array($app->application_status_category->value, [
                       ApplicationStatusCategoryConstants::FIRST_CHECK_VALUE,
                       ApplicationStatusCategoryConstants::INDUSTRY_CHECK_VALUE,
                       ApplicationStatusCategoryConstants::CONTROL_CHECK_VALUE,
                       ApplicationStatusCategoryConstants::FINAL_DECISION_VALUE
                   ]);
        });

        $this->completedApplications = $allApplications->filter(function($app) {
            return $app->application_status_category &&
                   in_array($app->application_status_category->value, [
                       ApplicationStatusCategoryConstants::APPROVED_VALUE,
                       ApplicationStatusCategoryConstants::REVOKED_VALUE
                   ]);
        });

        $this->cancelledApplications = $allApplications->filter(function($app) {
            return $app->application_status_category &&
                   $app->application_status_category->value === ApplicationStatusCategoryConstants::REJECTED_VALUE;
        });

        // Set current applications based on active tab
        $this->setActiveApplications();
    }

    private function getUserClubIds()
    {
        $user = Auth::user();
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

    private function setActiveApplications()
    {
        switch ($this->activeTab) {
            case 'pending':
                $this->applications = $this->pendingApplications;
                break;
            case 'in_review':
                $this->applications = $this->inReviewApplications;
                break;
            case 'completed':
                $this->applications = $this->completedApplications;
                break;
            case 'cancelled':
                $this->applications = $this->cancelledApplications;
                break;
            default:
                $this->applications = collect();
        }
    }

    private function calculateStats()
    {
        $this->stats = [
            'pending' => $this->pendingApplications->count(),
            'in_review' => $this->inReviewApplications->count(),
            'completed' => $this->completedApplications->count(),
            'cancelled' => $this->cancelledApplications->count()
        ];
    }

    public function updatedSearch()
    {
        $this->loadApplications();
        $this->calculateStats();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->setActiveApplications();
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

    public function getApplicationStatusIcon($statusValue)
    {
        return match($statusValue) {
            ApplicationStatusCategoryConstants::DOCUMENT_SUBMISSION_VALUE => 'fas fa-upload',
            ApplicationStatusCategoryConstants::FIRST_CHECK_VALUE => 'fas fa-search',
            ApplicationStatusCategoryConstants::INDUSTRY_CHECK_VALUE => 'fas fa-industry',
            ApplicationStatusCategoryConstants::CONTROL_CHECK_VALUE => 'fas fa-clipboard-check',
            ApplicationStatusCategoryConstants::FINAL_DECISION_VALUE => 'fas fa-gavel',
            ApplicationStatusCategoryConstants::APPROVED_VALUE => 'fas fa-check-circle',
            ApplicationStatusCategoryConstants::REVOKED_VALUE => 'fas fa-times-circle',
            ApplicationStatusCategoryConstants::REJECTED_VALUE => 'fas fa-ban',
            default => 'fas fa-question-circle'
        };
    }

    public function getCriterionStatusColor($criterion)
    {
        if (!$criterion->is_ready) {
            return 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
        }

        $hasFailures = false;
        $hasPending = false;

        // Check different validation stages
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

    public function getCriterionStatusText($criterion)
    {
        $hasFailures = false;
        $hasPending = false;

        // Check validation stages
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
            return 'Требует исправлений';
        } elseif ($hasPending) {
            return 'На проверке';
        } else {
            return 'Принято';
        }
    }

    public function render()
    {
        return view('livewire.club.my-applications')
            ->layout(get_user_layout());
    }
}
