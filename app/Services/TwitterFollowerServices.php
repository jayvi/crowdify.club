<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 9/17/15
 * Time: 1:52 PM
 */

namespace App\Services;


use Abraham\TwitterOAuth\TwitterOAuth;
use App\Bank;
use App\FollowerPullingHelper;
use App\Helpers\DataUtils;
use App\Item;
use App\Profile;
use App\User;
use App\UserType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;


class TwitterFollowerServices
{

    private $config;

    public function __construct(){
        $this->config = Config::get('services');
    }

    public function startPulling(){


        die();
        $helper = FollowerPullingHelper::with('profile')->first();

        if(!$helper){
            $helper = new FollowerPullingHelper();
            $helper->cursor = '-1';
            $profile = $this->getProfile();
            $this->setHelperProfile($helper,$profile);
            if(!$profile){
                return;
            }
        }else{
            if($helper->profile->is_pulled || $helper->cursor == '0' || !$helper->cursor){
                $profile = $this->getProfile();
                $this->setHelperProfile($helper,$profile, false);
                if(!$profile){
                    return;
                }
            }
        }
        if($helper){
            $helper->number_of_request = 0;
            $helper->save();
            $this->pull($helper);
        }
    }

    private function pull($helper){
        if($helper->profile->is_pulled || $helper->cursor == '0' || !$helper->cursor){
            $helper->profile->is_pulled = true;
            $helper->profile->update();
            $profile = $this->getProfile();
            $this->setHelperProfile($helper,$profile);
            if(!$profile){
                return;
            }
        }

        if(!$helper){
            return;
        }
        if($this->isRequestExceeded($helper)){
            return;
        }

        if($helper->is_limit_exceeded){
            if($helper->next_request_time && $helper->next_request_time->lt(Carbon::now())){
                $this->reInitUpdateHelper($helper);
            }else{
                return;
            }
        }


        $this->pullFollowers($helper);

    }

    private function setHelperProfile($helper, $profile = null, $newUser = true){
        if(!$profile){
            $helper->delete();
            $helper = null;
            return;
        }
        $helper->profile()->associate($profile);
        if($newUser){
            $helper->cursor = '-1';
        }

        $helper->update();
    }

    private function isRequestExceeded($helper){
        if($helper->number_of_request >= 29){
            return true;
        }
        return false;
    }

    private function getProfile(){

        $selectedList = array(
//            'nealschaffer',
//            'iancleary',
//            'nickkellett',
//            'douglaskarr',
//            'iagdotme',
//            'stevenhealey',
//            'kmullett',
//            'growmap',
//            'mike_allton',
//            'zaibatsu',
//            'crowdifytech',
//            'empirekred',
//            'roncallari',
//            'sbhsbh',
//            'coinbase',
//            'techcrunch',
//            'jeffsheehan',
//            'kred',
//            'appearoo',
//            'techcrunch',
//            'jeffsheehan',
//            'klout'

            'bitcoin',
            'rogerver',
            'stringstory',
            'sbcfintechbootcamp',
            'amabaie',
            'twitterapi'
        );

        $profile = Profile::where('provider','=','twitter')->where('is_pulled','=', false)->whereIn('username',$selectedList)
            ->where(function($query){
                $query->where('is_private','=',false)
                    ->orWhere(function($query){
                        $query->whereNotNull('token')
                            ->whereNotNull('token_secret');
                    });
            })
            ->orderBy('twitter_followers','desc')->first();
        return $profile;
    }



    private function pullFollowers($helper){

        try{
            $this->makeRequest($helper->profile, $helper);
            $helper->number_of_request +=1;
            $helper->update();

          //  $this->pull($helper);


        }catch(\Exception $e){
            Log::error('TwitterFollowerServices:');
            Log::error('Line: '.$e->getLine());
            Log::error('File: '.$e->getFile());
            Log::error('error message: '.$e->getMessage());
        }
    }

    private function makeRequest($profile, $helper){

        Log::info('Requesting user: '.$profile->username);
        Log::info('Follower: '.$profile->twitter_followers);


        $connection = new TwitterOAuth($this->config['twitter']['client_id'], $this->config['twitter']['client_secret'], $profile->token, $profile->token_secret);
        $parameters = array(
            'screen_name' => $profile->username,
            'cursor' => $helper->cursor,
            'count' => 180,
            'skip_status' => true,
        );
        $result = $connection->get('friends/list', $parameters);

        if($connection->getLastHttpCode() == 200){

            if(is_array($result->users)){
                Log::info('Response follower coount: '.count($result->users));
            }


            if(is_array($result->users) && count($result->users) > 0){
                $helper->cursor = $result->next_cursor_str;
                $this->storeUsers($result->users, $helper);
            }else{
                $helper->cursor = $result->next_cursor_str;
                $helper->update();
                Log::info('no user found');
            }

            if($helper->cursor == '0' || !$helper->cursor){
                $helper->profile->is_pulled = true;
                $helper->profile->update();
                $nextProfile = $this->getProfile();
                $this->setHelperProfile($helper, $nextProfile);
            }


            //$this->storeUsers($result->users, $helper);
            //$helper->cursor = $result->users[count($result->users) - 1]->id_str;
//                $helper->update();
        }else{

            if($connection->getLastHttpCode() == 401){
                $helper->profile->is_private = true;
                $helper->profile->update();
                $this->setHelperProfile($helper, $this->getProfile());
            }

            if($connection->getLastHttpCode() == 429){
                Log::error('TwitterFollowerServices:'. 'Rate limit exceeded '.'http code: 429');
            }


            $response = $connection->getLastBody();
            print_r($response);

            $code = 0;
            if(isset($response->errors)){
                $errors = $response->errors;
                if($errors && count($errors) > 0) {
                    $code = $errors[0]->code;
                }
            }
            if($connection->getLastHttpCode() == 429 || $code == 88){
                $this->setNextRequestTime($helper);
            }
        }
    }

    private function storeUsers($users, $helper){

        if(is_array($users)){
            foreach($users as $twitterProfile){
                if(User::where('username','=', $twitterProfile->screen_name)->exists()
                    || Profile::where('username','=',$twitterProfile->screen_name)->where('provider','=','twitter')->exists()){
                    continue;
                }
                $this->addUserAndProfile($twitterProfile, 'twitter', DataUtils::$items);
            }

            $helper->update();
        }
    }


    public function addUserAndProfile($twitterProfile, $provider, $items)
    {
        $usertype = UserType::where('role','=','Free')->first();
        $user = new User();
        $user->usertype_id = $usertype->id;
        //$user->email = $provider == 'twitter' ? Carbon::now() .'_'. $socialProfile->id : $socialProfile->email;
        $user->email = Carbon::now() .'_'. $twitterProfile->id ;
        $user->verified = true;
        $user->username = $twitterProfile->screen_name;

        $user->save();

        foreach($items as $key => $value){
            $item = new Item();
            $item->key = $key;
            $item->value = $value;
            $item->user_id = $user->id;
            $item->save();
        }

        $bank = new Bank();
        $bank->crowd_coins = DataUtils::INITIAL_CROWD_COINS;
        $bank->user_id = $user->id;
        $bank->save();


        $profile = new Profile();
        $profile->user_id = $user->id;
        $profile->provider_id = $twitterProfile->id;
        $profile->name = $twitterProfile->name;
        $profile->username = $twitterProfile->screen_name;
        // $profile->email = $provider == 'twitter' ? Carbon::now() .'_'. $socialProfile->id : $socialProfile->email;
        $profile->avatar = $twitterProfile->profile_image_url;
        $profile->token =  null;
        $profile->token_secret = null;
        $profile->provider = $provider;
        $profile->active = true;
        $profile->social_profile_url = 'https://www.twitter.com/'.$twitterProfile->screen_name;
        $profile->social_count = $twitterProfile->statuses_count;
        $profile->is_manual = true;
        $profile->twitter_followers = $twitterProfile->followers_count;
        $profile->save();

        $user->avatar =  $profile->avatar;
        $user->bio = $twitterProfile->description;
        $user->twitter_followers = $twitterProfile->followers_count;
        $user->update();

        return $user;

    }

    private function setNextRequestTime($helper){
        $helper->is_limit_exceeded = true;
        $helper->next_request_time = Carbon::now()->addMinutes(15);
        $helper->update();
    }

    private function reInitUpdateHelper($helper){
        $helper->is_limit_exceeded = false;
        $helper->next_request_time = null;
        $helper->update();
    }

}