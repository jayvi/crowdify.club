<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class PaidUser
{
    private $auth;
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
        if(!$this->auth->user()->isPaidMember() && !$this->auth->user()->isAdmin()){
            if($request->ajax()){
                return response()->json(array('status' => 403, 'message' => 'You are not authorized for this action'),403);
            }
            return redirect()->route('perk::home');
        }
        return $next($request);
    }
}
