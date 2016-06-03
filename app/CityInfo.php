<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CityInfo extends Model
{
    protected $table = 'cities_infos';
    protected $fillable = array('title','description','city_photo');
}