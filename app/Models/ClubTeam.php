<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ClubTeam
 * 
 * @property int $id
 * @property int $club_id
 * @property int|null $role_id
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Club $club
 * @property Role|null $role
 * @property User $user
 *
 * @package App\Models
 */
class ClubTeam extends Model
{
	protected $table = 'club_team';

	protected $casts = [
		'club_id' => 'int',
		'role_id' => 'int',
		'user_id' => 'int'
	];

	protected $fillable = [
		'club_id',
		'role_id',
		'user_id'
	];

	public function club()
	{
		return $this->belongsTo(Club::class);
	}

	public function role()
	{
		return $this->belongsTo(Role::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
