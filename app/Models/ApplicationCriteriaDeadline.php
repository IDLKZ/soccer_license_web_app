<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ApplicationCriteriaDeadline
 * 
 * @property int $id
 * @property int $application_id
 * @property int $application_criteria_id
 * @property Carbon|null $deadline_start_at
 * @property Carbon $deadline_end_at
 * @property int $status_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property ApplicationCriterion $application_criterion
 * @property Application $application
 * @property ApplicationStatus $application_status
 *
 * @package App\Models
 */
class ApplicationCriteriaDeadline extends Model
{
	protected $table = 'application_criteria_deadline';

	protected $casts = [
		'application_id' => 'int',
		'application_criteria_id' => 'int',
		'deadline_start_at' => 'datetime',
		'deadline_end_at' => 'datetime',
		'status_id' => 'int'
	];

	protected $fillable = [
		'application_id',
		'application_criteria_id',
		'deadline_start_at',
		'deadline_end_at',
		'status_id'
	];

	public function application_criterion()
	{
		return $this->belongsTo(ApplicationCriterion::class, 'application_criteria_id');
	}

	public function application()
	{
		return $this->belongsTo(Application::class);
	}

	public function application_status()
	{
		return $this->belongsTo(ApplicationStatus::class, 'status_id');
	}
}
