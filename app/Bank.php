<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 8/25/15
 * Time: 12:24 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $table = 'banks';

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

}