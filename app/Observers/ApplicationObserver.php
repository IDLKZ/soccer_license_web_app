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
            //Create ApplicationSolution
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
     * Handle the Application "deleted" event.
     */
    public function deleted(Application $application): void
    {
        //
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
