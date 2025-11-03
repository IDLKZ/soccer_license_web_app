<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ApplicationStatus
 * 
 * @property int $id
 * @property int|null $category_id
 * @property int|null $previous_id
 * @property int|null $next_id
 * @property string $title_ru
 * @property string $title_kk
 * @property string|null $title_en
 * @property string|null $description_ru
 * @property string|null $description_kk
 * @property string|null $description_en
 * @property string $value
 * @property array|null $role_values
 * @property bool $is_active
 * @property bool $is_first
 * @property bool $is_last
 * @property int $result
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property ApplicationStatusCategory|null $application_status_category
 * @property ApplicationStatus|null $application_status
 * @property Collection|ApplicationCriterion[] $application_criteria
 * @property Collection|ApplicationStatus[] $application_statuses
 * @property Collection|ApplicationStep[] $application_steps
 *
 * @package App\Models
 */
class ApplicationStatus extends Model
{
	protected $table = 'application_statuses';

	protected $casts = [
		'category_id' => 'int',
		'previous_id' => 'int',
		'next_id' => 'int',
		'role_values' => 'json',
		'is_active' => 'bool',
		'is_first' => 'bool',
		'is_last' => 'bool',
		'result' => 'int'
	];

	protected $fillable = [
		'category_id',
		'previous_id',
		'next_id',
		'title_ru',
		'title_kk',
		'title_en',
		'description_ru',
		'description_kk',
		'description_en',
		'value',
		'role_values',
		'is_active',
		'is_first',
		'is_last',
		'result'
	];

	public function application_status_category()
	{
		return $this->belongsTo(ApplicationStatusCategory::class, 'category_id');
	}

	public function application_status()
	{
		return $this->belongsTo(ApplicationStatus::class, 'previous_id');
	}

	public function application_criteria()
	{
		return $this->hasMany(ApplicationCriterion::class, 'status_id');
	}

	public function application_statuses()
	{
		return $this->hasMany(ApplicationStatus::class, 'previous_id');
	}

	public function application_steps()
	{
		return $this->hasMany(ApplicationStep::class, 'status_id');
	}
}
