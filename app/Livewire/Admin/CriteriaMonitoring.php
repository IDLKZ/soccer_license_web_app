<?php

namespace App\Livewire\Admin;

use App\Models\ApplicationCriterion;
use App\Models\ApplicationDocument;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Locked;
use Livewire\Component;

class CriteriaMonitoring extends Component
{
    public $search = '';
    public $activeTab = 'all';

    #[Locked]
    public $canSync = false;

    public $syncingCriterionId = null;
    public $syncingAll = false;

    /**
     * Static method for sidebar badge
     */
    public static function getErrorCount(): int
    {
        // First level
        $firstDocs = ApplicationDocument::where([
            'is_first_passed' => true,
            'is_industry_passed' => null,
            'is_final_passed' => null,
        ])->get(['application_id', 'category_id'])
            ->groupBy('application_id')
            ->map(fn($items) => $items->pluck('category_id')->unique()->values());

        // Second level
        $secondDocs = ApplicationDocument::where([
            'is_first_passed' => true,
            'is_industry_passed' => true,
            'is_final_passed' => null,
        ])->get(['application_id', 'category_id'])
            ->groupBy('application_id')
            ->map(fn($items) => $items->pluck('category_id')->unique()->values());

        // Third level
        $thirdDocs = ApplicationDocument::where([
            'is_first_passed' => true,
            'is_industry_passed' => true,
            'is_final_passed' => true,
        ])->get(['application_id', 'category_id'])
            ->groupBy('application_id')
            ->map(fn($items) => $items->pluck('category_id')->unique()->values());

        $count = 0;

        // Count first level errors
        if ($firstDocs->isNotEmpty()) {
            $query = ApplicationCriterion::where('status_id', 1);
            $query->where(function ($q) use ($firstDocs) {
                foreach ($firstDocs as $applicationId => $categoryIds) {
                    $q->orWhere(function ($sub) use ($applicationId, $categoryIds) {
                        $sub->where('application_id', $applicationId)
                            ->whereIn('category_id', $categoryIds);
                    });
                }
            });
            $count += $query->count();
        }

        // Count second level errors
        if ($secondDocs->isNotEmpty()) {
            $query = ApplicationCriterion::where('status_id', 1);
            $query->where(function ($q) use ($secondDocs) {
                foreach ($secondDocs as $applicationId => $categoryIds) {
                    $q->orWhere(function ($sub) use ($applicationId, $categoryIds) {
                        $sub->where('application_id', $applicationId)
                            ->whereIn('category_id', $categoryIds);
                    });
                }
            });
            $count += $query->count();
        }

        // Count third level errors
        if ($thirdDocs->isNotEmpty()) {
            $query = ApplicationCriterion::where('status_id', 1);
            $query->where(function ($q) use ($thirdDocs) {
                foreach ($thirdDocs as $applicationId => $categoryIds) {
                    $q->orWhere(function ($sub) use ($applicationId, $categoryIds) {
                        $sub->where('application_id', $applicationId)
                            ->whereIn('category_id', $categoryIds);
                    });
                }
            });
            $count += $query->count();
        }

        return $count;
    }

    public function mount()
    {
        $this->authorize('view-full-application');
        $this->canSync = auth()->user()->can('manage-full-application');
    }

    public function updatedSearch()
    {
        // Reset when search changes
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    /**
     * Get criteria with sync errors (documents passed but criterion status_id = 1)
     */
    public function getErrorCriteria()
    {
        // First level: is_first_passed = true, but criterion status_id = 1
        $firstLevelDocs = ApplicationDocument::where([
            'is_first_passed' => true,
            'is_industry_passed' => null,
            'is_final_passed' => null,
        ])
            ->get(['application_id', 'category_id'])
            ->groupBy('application_id')
            ->map(fn($items) => $items->pluck('category_id')->unique()->values());

        // Second level: is_industry_passed = true, but criterion status_id = 1
        $secondLevelDocs = ApplicationDocument::where([
            'is_first_passed' => true,
            'is_industry_passed' => true,
            'is_final_passed' => null,
        ])
            ->get(['application_id', 'category_id'])
            ->groupBy('application_id')
            ->map(fn($items) => $items->pluck('category_id')->unique()->values());

        // Third level: is_final_passed = true, but criterion status_id = 1
        $thirdLevelDocs = ApplicationDocument::where([
            'is_first_passed' => true,
            'is_industry_passed' => true,
            'is_final_passed' => true,
        ])
            ->get(['application_id', 'category_id'])
            ->groupBy('application_id')
            ->map(fn($items) => $items->pluck('category_id')->unique()->values());

        $firstLevelCriteria = collect();
        $secondLevelCriteria = collect();
        $thirdLevelCriteria = collect();

        // First level criteria
        if ($firstLevelDocs->isNotEmpty()) {
            $query = ApplicationCriterion::with([
                'application.club',
                'application.licence',
                'category_document',
                'application_status'
            ]);

            $query->where(function ($q) use ($firstLevelDocs) {
                foreach ($firstLevelDocs as $applicationId => $categoryIds) {
                    $q->orWhere(function ($sub) use ($applicationId, $categoryIds) {
                        $sub->where('application_id', $applicationId)
                            ->whereIn('category_id', $categoryIds);
                    });
                }
            });

            $firstLevelCriteria = $query->where('status_id', 1)->get();
        }

        // Second level criteria
        if ($secondLevelDocs->isNotEmpty()) {
            $query = ApplicationCriterion::with([
                'application.club',
                'application.licence',
                'category_document',
                'application_status'
            ]);

            $query->where(function ($q) use ($secondLevelDocs) {
                foreach ($secondLevelDocs as $applicationId => $categoryIds) {
                    $q->orWhere(function ($sub) use ($applicationId, $categoryIds) {
                        $sub->where('application_id', $applicationId)
                            ->whereIn('category_id', $categoryIds);
                    });
                }
            });

            $secondLevelCriteria = $query->where('status_id', 1)->get();
        }

        // Third level criteria
        if ($thirdLevelDocs->isNotEmpty()) {
            $query = ApplicationCriterion::with([
                'application.club',
                'application.licence',
                'category_document',
                'application_status'
            ]);

            $query->where(function ($q) use ($thirdLevelDocs) {
                foreach ($thirdLevelDocs as $applicationId => $categoryIds) {
                    $q->orWhere(function ($sub) use ($applicationId, $categoryIds) {
                        $sub->where('application_id', $applicationId)
                            ->whereIn('category_id', $categoryIds);
                    });
                }
            });

            $thirdLevelCriteria = $query->where('status_id', 1)->get();
        }

        return [
            'first' => $firstLevelCriteria,
            'second' => $secondLevelCriteria,
            'third' => $thirdLevelCriteria,
        ];
    }

    /**
     * Get statistics
     */
    public function getStats()
    {
        $errors = $this->getErrorCriteria();

        return [
            'first' => $errors['first']->count(),
            'second' => $errors['second']->count(),
            'third' => $errors['third']->count(),
            'total' => $errors['first']->count() + $errors['second']->count() + $errors['third']->count(),
        ];
    }

    /**
     * Sync single criterion
     */
    public function syncCriterion($criterionId, $level)
    {
        if (!$this->canSync) {
            toastr()->error('У вас нет прав для синхронизации.');
            return;
        }

        $this->syncingCriterionId = $criterionId;

        try {
            $newStatusId = match ($level) {
                'first' => 2,  // AWAITING_FIRST_CHECK
                'second' => 4, // AWAITING_INDUSTRY_CHECK
                'third' => 6,  // AWAITING_CONTROL_CHECK
                default => null,
            };

            if (!$newStatusId) {
                toastr()->error('Неверный уровень синхронизации.');
                return;
            }

            ApplicationCriterion::where('id', $criterionId)->update([
                'status_id' => $newStatusId,
            ]);

            Log::info("Criterion #{$criterionId} synced to status_id={$newStatusId} by admin");

            toastr()->success("Критерий #{$criterionId} синхронизирован.");

        } catch (\Exception $e) {
            Log::error("Error syncing criterion #{$criterionId}: " . $e->getMessage());
            toastr()->error('Ошибка при синхронизации: ' . $e->getMessage());
        }

        $this->syncingCriterionId = null;
    }

    /**
     * Sync all criteria
     */
    public function syncAll()
    {
        if (!$this->canSync) {
            toastr()->error('У вас нет прав для синхронизации.');
            return;
        }

        $this->syncingAll = true;

        try {
            DB::beginTransaction();

            $errors = $this->getErrorCriteria();
            $synced = 0;

            // Sync first level → status_id = 2
            if ($errors['first']->isNotEmpty()) {
                $ids = $errors['first']->pluck('id')->toArray();
                ApplicationCriterion::whereIn('id', $ids)->update(['status_id' => 2]);
                $synced += count($ids);
            }

            // Sync second level → status_id = 4
            if ($errors['second']->isNotEmpty()) {
                $ids = $errors['second']->pluck('id')->toArray();
                ApplicationCriterion::whereIn('id', $ids)->update(['status_id' => 4]);
                $synced += count($ids);
            }

            // Sync third level → status_id = 6
            if ($errors['third']->isNotEmpty()) {
                $ids = $errors['third']->pluck('id')->toArray();
                ApplicationCriterion::whereIn('id', $ids)->update(['status_id' => 6]);
                $synced += count($ids);
            }

            DB::commit();

            Log::info("Admin synced {$synced} criteria");

            toastr()->success("Синхронизировано {$synced} критериев.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error syncing all criteria: " . $e->getMessage());
            toastr()->error('Ошибка при синхронизации: ' . $e->getMessage());
        }

        $this->syncingAll = false;
    }

    /**
     * Get error level for criterion
     */
    public function getErrorLevel($criterion)
    {
        $docs = ApplicationDocument::where('application_id', $criterion->application_id)
            ->where('category_id', $criterion->category_id)
            ->get();

        $hasFirstPassed = $docs->where('is_first_passed', true)->isNotEmpty();
        $hasIndustryPassed = $docs->where('is_industry_passed', true)->isNotEmpty();
        $hasFinalPassed = $docs->where('is_final_passed', true)->isNotEmpty();

        if ($hasFinalPassed) {
            return 'third';
        } elseif ($hasIndustryPassed) {
            return 'second';
        } elseif ($hasFirstPassed) {
            return 'first';
        }

        return null;
    }

    /**
     * Get expected status for criterion
     */
    public function getExpectedStatus($level)
    {
        return match ($level) {
            'first' => 'Ожидает первичной проверки (ID: 2)',
            'second' => 'Ожидает отраслевой проверки (ID: 4)',
            'third' => 'Ожидает контрольной проверки (ID: 6)',
            default => 'Неизвестно',
        };
    }

    public function render()
    {
        $errors = $this->getErrorCriteria();
        $stats = $this->getStats();

        // Filter by tab
        $criteria = match ($this->activeTab) {
            'first' => $errors['first'],
            'second' => $errors['second'],
            'third' => $errors['third'],
            default => $errors['first']->merge($errors['second'])->merge($errors['third']),
        };

        // Search filter
        if ($this->search) {
            $search = mb_strtolower($this->search);
            $criteria = $criteria->filter(function ($c) use ($search) {
                return str_contains(mb_strtolower($c->category_document?->title_ru ?? ''), $search)
                    || str_contains(mb_strtolower($c->application?->club?->short_name_ru ?? ''), $search)
                    || str_contains(mb_strtolower($c->application?->licence?->title_ru ?? ''), $search)
                    || str_contains((string) $c->id, $search)
                    || str_contains((string) $c->application_id, $search);
            });
        }

        return view('livewire.admin.criteria-monitoring', [
            'criteria' => $criteria,
            'stats' => $stats,
        ])->layout(get_user_layout());
    }
}
