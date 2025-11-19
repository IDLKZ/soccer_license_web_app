<?php

namespace App\Observers;

use App\Constants\ApplicationStatusCategoryConstants;
use App\Models\Application;
use App\Models\ApplicationSolution;
use App\Models\ApplicationStatusCategory;
use App\Models\LicenseCertificate;
use Illuminate\Support\Facades\Log;

class ApplicationObserver
{
    /**
     * Handle the Application "created" event.
     */
    public function created(Application $application): void
    {
        //
    }

    /**
     * Handle the Application "updated" event.
     */
    public function updated(Application $application): void
    {
        // Check if category_id was changed
        if ($application->isDirty('category_id')) {
            $this->createLicenseCertificateIfApproved($application);
        }
    }

    /**
     * Create LicenseCertificate when application is approved
     */
    private function createLicenseCertificateIfApproved(Application $application): void
    {
        if (! $application->category_id) {
            return;
        }

        try {
            // Get the application status category
            $category = ApplicationStatusCategory::find($application->category_id);

            if (! $category) {
                return;
            }

            // Check if category value is 'approved'
            if ($category->value !== ApplicationStatusCategoryConstants::APPROVED_VALUE) {
                return;
            }

            // Check if certificate already exists
            $existingCertificate = LicenseCertificate::where('application_id', $application->id)->first();

            if ($existingCertificate) {
                Log::info("LicenseCertificate already exists for application #{$application->id}, skipping creation.");

                return;
            }
            // Create ApplicationSolution
            ApplicationSolution::create([
                'application_id' => $application->id,
            ]);
            // Create LicenseCertificate
            LicenseCertificate::create([
                'application_id' => $application->id,
                'license_id' => $application->license_id,
                'club_id' => $application->club_id,
            ]);

            Log::info("LicenseCertificate created for application #{$application->id}");
        } catch (\Exception $e) {
            Log::error("Failed to create LicenseCertificate for application #{$application->id}: ".$e->getMessage());
        }
    }

    /**
     * Handle the Application "deleting" event.
     *
     * This method is called before the application is deleted.
     * We use 'deleting' instead of 'deleted' to ensure cascading deletes
     * happen before the parent record is removed.
     */
    public function deleting(Application $application): void
    {
        try {
            // Delete related application criteria deadlines
            if ($application->application_criteria_deadlines()->exists()) {
                $deadlinesCount = $application->application_criteria_deadlines()->count();
                $application->application_criteria_deadlines()->delete();
                Log::info("Deleted {$deadlinesCount} application criteria deadlines for application ID: {$application->id}");
            }

            // Delete related application criteria
            if ($application->application_criteria()->exists()) {
                $criteriaCount = $application->application_criteria()->count();
                $application->application_criteria()->delete();
                Log::info("Deleted {$criteriaCount} application criteria for application ID: {$application->id}");
            }

            // Delete related application documents (pivot table records)
            $documentsCount = \App\Models\ApplicationDocument::where('application_id', $application->id)->count();
            if ($documentsCount > 0) {
                \App\Models\ApplicationDocument::where('application_id', $application->id)->delete();
                Log::info("Deleted {$documentsCount} application documents for application ID: {$application->id}");
            }

            // Delete related application initial reports
            if ($application->application_initial_reports()->exists()) {
                $initialReportsCount = $application->application_initial_reports()->count();
                $application->application_initial_reports()->delete();
                Log::info("Deleted {$initialReportsCount} application initial reports for application ID: {$application->id}");
            }

            // Delete related application reports
            if ($application->application_reports()->exists()) {
                $reportsCount = $application->application_reports()->count();
                $application->application_reports()->delete();
                Log::info("Deleted {$reportsCount} application reports for application ID: {$application->id}");
            }

            // Delete related application solutions
            if ($application->application_solutions()->exists()) {
                $solutionsCount = $application->application_solutions()->count();
                $application->application_solutions()->delete();
                Log::info("Deleted {$solutionsCount} application solutions for application ID: {$application->id}");
            }

            // Delete related application steps
            if ($application->application_steps()->exists()) {
                $stepsCount = $application->application_steps()->count();
                $application->application_steps()->delete();
                Log::info("Deleted {$stepsCount} application steps for application ID: {$application->id}");
            }

            Log::info("Successfully cleaned up related records for application ID: {$application->id}");
        } catch (\Exception $e) {
            Log::error("Error deleting related records for application ID: {$application->id}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Re-throw the exception to prevent the application from being deleted if cleanup fails
            throw $e;
        }
    }

    /**
     * Handle the Application "deleted" event.
     *
     * This method is called after the application has been deleted.
     * Can be used for logging or notifications.
     */
    public function deleted(Application $application): void
    {
        Log::info('Application deleted successfully', [
            'id' => $application->id,
        ]);
    }

    /**
     * Handle the Application "restored" event.
     */
    public function restored(Application $application): void
    {
        //
    }

    /**
     * Handle the Application "force deleted" event.
     */
    public function forceDeleted(Application $application): void
    {
        //
    }
}
