<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{

    protected $table = 'events';

    protected $guarded = ['id'];

    protected $dates = ['start_date','end_date'];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function setStartDateAttribute($date){
        if($date){
            try{
                $validDate = Carbon::parse($date);
            }catch (Exception $e){
                $validDate = NULL;
            }
        }else{
            $validDate = NULL;
        }

        $this->attributes['start_date'] = $validDate;
    }

    public function setEndDateAttribute($date){
        if($date){
            try{
                $validDate = Carbon::parse($date);
            }catch (Exception $e){
                $validDate = NULL;
            }
        }else{
            $validDate = NULL;
        }

        $this->attributes['end_date'] = $validDate;
    }

    public function registrants(){
        return $this->hasMany(EventRegistrant::class, "event_id");
    }

    public function categories(){
        return $this->belongsToMany(Category::class, 'category_event' , 'event_id', 'category_id');
    }

    public function tickets()
    {
        return $this->hasMany(EventTicket::class, 'event_id', 'id');
    }


}
