<?php

namespace App\Observers;

use App\Models\Licence;
use Illuminate\Support\Facades\Log;

class LicenceObserver
{
    /**
     * Handle the Licence "deleting" event.
     *
     * This method is called before the licence is deleted.
     * We use 'deleting' instead of 'deleted' to ensure cascading deletes
     * happen before the parent record is removed.
     *
     * @param  \App\Models\Licence  $licence
     * @return void
     */
    public function deleting(Licence $licence): void
    {
        try {
            // Delete related licence requirements
            if ($licence->licence_requirements()->exists()) {
                $requirementsCount = $licence->licence_requirements()->count();
                $licence->licence_requirements()->delete();
                Log::info("Deleted {$requirementsCount} licence requirements for licence ID: {$licence->id}");
            }

            // Delete related licence deadlines
            if ($licence->licence_deadlines()->exists()) {
                $deadlinesCount = $licence->licence_deadlines()->count();
                $licence->licence_deadlines()->delete();
                Log::info("Deleted {$deadlinesCount} licence deadlines for licence ID: {$licence->id}");
            }

            // Delete related license certificates
            if ($licence->license_certificates()->exists()) {
                $certificatesCount = $licence->license_certificates()->count();
                $licence->license_certificates()->delete();
                Log::info("Deleted {$certificatesCount} license certificates for licence ID: {$licence->id}");
            }

            Log::info("Successfully cleaned up related records for licence ID: {$licence->id}");
        } catch (\Exception $e) {
            Log::error("Error deleting related records for licence ID: {$licence->id}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Re-throw the exception to prevent the licence from being deleted if cleanup fails
            throw $e;
        }
    }

    /**
     * Handle the Licence "deleted" event.
     *
     * This method is called after the licence has been deleted.
     * Can be used for logging or notifications.
     *
     * @param  \App\Models\Licence  $licence
     * @return void
     */
    public function deleted(Licence $licence): void
    {
        Log::info("Licence deleted successfully", [
            'id' => $licence->id,
            'title' => $licence->title_ru
        ]);
    }
}
