<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 10/7/15
 * Time: 2:25 PM
 */

namespace App\Services;


use App\User;

class FollowerCountService
{

    public function __construct(){

    }

    public function set(){


        $users = User::all();
        foreach($users as $user){
            $twitterProfile = $user->profiles()->where('provider','=','twitter')->first();
            if($twitterProfile){
                $twitterProfile->twitter_followers = $user->twitter_followers;
                $twitterProfile->update();
            }
        }
    }

}