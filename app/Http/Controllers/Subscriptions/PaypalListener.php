<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 10/19/15
 * Time: 8:39 PM
 */
namespace App\Http\Controllers\Subscriptions;

use Illuminate\Http\Request;

interface PaypalListener
{
    public function redirectForApproval($approvalUrl);
    public function success($message);
    public function failed($error);
}