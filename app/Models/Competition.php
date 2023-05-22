<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Competition
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $min_amount_competitors
 * @property int $max_amount_competitors
 * @property Carbon $start_date
 * @property Carbon $end_date
 * @property string $user_id
 * @property Carbon|null $archived_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $category_id
 * @property int $min_amount_pictures
 * @property int $max_amount_pictures
 * @property int|null $winner
 *
 * @property Category $category
 * @property User|null $user
 * @property Collection|Notification[] $notifications
 * @property Collection|Participation[] $participations
 * @property Collection|UserPicture[] $user_pictures
 *
 * @package App\Models
 */
class Competition extends Model
{
	protected $table = 'competitions';

	protected $casts = [
		'min_amount_competitors' => 'int',
		'max_amount_competitors' => 'int',
		'start_date' => 'datetime',
		'end_date' => 'datetime',
		'archived_at' => 'datetime',
		'category_id' => 'int',
		'min_amount_pictures' => 'int',
		'max_amount_pictures' => 'int',
		'winner' => 'int'
	];

	protected $fillable = [
		'name',
		'description',
		'min_amount_competitors',
		'max_amount_competitors',
		'start_date',
		'end_date',
		'user_id',
		'archived_at',
		'category_id',
		'min_amount_pictures',
		'max_amount_pictures',
		'winner'
	];

	public function category()
	{
		return $this->belongsTo(Category::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'winner');
	}

	public function notifications()
	{
		return $this->hasMany(Notification::class);
	}

	public function participations()
	{
		return $this->hasMany(Participation::class);
	}

	public function user_pictures()
	{
		return $this->hasMany(UserPicture::class);
	}

    public function hasEnded() : bool
    {
        return $this->end_date < Carbon::now();
    }

    public function hasStarted() : bool
    {
        return $this->start_date < Carbon::now();
    }

      public function settings()
    {
        return $this->belongsTo(Setting::class, 'setting_id');
    }


}
