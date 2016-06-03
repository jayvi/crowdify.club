<?php

namespace App\Listeners;

use App\Events\HugCompletionApproved;
use App\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class HugCompletionApprovedListner
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  HugCompletionApproved  $event
     * @return void
     */
    public function handle(HugCompletionApproved $event)
    {
//        foreach($event->completers as $completer){
//            $this->sendNotification($event->hug, $completer);
//        }

    }

    private function sendNotification($hug, $completer)
    {
        $notification = new Notification();
        $notification->sender_id = $hug->user_id;
        $notification->recipient_id = $completer->id;
        $notification->title = 'Task Completion Approved';
        $notification->description = '%%username%% has approved your task completion. You have got '.$hug->reward.' Crowdify Coins';
        $notification->type = 'HUG-COMPLETION-APPROVED';
        $notification->save();
    }


}
