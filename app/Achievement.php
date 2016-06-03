<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 8/25/15
 * Time: 12:24 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    protected $table = 'achievements';

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}