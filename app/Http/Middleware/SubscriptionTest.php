<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Guard;

class SubscriptionTest
{

    protected $auth;
    public function __construct(Guard $auth){
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
        if($this->auth->check()){
            if($this->auth->user()->username == 'SohelTechnext' || $this->auth->user()->username == 'sohel_sohel_'
                || $this->auth->user()->username == 'mqtodd' || $this->auth->user()->username == 'You_Search'){
                return $next($request);
            }
        }

        return redirect()->route('perk::home');
    }
}
