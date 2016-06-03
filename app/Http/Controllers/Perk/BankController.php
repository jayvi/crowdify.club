<?php

namespace App\Http\Controllers\Perk;

use App\Events\CrowdCoinsSent;
use App\Services\TweetService;
use App\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Wallet;

use BlockIo;

class BankController extends Controller
{
    public function __construct(Guard $auth)
    {
        parent::__construct($auth);
        $this->middleware('auth');
        $this->middleware('email.check');
    }

    public function index(Request $request)
    {
        $user = $this->auth->user()->username;
        if(Wallet::where('label', '=', $user )->first()) {
            $wallet = $this->auth->user()->wallet()->first();
        }
        else {
            $wallet = null;
        }
        $bank = $this->auth->user()->bank()->first();
        $item = $this->auth->user()->items()->with(array('soldShares'))->where('key','=','Main')->first();
        $firstTimeInBank = $this->isFirstTimeInBank($request);
        if($firstTimeInBank){
            $this->forgetFirstTimeInBank($request);
        }
        return $this->createView('perk.bank', compact('bank','item','firstTimeInBank','wallet', 'user'));
    }

    public function sendCoin(Request $request, TweetService $tweetService){

    if($request->has('username')){
        $user = User::where('username','=', $request->get('username'))->first();
        if($user){

            $twitterProfile = $this->auth->user()->profiles()->where('provider','=','twitter')->first();
            if($request->has('tweet')){
                if(!$request->get('tweet')){
                    return response()->json(array('message'=>"Please enter tweet text"), 400);
                }
            }

            if((int)$this->auth->user()->bank->seed_coins < (int)$request->get('amount')){
                return response()->json(array('message'=>"Sorry you do not have enough Crowdify Coins to send this gift"), 400);
            }
            if(!$request->get('amount')){
                return response()->json(array('message'=>"Please Enter gift amount"), 400);
            }
            $user->bank->seed_coins += (int)$request->get('amount');
            $user->bank->update();
            $this->auth->user()->bank->seed_coins -= (int)$request->get('amount');
            $this->auth->user()->bank->update();

            $this->fireEvent(new CrowdCoinsSent($this->auth->user(), $user, $request->get('amount')));

            if($request->has('tweet')){
                if($tweetService->tweet($twitterProfile, $request->get('tweet'))){
                    return response()->json(array('status' => 200,'message'=>'Gift is successfully sent!','bank' => $this->auth->user()->bank), 200);
                }else{
                    return response()->json(array('status' => 400,'message'=>$tweetService->getErrorMessage()), 400);
                }
            }


            if($request->ajax()){
                return response()->json(array('message'=>'Gift is successfully sent!','bank' => $this->auth->user()->bank), 200);
            }
            return redirect()->back()->with('success', 'Gift is successfully sent!');

        }
    }
    if($request->ajax()){
        return response()->json(array('message'=>'Something is wrong, please try again!'), 400);
    }
    return redirect()->back()->with('error', 'Something is wrong, please try again!');
}
    public function sendPoint(Request $request, TweetService $tweetService){

        if($request->has('username')){
            $user = User::where('username','=', $request->get('username'))->first();
            if($user){

                $twitterProfile = $this->auth->user()->profiles()->where('provider','=','twitter')->first();
                if($request->has('tweet')){
                    if(!$request->get('tweet')){
                        return response()->json(array('message'=>"Please enter tweet text"), 400);
                    }
                }

                if((int)$this->auth->user()->bank->crowd_coins < (int)$request->get('amount')){
                    return response()->json(array('message'=>"Sorry you do not have enough Crowdify Coins to send this gift"), 400);
                }
                if(!$request->get('amount')){
                    return response()->json(array('message'=>"Please Enter gift amount"), 400);
                }
                $user->bank->crowd_coins += (int)$request->get('amount');
                $user->bank->update();
                $this->auth->user()->bank->crowd_coins -= (int)$request->get('amount');
                $this->auth->user()->bank->update();

                $this->fireEvent(new CrowdCoinsSent($this->auth->user(), $user, $request->get('amount')));

                if($request->has('tweet')){
                    if($tweetService->tweet($twitterProfile, $request->get('tweet'))){
                        return response()->json(array('status' => 200,'message'=>'Gift is successfully sent!','bank' => $this->auth->user()->bank), 200);
                    }else{
                        return response()->json(array('status' => 400,'message'=>$tweetService->getErrorMessage()), 400);
                    }
                }


                if($request->ajax()){
                    return response()->json(array('message'=>'Gift is successfully sent!','bank' => $this->auth->user()->bank), 200);
                }
                return redirect()->back()->with('success', 'Gift is successfully sent!');

            }
        }
        if($request->ajax()){
            return response()->json(array('message'=>'Something is wrong, please try again!'), 400);
        }
        return redirect()->back()->with('error', 'Something is wrong, please try again!');
    }
}
