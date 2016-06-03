<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 10/28/15
 * Time: 3:13 PM
 */

namespace App\Services\Affiliate;


use App\Affiliate;
use App\AffiliateReference;
use App\Affrelation;
use App\Helpers\DataUtils;
use App\User;
use Illuminate\Auth\Guard;
use Illuminate\Support\Facades\Cookie;

class AffiliateService
{

    public function __construct(Guard $auth){

    }

    public function addAffiliateIfNeeded($profile){

        try{
            $affiliateUsername = Cookie::get('affiliate',null);
            if($affiliateUsername){
                $user = User::where('username','=',$affiliateUsername)->first();
                if(!$user || ($user->id == $profile->user_id)){
                    return;
                }
                if(!$user->isFreeUser()){
                    $this->addAffiliate($affiliateUsername, $profile);
                    Cookie::forget('affiliate');
                }
            }
            return true;
        }catch (\Exception $e){
            return false;
        }


    }

    public function addAffiliate($affiliateUsername, $profile){
        $affiliate = Affiliate::where('username', '=', $affiliateUsername)->first();
        if(!$affiliate){
            $affiliate = new Affiliate();
            $affiliate->username = $affiliateUsername;
            $affiliate->reference_total_amount = DataUtils::PAID_MEMBERSHIP_AMOUNT;
            $affiliate->save();
        }

        $affiliateReference = new AffiliateReference();
        $affiliateReference->affiliate_id = $affiliate->id;
        $affiliateReference->user_id = $profile->user_id;
        $affiliateReference->save();
    }


}