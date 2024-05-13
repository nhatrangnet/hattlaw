<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;


class AdminLoginMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'admin', $redirectTo = '/dashboard/login')
    {
        if (!Auth::guard($guard)->check() || Auth::guard($guard)->user()->status == config('constant.status.off')) {
            Auth::logout();
            return redirect($redirectTo);
        }
        return $next($request);
    }
}
