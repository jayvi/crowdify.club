<?php

namespace App\Console\Commands;

use App\Affiliate;
use App\AffiliateReference;
use App\Affrelation;
use App\User;
use Illuminate\Console\Command;

class AffliateUpdater extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'AffiliateUpdater:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

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
        $affrelations = Affrelation::all();
        foreach($affrelations as $affiliateRel){
            $referenceUser =  User::where('username','=',$affiliateRel->username)->first();

            $affiliate = Affiliate::where('username','=',$affiliateRel->affiliate)->first();

            if($referenceUser && $affiliate){
                $affiliateReference = new AffiliateReference();
                $affiliateReference->affiliate_id = $affiliate->id;
                $affiliateReference->user_id = $referenceUser->id;
                $affiliateReference->save();
            }

        }
    }
}
