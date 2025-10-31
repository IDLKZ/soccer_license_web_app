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
 * Class ApplicationStatusCategory
 * 
 * @property int $id
 * @property int|null $cat_previous_id
 * @property int|null $cat_next_id
 * @property string $title_ru
 * @property string $title_kk
 * @property string|null $title_en
 * @property string|null $description_ru
 * @property string|null $description_kk
 * @property string|null $description_en
 * @property string $value
 * @property array|null $role_values
 * @property bool $is_active
 * @property bool $is_first
 * @property bool $is_last
 * @property int $result
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property ApplicationStatusCategory|null $application_status_category
 * @property Collection|ApplicationStatusCategory[] $application_status_categories
 * @property Collection|ApplicationStatus[] $application_statuses
 * @property Collection|Application[] $applications
 *
 * @package App\Models
 */
class ApplicationStatusCategory extends Model
{
	use Sluggable;

	protected $table = 'application_status_categories';

	protected $casts = [
		'cat_previous_id' => 'int',
		'cat_next_id' => 'int',
		'role_values' => 'json',
		'is_active' => 'bool',
		'is_first' => 'bool',
		'is_last' => 'bool',
		'result' => 'int'
	];

	protected $fillable = [
		'cat_previous_id',
		'cat_next_id',
		'title_ru',
		'title_kk',
		'title_en',
		'description_ru',
		'description_kk',
		'description_en',
		'value',
		'role_values',
		'is_active',
		'is_first',
		'is_last',
		'result'
	];

	public function application_status_category()
	{
		return $this->belongsTo(ApplicationStatusCategory::class, 'cat_previous_id');
	}

	public function application_status_categories()
	{
		return $this->hasMany(ApplicationStatusCategory::class, 'cat_previous_id');
	}

	public function application_statuses()
	{
		return $this->hasMany(ApplicationStatus::class, 'category_id');
	}

	public function applications()
	{
		return $this->hasMany(Application::class, 'category_id');
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
