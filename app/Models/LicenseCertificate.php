<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LicenseCertificate
 * 
 * @property int $id
 * @property int|null $application_id
 * @property int|null $license_id
 * @property int|null $club_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Application|null $application
 * @property Club|null $club
 * @property Licence|null $licence
 *
 * @package App\Models
 */
class LicenseCertificate extends Model
{
	protected $table = 'license_certificates';

	protected $casts = [
		'application_id' => 'int',
		'license_id' => 'int',
		'club_id' => 'int'
	];

	protected $fillable = [
		'application_id',
		'license_id',
		'club_id'
	];

	public function application()
	{
		return $this->belongsTo(Application::class);
	}

	public function club()
	{
		return $this->belongsTo(Club::class);
	}

	public function licence()
	{
		return $this->belongsTo(Licence::class, 'license_id');
	}
}
