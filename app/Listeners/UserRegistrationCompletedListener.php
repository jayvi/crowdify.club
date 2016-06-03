<?php

namespace App\Listeners;

use App\Events\UserRegistrationCompleted;

use App\MailConfirmation;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserRegistrationCompletedListener
{

    private $mailer;
    /**
     * Create the event listener.
     *
     * @return void
     */

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Handle the event.
     *
     * @param  UserRegistrationCompleted  $event
     * @return void
     */
    public function handle(UserRegistrationCompleted $event)
    {
        $this->sendConfirmationEmail($event->user);
    }


    /**
     * send confirmation link to user email
     *
     * @param $user
     */
    private function sendConfirmationEmail($user){

        $mailConfirmation = new MailConfirmation();
        $mailConfirmation->email = $user->email;
        $mailConfirmation->confirmation_code = str_random(16);
        $mailConfirmation->save();

        $this->mailer->send('mail.account_confirmation', compact('user','mailConfirmation'), function($message) use ($user){
            $message->to($user->email);
            $message->subject('Crowdify Account Confirmation');
        });
    }
}
