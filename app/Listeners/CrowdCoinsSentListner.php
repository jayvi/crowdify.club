<?php

namespace App\Listeners;

use App\Events\CrowdCoinsSent;
use App\Notification;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CrowdCoinsSentListner
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
     * @param  CrowdCoinsSent  $event
     * @return void
     */
    public function handle(CrowdCoinsSent $event)
    {
        $user = $event->recipient;

        $this->sendNotification($event->sender, $event->recipient, $event->ammount);
//        $this->mailer->send('perk.mail.crowd_coins_gift', array('sender' => $event->sender, 'recipient' => $event->recipient, 'ammount' => $event->ammount), function($message) use ($user) {
//            $message->to($user->email);
//            $message->subject('Crowdify Coins Gift Received');
//
//        });
    }

    private function sendNotification($sender, $recipient, $ammount)
    {
        $notification = new Notification();
        $notification->sender_id = $sender->id;
        $notification->recipient_id = $recipient->id;
        $notification->title = 'Crowdify Coin Gifts Received';
        $notification->description = '%%username%% has sent you ' . $ammount. 'Crowdify Coins.';
        $notification->type = 'CROWD-COIN-GIFT';
        $notification->save();
    }
}
