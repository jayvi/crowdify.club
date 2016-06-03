<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 8/17/15
 * Time: 3:27 PM
 */

namespace App\Services;




use Illuminate\Mail\Mailer;

class EmailService
{

    private $mailer;

    public function __construct(Mailer $mailer){
        $this->mailer = $mailer;
    }

    public function sendWithView($view, $data, $from, $to, $subject, $type = null){
        $this->mailer->queue($view, $data, function($message) use ($from, $to, $subject){
            $message->subject($subject);
            if($from){
                $message->from($from);
            }
            $message->to($to);
        });
    }
}