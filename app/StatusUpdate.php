<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatusUpdate extends Model
{
    protected $table = 'status_updates';

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function comments()
    {
        return $this->hasMany(StatusComments::class, 'status_id');
    }
}
