<?php

namespace App\Listeners;

use App\Events\SharePurchased;
use App\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SharePurchasedListener
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
     * @param  SharePurchased  $event
     * @return void
     */
    public function handle(SharePurchased $event)
    {
       // $this->sendNotification($event->investor, $event->investee, $event->amount, $event->item);
    }

    private function sendNotification($investor, $investee, $amount, $item)
    {
        $notification = new Notification();
        $notification->sender_id = $investor->id;
        $notification->recipient_id = $investee->id;
        $notification->title = 'Share Purchased';
        $notification->description = '%%username%% has invested '. $amount .' share on your '.$item->value;
        $notification->type = 'SHARE-PURCHASED';
        $notification->save();
    }
}
