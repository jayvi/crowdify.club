<?php

namespace App\Listeners;

use App\Events\EventCreated;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EventCreatedListener
{
    private $mailer;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Handle the event.
     *
     * @param  EventCreated  $event
     * @return void
     */
    public function handle(EventCreated $event)
    {
//       $event = $event->event;
//        $this->mailer->send('mail.event_created', array('event'=>$event), function($message) use ($event){
//            $message->to('sohel.technext@gmail.com');
//            $message->subject('Event created');
//        });
    }


}
