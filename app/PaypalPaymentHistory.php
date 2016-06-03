<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaypalPaymentHistory extends Model
{
    protected $table = "paypal_payment_histories";
    protected $guarded = ['id'];
}
