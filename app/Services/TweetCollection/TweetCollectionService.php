<?php
/**
 * Created by PhpStorm.
 * User: aqib
 * Date: 5/19/16
 * Time: 1:35 PM
 */

namespace App\Services\TweetCollection;


use Abraham\TwitterOAuth\TwitterOAuth;
use App\Models\MachineLearningDataSet\Tweet;
use App\Models\MachineLearningDataSet\TweetUser;
use App\Models\MachineLearningDataSet\TweetUserHelper;
use App\Profile;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class TweetCollectionService
{
    private $config;

    public function  __construct(){
        $this->config = Config::get('services')['twitter'];
    }

    public function getBatchTweetByUser()
    {
        $tweet_collection_user_helper = TweetUserHelper::where('key', '=', 'tweet_profile_id_offset')->first();
        $tweet_collection_user_offset = 0;
        if($tweet_collection_user_helper){
            $tweet_collection_user_offset = $tweet_collection_user_helper->value;
        }
        else {
            $tweet_collection_user_helper = TweetUserHelper::create(['key' => 'tweet_profile_id_offset', 'value' => 0]);
        }
        $profiles = TweetUser::where('is_pulled', '=', 0)->skip($tweet_collection_user_offset)->take(300)->get();
        if($profiles && count($profiles)) $tweet_collection_user_helper->update(['value' => ($tweet_collection_user_offset + count($profiles))]);
        else $tweet_collection_user_helper->update(['value' => 0 ]);
        foreach ($profiles as $profile) {
            $this->getTweetByUser($profile);
        }
    }

    public function getTweetByUser($profile){
        $userTweetHelper = TweetUserHelper::where('key', '=', $profile->twitter_id.'_user_tweet_max_id')->first();
        $userTweetMaxId = 0;
        if($userTweetHelper){
            $userTweetMaxId = $userTweetHelper->value;
        }
        else{
            $userTweetHelper = TweetUserHelper::create([ 'key' => $profile->twitter_id.'_user_tweet_max_id', 'value' => 0 ]);
        }
        $userProfile = Profile::find($profile->crowdify_profile_id);
//        $profile = array();
        $connection = new TwitterOAuth($this->config['client_id'], $this->config['client_secret'], $userProfile->token, $userProfile->token_secret);
        $parameters = array(
            'screen_name' => $profile->username,
            'count' => 180,
//            'max_id' => 1
        );
        if($userTweetMaxId != 0){
            $parameters['max_id'] = $userTweetMaxId;
        }
        $results = $connection->get('statuses/user_timeline', $parameters);
        if($connection->getLastHttpCode() == 200){
            if(count($results)){
//                dd($results);
                if($userTweetMaxId == 0){
                    $userSinceIDHelper = TweetUserHelper::where('key', '=', $profile->twitter_id.'_user_tweet_since_id')->first();
                    if(!$userSinceIDHelper) TweetUserHelper::create(['key' => $profile->twitter_id.'_user_tweet_since_id', 'value' => $results[0]->id_str]);
                }
                if(count($results) == 1 && $results[0]->id_str == $userTweetMaxId){
                    $profile->update(['is_pulled' => 1]);
                    return;
                }
                foreach ($results as $result){
                    if($result->id_str == $userTweetMaxId) continue;
//                    dd($result);
                    $this->insertTweet($result, $profile);
                }
                $userTweetHelper->update(['value' => $results[count($results) - 1]->id_str]);
            }
            else{
                $profile->update(['is_pulled' => 1]);
            }
        }
    }

    public function insertTweet($tweet, $profile)
    {
//        dd($tweet);

//        Log::error(json_encode($tweet));
        $args = array(
            'tweet_user_id'             => $profile->id,
            'tweet_id'                  => $tweet->id_str,
            'tweeted_at'                => isset($tweet->created_at) ? Carbon::parse($tweet->created_at) : Carbon::now(),
            'text'                      => isset($tweet->text) ? $tweet->text : '',
            'entities'                  => isset($tweet->entities) ? json_encode($tweet->entities) : null,
            'source'                    => isset($tweet->source) ? $tweet->source : null,
            'in_reply_to_status_id'     => isset($tweet->in_reply_to_status_id) ? $tweet->in_reply_to_status_id : null,
            'in_reply_to_user_id'       => isset($tweet->in_reply_to_user_id) ? $tweet->in_reply_to_user_id : null,
            'in_reply_to_screen_name'   => isset($tweet->in_reply_to_screen_name) ? $tweet->in_reply_to_screen_name : null,
            'geo'                       => isset($tweet->geo) ? json_encode($tweet->geo) : null,
            'coordinates'               => isset($tweet->coordinates) ? json_encode($tweet->coordinates) : null,
            'place'                     => isset($tweet->place) ? json_encode($tweet->place) : null,
            'contributors'              => isset($tweet->contributors) ? json_encode($tweet->contributors) : null,
            'is_quote_status'           => isset($tweet->is_quote_status) ? $tweet->is_quote_status : false,
            'retweet_count'             => isset($tweet->retweet_count) ? $tweet->retweet_count : 0,
            'favourite_count'           => isset($tweet->favorite_count) ? $tweet->favorite_count : 0,
            'favourited'                => isset($tweet->favorited) ? $tweet->favorited : false,
            'retweeted'                 => isset($tweet->retweeted) ? $tweet->retweeted : false,
            'possibly_sensitive'        => isset($tweet->possibly_sensitive) ? $tweet->possibly_sensitive : false,
            'lang'                      => isset($tweet->lang) ? $tweet->lang : null
        );

        try{
            Tweet::create($args);
        }catch (\Exception $e){
//            Log::error($e->getMessage().' '.$e->getLine().' '.$e->getFile());
        }
    }
}