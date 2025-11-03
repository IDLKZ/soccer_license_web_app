<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
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
 * @property Collection|ApplicationCriterion[] $application_criteria
 * @property Collection|ApplicationDocument[] $application_documents
 * @property Collection|Document[] $documents
 * @property Collection|LicenceRequirement[] $licence_requirements
 *
 * @package App\Models
 */
class CategoryDocument extends Model
{
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

	public function application_criteria()
	{
		return $this->hasMany(ApplicationCriterion::class, 'category_id');
	}

	public function application_documents()
	{
		return $this->hasMany(ApplicationDocument::class, 'category_id');
	}

	public function documents()
	{
		return $this->hasMany(Document::class, 'category_id');
	}

	public function licence_requirements()
	{
		return $this->hasMany(LicenceRequirement::class, 'category_id');
	}
}
