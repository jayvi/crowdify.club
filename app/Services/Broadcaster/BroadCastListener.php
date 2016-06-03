<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 10/24/15
 * Time: 1:36 PM
 */
namespace App\Services\Broadcaster;

interface BroadCastListener
{
    public function onBroadCasted($message);

    public function onError($error);
}