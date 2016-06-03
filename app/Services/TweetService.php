<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 9/1/15
 * Time: 1:46 PM
 */

namespace App\Services;


use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class TweetService
{

    private $connection;
    private $errorMessage;
    public function __construct(){
        $this->errorMessage = '';//'Sorry, Currently we are having problem with twitter api. We are resolving this as soon as possible. <br/> Thanks for you patience';
    }

    public function tweet($profile, $tweet){
        //return false;
        $config = Config::get('services');
        $this->connection = new TwitterOAuth($config['twitter']['client_id'], $config['twitter']['client_secret'], $profile->token, $profile->token_secret);
        $parameters = array(
            'status' => $tweet
        );
        $this->connection->post('statuses/update', $parameters);
        if($this->connection->getLastHttpCode() == 200){
            return true;
        }else{
            $response = $this->connection->getLastBody();
            if($response->errors && count($response->errors) > 0) {
                $this->errorMessage = $response->errors[0]->message;
            }
           // $this->errorMessage = $response->errors;
            return false;
        }
    }

    public function getLastHttpCode(){
        return $this->connection->getLastHttpCode();
    }

    public function getErrorMessage(){
        return $this->errorMessage;
    }
}