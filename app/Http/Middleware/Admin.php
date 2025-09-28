<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Page;
use Illuminate\Support\Facades\Auth;

class Admin{
    public function handle($request, Closure $next){
        if (Page::where('url', $request->url())->exists()) {
            return $next($request);
        }
        if(Auth::check() && Auth::user()->isAdmin() && Auth::user()->isActive()){
            return $next($request);
        } else {
            Auth::logout();
        }
        abort(401);
    }
}
