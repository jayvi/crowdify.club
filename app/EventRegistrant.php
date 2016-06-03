<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventRegistrant extends Model
{
    protected $table = 'event_registrants';

    protected $guarded = [
        'id'
    ];
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function ticket()
    {
        return $this->belongsTo(EventTicket::class, 'event_ticket_id', 'id');
    }
}
