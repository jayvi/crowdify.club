<?php

namespace App\Http\Middleware;

use Closure;

class CheckForSignature
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
        if(!$request->is('auth/signature') && !$request->is('auth/password-settings')) {
            if (auth()->check()) {
                if (!auth()->user()->signature || auth()->user()->signature == '') {
                    return redirect(route('signature::get'));
                }
            }
        }
        return $next($request);
    }
}
