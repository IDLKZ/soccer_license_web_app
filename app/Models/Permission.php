<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Permission
 * 
 * @property int $id
 * @property string $title_ru
 * @property string $title_kk
 * @property string|null $title_en
 * @property string|null $description_ru
 * @property string|null $description_kk
 * @property string|null $description_en
 * @property string $value
 * @property bool $is_system
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Role[] $roles
 *
 * @package App\Models
 */
class Permission extends Model
{
	use Sluggable;

	protected $table = 'permissions';

	protected $casts = [
		'is_system' => 'bool'
	];

	protected $fillable = [
		'title_ru',
		'title_kk',
		'title_en',
		'description_ru',
		'description_kk',
		'description_en',
		'value',
		'is_system'
	];

	public function roles()
	{
		return $this->belongsToMany(Role::class, 'role_permission')
					->withPivot('id')
					->withTimestamps();
	}

	public function sluggable(): array
	{
		return [
			'value' => [
				'source' => 'title_ru'
			]
		];
	}
}
