<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 11/6/15
 * Time: 3:55 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class AffiliateReference extends Model
{
    protected $table = "affiliate_references";

    public function affiliate(){
        return $this->belongsTo(Affiliate::class, 'affiliate_id');
    }

    public function earnings(){
        return $this->hasMany(AffiliateEarning::class, 'affiliate_reference_id');
    }

    public function referenceUser(){
        return $this->belongsTo(User::class, 'user_id');
    }


}