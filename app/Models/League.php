<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class League
 * 
 * @property int $id
 * @property string|null $image_url
 * @property string $title_ru
 * @property string $title_kk
 * @property string|null $title_en
 * @property string|null $description_ru
 * @property string|null $description_kk
 * @property string|null $description_en
 * @property string $value
 * @property bool $is_active
 * @property int $level
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Licence[] $licences
 *
 * @package App\Models
 */
class League extends Model
{
	protected $table = 'leagues';

	protected $casts = [
		'is_active' => 'bool',
		'level' => 'int'
	];

	protected $fillable = [
		'image_url',
		'title_ru',
		'title_kk',
		'title_en',
		'description_ru',
		'description_kk',
		'description_en',
		'value',
		'is_active',
		'level'
	];

	public function licences()
	{
		return $this->hasMany(Licence::class);
	}
}
