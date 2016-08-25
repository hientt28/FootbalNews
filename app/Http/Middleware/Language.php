<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Config;
use App;

class Language
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
        if (Session::has('lang')) {
            App::setLocale(Session::get('lang'));
        } else {
            App::setLocale(Config::get('app.fallback_locale'));
        }
        return $next($request);
    }
}
