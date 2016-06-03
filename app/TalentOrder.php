<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TalentOrder extends Model
{
    protected $table = 'talent_orders';
    protected $guarded = ['id'];
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
