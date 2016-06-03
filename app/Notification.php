<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    public function sender(){
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient(){
        return $this->belongsTo(User::class, 'recipient_id');
    }
}
