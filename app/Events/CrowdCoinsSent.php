<?php

namespace App\Events;

use App\Events\Event;
use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CrowdCoinsSent extends Event
{
    use SerializesModels;

    public $sender;
    public $recipient;
    public $ammount;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $sender, User $recipient, $ammount)
    {
        $this->sender = $sender;
        $this->recipient = $recipient;
        $this->ammount = $ammount;
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
