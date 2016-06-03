<?php

namespace App\Console\Commands;

use App\Services\SharePriceService;
use Illuminate\Console\Command;

class SharePriceCalculator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SharePriceCalculator:calculate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command calculate share price and save record foreach user monthly.';

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
        (new SharePriceService())->saveMonthlyRecord();
    }
}
