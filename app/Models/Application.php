<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Application
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $license_id
 * @property int|null $club_id
 * @property int|null $category_id
 * @property bool|null $can_reupload_after_ending
 * @property array|null $can_reupload_after_endings_doc_ids
 * @property bool $is_ready
 * @property bool|null $is_active
 * @property Carbon|null $deadline
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property ApplicationStatusCategory|null $application_status_category
 * @property Club|null $club
 * @property Licence|null $licence
 * @property User|null $user
 * @property Collection|ApplicationCriterion[] $application_criteria
 * @property Collection|ApplicationCriteriaDeadline[] $application_criteria_deadlines
 * @property Collection|ApplicationInitialReport[] $application_initial_reports
 * @property Collection|Document[] $documents
 * @property Collection|ApplicationReport[] $application_reports
 * @property Collection|ApplicationSolution[] $application_solutions
 * @property Collection|ApplicationStep[] $application_steps
 */
class Application extends Model
{
    protected $table = 'applications';

    protected $casts = [
        'user_id' => 'int',
        'license_id' => 'int',
        'club_id' => 'int',
        'category_id' => 'int',
        'can_reupload_after_ending' => 'bool',
        'can_reupload_after_endings_doc_ids' => 'json',
        'is_ready' => 'bool',
        'is_active' => 'bool',
        'deadline' => 'datetime',
    ];

    protected $fillable = [
        'user_id',
        'license_id',
        'club_id',
        'category_id',
        'can_reupload_after_ending',
        'can_reupload_after_endings_doc_ids',
        'is_ready',
        'is_active',
        'deadline',
    ];

    public function application_status_category()
    {
        return $this->belongsTo(ApplicationStatusCategory::class, 'category_id');
    }

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function licence()
    {
        return $this->belongsTo(Licence::class, 'license_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function application_criteria()
    {
        return $this->hasMany(ApplicationCriterion::class);
    }

    public function application_criteria_deadlines()
    {
        return $this->hasMany(ApplicationCriteriaDeadline::class);
    }

    public function application_initial_reports()
    {
        return $this->hasMany(ApplicationInitialReport::class);
    }

    public function documents()
    {
        return $this->belongsToMany(Document::class, 'application_documents')
            ->withPivot('id', 'category_id', 'file_url', 'uploaded_by_id', 'uploaded_by', 'first_checked_by_id', 'first_checked_by', 'checked_by_id', 'checked_by', 'control_checked_by_id', 'control_checked_by', 'is_first_passed', 'is_industry_passed', 'is_final_passed', 'title', 'info', 'first_comment', 'industry_comment', 'control_comment', 'deadline')
            ->withTimestamps();
    }

    public function application_reports()
    {
        return $this->hasMany(ApplicationReport::class);
    }

    public function application_solutions()
    {
        return $this->hasMany(ApplicationSolution::class);
    }

    public function application_steps()
    {
        return $this->hasMany(ApplicationStep::class);
    }
}
