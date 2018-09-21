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
        if (Auth::guest()) {
            
            if ($request->ajax()) {
                return Response::make('Unauthorized', 401);
            } 
                
            return redirect('login')->with('error-message', __('auth.insufficient_permission'));
            
        }

        return $next($request);
    }
}
