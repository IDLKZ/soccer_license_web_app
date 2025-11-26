<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ApplicationSolution
 * 
 * @property int $id
 * @property int|null $application_id
 * @property int|null $secretary_id
 * @property string|null $secretary_name
 * @property Carbon|null $meeting_date
 * @property string|null $meeting_place
 * @property string|null $department_name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Application|null $application
 * @property User|null $user
 *
 * @package App\Models
 */
class ApplicationSolution extends Model
{
	protected $table = 'application_solutions';

	protected $casts = [
		'application_id' => 'int',
		'secretary_id' => 'int',
		'meeting_date' => 'datetime',
		'list_documents' => 'array',
		'list_criteria' => 'array'
	];

	protected $hidden = [
		'secretary_id',
		'secretary_name'
	];

	protected $fillable = [
		'application_id',
		'secretary_id',
		'secretary_name',
		'secretary_position',
		'director_position',
		'director_name',
		'type',
		'meeting_date',
		'meeting_place',
		'department_name',
		'list_documents',
		'list_criteria'
	];

	public function application()
	{
		return $this->belongsTo(Application::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'secretary_id');
	}
}
