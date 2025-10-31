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
 * Class CategoryDocument
 * 
 * @property int $id
 * @property string $title_ru
 * @property string $title_kk
 * @property string|null $title_en
 * @property string $value
 * @property int $level
 * @property array|null $roles
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Document[] $documents
 *
 * @package App\Models
 */
class CategoryDocument extends Model
{
	use Sluggable;

	protected $table = 'category_documents';

	protected $casts = [
		'level' => 'int',
		'roles' => 'json'
	];

	protected $fillable = [
		'title_ru',
		'title_kk',
		'title_en',
		'value',
		'level',
		'roles'
	];

	public function documents()
	{
		return $this->hasMany(Document::class, 'category_id');
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
