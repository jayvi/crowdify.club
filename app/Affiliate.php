<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Affiliate extends Model
{
    protected $table = "affiliates";

    public function references(){
        return $this->hasMany(AffiliateReference::class, 'affiliate_id');
    }

    public function user(){
        return $this->hasOne(User::class,'username','username');
    }




}
