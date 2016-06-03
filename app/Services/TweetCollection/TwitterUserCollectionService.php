<?php
/**
 * Created by PhpStorm.
 * User: aqib
 * Date: 5/20/16
 * Time: 3:35 PM
 */

namespace App\Services\TweetCollection;


use Abraham\TwitterOAuth\TwitterOAuth;
use App\Models\MachineLearningDataSet\TweetUser;
use App\Models\MachineLearningDataSet\TweetUserHelper;
use App\Profile;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class TwitterUserCollectionService
{
    private $config;

    public function __construct()
    {

        $this->config = Config::get('services')['twitter'];
    }

    public function pullTwitterUsersFromTwitter(){
        $profile_helper = TweetUserHelper::where('key', '=', 'profile_id_offset')->first();
        $profile_offset = 0;
        if($profile_helper) {
            $profile_offset = $profile_helper->value;
        }
        else {
            $profile_helper = TweetUserHelper::create(['key' => 'profile_id_offset', 'value' => 0]);
        }

        $profiles = Profile::where('provider', '=', 'twitter')->skip($profile_offset)->limit(180)->get();
        $profile_helper->update(['value' => $profile_offset + count($profiles)]);

        foreach ($profiles as $profile){
            $this->pullSingleTwitterUser($profile);
        }
//        dd($profiles);
    }

    public function pullSingleTwitterUser($profile){
        $connection = new TwitterOAuth($this->config['client_id'], $this->config['client_secret'], $profile->token, $profile->token_secret);
        try {
            $result = $connection->get('users/show', array('user_id' => $profile->provider_id,
                "screen_name" => $profile->username, 'include_entities' => true));

            if($connection->getLastHttpCode() == 200){
                $args = array();

                $args['crowdify_profile_id'] = $profile->id;
                $args['twitter_id'] = $result->id;

                $args['name'] = isset($result->name) ? $result->name : null ;
                $args['screen_name'] = $result->screen_name;
                $args['location_name'] = isset($result->location) ? $result->location : null;
                $args['description'] = isset($result->description) ? $result->description : null;
                $args['url'] = isset($result->url) ? $result->url : null;
                $args['entities'] = isset($result->entities) ? json_encode($result->entities) : null;
                $args['followers_count'] = isset($result->followers_count) ? $result->followers_count : 0;
                $args['friends_count'] = isset($result->friends_count) ? $result->friends_count : 0;
                $args['listed_count'] = isset($result->listed_count) ? $result->listed_count : 0;
                $args['user_created_at'] = isset($result->created_at) ? Carbon::parse($result->created_at) : Carbon::now();
                $args['favourites_count'] = isset($result->favourites_count) ? $result->favourites_count : 0;
                $args['utc_offset'] = isset($result->utc_offset) ? $result->utc_offset : 0;
                $args['time_zone'] = isset($result->time_zone) ? $result->time_zone : null;
                $args['statuses_count'] = isset($result->statuses_count) ? $result->statuses_count : 0;
                $args['lang'] = isset($result->lang) ? $result->lang : null;
                $args['contributor_enabled'] = isset($result->contributors_enabled) ? $result->contributors_enabled : false;
                $args['is_translator'] = isset($result->is_translator) ? $result->is_translator : false;
                $args['is_translation_enabled'] = isset($result->is_translation_enabled) ? $result->is_translation_enabled : false;
                $args['has_extended_profile'] = isset($result->has_extended_profile) ? $result->has_extended_profile : false;
                $args['default_profile'] = isset($result->default_profile) ? $result->default_profile : null;
                $args['following'] = isset($result->following) ? $result->following : null;
                $args['follow_request_sent'] = isset($result->follow_request_sent) ? $result->follow_request_sent : 0;

                TweetUser::create(
                    $args
                );
            }


        }catch (\Exception $e){
            Log::error($e->getMessage());
        }
    }
}