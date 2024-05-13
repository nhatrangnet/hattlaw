<?php

namespace App\Http\Middleware;

use Closure, Session, Lang;

class LocaleAdmin
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
        if(!Session::has('website_language_admin'))
        {
            Session::put('website_language_admin', config('app.locale_admin'));
        }
        Lang::setLocale(Session::get('website_language_admin'));
        return $next($request);
   }
}
