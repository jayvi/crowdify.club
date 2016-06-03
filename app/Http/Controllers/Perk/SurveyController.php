<?php

namespace App\Http\Controllers\Perk;


use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SurveyController extends Controller
{
   public function __construct(Guard $auth){
       parent::__construct($auth);
       $this->middleware('auth');
       $this->middleware('email.check');
   }

    public function getAccountDetails(Request $request){

    }

    public function postAccountDetails(Request $request){

    }


}
