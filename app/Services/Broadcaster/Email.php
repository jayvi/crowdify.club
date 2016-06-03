<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 10/24/15
 * Time: 12:22 PM
 */

namespace App\Services\Broadcaster;


class Email
{
    private $subject;
    private $body;

    public function __construct($subject, $body){
        $this->subject = $subject;
        $this->body = $body;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }


}