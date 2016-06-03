<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Share extends Model
{
    protected $table = 'shares';

    public function investor(){
        return $this->belongsTo(User::class, 'investor_id');
    }

    public function item(){
        return $this->belongsTo(Item::class, 'item_id');
    }
}
