<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    protected $table = "usertypes";

    protected $guarded = ['id'];

    public function users(){
        return $this->hasMany(User::class, 'usertype_id');
    }
}
