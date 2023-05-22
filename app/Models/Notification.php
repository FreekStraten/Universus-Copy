<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



/**
 * Class Competition
 *
 * @property int $id
 * @property string $title
 * @property string|null $body
 * @property bool $read
 * @property string|null $message
 * @property int|null $competition_id
 * @property int|null $participation_id
 * @property int $user_id
 * @package App\Models
 */
class Notification extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $table = 'notifications';

    protected $casts = [
        'read' => 'bool',
        'user_id' => 'int'
    ];

    protected $fillable = [
        'title',
        'body',
        'read',
        'user_id',
        'competition_id',
        'participation_id',
        'message'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
