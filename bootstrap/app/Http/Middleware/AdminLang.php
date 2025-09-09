<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use Carbon\Carbon;

class AdminLang
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
       
        $lang = defaultLang();

        if (Session::has('lang') && in_array(Session::get('lang'), languages())) {
            $lang = Session::get('lang');
        }

        App::setLocale($lang);
        Carbon::setLocale($lang);
        return $next($request);
    
    }
}
