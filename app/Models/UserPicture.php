<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserPicture
 *
 * @property string $id
 * @property int $userId
 * @property int $wedstrijdId
 *
 * @property User $user
 * @property Competition $competition
 *
 * @package App\Models
 */
class UserPicture extends Model
{
	protected $table = 'user_picture';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'userId' => 'int',
		'competition_id' => 'int',
	];

	protected $fillable = [
		'id',
		'userId',
		'competition_id',
        'submission_date',
        'participation_id',
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'userId');
	}

	public function competition()
	{
		return $this->belongsTo(Competition::class, 'competition_id');
	}
}
