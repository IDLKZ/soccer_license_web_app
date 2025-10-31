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
 * Class ClubType
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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Club[] $clubs
 *
 * @package App\Models
 */
class ClubType extends Model
{
	use Sluggable;

	protected $table = 'club_types';

	protected $casts = [
		'is_active' => 'bool'
	];

	protected $fillable = [
		'title_ru',
		'title_kk',
		'title_en',
		'description_ru',
		'description_kk',
		'description_en',
		'value',
		'is_active'
	];

	public function clubs()
	{
		return $this->hasMany(Club::class, 'type_id');
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
