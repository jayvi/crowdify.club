<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
//        \App\Http\Middleware\CheckForSignature::class
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'email.check' => \App\Http\Middleware\CheckEmail::class,
        'subscription.test'=> \App\Http\Middleware\SubscriptionTest::class,
        'admin' => \App\Http\Middleware\Admin::class,
        'user.paid' => \App\Http\Middleware\PaidUser::class,
        'communityAdmin' => \App\Http\Middleware\CommunityAdmin::class,
        'check_for_signature' => \App\Http\Middleware\CheckForSignature::class
    ];
}
