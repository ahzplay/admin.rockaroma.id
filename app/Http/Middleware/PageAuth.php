<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PageAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(!$request->session()->get('isLogin'))
            return redirect()->to('/')->with(['error'=>'Your session has expired']);
        else
            return $next($request);
    }
}
