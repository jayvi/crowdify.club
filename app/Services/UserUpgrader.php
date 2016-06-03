<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 11/5/15
 * Time: 3:28 PM
 */

namespace App\Services;


use App\Helpers\DataUtils;
use App\User;
use App\UserType;

class UserUpgrader
{
    public function upgradeManualList(){
        $manualList = DataUtils::$manualUpgradeUserList;
        $userType = UserType::where('role','=','Paid')->first();

        $users = User::whereIn('username',$manualList)->get();
        foreach($users as $user){
            if($user){
                if(!$user->isPaidMember() && !$user->isAdmin() ){
                    $user->usertype_id = $userType->id;
                    $user->is_manually_upgraded = true;
                    $user->update();
                }

            }
        }

    }
}