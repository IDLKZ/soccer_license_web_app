# Application Observer Documentation

## Overview
The `ApplicationCriterionObserver` automatically updates the status of `Application` when any `ApplicationCriterion` changes its status to specific workflow stages.

## Workflow Logic

### Trigger Statuses
The observer monitors the following statuses in `ApplicationCriterion`:
- `awaiting-first-check`
- `awaiting-industry-check`
- `awaiting-control-check`
- `awaiting-final-decision`

### Update Rule
When an `ApplicationCriterion` changes to one of the trigger statuses:
1. The observer finds the corresponding `ApplicationStatusCategory`
2. Updates `application.category_id` to match the status's category
3. **Only updates if moving forward** - `new_category_id > current_category_id`

### Implementation Details

#### File: `app/Observers/ApplicationCriterionObserver.php`

```php
public function updated(ApplicationCriterion $applicationCriterion): void
{
    // Check if application_status_id was changed
    if ($applicationCriterion->isDirty('application_status_id')) {
        $this->updateApplicationStatus($applicationCriterion);
    }
}
```

#### File: `app/Providers/AppServiceProvider.php`
```php
public function boot(): void
{
    ApplicationCriterion::observe(ApplicationCriterionObserver::class);
}
```

## Status Flow

| Criterion Status | Application Category |
|------------------|---------------------|
| `awaiting-first-check` | `first-check` |
| `awaiting-industry-check` | `industry-check` |
| `awaiting-control-check` | `control-check` |
| `awaiting-final-decision` | `final-decision` |

## Important Notes

1. **One-way progression**: Application category only moves forward, never backward
2. **Transaction safety**: All updates wrapped in database transactions
3. **Logging**: Changes are logged for debugging
4. **No manual updates needed**: The system handles this automatically

## Example Scenario

1. User submits documents → `ApplicationCriterion` status = `awaiting-documents`
2. Licensing department reviews → status changes to `awaiting-first-check`
3. **Observer triggers**: `Application.category_id` updates to `first-check` category
4. Industry department reviews → status changes to `awaiting-industry-check`
5. **Observer triggers**: `Application.category_id` updates to `industry-check` category

This ensures the application status always reflects the current workflow stage of its criteria.

## ApplicationStep Creation

In addition to updating the application status, the observer also creates `ApplicationStep` records to track the workflow progress.

### Step Creation Logic

When an `ApplicationCriterion` changes status, a new `ApplicationStep` is created with:

- **Application ID**: The parent application
- **Criterion ID**: The specific criterion that changed
- **Status ID**: The new status of the criterion
- **Responsible User**: Assigned based on status type
- **Responsible By**: Description of the responsible department
- **Is Passed**: Boolean indicating step completion
- **Result**: Text description of the step result

### Responsible User Assignment

| Status | Responsible User | Responsible By |
|--------|------------------|----------------|
| `awaiting-first-check` | Licensing Department user | "Licensing Department" |
| `awaiting-industry-check` | Legal/Finance/Infrastructure user | Based on category |
| `awaiting-control-check` | Control Department user | "Control Department" |
| Revision statuses | Club user (if logged in) | "Club" |
| Final statuses | System assigned | "System" |

### Industry Department Assignment

Based on criterion category:
- **Categories 1-2**: Legal Department
- **Categories 3-4**: Finance Department
- **Categories 5-6**: Infrastructure Department

### Step Status Determination

- **Passed**: `fully-approved` status
- **Failed**: `revoked`, `rejected` status
- **In Progress**: All other statuses (null)

### Example Workflow

1. Club submits documents → Criterion status = `awaiting-first-check`
2. **Step created**: ApplicationStep with responsible = Licensing Department
3. Licensing approves → Criterion status = `awaiting-industry-check`
4. **Step created**: ApplicationStep with responsible = appropriate industry department
5. Industry approves → Criterion status = `fully-approved`
6. **Step created**: ApplicationStep marked as passed

This provides a complete audit trail of who handled each step of the application process.