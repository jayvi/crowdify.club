<?php

namespace App\Events;

use App\Events\Event;
use App\Hug;
use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class HugCompletionApproved extends Event
{
    use SerializesModels;

    public $hug;
    public $completers;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Hug $hug, $completers)
    {
        $this->hug = $hug;
        $this->completers = $completers;
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
