<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiCors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next) {
        $headers = [
          "Access-Control-Allow-Origin"  => "*",
          "Access-Control-Allow-Headers" => "X-Requested-With, Content-Type, X-Token-Auth, Authorization",
          "Access-Control-Allow-Methods" => "POST, GET, OPTIONS, PUT, DELETE",
          "Content-Type"                 => "application/json",
          "Access-Control-Max-Age"       => "1000",
        ];
      
        $response = $next($request);
    
        foreach ($headers as $key => $value) {
          $response->header($key, $value);
        }
    
        return $response;
    }
}
