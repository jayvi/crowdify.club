<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MonthlySharePrice extends Model
{
    protected $table = 'monthly_share_prices';

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
