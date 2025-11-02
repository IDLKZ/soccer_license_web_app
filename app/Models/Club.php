<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Club
 *
 * @property int $id
 * @property string|null $image_url
 * @property int|null $parent_id
 * @property int|null $type_id
 * @property string $full_name_ru
 * @property string $full_name_kk
 * @property string|null $full_name_en
 * @property string $short_name_ru
 * @property string $short_name_kk
 * @property string|null $short_name_en
 * @property string|null $description_ru
 * @property string|null $description_kk
 * @property string|null $description_en
 * @property string $bin
 * @property Carbon $foundation_date
 * @property string $legal_address
 * @property string $actual_address
 * @property string|null $website
 * @property string|null $email
 * @property string|null $phone_number
 * @property bool $verified
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Club|null $club
 * @property ClubType|null $club_type
 * @property Collection|ClubTeam[] $club_teams
 * @property Collection|Club[] $clubs
 *
 * @package App\Models
 */
class Club extends Model
{
	protected $table = 'clubs';

	protected $casts = [
		'parent_id' => 'int',
		'type_id' => 'int',
		'foundation_date' => 'datetime',
		'verified' => 'bool'
	];

	protected $fillable = [
		'image_url',
		'parent_id',
		'type_id',
		'full_name_ru',
		'full_name_kk',
		'full_name_en',
		'short_name_ru',
		'short_name_kk',
		'short_name_en',
		'description_ru',
		'description_kk',
		'description_en',
		'bin',
		'foundation_date',
		'legal_address',
		'actual_address',
		'website',
		'email',
		'phone_number',
		'verified'
	];

	public function club()
	{
		return $this->belongsTo(Club::class, 'parent_id');
	}

	public function club_type()
	{
		return $this->belongsTo(ClubType::class, 'type_id');
	}

	public function club_teams()
	{
		return $this->hasMany(ClubTeam::class);
	}

	public function clubs()
	{
		return $this->hasMany(Club::class, 'parent_id');
	}

	public function parent()
	{
		return $this->belongsTo(Club::class, 'parent_id');
	}
}
