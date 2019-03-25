<?php

namespace App\Http\Middleware;

use Closure;

class RedirectForSeo
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
        if(substr($_SERVER['REQUEST_URI'], -4) != 'html') {
            if(substr($_SERVER['REQUEST_URI'], -1) != '/') {
                header("HTTP/1.1 301 Moved Permanently");
                header('Location: '.url($_SERVER['REQUEST_URI']).'/');
                exit;
            }
        }
        return $next($request);
    }
}
