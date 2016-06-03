<?php

namespace App\Http\Controllers\Perk;

use Illuminate\Auth\Guard;
use Illuminate\Http\Request;

use App\Http\Requests;

class CommentController extends BaseController
{

    public function __construct(Guard $auth){
        parent::__construct($auth);
        $this->middleware('auth');
    }

    public function comments($hug_id){

    }
}
