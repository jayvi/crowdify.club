<?php

namespace App\Http\Controllers;


use App\Notification;
use App\SiteMeta;
use Carbon\Carbon;
use Event;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Lang;

abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    public $auth;
    public $COMMENT_ENABLE = false;
    public $maxNoOfCrowdCoinPopupCount = 50;
    public $randomCrowdCoinDeposits = [2500, 5000, 10000];


    protected $items = array('main'=>'Main','city'=> 'City', 'category_1'=> 'Category 1', 'category_2'=> 'Category 2', 'interest'=> 'Interest','my_idea'=>'My Idea');

    public function __construct(Guard $auth){
        $this->auth = $auth;
    }

    protected function createView($name, $data = array()){

        $showCrowdCoinGiftPopup = $this->isTimeToShowCrowdCoinPopup();
        $crowdCoinAmount = 0;
        if($showCrowdCoinGiftPopup){
            $crowdCoinAmount = $this->randomDepositTo($this->auth->user());
        }

        return view($name, $data)
            ->with('auth', $this->auth)
            ->with('showCrowdCoinPopup', $showCrowdCoinGiftPopup)
            ->with('crowdCoinAmount',$crowdCoinAmount);
    }

    public function isTimeToShowCrowdCoinPopup(){

        if($this->auth->check()){
            if(rand(0,24) == 0 || rand(0,24) == 12 || rand(0,24) == 24){
                $siteMeta = SiteMeta::where('key','=','crowd_coin_gift_popup_count')->first();
                if($siteMeta && (Carbon::now()->subMinutes(20)->gt($siteMeta->updated_at)) ){
                    if((int)$siteMeta->value < (int)$this->maxNoOfCrowdCoinPopupCount ){
                        $siteMeta->value = ''.((int)$siteMeta->value + 1);
                        $siteMeta->update();
                        return true;
                    }
                }
            }
        }
        return false;
    }

    public function randomDepositTo($user){
        if($user){
            $amount = $this->randomCrowdCoinDeposits[rand(0,count($this->randomCrowdCoinDeposits) -1)];
            $user->bank->crowd_coins += (int)$amount;
            $user->bank->update();
            return $amount;
        }

        return 0;

    }



    protected function calculateSharePrice($currentSharePrice, $shareAmount){
        return $currentSharePrice + ($shareAmount  / 10000);
    }

    protected function refundShare($currentSharePrice, $shareAmount){
        return $currentSharePrice * $shareAmount;
    }

    protected function fireEvent(\App\Events\Event $event){
        Event::fire($event);
    }

    public function getMessage($key){
        return Lang::has($key)
            ? Lang::get($key)
            : '';
    }

    public function setSessionValues(Request $request){
        $request->session()->put('see_introduction_video',true);
        $request->session()->put('edit_profile',true);
        $request->session()->put('see_explanation_video', true);
        $request->session()->put('first_time_in_bank', true);
        $request->session()->put('first_time_in_tasks', true);
        $request->session()->put('first_time_in_perks', true);
    }

    public function isFirstTimeInPerks(Request $request){
        return $request->session()->get('first_time_in_perks', false);
    }

    public function forgetFirstTimeInPerks(Request $request){
        return $request->session()->forget('first_time_in_perks');
    }

    public function isFirstTimeInTasks(Request $request){
        return $request->session()->get('first_time_in_tasks', false);
    }

    public function forgetFirstTimeInTasks(Request $request){
        return $request->session()->forget('first_time_in_tasks');
    }
    public function isFirstTimeInBank(Request $request){
        return $request->session()->get('first_time_in_bank', false);
    }

    public function forgetFirstTimeInBank(Request $request){
        return $request->session()->forget('first_time_in_bank');
    }

    public function getIntroVideoSession(Request $request){
        return $request->session()->get('see_introduction_video',false);
    }
    public function forgetIntroVideoSession(Request $request){
        $request->session()->forget('see_introduction_video');
    }

    public function getProfileEditSession(Request $request){
        return $request->session()->get('edit_profile',false);
    }
    public function forgetProfileEditSession(Request $request){
        $request->session()->forget('edit_profile');
    }

   public function getExplanationVideoSession(Request $request){
       return $request->session()->get('see_explanation_video',false);
   }

    public function forgetExplanationVideoSession(Request $request){
        $request->session()->forget('see_explanation_video');
    }

    protected function readNotification(Request $request){
        if($request->has('n')){
            $notification = Notification::find($request->get('n'));
            if($notification && $notification->recipient_id == $this->auth->id()){
                $notification->seen = true;
                $notification->update();
            }
        }
    }

    protected function uploadPhoto($file, $basePath){
        if($file){
            $destinationPath =public_path().$basePath;
            $fileName = rand(1, 100000).strtotime(date('Y-m-d H:i:s')).$this->auth->id().".".$file->getClientOriginalExtension();
            $file->move($destinationPath, $fileName);

            //$fileName = $destinationPath.$fileName;
            $fileName = $basePath.$fileName;
            return $fileName;
        }
        return null;
    }




}
