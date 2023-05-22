<?php

namespace App\Models\Navigation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NavBarDropdown extends Model
{
    use HasFactory;

    protected $table = 'nav_bar_dropdown';

    protected $fillable = [
        'nav_bar_id',
        'name',
        'url',
        'order',
    ];

    public function nav_bar()
    {
        return $this->belongsTo(NavBar::class, 'nav_bar_id');
    }
}
