<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ApplicationDocument
 * 
 * @property int $id
 * @property int|null $application_id
 * @property int|null $category_id
 * @property int|null $document_id
 * @property string $file_url
 * @property int|null $uploaded_by_id
 * @property string|null $uploaded_by
 * @property int|null $first_checked_by_id
 * @property string|null $first_checked_by
 * @property int|null $checked_by_id
 * @property string|null $checked_by
 * @property int|null $control_checked_by_id
 * @property string|null $control_checked_by
 * @property bool|null $is_first_passed
 * @property bool|null $is_industry_passed
 * @property bool|null $is_final_passed
 * @property string $title
 * @property string|null $info
 * @property string|null $first_comment
 * @property string|null $industry_comment
 * @property string|null $control_comment
 * @property Carbon|null $deadline
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Application|null $application
 * @property CategoryDocument|null $category_document
 * @property User|null $user
 * @property Document|null $document
 *
 * @package App\Models
 */
class ApplicationDocument extends Model
{
	protected $table = 'application_documents';

	protected $casts = [
		'application_id' => 'int',
		'category_id' => 'int',
		'document_id' => 'int',
		'uploaded_by_id' => 'int',
		'first_checked_by_id' => 'int',
		'checked_by_id' => 'int',
		'control_checked_by_id' => 'int',
		'is_first_passed' => 'bool',
		'is_industry_passed' => 'bool',
		'is_final_passed' => 'bool',
		'deadline' => 'datetime'
	];

	protected $fillable = [
		'application_id',
		'category_id',
		'document_id',
		'file_url',
		'uploaded_by_id',
		'uploaded_by',
		'first_checked_by_id',
		'first_checked_by',
		'checked_by_id',
		'checked_by',
		'control_checked_by_id',
		'control_checked_by',
		'is_first_passed',
		'is_industry_passed',
		'is_final_passed',
		'title',
		'info',
		'first_comment',
		'industry_comment',
		'control_comment',
		'deadline'
	];

	public function application()
	{
		return $this->belongsTo(Application::class);
	}

	public function category_document()
	{
		return $this->belongsTo(CategoryDocument::class, 'category_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'uploaded_by_id');
	}

	public function document()
	{
		return $this->belongsTo(Document::class);
	}
}
