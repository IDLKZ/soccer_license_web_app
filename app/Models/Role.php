<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Role
 * 
 * @property int $id
 * @property string $title_ru
 * @property string $title_kk
 * @property string|null $title_en
 * @property string|null $description_ru
 * @property string|null $description_kk
 * @property string|null $description_en
 * @property string $value
 * @property bool $is_active
 * @property bool $can_register
 * @property bool $is_system
 * @property bool $is_administrative
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|ClubTeam[] $club_teams
 * @property Collection|Permission[] $permissions
 * @property Collection|User[] $users
 *
 * @package App\Models
 */
class Role extends Model
{
	protected $table = 'roles';

	protected $casts = [
		'is_active' => 'bool',
		'can_register' => 'bool',
		'is_system' => 'bool',
		'is_administrative' => 'bool'
	];

	protected $fillable = [
		'title_ru',
		'title_kk',
		'title_en',
		'description_ru',
		'description_kk',
		'description_en',
		'value',
		'is_active',
		'can_register',
		'is_system',
		'is_administrative'
	];

	public function club_teams()
	{
		return $this->hasMany(ClubTeam::class);
	}

	public function permissions()
	{
		return $this->belongsToMany(Permission::class, 'role_permission')
					->withPivot('id')
					->withTimestamps();
	}

	public function users()
	{
		return $this->hasMany(User::class);
	}
}
