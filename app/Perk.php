<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Perk extends Model
{
    protected $table = 'perks';

    protected $guarded = ['id'];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function perkType(){
        return $this->belongsTo(PerkType::class, 'type_id');
    }
}
