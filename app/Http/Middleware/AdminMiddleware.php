<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
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
        if ($request->user()->type != 1) {
            Auth::logout();
            $request->session()->flash('error', "You don't have an access for this portion.");  
            return redirect('/login');
        }
        return $next($request);
    }
}
