<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class HomepageBannerPicture
 * 
 * @property int $id
 * @property string $banner_id
 * @property string|null $current_banner
 *
 * @package App\Models
 */
class HomepageBannerPicture extends Model
{
	protected $table = 'homepage_banner_picture';
	public $timestamps = false;

	protected $fillable = [
		'banner_id',
		'current_banner'
	];
}
