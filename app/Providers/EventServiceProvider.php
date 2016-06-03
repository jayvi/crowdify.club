<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [

        'App\Events\EventCreated' => [
            'App\Listeners\EventCreatedListener',
        ],
        'App\Events\UserRegistrationCompleted' => [
            'App\Listeners\UserRegistrationCompletedListener'
        ],
        'App\Events\HugCompleted' => [
            'App\Listeners\HugCompletedListener'
        ],
        'App\Events\HugCompletionApproved' => [
            'App\Listeners\HugCompletionApprovedListner'
        ],
        'App\Events\CrowdCoinsSent' => [
            'App\Listeners\CrowdCoinsSentListner'
        ],
        'App\Events\SharePurchased' => [
            'App\Listeners\SharePurchasedListener'
        ],
        'App\Events\CommentAdded' => [
            'App\Listeners\CommentAddedListeners'
        ],
        'App\Events\UserAccountUpgraded' => [
            'App\Listeners\UserAccountUpgradeListener'
        ],
        'App\Events\CommunityPostAdded' => [
            'App\Listeners\CommunityPostAddedListener'
        ],
        'App\Events\CommunityPostCommentAdded' => [
            'App\Listeners\CommunityPostCommentAddedListener'
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
