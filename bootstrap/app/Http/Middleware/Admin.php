<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Admin{
    public function handle($request, Closure $next){
        if(Auth::check() && Auth::user()->isAdmin() && Auth::user()->isActive()){
            return $next($request);
        } else {
            Auth::logout();
        }
        abort(401);
    }
}
