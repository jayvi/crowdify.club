<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 8/29/15
 * Time: 9:06 PM
 */

namespace App\Http\Controllers\Perk;


use App\Bank;
use Illuminate\Auth\Guard;

class LeaderboardController extends BaseController
{
    public function __construct(Guard $auth){
        parent::__construct($auth);
        $this->middleware('auth');
    }

    public function index(){
        $banks = Bank::with(array('user'))->orderBy('crowd_coins','desc')->get();
        return $this->createView('perk.leaderboard', compact('banks'));
    }
}