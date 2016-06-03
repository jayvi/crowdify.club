<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 11/11/15
 * Time: 12:53 PM
 */

namespace App\Services;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Bank;
use App\Helpers\DataUtils;
use App\Item;
use App\Profile;
use App\User;
use App\UserType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use App\TwitterAccount;

class TwitterAccountsService
{

    private $config;

    public function __construct(){
        $this->config = Config::get('services');
    }

    private function getAccountLists()
    {
        return [
            'mashable'
        ];
    }

    private function addTwitterAccountsToPullIfNotExists()
    {
        $accounts = $this->getAccountLists();
        foreach ($accounts as $account){
            $twitterAccount = TwitterAccount::where('username','=',$account)->first();
            if(!$twitterAccount){
                $twitterAccount = new TwitterAccount();
                $twitterAccount->username = 'mashable';
                $twitterAccount->pull_followers = false;
                $twitterAccount->pull_friends = true;
                $twitterAccount->is_followers_pulled = false;
                $twitterAccount->is_friends_pulled = false;
                $twitterAccount->is_private = false;
                $twitterAccount->cursor_followers = -1;
                $twitterAccount->cursor_friends = -1;
                $twitterAccount->save();
            }
        }
    }

    private function getTwitterAccount()
    {

        $this->addTwitterAccountsToPullIfNotExists();

        $twitterAccountToPull = TwitterAccount::where('is_private','=',false)->where(function($query){
            $query->where(function($query){
                $query->where('pull_followers','=',true)->where('is_followers_pulled','=',false);
            })->orWhere(function($query){
                $query->where('pull_friends','=',true)->where('is_friends_pulled','=',false);
            });
        })->first();
        return $twitterAccountToPull;

    }

    public function pull(){

        $twitterAccount = $this->getTwitterAccount();
        if($twitterAccount){
            if($twitterAccount->pull_friends && !$twitterAccount->is_friends_pulled){
                $isFollower = false;
            }else{
                $isFollower = true;
            }

            $this->makeRequest($twitterAccount, $isFollower);
        }

    }

    private function makeRequest($twitterAccount, $isFollower){

        Log::info('Requesting user: '.$twitterAccount->username);


        $connection = new TwitterOAuth($this->config['twitter']['client_id'], $this->config['twitter']['client_secret'], null, null);
        $parameters = array(
            'screen_name' => $twitterAccount->username,
            'cursor' => $isFollower ? $twitterAccount->cursor_followers :$twitterAccount->cursor_friends,
            'count' => 180,
            'skip_status' => true,
        );

        if(!$isFollower){
            $result = $connection->get('friends/list', $parameters);
        }else{
            $result = $connection->get('followers/list', $parameters);
        }

        if($connection->getLastHttpCode() == 200){

            if(is_array($result->users)){
                Log::info('Response follower coount: '.count($result->users));
            }


            if(is_array($result->users) && count($result->users) > 0){
                if($isFollower){
                    $twitterAccount->cursor_followers = $result->next_cursor_str;
                }else{
                    $twitterAccount->cursor_friends = $result->next_cursor_str;
                }

                $twitterAccount->update();
                $this->storeUsers($result->users, $twitterAccount);
            }else{
                if($isFollower){
                    $twitterAccount->cursor_followers = $result->next_cursor_str;
                }else{
                    $twitterAccount->cursor_friends = $result->next_cursor_str;
                }
                $twitterAccount->update();
                Log::info('no user found');
            }
            if($isFollower){
                if($twitterAccount->cursor_followers == '0' || !$twitterAccount->cursor_followers){
                    $twitterAccount->is_followers_pulled = true;
                    $twitterAccount->update();
                }
            }else{
                if($twitterAccount->cursor_friends == '0' || !$twitterAccount->cursor_friends){
                    $twitterAccount->is_friends_pulled = true;
                    $twitterAccount->update();
                }
            }



            //$this->storeUsers($result->users, $helper);
            //$helper->cursor = $result->users[count($result->users) - 1]->id_str;
//                $helper->update();
        }else{

            if($connection->getLastHttpCode() == 401){
                $twitterAccount->is_private = true;
                $twitterAccount->update();
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

            }
        }
    }

    private function storeUsers($users){

        if(is_array($users)){
            foreach($users as $twitterProfile){
                if(User::where('username','=', $twitterProfile->screen_name)->exists()
                    || Profile::where('username','=',$twitterProfile->screen_name)->where('provider','=','twitter')->exists()){
                    continue;
                }
                $this->addUserAndProfile($twitterProfile, 'twitter', DataUtils::$items);
            }

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
}