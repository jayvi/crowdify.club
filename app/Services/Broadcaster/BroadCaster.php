<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 10/24/15
 * Time: 11:25 AM
 */

namespace App\Services\Broadcaster;


use Illuminate\Contracts\Mail\Mailer;

class BroadCaster
{

    private $mailer;
    private $emailShortCodes = array('%%firstname%%','%%lastname%%');

    public function __construct(Mailer $mailer){
        $this->mailer = $mailer;
    }

    public function broadCast($users, Email $email, BroadCastListener $listener){
        try{
            foreach($users as $user){
                $replaceArray = array(
                    $user->first_name,
                    $user->last_name,
                );
                $subject = str_replace($this->emailShortCodes, $replaceArray, $email->getSubject());

                $body = str_replace($this->emailShortCodes,$replaceArray, $email->getBody());
                $emailAddress = $user->email;

                $this->mailer->queue('mail.broadcast_template', compact('body'), function($m) use ($emailAddress, $subject)
                {
                    $m->to($emailAddress);
                    //$m->to('sohel.technext@gmail.com');
                    $m->subject($subject);

                });
            }
            return $listener->onBroadCasted('Successfully Sent');
        }catch(\Exception $e){
            return $listener->onError('An un-known error occurred when sending email');
        }

    }
}