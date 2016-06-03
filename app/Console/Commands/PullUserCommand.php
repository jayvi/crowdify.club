<?php

namespace App\Console\Commands;

use App\Services\TweetCollection\TwitterUserCollectionService;
use Illuminate\Console\Command;

class PullUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'twitteruser:pull';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        (new TwitterUserCollectionService())->pullTwitterUsersFromTwitter();
    }
}
