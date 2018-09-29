<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Session;

class AuthenticationMiddleware
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
        if (! Auth::check() || ! Auth::user()->isActivated()) {           
            return redirect('logout')->with('error-message', __('auth.insufficient_permission'));
        }

        return $next($request);
    }
}
