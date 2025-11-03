# ApplicationStep Example Usage

## Testing the Observer

To test the ApplicationStep creation functionality:

### 1. Create Test Application Criterion

```php
use App\Models\Application;
use App\Models\ApplicationCriterion;
use App\Models\ApplicationStatus;

// Get or create test application
$application = Application::find(1);

// Get first check status
$status = ApplicationStatus::where('value', 'awaiting-first-check')->first();

// Create criterion with status
$criterion = ApplicationCriterion::create([
    'application_id' => $application->id,
    'category_id' => 1,
    'application_status_id' => $status->id,
    'title_ru' => 'Test Criterion',
    'title_kk' => 'Test Criterion',
    'title_en' => 'Test Criterion',
]);
```

### 2. Check Created ApplicationStep

```php
use App\Models\ApplicationStep;

// Get the created step
$step = ApplicationStep::where('application_criteria_id', $criterion->id)->first();

echo "Step ID: " . $step->id . "\n";
echo "Application ID: " . $step->application_id . "\n";
echo "Criterion ID: " . $step->application_criteria_id . "\n";
echo "Status: " . $step->application_status->value . "\n";
echo "Responsible By: " . $step->responsible_by . "\n";
echo "Is Passed: " . ($step->is_passed ? 'Yes' : 'No') . "\n";
echo "Result: " . $step->result . "\n";
```

### 3. Update Criterion Status

```php
// Get industry check status
$newStatus = ApplicationStatus::where('value', 'awaiting-industry-check')->first();

// Update criterion status - this will trigger the observer
$criterion->update(['application_status_id' => $newStatus->id]);

// Check if new step was created
$newStep = ApplicationStep::where('application_criteria_id', $criterion->id)
    ->where('status_id', $newStatus->id)
    ->first();

if ($newStep) {
    echo "New step created successfully!\n";
    echo "New Responsible By: " . $newStep->responsible_by . "\n";
}
```

### 4. Check Application Status Update

```php
// Refresh application
$application->refresh();

echo "Application Category ID: " . $application->category_id . "\n";
echo "Application Status: " . $application->application_status_category->value . "\n";
```

## Expected Results

1. **Initial creation**: Creates ApplicationStep with Licensing Department as responsible
2. **Status update**: Creates new ApplicationStep with appropriate industry department
3. **Application status**: Application category_id updates automatically
4. **Logging**: Check logs for observer activity

## Common Issues

- **No steps created**: Check if observer is registered in AppServiceProvider
- **Wrong responsible user**: Verify user roles and active status
- **No application status update**: Check trigger statuses and category progression