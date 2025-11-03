<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 * 
 * @property int $id
 * @property int|null $role_id
 * @property string|null $image_url
 * @property string $email
 * @property string $phone
 * @property string $username
 * @property string|null $iin
 * @property string $first_name
 * @property string|null $last_name
 * @property string|null $patronymic
 * @property string|null $position
 * @property string|null $password
 * @property bool $is_active
 * @property bool $verified
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Role|null $role
 * @property Collection|ApplicationCriterion[] $application_criteria
 * @property Collection|ApplicationDocument[] $application_documents
 * @property Collection|ApplicationSolution[] $application_solutions
 * @property Collection|ApplicationStep[] $application_steps
 * @property Collection|Application[] $applications
 * @property Collection|ClubTeam[] $club_teams
 *
 * @package App\Models
 */
class User extends Model
{
	protected $table = 'users';

	protected $casts = [
		'role_id' => 'int',
		'is_active' => 'bool',
		'verified' => 'bool'
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'role_id',
		'image_url',
		'email',
		'phone',
		'username',
		'iin',
		'first_name',
		'last_name',
		'patronymic',
		'position',
		'password',
		'is_active',
		'verified',
		'remember_token'
	];

	public function role()
	{
		return $this->belongsTo(Role::class);
	}

	public function application_criteria()
	{
		return $this->hasMany(ApplicationCriterion::class, 'uploaded_by_id');
	}

	public function application_documents()
	{
		return $this->hasMany(ApplicationDocument::class, 'uploaded_by_id');
	}

	public function application_solutions()
	{
		return $this->hasMany(ApplicationSolution::class, 'secretary_id');
	}

	public function application_steps()
	{
		return $this->hasMany(ApplicationStep::class, 'responsible_id');
	}

	public function applications()
	{
		return $this->hasMany(Application::class);
	}

	public function club_teams()
	{
		return $this->hasMany(ClubTeam::class);
	}
}
