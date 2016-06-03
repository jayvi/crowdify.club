<?php

namespace App\Events;

use App\Community;
use App\CommunityPost;
use App\Events\Event;
use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CommunityPostCommentAdded extends Event
{
    use SerializesModels;

    public $senderId;
    public $community;
    public $communityPost;
    public $recipientId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($senderId, $recipientId, Community $community, $communityPost)
    {
        $this->senderId = $senderId;
        $this->recipientId = $recipientId;
        $this->community = $community;
        $this->communityPost = $communityPost;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
