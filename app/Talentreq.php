<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Talentreq extends Model
{
    protected $table = 'talentreq';
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

