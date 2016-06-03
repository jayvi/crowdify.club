<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    public function events(){
        return $this->belongsToMany(Event::class, 'category_event' ,'category_id', 'event_id');
    }
}
