<?php

namespace App\Events;

use App\Community;
use App\CommunityPost;
use App\Events\Event;
use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CommunityPostAdded extends Event
{
    use SerializesModels;

    public $user;
    public $community;
    public $recipients;
    public $communityPost;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, $recipients, Community $community, CommunityPost $communityPost)
    {
        $this->user = $user;
        $this->recipients = $recipients;
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
