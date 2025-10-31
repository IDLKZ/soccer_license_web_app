<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Licence
 * 
 * @property int $id
 * @property int|null $season_id
 * @property int|null $league_id
 * @property string $title_ru
 * @property string $title_kk
 * @property string|null $title_en
 * @property string|null $description_ru
 * @property string|null $description_kk
 * @property string|null $description_en
 * @property Carbon $start_at
 * @property Carbon $end_at
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property League|null $league
 * @property Season|null $season
 * @property Collection|Application[] $applications
 * @property Collection|LicenceDeadline[] $licence_deadlines
 * @property Collection|LicenceRequirement[] $licence_requirements
 *
 * @package App\Models
 */
class Licence extends Model
{
	protected $table = 'licences';

	protected $casts = [
		'season_id' => 'int',
		'league_id' => 'int',
		'start_at' => 'datetime',
		'end_at' => 'datetime',
		'is_active' => 'bool'
	];

	protected $fillable = [
		'season_id',
		'league_id',
		'title_ru',
		'title_kk',
		'title_en',
		'description_ru',
		'description_kk',
		'description_en',
		'start_at',
		'end_at',
		'is_active'
	];

	public function league()
	{
		return $this->belongsTo(League::class);
	}

	public function season()
	{
		return $this->belongsTo(Season::class);
	}

	public function applications()
	{
		return $this->hasMany(Application::class, 'license_id');
	}

	public function licence_deadlines()
	{
		return $this->hasMany(LicenceDeadline::class);
	}

	public function licence_requirements()
	{
		return $this->hasMany(LicenceRequirement::class);
	}
}
