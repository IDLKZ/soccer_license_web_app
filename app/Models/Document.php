<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Document
 * 
 * @property int $id
 * @property int|null $category_id
 * @property string|null $example_file_url
 * @property string $title_ru
 * @property string $title_kk
 * @property string|null $title_en
 * @property string|null $description_ru
 * @property string|null $description_kk
 * @property string|null $description_en
 * @property string $value
 * @property int $level
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property CategoryDocument|null $category_document
 *
 * @package App\Models
 */
class Document extends Model
{
	use Sluggable;

	protected $table = 'documents';

	protected $casts = [
		'category_id' => 'int',
		'level' => 'int'
	];

	protected $fillable = [
		'category_id',
		'example_file_url',
		'title_ru',
		'title_kk',
		'title_en',
		'description_ru',
		'description_kk',
		'description_en',
		'value',
		'level'
	];

	public function category_document()
	{
		return $this->belongsTo(CategoryDocument::class, 'category_id');
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
