<?php

namespace App\Listeners;

use App\AffiliateEarning;
use App\Events\UserAccountUpgraded;
use App\User;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserAccountUpgradeListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserAccountUpgraded  $event
     * @return void
     */
    public function handle(UserAccountUpgraded $event)
    {
        $this->addPaymentToAffiliate($event->user);
    }

    private function addPaymentToAffiliate(User $user){

        try{
            $affiliateReference = $user->affiliateReference()->with(array('affiliate'))->first();
            if($affiliateReference){
                $affiliate = $affiliateReference->affiliate;
                $affiliateEarning = new AffiliateEarning();
                $affiliateEarning->affiliate_id = $affiliate->id;
                $affiliateEarning->affiliate_reference_id = $affiliateReference->id;
                $affiliateEarning->user_id = $user->id;
                $affiliateEarning->amount = ($affiliate->reference_total_amount / 2); //50%
                $affiliateEarning->payment_date = Carbon::now()->addMonth(1);
                $affiliateEarning->save();
                $affiliateReference->earnings()->save();

                $affiliate->total_earnings += ($affiliate->reference_total_amount / 2);
                $affiliate->update();
            }
        }catch (\Exception $e){

        }

    }
}
