<?php

namespace App\Events;

use App\Events\Event;
use App\Hug;
use App\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class HugCompleted extends Event implements ShouldQueue
{
    use SerializesModels;

    public $hug;
    public $completer;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Hug $hug, User $completer)
    {
        $this->hug = $hug;
        $this->completer = $completer;
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
