<?php

namespace App\Events;

use App\Comment;
use App\Events\Event;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CommentAdded extends Event
{
    use SerializesModels;

    public $commenter;
    public $model;
    public $comment;


    /**
     * @param User $commenter
     * @param Model $model
     * @param Comment $comment
     */
    public function __construct(User $commenter, Model $model, Comment $comment)
    {
        $this->commenter = $commenter;
        $this->model = $model;
        $this->comment = $comment;
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
