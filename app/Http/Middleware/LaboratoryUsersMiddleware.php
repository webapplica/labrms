<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class LaboratoryUsersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if( ! (Auth::user()->isStudent() || Auth::user()->isFaculty()) ) {
            return redirect('dashboard');
        }

        return $next($request);
    }
}
