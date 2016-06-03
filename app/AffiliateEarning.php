<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 11/6/15
 * Time: 3:55 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class AffiliateEarning extends Model
{
    protected $table = "affiliate_earnings";
    protected $dates = ['payment_date','payment_completion_date'];


    public function affiliate(){
        return $this->belongsTo(Affiliate::class, 'affiliate_id');
    }

    public function reference(){
        return $this->belongsTo(AffiliateReference::class, 'affiliate_reference_id');
    }

    public function referenceUser(){
        return $this->belongsTo(User::class, 'user_id');
    }

}