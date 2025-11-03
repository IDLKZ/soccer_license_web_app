<?php

namespace App\Observers;

use App\Constants\ApplicationStatusConstants;
use App\Constants\RoleConstants;
use App\Models\Application;
use App\Models\ApplicationCriterion;
use App\Models\ApplicationStatus;
use App\Models\ApplicationStatusCategory;
use App\Models\ApplicationStep;
use App\Models\User;
use App\Models\Role;
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
        if ($applicationCriterion->application_status_id) {
            $this->updateApplicationStatus($applicationCriterion);
            $this->createApplicationStep($applicationCriterion);
        }
    }

    /**
     * Handle the ApplicationCriterion "updated" event.
     */
    public function updated(ApplicationCriterion $applicationCriterion): void
    {
        // Check if application_status_id was changed
        if ($applicationCriterion->isDirty('application_status_id')) {
            $this->updateApplicationStatus($applicationCriterion);
            $this->createApplicationStep($applicationCriterion);
        }
    }

    /**
     * Update application category based on criterion status changes
     */
    private function updateApplicationStatus(ApplicationCriterion $applicationCriterion): void
    {
        $application = $applicationCriterion->application;

        if (!$application || !$applicationCriterion->application_status) {
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

        if (!in_array($statusValue, $triggerStatuses)) {
            return;
        }

        // Get the status category for the current status
        $status = ApplicationStatus::where('value', $statusValue)->first();

        if (!$status || !$status->category) {
            return;
        }

        $newCategoryId = $status->category->id;
        $currentCategoryId = $application->category_id;

        // Only update if moving forward (category_id should increase)
        if ($newCategoryId > $currentCategoryId) {
            DB::transaction(function () use ($application, $newCategoryId, $statusValue) {
                $application->update([
                    'category_id' => $newCategoryId
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
        if (!$applicationCriterion->application_status) {
            return;
        }

        try {
            ApplicationStep::create([
                'application_id' => $applicationCriterion->application_id,
                'application_criteria_id' => $applicationCriterion->id,
                'status_id' => $applicationCriterion->application_status_id,
                'responsible_id' => $this->getResponsibleUser($applicationCriterion),
                'responsible_by' => $this->getResponsibleBy($applicationCriterion),
                'is_passed' => $this->determineStepStatus($applicationCriterion),
                'result' => $this->getStepResult($applicationCriterion)
            ]);

            Log::info("ApplicationStep created for criterion #{$applicationCriterion->id} with status '{$applicationCriterion->application_status->value}'");
        } catch (\Exception $e) {
            Log::error("Failed to create ApplicationStep for criterion #{$applicationCriterion->id}: " . $e->getMessage());
        }
    }

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

        return match($statusValue) {
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

        return match($statusValue) {
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

        return match($statusValue) {
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

        return match($statusValue) {
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
            if (!$role) {
                return null;
            }

            // Get the first active user with this role
            $user = User::where('role_id', $role->id)
                ->where('is_active', true)
                ->first();

            return $user?->id;
        } catch (\Exception $e) {
            Log::error("Error getting user by role {$roleValue}: " . $e->getMessage());
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

        $roleValue = match($categoryId) {
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

        return match($categoryId) {
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
