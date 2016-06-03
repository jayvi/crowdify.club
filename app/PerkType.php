<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PerkType extends Model
{
    protected $table = 'perk_types';
    protected $guarded = ['id'];

    public function perks(){
        return $this->hasMany(Perk::class, 'type_id');
    }
}
