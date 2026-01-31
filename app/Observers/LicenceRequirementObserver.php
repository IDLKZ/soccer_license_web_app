<?php

namespace App\Observers;

use App\Constants\ApplicationStatusCategoryConstants;
use App\Models\Application;
use App\Models\ApplicationCriterion;
use App\Models\LicenceRequirement;
use Illuminate\Support\Facades\DB;

/**
 * LicenceRequirement Observer
 *
 * Automatically synchronizes application_criteria when licence_requirements change.
 *
 * Logic flow:
 * 1. Licence has LicenceRequirement entries (through licence_id)
 * 2. Application has license_id linking to Licence
 * 3. ApplicationCriterion has application_id and category_id (unique pair)
 *
 * When LicenceRequirement is created/updated/deleted:
 * - Finds all Applications with matching license_id that are NOT in final states (rejected/revoked/approved)
 * - Synchronizes ApplicationCriterion entries to match category_ids from LicenceRequirement
 * - Adds missing category_ids with default values (status_id=1, is_ready=false)
 * - Removes excess category_ids that no longer exist in requirements
 *
 * Unique constraint: (application_id, category_id) - no duplicates allowed
 *
 * @see OBSERVER_EXAMPLE.md for detailed examples
 */
class LicenceRequirementObserver
{
    /**
     * Handle the LicenceRequirement "created" event.
     */
    public function created(LicenceRequirement $licenceRequirement): void
    {
        $this->syncApplicationCriteria($licenceRequirement);
    }

    /**
     * Handle the LicenceRequirement "updated" event.
     */
    public function updated(LicenceRequirement $licenceRequirement): void
    {
        // Only sync if category_id changed
        if ($licenceRequirement->isDirty('category_id')) {
            $this->syncApplicationCriteria($licenceRequirement);
        }
    }

    /**
     * Handle the LicenceRequirement "deleted" event.
     */
    public function deleted(LicenceRequirement $licenceRequirement): void
    {
        $this->syncApplicationCriteria($licenceRequirement, true);
    }

    /**
     * Handle the LicenceRequirement "restored" event.
     */
    public function restored(LicenceRequirement $licenceRequirement): void
    {
        $this->syncApplicationCriteria($licenceRequirement);
    }

    /**
     * Handle the LicenceRequirement "force deleted" event.
     */
    public function forceDeleted(LicenceRequirement $licenceRequirement): void
    {
        //
    }

    /**
     * Sync application criteria for all affected applications
     */
    protected function syncApplicationCriteria(LicenceRequirement $licenceRequirement, bool $isDeleted = false): void
    {
        // Get all applications for this licence that are not in final states
        $excludedCategoryValues = [
            ApplicationStatusCategoryConstants::REJECTED_VALUE,
            ApplicationStatusCategoryConstants::REVOKED_VALUE,
            ApplicationStatusCategoryConstants::APPROVED_VALUE,
        ];

        // Find all applications with this license_id that are not in final states
        $applications = Application::where('license_id', $licenceRequirement->licence_id)
            ->whereHas('application_status_category', function ($query) use ($excludedCategoryValues) {
                $query->whereNotIn('value', $excludedCategoryValues);
            })
            ->get();

        foreach ($applications as $application) {
            $this->syncCriteriaForApplication($application);
        }
    }

    /**
     * Sync criteria for a specific application based on its licence requirements
     */
    protected function syncCriteriaForApplication(Application $application): void
    {
        // Get all unique category_ids from licence_requirements for this application's license
        // Cast to integers to ensure consistent comparison
        $requiredCategoryIds = DB::table('licence_requirements')
            ->where('licence_id', $application->license_id)
            ->distinct()
            ->pluck('category_id')
            ->filter() // Remove null values
            ->map(fn($id) => (int) $id)
            ->toArray();

        // Get existing application_criteria for this application
        // Key by category_id since (application_id, category_id) is unique
        $existingCriteria = ApplicationCriterion::where('application_id', $application->id)
            ->select('id', 'category_id')
            ->get()
            ->keyBy('category_id');

        // Cast to integers for consistent comparison
        $existingCategoryIds = $existingCriteria->keys()->map(fn($id) => (int) $id)->toArray();

        // Find missing category_ids that need to be added
        $missingCategoryIds = array_diff($requiredCategoryIds, $existingCategoryIds);

        // Add missing application_criteria (with unique constraint: application_id + category_id)
        foreach ($missingCategoryIds as $categoryId) {
            // Use firstOrCreate to avoid duplicate key errors
            ApplicationCriterion::firstOrCreate(
                [
                    'application_id' => $application->id,
                    'category_id' => $categoryId,
                ],
                [
                    'status_id' => 1, // Default status
                    'is_ready' => false,
                ]
            );
        }

        // Find excess category_ids that need to be removed (integers already)
        $excessCategoryIds = array_diff($existingCategoryIds, $requiredCategoryIds);

        // DEBUG: Log sync operation
        \Illuminate\Support\Facades\Log::info("LicenceRequirementObserver::syncCriteriaForApplication", [
            'application_id' => $application->id,
            'requiredCategoryIds' => $requiredCategoryIds,
            'existingCategoryIds' => $existingCategoryIds,
            'missingCategoryIds' => $missingCategoryIds,
            'excessCategoryIds' => $excessCategoryIds,
        ]);

        // Delete excess application_criteria ONLY if they haven't started processing
        // Don't delete criteria that have status_id > 1 (already in review process)
        if (! empty($excessCategoryIds)) {
            $deletedCount = ApplicationCriterion::where('application_id', $application->id)
                ->whereIn('category_id', $excessCategoryIds)
                ->where('status_id', 1) // Only delete if still in initial state
                ->delete();

            if ($deletedCount > 0) {
                \Illuminate\Support\Facades\Log::warning("LicenceRequirementObserver: Deleted {$deletedCount} criteria", [
                    'application_id' => $application->id,
                    'category_ids' => $excessCategoryIds,
                ]);
            }

            // Log warning if there are criteria that couldn't be deleted
            $remainingCount = ApplicationCriterion::where('application_id', $application->id)
                ->whereIn('category_id', $excessCategoryIds)
                ->where('status_id', '>', 1)
                ->count();

            if ($remainingCount > 0) {
                \Illuminate\Support\Facades\Log::warning("LicenceRequirementObserver: {$remainingCount} criteria not deleted (already in review)", [
                    'application_id' => $application->id,
                    'category_ids' => $excessCategoryIds,
                ]);
            }
        }
    }
}
