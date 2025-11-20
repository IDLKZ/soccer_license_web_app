<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ApplicationReport
 *
 * @property int $id
 * @property int|null $application_id
 * @property int|null $criteria_id
 * @property int $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Application|null $application
 * @property ApplicationCriterion|null $application_criterion
 *
 * @package App\Models
 */
class ApplicationReport extends Model
{
	protected $table = 'application_reports';

	protected $casts = [
		'application_id' => 'int',
		'criteria_id' => 'int',
		'status' => 'int',
		'list_documents' => 'array'
	];

	protected $fillable = [
		'application_id',
		'criteria_id',
		'status',
		'list_documents'
	];

	public function application()
	{
		return $this->belongsTo(Application::class);
	}

	public function application_criterion()
	{
		return $this->belongsTo(ApplicationCriterion::class, 'criteria_id');
	}
}
