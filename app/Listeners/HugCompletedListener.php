<?php

namespace App\Listeners;

use App\Events\HugCompleted;
use App\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class HugCompletedListener
{


    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  HugCompleted  $event
     * @return void
     */
    public function handle(HugCompleted $event)
    {

       // $this->sendNotification($event->hug, $event->completer);
    }

    private function sendNotification($hug, $completer)
    {
        $notification = new Notification();
        $notification->sender_id = $completer->id;
        $notification->recipient_id = $hug->user_id;
        $notification->title = 'Task Completed';
        $notification->description = $completer->username.' has completed your task';
        $notification->type = 'HUG-COMPLETED';
        $notification->save();
    }
}
