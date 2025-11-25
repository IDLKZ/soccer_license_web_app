<?php

namespace App\Observers;

use App\Constants\ApplicationStatusConstants;
use App\Constants\RoleConstants;
use App\Models\Application;
use App\Models\ApplicationCriterion;
use App\Models\ApplicationInitialReport;
use App\Models\ApplicationReport;
use App\Models\ApplicationSolution;
use App\Models\ApplicationStatus;
use App\Models\ApplicationStep;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApplicationCriterionObserver
{
    /**
     * Handle the ApplicationCriterion "created" event.
     */
    public function created(ApplicationCriterion $applicationCriterion): void
    {
        // Check if the new criterion has a status that should update the application
        if ($applicationCriterion->status_id) {
            $this->updateApplicationStatus($applicationCriterion);
            $this->createApplicationStep($applicationCriterion);
            $this->createApplicationInitialReportIfAwaitingIndustryCheck($applicationCriterion);
            $this->createApplicationReportIfAwaitingControlCheck($applicationCriterion);
        }
    }

    /**
     * Handle the ApplicationCriterion "updated" event.
     */
    public function updated(ApplicationCriterion $applicationCriterion): void
    {
        // Check if status_id was changed
        if ($applicationCriterion->isDirty('status_id')) {
            $this->updateApplicationStatus($applicationCriterion);
            $this->createApplicationStep($applicationCriterion);
            $this->createApplicationInitialReportIfAwaitingIndustryCheck($applicationCriterion);
            $this->createApplicationReportIfAwaitingControlCheck($applicationCriterion);
        }
    }

    /**
     * Update application category based on criterion status changes
     */
    private function updateApplicationStatus(ApplicationCriterion $applicationCriterion): void
    {
        $application = $applicationCriterion->application;

        if (! $application) {
            return;
        }

        // Reload the application_status relationship to get the NEW status
        $applicationCriterion->load('application_status');

        if (! $applicationCriterion->application_status) {
            return;
        }

        $statusValue = $applicationCriterion->application_status->value;

        // Define the statuses that trigger application category updates
        $triggerStatuses = [
            ApplicationStatusConstants::AWAITING_FIRST_CHECK_VALUE,
            ApplicationStatusConstants::AWAITING_INDUSTRY_CHECK_VALUE,
            ApplicationStatusConstants::AWAITING_CONTROL_CHECK_VALUE,
            ApplicationStatusConstants::AWAITING_FINAL_DECISION_VALUE,
        ];

        if (! in_array($statusValue, $triggerStatuses)) {
            return;
        }

        // Get the status category for the current status
        $status = ApplicationStatus::with('application_status_category')
            ->where('value', $statusValue)
            ->first();

        if (! $status || ! $status->application_status_category) {
            return;
        }

        $newCategoryId = $status->application_status_category->id;
        $currentCategoryId = $application->category_id;

        // Only update if moving forward (category_id should increase)
        if ($newCategoryId > $currentCategoryId) {
            DB::transaction(function () use ($application, $newCategoryId, $statusValue) {
                $application->update([
                    'category_id' => $newCategoryId,
                ]);

                Log::info("Application #{$application->id} category updated to '{$statusValue}' (category_id: {$newCategoryId}) based on criterion status change.");
            });
        }
    }

    /**
     * Create ApplicationStep when criterion status changes
     */
    private function createApplicationStep(ApplicationCriterion $applicationCriterion): void
    {
        if (! $applicationCriterion->application_status) {
            return;
        }

        try {
            ApplicationStep::create([
                'application_id' => $applicationCriterion->application_id,
                'application_criteria_id' => $applicationCriterion->id,
                'status_id' => $applicationCriterion->status_id,
                'responsible_id' => $this->getResponsibleUser($applicationCriterion),
                'responsible_by' => $this->getResponsibleBy($applicationCriterion),
                'is_passed' => $this->determineStepStatus($applicationCriterion),
                'result' => $this->getStepResult($applicationCriterion),
            ]);

            Log::info("ApplicationStep created for criterion #{$applicationCriterion->id} with status '{$applicationCriterion->application_status->value}'");
        } catch (\Exception $e) {
            Log::error("Failed to create ApplicationStep for criterion #{$applicationCriterion->id}: ".$e->getMessage());
        }
    }

    /**
     * Create ApplicationInitialReport when status changes to awaiting-industry-check
     */
    private function createApplicationInitialReportIfAwaitingIndustryCheck(ApplicationCriterion $applicationCriterion): void
    {
        if (! $applicationCriterion->application_status) {
            return;
        }

        $statusValue = $applicationCriterion->application_status->value;

        // Check if status is awaiting-industry-check
        if ($statusValue !== ApplicationStatusConstants::AWAITING_INDUSTRY_CHECK_VALUE) {
            return;
        }

        try {
            // Check if ApplicationInitialReport already exists for this application
            $existingReport = ApplicationInitialReport::where('application_id', $applicationCriterion->application_id)
                ->where('criteria_id',$applicationCriterion->id)
                ->first();

            if (! $existingReport) {
                ApplicationInitialReport::create([
                    'application_id' => $applicationCriterion->application_id,
                    'criteria_id' => $applicationCriterion->id,
                    'status' => 1, // Default status
                ]);

                Log::info("ApplicationInitialReport created for application #{$applicationCriterion->application_id} when criterion reached awaiting-industry-check status");
            } else {
                Log::info("ApplicationInitialReport already exists for application #{$applicationCriterion->application_id}, skipping creation.");
            }
        } catch (\Exception $e) {
            Log::error("Failed to create ApplicationInitialReport for application #{$applicationCriterion->application_id}: ".$e->getMessage());
        }
    }

    /**
     * Create ApplicationReport when status changes to awaiting-control-check
     * Only creates if all other criteria have status_id >= current status_id
     */
    private function createApplicationReportIfAwaitingControlCheck(ApplicationCriterion $applicationCriterion): void
    {
        if (! $applicationCriterion->application_status) {
            return;
        }

        $statusValue = $applicationCriterion->application_status->value;

        // Check if status is awaiting-control-check
        if ($statusValue !== ApplicationStatusConstants::AWAITING_CONTROL_CHECK_VALUE && $statusValue !== ApplicationStatusConstants::AWAITING_FINAL_DECISION_VALUE) {
            return;
        }

        // General application report (criteria_id = null) will be created automatically
        // after all criteria reports are generated via the "Generate Report" button

        // Individual criterion reports are now created manually via "Generate Report" button
        // with document selection, so we don't auto-create them here anymore
        Log::info("Criterion #{$applicationCriterion->id} reached awaiting-control-check. Report can be generated manually.");
    }

    /**
     * Check if all application criteria have final statuses and create ApplicationSolution if so
     */

    /**
     * Check if all criteria in an application have final statuses
     */

    /**
     * Get responsible user for the step
     */
    private function getResponsibleUser(ApplicationCriterion $applicationCriterion): ?int
    {
        // If there's a logged in user and they are department staff, assign to them
        $user = Auth::user();
        if ($user && $user->isDepartmentUser()) {
            return $user->id;
        }

        // Otherwise, try to determine based on status
        $statusValue = $applicationCriterion->application_status->value;

        return match ($statusValue) {
            ApplicationStatusConstants::AWAITING_FIRST_CHECK_VALUE => $this->getDepartmentUserByRole(RoleConstants::LICENSING_DEPARTMENT_VALUE),
            ApplicationStatusConstants::AWAITING_INDUSTRY_CHECK_VALUE => $this->getIndustryDepartmentUserId($applicationCriterion),
            ApplicationStatusConstants::AWAITING_CONTROL_CHECK_VALUE => $this->getDepartmentUserByRole(RoleConstants::CONTROL_DEPARTMENT_VALUE),
            default => null
        };
    }

    /**
     * Get responsible by description
     */
    private function getResponsibleBy(ApplicationCriterion $applicationCriterion): string
    {
        $statusValue = $applicationCriterion->application_status->value;

        return match ($statusValue) {
            ApplicationStatusConstants::AWAITING_FIRST_CHECK_VALUE => 'Licensing Department',
            ApplicationStatusConstants::AWAITING_INDUSTRY_CHECK_VALUE => $this->getIndustryDepartmentName($applicationCriterion),
            ApplicationStatusConstants::AWAITING_CONTROL_CHECK_VALUE => 'Control Department',
            ApplicationStatusConstants::FIRST_CHECK_REVISION_VALUE => 'Club',
            ApplicationStatusConstants::INDUSTRY_CHECK_REVISION_VALUE => 'Club',
            ApplicationStatusConstants::CONTROL_CHECK_REVISION_VALUE => 'Club',
            default => 'System'
        };
    }

    /**
     * Determine if step is passed
     */
    private function determineStepStatus(ApplicationCriterion $applicationCriterion): ?bool
    {
        $statusValue = $applicationCriterion->application_status->value;

        return match ($statusValue) {
            ApplicationStatusConstants::FULLY_APPROVED_VALUE => true,
            ApplicationStatusConstants::REVOKED_VALUE => false,
            ApplicationStatusConstants::REJECTED_VALUE => false,
            default => null // Still in progress
        };
    }

    /**
     * Get step result description
     */
    private function getStepResult(ApplicationCriterion $applicationCriterion): ?string
    {
        $statusValue = $applicationCriterion->application_status->value;

        return match ($statusValue) {
            ApplicationStatusConstants::FULLY_APPROVED_VALUE => 'Fully approved',
            ApplicationStatusConstants::PARTIALLY_APPROVED_VALUE => 'Partially approved',
            ApplicationStatusConstants::REVOKED_VALUE => 'Revoked',
            ApplicationStatusConstants::REJECTED_VALUE => 'Rejected',
            ApplicationStatusConstants::FIRST_CHECK_REVISION_VALUE => 'Returned for revision',
            ApplicationStatusConstants::INDUSTRY_CHECK_REVISION_VALUE => 'Returned for revision',
            ApplicationStatusConstants::CONTROL_CHECK_REVISION_VALUE => 'Returned for revision',
            default => null
        };
    }

    /**
     * Get user by role
     */
    private function getDepartmentUserByRole(string $roleValue): ?int
    {
        try {
            $role = Role::where('value', $roleValue)->first();
            if (! $role) {
                return null;
            }

            // Get the first active user with this role
            $user = User::where('role_id', $role->id)
                ->where('is_active', true)
                ->first();

            return $user?->id;
        } catch (\Exception $e) {
            Log::error("Error getting user by role {$roleValue}: ".$e->getMessage());

            return null;
        }
    }

    /**
     * Get industry department user ID based on criterion category
     */
    private function getIndustryDepartmentUserId(ApplicationCriterion $applicationCriterion): ?int
    {
        // Determine which industry department based on criterion category
        $categoryId = $applicationCriterion->category_id;

        $roleValue = match ($categoryId) {
            // Legal documents (categories 1-2)
            1, 2 => RoleConstants::LEGAL_DEPARTMENT_VALUE,
            // Financial documents (categories 3-4)
            3, 4 => RoleConstants::FINANCE_DEPARTMENT_VALUE,
            // Infrastructure documents (categories 5-6)
            5, 6 => RoleConstants::INFRASTRUCTURE_DEPARTMENT_VALUE,
            default => null
        };

        return $roleValue ? $this->getDepartmentUserByRole($roleValue) : null;
    }

    /**
     * Get industry department name based on criterion category
     */
    private function getIndustryDepartmentName(ApplicationCriterion $applicationCriterion): string
    {
        $categoryId = $applicationCriterion->category_id;

        return match ($categoryId) {
            1, 2 => 'Legal Department',
            3, 4 => 'Finance Department',
            5, 6 => 'Infrastructure Department',
            default => 'Industry Department'
        };
    }

    /**
     * Handle the ApplicationCriterion "deleted" event.
     */
    public function deleted(ApplicationCriterion $applicationCriterion): void
    {
        //
    }

    /**
     * Handle the ApplicationCriterion "restored" event.
     */
    public function restored(ApplicationCriterion $applicationCriterion): void
    {
        //
    }

    /**
     * Handle the ApplicationCriterion "force deleted" event.
     */
    public function forceDeleted(ApplicationCriterion $applicationCriterion): void
    {
        //
    }
}
