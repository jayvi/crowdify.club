<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 5/24/16
 * Time: 3:13 PM
 */

namespace App\Services;


use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SubscriptionChecker
{
    // cyclist, driver, pilot needs to be checked, no need to check for astronaut because astronaut is life time subscription
    private $userTypesToCheck = [9,10,11];
    public function check()
    {
        $users = User::whereIn('usertype_id',$this->userTypesToCheck)->get();
        foreach ($users as $user){
            if($user->payment_type && !$user->is_manually_upgraded && $user->last_payment_date){
                if(Carbon::now()->gt($user->last_payment_date->addMonth(1))){
                    // subscription expired. lets downgrade this users subscription
                    $user->update(['usertype_id' => 1]);
                }
            }
        }
    }
}