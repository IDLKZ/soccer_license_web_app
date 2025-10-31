<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
 * @property Collection|ClubTeam[] $club_teams
 *
 * @package App\Models
 */
class User extends Authenticatable
{
	use HasFactory, Notifiable;

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

	public function club_teams()
	{
		return $this->hasMany(ClubTeam::class);
	}

	/**
	 * Get the user's full name
	 *
	 * @return string
	 */
	public function getNameAttribute(): string
	{
		$parts = array_filter([
			$this->last_name,
			$this->first_name,
			$this->patronymic,
		]);

		return implode(' ', $parts) ?: $this->username;
	}

	/**
	 * Check if user has a specific role
	 *
	 * @param string $roleValue
	 * @return bool
	 */
	public function hasRole(string $roleValue): bool
	{
		return $this->role && $this->role->value === $roleValue;
	}

	/**
	 * Check if user has any of the given roles
	 *
	 * @param array $roleValues
	 * @return bool
	 */
	public function hasAnyRole(array $roleValues): bool
	{
		return $this->role && in_array($this->role->value, $roleValues);
	}

	/**
	 * Check if user is admin
	 *
	 * @return bool
	 */
	public function isAdmin(): bool
	{
		return $this->hasRole(\App\Constants\RoleConstants::ADMIN_ROLE_VALUE);
	}

	/**
	 * Check if user is from a club
	 *
	 * @return bool
	 */
	public function isClubUser(): bool
	{
		return $this->hasAnyRole([
			\App\Constants\RoleConstants::CLUB_ADMINISTRATOR_VALUE,
			\App\Constants\RoleConstants::LEGAL_SPECIALIST_VALUE,
			\App\Constants\RoleConstants::FINANCIAL_SPECIALIST_VALUE,
			\App\Constants\RoleConstants::SPORTING_DIRECTOR_VALUE,
		]);
	}

	/**
	 * Check if user is from a department
	 *
	 * @return bool
	 */
	public function isDepartmentUser(): bool
	{
		return $this->hasAnyRole([
			\App\Constants\RoleConstants::LICENSING_DEPARTMENT_VALUE,
			\App\Constants\RoleConstants::LEGAL_DEPARTMENT_VALUE,
			\App\Constants\RoleConstants::FINANCE_DEPARTMENT_VALUE,
			\App\Constants\RoleConstants::INFRASTRUCTURE_DEPARTMENT_VALUE,
			\App\Constants\RoleConstants::CONTROL_DEPARTMENT_VALUE,
		]);
	}
}
