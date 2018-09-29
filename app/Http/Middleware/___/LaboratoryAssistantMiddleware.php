<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class LaboratoryAssistantMiddleware
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
        if ( ! (Auth::user()->isAssistant() || Auth::user()->isAdmin()) ) {
            return redirect('dashboard');
        }

        return $next($request);
    }
}
