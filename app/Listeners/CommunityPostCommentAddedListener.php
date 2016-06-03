<?php

namespace App\Listeners;

use App\Notification;
use App\Events\CommunityPostCommentAdded;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CommunityPostCommentAddedListener
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
     * @param  CommunityPostCommentAdded  $event
     * @return void
     */
    public function handle(CommunityPostCommentAdded $event)
    {
        $notification = new Notification();
        $notification->sender_id = $event->senderId;
        $notification->recipient_id = $event->recipientId;
        $notification->title = 'Comment Added';
        $notification->description = '%%username%% has commented on your ' . $event->communityPost->subject . ' post';
        $notification->type = 'COMMENT';
        $notification->target_link = route('community::communityPost.each', ['communitySlug' => $event->community->slug, 'communityPostId' => $event->communityPost->id]);

        $notification->save();
    }
}
