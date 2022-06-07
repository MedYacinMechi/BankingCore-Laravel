<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PhoneIsVerified
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
       
        // return redirect()->route('phone.verification');
        if (!$request->user()->userPhoneVerified()) {
            return redirect('/phone-verification-show');
            // abort(403);
        }
        return $next($request);
    }
}
