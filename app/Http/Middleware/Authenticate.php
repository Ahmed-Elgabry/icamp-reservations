<?php

namespace App\Http\Middleware;

use App\Models\Page;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Authenticate extends Middleware
{
    /**
     * Handle an incoming request.
     * Check page availability and authentication requirements from pages table.
     */
    public function handle($request, \Closure $next, ...$guards)
    {
        $url = $request->url();
        $page = Page::where('url', $url)->first();
        
        if ($page) {
            // If page is not available, return 404
            if (!$page->is_available) {
                abort(404);
            }
            
            // If page doesn't require authentication, skip auth check and continue
            if (!$page->is_authenticated) {
                return $next($request);
            }
        }
        
        // If page requires auth or no page found, run standard authentication
        return parent::handle($request, $next, ...$guards);
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->is('api/*')) {
            return route('show.login');
        }
    }
}
