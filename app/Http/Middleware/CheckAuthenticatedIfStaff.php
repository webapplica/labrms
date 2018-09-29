<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckAuthenticatedIfStaff
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
        // check if the current authenticated user is not
        // a staff, redirect to dashboard
        if(! Auth::user()->isStaff()) {
            redirect('/')->with('error-message', __('auth.insufficient_permission'));
        }

        return $next($request);
    }
}
