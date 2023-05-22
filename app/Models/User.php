<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

//use App\Models\Competitions\Competition;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $user_role
 *
 *
 * @package App\Models
 */
class User extends Authenticatable
{
	protected $table = 'users';

	protected $casts = [
		'user_role' => 'int'
	];

	protected $dates = [
		'email_verified_at'
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
        'id',
		'name',
		'email',
		'email_verified_at',
		'password',
		'remember_token',
		'user_role'
	];

	public function user_role()
	{
		return $this->belongsTo(UserRole::class, 'user_role');
	}

    public function isSuperAdmin(){
        return $this->user_role == 1;
    }

    public function isUser(){
        return $this->user_role == 2;
    }

    public function isArchived()
    {
        return !is_null($this->archived_at);
    }


    /**
     * Get all the competitions that are assigned this tag.
     */


    //create relation to competitions
    public function competitions()
    {
        return $this->hasMany(Competition::class, 'user_id');
    }


}
