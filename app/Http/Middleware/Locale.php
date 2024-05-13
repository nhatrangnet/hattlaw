<?php

namespace App\Http\Middleware;

use Closure, Session, Lang;

class Locale
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
        if(!Session::has('hatlaw_language'))
        {
            Session::put('hatlaw_language', config('app.locale'));
        }
        Lang::setLocale(Session::get('hatlaw_language'));
        return $next($request);
    }
}
