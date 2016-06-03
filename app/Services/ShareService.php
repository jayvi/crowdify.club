<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 8/15/15
 * Time: 4:26 PM
 */

namespace App\Services;


use App\Event;
use App\Interfaces\ShareInterface;
use App\Profile;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Support\Facades\Config;
use LinkedIn\LinkedIn;

class ShareService
{

    private $auth;

    public function __construct(Guard $auth){
        $this->auth = $auth;
    }

    public function share(Request $request, $event_id, ShareInterface $listener, $provider){
        $user = $this->auth->user();
        $profile = $user->profiles()->where('provider','=',$provider)->first();
        $event = Event::find($event_id);

        $response = null;
        switch($provider){
            case 'twitter':
                $response =  $this->shareInTwitter($request, $profile, $event, $listener);
                break;

            case 'facebook':
                $response = $this->shareInFacebook($request, $profile, $event, $listener);
                break;
            case 'linkedin':
               // $response = $this->shareInLinkedIn($request, $profile, $event, $listener);
                break;
        }

        return $response;
    }

    private function shareInTwitter(Request $request, Profile $profile,Event $event,ShareInterface $listener)
    {
        $config = Config::get('services');
        $connection = new TwitterOAuth($config['twitter']['client_id'], $config['twitter']['client_secret'], $profile->token, $profile->token_secret);
        if($event->logo){
            $media = $connection->upload('media/upload', array('media' => $event->logo));
            $parameters = array(
                'status' => $event->title,
                'media_ids' => implode(',', array($media->media_id_string)),
            );
            $result = $connection->post('statuses/update', $parameters);
            if($connection->getLastHttpCode() == 200){
                return $listener->onShareComplete(array('success' => 'Successfully Shared'));
            }
            return $listener->onShareFailed(array('error' => 'Failed to share'));
        }else{

        }
    }

    private function shareInFacebook(Request $request, Profile $profile,Event $event,ShareInterface $listener){

        $config = Config::get('services');
        $fb = new Facebook([
            'app_id' =>  $config['facebook']['client_id'],
            'app_secret' => $config['facebook']['client_secret'],
            'default_graph_version' => 'v2.2',
        ]);

        $linkData = [
            'link' => url('/event/'.$event->id.'/show'),
            'message' => $event->title,
        ];

        try {
            // Returns a `Facebook\FacebookResponse` object
            $response = $fb->post('/me/feed', $linkData, $profile->token);

            return $listener->onShareComplete(array());
        } catch(FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
    }

    private function shareInLinkedIn(Request $request, Profile $profile,Event $event,ShareInterface $listener)
    {

        $config = Config::get('services');

        $li = new LinkedIn(
            array('api_key' => $config['linkedin']['client_id'],
                'api_secret' =>$config['linkedin']['client_secret'],
                'callback_url' => $config['linkedin']['redirect'])
        );
        $li->setAccessToken($profile->token);

        $body = array(
            'comment' => 'Im testing Happyr LinkedIn client! https://github.com/Happyr/LinkedIn-API-client',
            'visibility' => array(
                'code' => 'anyone'
            )
        );
//        $body = new \stdClass();
//        $body->comment = 'Some comment';
//        $body->content = new \stdClass();
//        $body->content->title = 'Some title';
//        $body->content->description = 'Some description';
//        $body->content->{'submitted-url'} = 'http://www.mycompany.com/article_id/123456'; // ID of your company page in LinkedIn
//        $body->visibility = new \stdClass();
//        $body->visibility->code = 'anyone';
//        $body_json = json_encode($body);
        $info = $li->get('/people/~/shares',$body);

        return json_encode($info);

//        $linkedIn = new LinkedInService($config['linkedin']['client_id'],$config['linkedin']['client_secret'], $profile->token);
//        return $linkedIn->post(array());

    }

}