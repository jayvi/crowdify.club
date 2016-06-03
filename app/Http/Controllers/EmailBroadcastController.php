<?php

namespace App\Http\Controllers;


use App\Services\Broadcaster\BroadCaster;
use App\Services\Broadcaster\BroadCastListener;
use App\Services\Broadcaster\Email;
use App\User;
use Illuminate\Auth\Guard;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;

class EmailBroadcastController extends Controller implements BroadCastListener
{

    protected $broadCaster;
    public function __construct(Guard $auth, BroadCaster $broadCaster){
        parent::__construct($auth);
        $this->broadCaster = $broadCaster;
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index(){
        return $this->createView('broadcaster.index',array());
    }

    public function broadCast(Request $request){

        set_time_limit(0);

        $rules = [
           'subject' => 'required',
           'body' => 'required|min:12'
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return redirect()->back()->withInput()->withErrors($validator->messages());
        }
        $users = User::where('is_email','=', true)->get();

        return $this->broadCaster->broadCast($users, new Email($request->get('subject'), $request->get('body')),$this);
    }

    public function onBroadCasted($message){
        return redirect()->back()->with('success',$message);
    }

    public function onError($error){
        return redirect()->back()->with('error',$error);
    }
}
