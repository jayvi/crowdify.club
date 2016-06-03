<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 8/15/15
 * Time: 4:32 PM
 */
namespace App\Interfaces;

interface ShareInterface
{
    public function onShareComplete($data);
    public function onShareFailed($data);
}