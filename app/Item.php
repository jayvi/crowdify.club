<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'items';

    public function soldShares(){
        return $this->hasMany(Share::class, 'item_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
