<?php

namespace App\Listeners;

use App\Events\CommentAdded;
use App\Hug;
use App\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CommentAddedListeners
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
     * @param  CommentAdded  $event
     * @return void
     */
    public function handle(CommentAdded $event)
    {
        // need to do the logic who will be the notification recipients, for now just send to owner of hug
        if($event->commenter->id != $event->model->user_id){
            $this->sendNotification($event->commenter ,$event->model->user, $event->model, $event->comment);
        }
    }

    /**
     * @param $sender
     * @param $recipient
     * @param $model
     */
    private function sendNotification($sender, $recipient, $model, $comment)
    {
        $notification = new Notification();
        $notification->sender_id = $sender->id;
        $notification->recipient_id = $recipient->id;
        $notification->title = 'Comment added';
        $notification->description = '%%username%% has commented on your '.($model instanceof Hug ? 'Hug' : 'Perk');
        $notification->type = 'COMMENT';
        $notification->save();

        $notification->target_link =  $this->getTargetLink($model, $comment, $notification);
        $notification->update();
    }

    private function getTargetLink($model, $comment, $notification){
        if($model instanceof Hug){
            return route('hugs::show', array('hug_id' => $model->id)).'?n='.$notification->id.'#comment-id-'.$comment->id;
        }
        return route('hugs::show', array('hug_id' => $model->id)).'?n='.$notification->id.'#comment-id-'.$comment->id;
    }
}
