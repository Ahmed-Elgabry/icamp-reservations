<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Page;
use Illuminate\Support\Facades\Auth;

class Admin{
    public function handle($request, Closure $next){
        if ($page = Page::where('url', $request->url())->first()) {
            if ($page->is_available) {
                if (!$page->is_authenticated) {
                    return $next($request);
                }
            }else{
                abort(404);
            }
        }
        if(Auth::check() && Auth::user()->isAdmin() && Auth::user()->isActive()){
            return $next($request);
        } else {
            Auth::logout();
        }
        abort(401);
    }
}
