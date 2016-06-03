<?php

namespace App\Events;

use App\Events\Event;
use App\Item;
use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SharePurchased extends Event
{
    use SerializesModels;

    public $investor;
    public $investee;
    public $amount;
    public $item;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $investor, User $investee, $amount, Item $item)
    {
        $this->investor = $investor;
        $this->investee = $investee;
        $this->amount = $amount;
        $this->item = $item;
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
