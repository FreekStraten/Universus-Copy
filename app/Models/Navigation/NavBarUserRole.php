<?php

namespace App\Models\Navigation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NavBarUserRole extends Model
{
    use HasFactory;

    protected $table = 'nav_bar_user_role';

    protected $fillable = [
        'nav_bar_id',
        'user_role',
    ];

    public function nav_bar()
    {
        // can belong to many nav bar items or many dropdown items
        return $this->belongsTo(NavBar::class, 'nav_bar_id');
    }


}
