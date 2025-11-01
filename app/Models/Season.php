<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Season
 * 
 * @property int $id
 * @property string $title_ru
 * @property string $title_kk
 * @property string|null $title_en
 * @property string $value
 * @property Carbon $start
 * @property Carbon $end
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Season extends Model
{
	use Sluggable;

	protected $table = 'seasons';

	protected $casts = [
		'start' => 'datetime',
		'end' => 'datetime',
		'is_active' => 'bool'
	];

	protected $fillable = [
		'title_ru',
		'title_kk',
		'title_en',
		'value',
		'start',
		'end',
		'is_active'
	];

	public function sluggable(): array
	{
		return [
			'value' => [
				'source' => 'title_ru'
			]
		];
	}

	/**
	 * Get the licences for the season.
	 */
	public function licences()
	{
		return $this->hasMany(Licence::class);
	}
}
