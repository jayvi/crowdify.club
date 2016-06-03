<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hug extends Model
{
    protected $table = 'hugs';

    protected $guarded = ['id'];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function completers(){
        return $this->belongsToMany(User::class, 'hug_completer', 'hug_id', 'completer_id')->withPivot( 'approved');
    }

    public function comments(){
        return $this->hasMany(Comment::class, 'hug_id');
    }
}
