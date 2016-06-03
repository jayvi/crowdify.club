<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 11/3/15
 * Time: 2:51 PM
 */

namespace App\Http\Controllers;


use App\User;
use Illuminate\Auth\Guard;

class UserController extends Controller
{

    public function __construct(Guard $auth){
        parent::__construct($auth);
        $this->middleware('auth');
        $this->middleware('admin', ['only' => ['getPremiumMembers']]);
    }
    public function index()
    {
        $totalCount = User::count();
        $users = User::with(array('followers'))->orderBy('is_email','desc')->paginate(30);
        return $this->createView('users.index', array(
            'users' => $users,
            'totalCount' => $totalCount
        ));
    }

    public function getPremiumMembers()
    {
        $users = User::where('usertype_id', '>', 1)->whereNotNull('payment_type')->whereNotNull('last_payment_date')->paginate(30);
        return $this->createView('users.premiumMembers', array(
            'users' => $users
        ));
    }
    
    

}