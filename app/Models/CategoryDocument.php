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
		'roles' => 'array'
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

	/**
	 * Get the roles attribute, ensuring it's always an array or null
	 */
	public function getRolesAttribute($value)
	{
		if (is_null($value)) {
			return null;
		}

		if (is_string($value)) {
			$decoded = json_decode($value, true);
			return is_array($decoded) ? $decoded : null;
		}

		return is_array($value) ? $value : null;
	}

	/**
	 * Set the roles attribute
	 * Stores role values (slugs) as JSON array
	 */
	public function setRolesAttribute($value)
	{
		if (is_null($value) || (is_array($value) && empty($value))) {
			$this->attributes['roles'] = null;
		} elseif (is_array($value)) {
			// Store role values (slugs) as array
			$this->attributes['roles'] = json_encode(array_values($value));
		} else {
			$this->attributes['roles'] = $value;
		}
	}
}
