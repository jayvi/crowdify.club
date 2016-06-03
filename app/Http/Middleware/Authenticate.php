<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class Authenticate
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->auth->guest()) {
            if ($request->ajax()) {
                return response(array('message' => 'You need to log in to perform this action'), 401);
            } else {
                return redirect()->guest(route('auth::login'));
            }
        }

        if($this->auth->user()->is_blocked){
            $this->auth->logout();
            return redirect()->route('auth::login')->with('error','Sorry, Your account is Currently blocked');
        }

        return $next($request);
    }
}
