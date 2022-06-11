<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class checkLogin
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
        if (! Session::get('user')) {
            return redirect()->route('login');
        }
        
        return $next($request);
    }
}
