<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventTicket extends Model
{
    protected $table = 'event_tickets';

    protected $guarded = ['id'];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id','id');
    }

    public function registrants()
    {
        return $this->hasMany(EventRegistrant::class, 'event_ticket_id', 'id');
    }
}
