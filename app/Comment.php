<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    protected $table = 'comments';

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function hug(){
        return $this->belongsTo(Hug::class, 'hug_id');
    }

}
