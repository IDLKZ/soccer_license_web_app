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
 * @property Collection|Application[] $applications
 * @property Collection|LicenceRequirement[] $licence_requirements
 *
 * @package App\Models
 */
class Document extends Model
{
	protected $table = 'documents';
    use Sluggable;

    public function sluggable(): array
    {
        return [
            'value' => [
                'source' => 'title_ru'
            ]
        ];
    }
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

	public function applications()
	{
		return $this->belongsToMany(Application::class, 'application_documents')
					->withPivot('id', 'category_id', 'file_url', 'uploaded_by_id', 'uploaded_by', 'first_checked_by_id', 'first_checked_by', 'checked_by_id', 'checked_by', 'control_checked_by_id', 'control_checked_by', 'is_first_passed', 'is_industry_passed', 'is_final_passed', 'title', 'info', 'first_comment', 'industry_comment', 'control_comment', 'deadline')
					->withTimestamps();
	}

	public function licence_requirements()
	{
		return $this->hasMany(LicenceRequirement::class);
	}
}
