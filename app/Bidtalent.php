<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bidtalent extends Model
{
    protected $table = 'bidtalent';
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'bidder_id');
    }
}
