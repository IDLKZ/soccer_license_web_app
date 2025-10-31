<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LicenceDeadline
 * 
 * @property int $id
 * @property int|null $licence_id
 * @property int|null $club_id
 * @property Carbon $start_at
 * @property Carbon $end_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Club|null $club
 * @property Licence|null $licence
 *
 * @package App\Models
 */
class LicenceDeadline extends Model
{
	protected $table = 'licence_deadlines';

	protected $casts = [
		'licence_id' => 'int',
		'club_id' => 'int',
		'start_at' => 'datetime',
		'end_at' => 'datetime'
	];

	protected $fillable = [
		'licence_id',
		'club_id',
		'start_at',
		'end_at'
	];

	public function club()
	{
		return $this->belongsTo(Club::class);
	}

	public function licence()
	{
		return $this->belongsTo(Licence::class);
	}
}
