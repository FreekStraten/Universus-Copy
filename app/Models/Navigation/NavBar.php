<?php

namespace App\Models\Navigation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NavBar extends Model
{
    use HasFactory;

    protected $table = 'nav_bar';

    protected $fillable = [
        'order',
        'url',
        'name',
    ];






}
