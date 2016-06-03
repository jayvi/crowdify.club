<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HowToVid extends Model
{
    protected $table = 'howtovids';
    protected $fillable = array('videoid');
}