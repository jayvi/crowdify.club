<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\Inspire::class,
        \App\Console\Commands\PullTwitterFollowers::class,
        \App\Console\Commands\SetTwitterFollowersInProfile::class,
        \App\Console\Commands\UpdateInterests::class,
        \App\Console\Commands\UpdateCities::class,
        \App\Console\Commands\UpgradeUserManualList::class,
        \App\Console\Commands\AffliateUpdater::class,
        \App\Console\Commands\SharePriceCalculator::class,
        \App\Console\Commands\KloutScoreCalculator::class,
        \App\Console\Commands\AddNewCitiesCommand::class,
        \App\Console\Commands\AddEventCategoriesCommand::class,
        \App\Console\Commands\PullUserCommand::class,
        \App\Console\Commands\PullTweetsCommand::class,
        \App\Console\Commands\SubscriptionCheckerCommand::class

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//        $schedule->command('inspire')
//                 ->hourly();


        $schedule->command('followers:pull')
                 ->cron('*/3 * * * *')
                ->withoutOverlapping();

        $schedule->command('SharePriceCalculator:calculate')->monthly();
        $schedule->command('KloutScoreCalculator:calculate')->daily();
        $schedule->command('twitteruser:pull')->cron('*/15 * * * *')->withoutOverlapping();
        $schedule->command('tweets:pull')->cron('*/15 * * * *')->withoutOverlapping();
        $schedule->command('subscription:check')->daily();

    }
}
