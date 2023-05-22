<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Builder;

class UserPictureRating extends Model
{
    use HasFactory;

    protected $table = 'user_has_picture_rating';

    protected $fillable = ['user_id', 'participation_id', 'star_rating'];

    protected $primaryKey = ['user_id', 'participation_id'];
    public $incrementing = false;


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function picture(): BelongsTo
    {
        return $this->belongsTo(UserPicture::class);
    }


    //set the primary key for the query because normal composite key array does not work
    protected function setKeysForSaveQuery($query)
    {
        $query
            ->where('user_id', '=', $this->getAttribute('user_id'))
            ->where('participation_id', '=', $this->getAttribute('picture_id'));
        return $query;
    }
}
