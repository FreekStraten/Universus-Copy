<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class OnlySuperAdmin
{
    /**
     * If the user is logged in and the user's role is 'Admin', then the user is allowed to access the
     * page. Otherwise, the user is redirected back to the previous page with a flash message
     *
     * @param Request request The incoming request.
     * @param Closure next The next middleware to be executed.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!is_null(Auth::user()) && Auth::user()->isSuperAdmin())
        {
            return $next($request);
        }

        return redirect('/')->with('flash_message', 'you are not allowed to access this');
    }
}
