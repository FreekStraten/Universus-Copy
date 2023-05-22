<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ErrorPageController extends Controller
{
    public function error404()
    {
        return view('errors.404');
    }

    public function error403()
    {
        return view('errors.403');
    }
}
