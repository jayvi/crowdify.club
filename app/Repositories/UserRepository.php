<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 8/6/15
 * Time: 3:42 PM
 */

namespace App\Repositories;


use Abraham\TwitterOAuth\TwitterOAuth;
use App\Affiliate;
use App\Affrelation;
use App\Bank;
use App\Services\Affiliate\AffiliateService;
use Cookie;
use App\Helpers\DataUtils;
use App\Item;
use App\Profile;
use App\User;
use App\UserType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;

class UserRepository {

    /**
     * Add new profile on new social account connect
     *
     * @param $user
     * @param $socialProfile
     * @param $provider
     * @return Profile
     */
    public function addProfile($user, $socialProfile, $provider, AffiliateService $affiliateService = null)
    {
        $profile = new Profile();
        $profile->user_id = $user->id;
        if($provider == 'tumblr'){
            $profile->provider_id = $socialProfile->nickname;
        }else{
            $profile->provider_id = $socialProfile->id;
        }



        $profile->name = $socialProfile->name;
        $profile->username = $socialProfile->nickname;
        // $profile->email = $provider == 'twitter' ? Carbon::now() .'_'. $socialProfile->id : $socialProfile->email;
        $profile->avatar = $socialProfile->avatar;
        $profile->token = isset($socialProfile->token) ? $socialProfile->token : null;
        $profile->token_secret = isset($socialProfile->tokenSecret) ? $socialProfile->tokenSecret : null;
        $profile->provider = $provider;
        $profile->active = true;
        $profile->social_profile_url = $this->getSocialProfileUrl($socialProfile, $provider);
        $profile->affiliate = cookie::get('affiliate');
        $profile->save();

        if($affiliateService && $provider=='twitter'){
            $affiliateService->addAffiliateIfNeeded($profile);
        }




        if($provider == 'twitter'){
            if(!$user->avatar_original){
                $this->updateUserAvatar($user, $profile->avatar, isset($socialProfile->avatar_original) ? $socialProfile->avatar_original : $socialProfile->avatar);
            }
            $this->updateExtraInformationFromTwitter($profile, $user);
        }


        return $profile;

    }

    private function getSocialProfileUrl($socialProfile, $provider){
        $url = null;
        switch($provider){
            case 'twitter':
                $url = 'https://www.twitter.com/'.$socialProfile->nickname;
                break;
            case 'facebook':
                $url = 'https://www.facebook.com/'.$socialProfile->id;
                break;
            case 'google':
                $url = $socialProfile->user['url'];
                break;
            case 'linkedin':
                $url = $socialProfile->user['publicProfileUrl'];
                break;
            case 'foursquare':
                $url = $socialProfile->user['canonicalUrl'];
                break;
            case 'flickr':
                $url = $socialProfile->user['profileurl']['_content'];
                break;
            case 'instagram':
                $url = 'https://instagram.com/'.$socialProfile->nickname;
                break;
            case 'youtube':
                $url = 'https://www.youtube.com/channel/'.$socialProfile->id;
                break;
            case 'blogger':
                $url = $socialProfile->user['url'];
                break;
            case 'facebookPage':
                $url = 'https://www.facebook.com/pages/'.$socialProfile['name'].'/'.$socialProfile['id'];
                break;
            case 'tumblr':
                $url = 'http://'.$socialProfile->nickname.'.tumblr.com';
                break;
            case 'paypal':
                $url = 'https://www'.$this->getPaypalModeUrl().'.paypal.com/webapps/auth/identity/user/'.$socialProfile->id;
                break;
            default:
                break;
        }
        return $url;
    }

    private function getPaypalModeUrl(){
        return env('PAYPAL_MODE','live') == 'sandbox' ? ".sandbox" : '';
    }

    /**
     * update the user avatar if not set yet
     *
     * @param $user
     * @param $avatar
     */
    private function updateUserAvatar($user, $avatar, $avatar_original){
        $user->avatar = $avatar ? $avatar : $avatar_original;
        $user->avatar_original = $avatar_original ;
        $user->update();
    }

    /**
     * update social profile if any changes made
     *
     * @param $profile
     * @param $socialProfile
     * @return mixed
     */
    public function updateProfile($profile, $socialProfile)
    {
        $socialData = [
            'name' => $socialProfile->name,
            'username' => $socialProfile->nickname,
            // 'email' => $provider == 'twitter' ? Carbon::now() .'_'. $socialProfile->id : $socialProfile->email,
            'token' => isset($socialProfile->token) ? $socialProfile->token : null,
            'token_secret' => isset($socialProfile->tokenSecret) ? $socialProfile->tokenSecret : null,
            'active' => true,
            'social_profile_url' => $this->getSocialProfileUrl($socialProfile, $profile->provider)
        ];

        $dbData = [
            'name' => $profile->name,
            'username' => $profile->username,
            //'email' => $user->email,

            'token' => $profile->token,
            'token_secret' => $profile->token_secret,
            'active' => $profile->active,
            'social_profile_url' => $profile->social_profile_url
        ];
        if($profile->provider == 'twitter'){
            $socialData['avatar'] = $socialProfile->avatar;
            $dbData['avatar'] = $profile->avatar;
        }

        if (!empty(array_diff($socialData, $dbData))) {
            $profile->name= $socialData['name'];
            $profile->username = $socialData['username'];
            // $profile->email = $socialData['email'];
            //$profile->avatar = $socialData['avatar'];
            $profile->token = $socialData['token'];
            $profile->token_secret = $socialData['token_secret'];
            $profile->active = $socialData['active'];
            $profile->social_profile_url = $socialData['social_profile_url'];
            $profile->affiliate = cookie::get('affiliate');
            $profile->save();

        }
        $user = $profile->user;
        if($profile->provider == 'twitter'){
            $user->username = $profile->username;
            $this->updateUserAvatar($user, $profile->avatar,isset($socialProfile->avatar_original) ? $socialProfile->avatar_original : $socialProfile->avatar);
        }

        if($profile->provider == 'twitter'){
            $this->updateExtraInformationFromTwitter($profile, $user);
        }

        return $profile;

    }

    /**
     * add new user and profile
     *
     * @param $socialProfile
     * @param $provider
     * @param $items
     * @return User
     */
    public function addUserAndProfile($socialProfile, $provider, $items, AffiliateService $affiliateService = null)
    {
        $usertype = UserType::where('role','=','Free')->first();

        $user = new User();

        $user->usertype_id = $usertype->id;
        //$user->email = $provider == 'twitter' ? Carbon::now() .'_'. $socialProfile->id : $socialProfile->email;
        $user->first_name = $socialProfile->name ? $socialProfile->name : '';
        $user->email = Carbon::now() .'_'. $socialProfile->id ;
        $user->verified = true;
        $user->username = $socialProfile->nickname;

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
        $profile->provider_id = $socialProfile->id;
        $profile->name = $socialProfile->name;
        $profile->username = $socialProfile->nickname;
        // $profile->email = $provider == 'twitter' ? Carbon::now() .'_'. $socialProfile->id : $socialProfile->email;
        $profile->avatar = $socialProfile->avatar;
        $profile->token = isset($socialProfile->token) ? $socialProfile->token : null;
        $profile->token_secret = isset($socialProfile->tokenSecret) ? $socialProfile->tokenSecret : null;
        $profile->provider = $provider;
        $profile->active = true;
        $profile->social_profile_url = $this->getSocialProfileUrl($socialProfile, $provider);
        $profile->affiliate = cookie::get('affiliate');
        $profile->save();

        if($affiliateService && $provider=='twitter'){
            $affiliateService->addAffiliateIfNeeded($profile);
        }


        $user->avatar =  $profile->avatar;
        $user->update();

        if($provider == 'twitter'){
            $this->updateExtraInformationFromTwitter($profile, $user);
        }

        return $user;

    }

    /**
     * update extra information like bio etc. from twitter
     *
     * @param $profile
     * @param $user
     */
    private function updateExtraInformationFromTwitter($profile, $user){
        $config = Config::get('services');
        $connection = new TwitterOAuth($config['twitter']['client_id'], $config['twitter']['client_secret'], $profile->token, $profile->token_secret);

        $result = $connection->get('users/show', array('user_id' => $profile->provider_id,
            "screen_name" => $profile->username, 'include_entities' => false));

        if($result){
            if($result->description){
                $user->bio = $result->description;
            }
            if($result->followers_count){
                $user->twitter_followers = $result->followers_count;
                $profile->twitter_followers = $result->followers_count;
            }

            $profile->social_count = $result->statuses_count;
            $profile->update();

            if($result->followers_count){
                $user->twitter_followers = $result->followers_count;
            }
            $user->update();

        }
    }

    public function addFacebookPage($socialProfile, $provider ,$page, $user)
    {
        $profile = new Profile();
        $profile->user_id = $user->id;
        $profile->provider_id = $page['id'];
        $profile->name = $page['name'];
        $profile->username = null;
        // $profile->email = $provider == 'twitter' ? Carbon::now() .'_'. $socialProfile->id : $socialProfile->email;
        $profile->avatar = 'https://graph.facebook.com/'.$page['id'].'/picture';
        $profile->token = isset($socialProfile->token) ? $socialProfile->token : null;
        $profile->token_secret = isset($socialProfile->tokenSecret) ? $socialProfile->tokenSecret : null;
        $profile->page_access_token = $page['page_access_token'];
        $profile->provider = $provider;
        $profile->active = true;

        $profile->social_profile_url = $this->getSocialProfileUrl($page, $provider);

        $profile->save();


    }


}