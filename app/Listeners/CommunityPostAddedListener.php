<?php

namespace App\Listeners;

use App\Notification;
use App\Events\CommunityPostAdded;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CommunityPostAddedListener {

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
     * @param  CommunityPostAdded $event
     *
     * @return void
     */
    public function handle(CommunityPostAdded $event)
    {
        $this->sendNotification($event->user, $event->recipients, $event->community, $event->communityPost);
    }

    private function sendNotification($sender, $recipients, $community, $communityPost)
    {
        $data = [];

        foreach ($recipients as $recipient)
        {
            $dataMake = [
                'sender_id' => $sender->id,
                'recipient_id' => $recipient->id,
                'title' => 'Community Post Added',
                'description' => '%%username%% has posted on ' . $community->name . ' community',
                'target_link' => route('community::communityPost.each', ['communitySlug' => $community->slug, 'communityPostId' => $communityPost->id])
            ];

            array_push($data, $dataMake);
        }

        Notification::insert($data);
    }
}
