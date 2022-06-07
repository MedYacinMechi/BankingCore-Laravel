<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserMiddleware
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
        if(Auth::check() && Auth::user()->role_id == 2) { 
            if(Auth::user()->two_step_auth == 1 && Session::has('otp_verified')) {
                return $next($request);
            } elseif(Auth::user()->two_step_auth == 0) {
                return $next($request);
            } else {
                return redirect('/user/otp');
            }
        } else{
            Session::flash('message', 'Your account is not active!');
            Auth::logout();
            return redirect('/login');
        }

    }
}
