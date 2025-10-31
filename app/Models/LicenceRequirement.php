<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LicenceRequirement
 * 
 * @property int $id
 * @property int|null $licence_id
 * @property int|null $category_id
 * @property int|null $document_id
 * @property bool $is_required
 * @property array|null $allowed_extensions
 * @property float $max_file_size_mb
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property CategoryDocument|null $category_document
 * @property Document|null $document
 * @property Licence|null $licence
 *
 * @package App\Models
 */
class LicenceRequirement extends Model
{
	protected $table = 'licence_requirements';

	protected $casts = [
		'licence_id' => 'int',
		'category_id' => 'int',
		'document_id' => 'int',
		'is_required' => 'bool',
		'allowed_extensions' => 'json',
		'max_file_size_mb' => 'float'
	];

	protected $fillable = [
		'licence_id',
		'category_id',
		'document_id',
		'is_required',
		'allowed_extensions',
		'max_file_size_mb'
	];

	public function category_document()
	{
		return $this->belongsTo(CategoryDocument::class, 'category_id');
	}

	public function document()
	{
		return $this->belongsTo(Document::class);
	}

	public function licence()
	{
		return $this->belongsTo(Licence::class);
	}
}
