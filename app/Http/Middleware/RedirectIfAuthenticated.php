<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use JWTAuth;
use Auth;
use Tymon\JWTAuth\Exceptions\JWTException;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        dd($request->all());
        try{
        $user = JWTAuth::toUser($request->input('token'));
        dd($user);
        }catch(JWTException $e){
            dd($e);
        }
        dd(Auth::guard('community')->user());
        if (!Auth::check()) {
            return redirect('/login.html');
        }
        $id = Auth::user()->id;
        if (!User::find($id)->getCustomer) {
            return redirect('/login.html');
        }
        return $next($request);
    }
}
