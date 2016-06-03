<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatusComments extends Model
{
    protected $table = 'status_comments';

    protected $guarded = ['id'];

    public function commenter(){
        return $this->belongsTo(User::class, 'commenter_id');
    }

    public function status()
    {
        return $this->belongsTo(StatusUpdate::class, 'status_id');
    }
}
