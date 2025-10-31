<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ApplicationStep
 * 
 * @property int $id
 * @property int|null $application_id
 * @property int|null $application_criteria_id
 * @property int|null $status_id
 * @property int|null $responsible_id
 * @property string|null $file_url
 * @property string|null $responsible_by
 * @property bool|null $is_passed
 * @property string|null $result
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property ApplicationCriterion|null $application_criterion
 * @property Application|null $application
 * @property User|null $user
 * @property ApplicationStatus|null $application_status
 *
 * @package App\Models
 */
class ApplicationStep extends Model
{
	protected $table = 'application_steps';

	protected $casts = [
		'application_id' => 'int',
		'application_criteria_id' => 'int',
		'status_id' => 'int',
		'responsible_id' => 'int',
		'is_passed' => 'bool'
	];

	protected $fillable = [
		'application_id',
		'application_criteria_id',
		'status_id',
		'responsible_id',
		'file_url',
		'responsible_by',
		'is_passed',
		'result'
	];

	public function application_criterion()
	{
		return $this->belongsTo(ApplicationCriterion::class, 'application_criteria_id');
	}

	public function application()
	{
		return $this->belongsTo(Application::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'responsible_id');
	}

	public function application_status()
	{
		return $this->belongsTo(ApplicationStatus::class, 'status_id');
	}
}
