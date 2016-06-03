<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FollowerPullingHelper extends Model
{

    protected $table = 'follower_pulling_helpers';

    protected $dates = ['start_time','next_request_time'];

    public function profile(){
        return $this->belongsTo(Profile::class, 'profile_id');
    }
}
