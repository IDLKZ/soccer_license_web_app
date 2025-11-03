<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ApplicationCriterion
 *
 * @property int $id
 * @property int|null $application_id
 * @property int|null $category_id
 * @property int|null $status_id
 * @property int|null $uploaded_by_id
 * @property string|null $uploaded_by
 * @property int|null $first_checked_by_id
 * @property string|null $first_checked_by
 * @property int|null $checked_by_id
 * @property string|null $checked_by
 * @property int|null $control_checked_by_id
 * @property string|null $control_checked_by
 * @property bool $is_ready
 * @property bool|null $is_first_passed
 * @property string|null $first_comment
 * @property bool|null $is_industry_passed
 * @property string|null $industry_comment
 * @property bool|null $is_final_passed
 * @property string|null $final_comment
 * @property string|null $last_comment
 * @property bool|null $can_reupload_after_ending
 * @property array|null $can_reupload_after_endings_doc_ids
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Application|null $application
 * @property CategoryDocument|null $category_document
 * @property User|null $user
 * @property ApplicationStatus|null $application_status
 * @property Collection|ApplicationReport[] $application_reports
 * @property Collection|ApplicationStep[] $application_steps
 *
 * @package App\Models
 */
class ApplicationCriterion extends Model
{
	protected $table = 'application_criteria';

	protected $casts = [
		'application_id' => 'int',
		'category_id' => 'int',
		'status_id' => 'int',
		'uploaded_by_id' => 'int',
		'first_checked_by_id' => 'int',
		'checked_by_id' => 'int',
		'control_checked_by_id' => 'int',
		'is_ready' => 'bool',
		'is_first_passed' => 'bool',
		'is_industry_passed' => 'bool',
		'is_final_passed' => 'bool',
		'can_reupload_after_ending' => 'bool',
		'can_reupload_after_endings_doc_ids' => 'json'
	];

	protected $fillable = [
		'application_id',
		'category_id',
		'status_id',
		'uploaded_by_id',
		'uploaded_by',
		'first_checked_by_id',
		'first_checked_by',
		'checked_by_id',
		'checked_by',
		'control_checked_by_id',
		'control_checked_by',
		'is_ready',
		'is_first_passed',
		'first_comment',
		'is_industry_passed',
		'industry_comment',
		'is_final_passed',
		'final_comment',
		'last_comment',
		'can_reupload_after_ending',
		'can_reupload_after_endings_doc_ids'
	];

	public function application()
	{
		return $this->belongsTo(Application::class);
	}

	public function category_document()
	{
		return $this->belongsTo(CategoryDocument::class, 'category_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'uploaded_by_id');
	}

	public function application_status()
	{
		return $this->belongsTo(ApplicationStatus::class, 'status_id');
	}

	public function application_reports()
	{
		return $this->hasMany(ApplicationReport::class, 'criteria_id');
	}

	public function application_steps()
	{
		return $this->hasMany(ApplicationStep::class, 'application_criteria_id');
	}
}
