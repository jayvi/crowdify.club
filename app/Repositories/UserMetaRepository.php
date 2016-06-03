<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 8/29/15
 * Time: 4:34 PM
 */

namespace App\Repositories;


class UserMetaRepository
{

    public function getActiveHugsLimit($userType){
        $limit = 3;
        switch($userType){
            case 'Free':
                $limit = 3;
                break;
            case 'Paid':
                $limit = 3;
                break;
            case 'Founding Member':
                $limit = 5;
                break;
            case 'Sponsor':
                $limit = 3;
                break;
            case 'Admin':
                $limit = 10000000;
             default:
                break;
        }
        return $limit;
    }

    public function getTaskTimeToLive(){
        return 5; //days
    }
}