<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Request;

class ManagerRedirectedIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$guard = 'manager')
    {
        if (Auth::guard($guard)->check()) {
            if(Auth::guard("manager")->user()->HotelManager->isEmpty() == false)
                return $next($request);
        }
        return redirect()->route('login');
    }
}
