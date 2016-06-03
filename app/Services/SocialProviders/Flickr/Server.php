<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 9/16/15
 * Time: 7:09 PM
 */

namespace App\Services\SocialProviders\Flickr;

use Laravel\Socialite\One\User;
use League\OAuth1\Client\Credentials\TokenCredentials;
use League\OAuth1\Client\Server\Server as BaseServer;


class Server extends BaseServer
{

    /**
     * {@inheritDoc}
     */
    public function urlTemporaryCredentials()
    {
        return 'https://www.flickr.com/services/oauth/request_token';
    }
    /**
     * {@inheritDoc}
     */
    public function urlAuthorization()
    {
        return 'https://www.flickr.com/services/oauth/authorize?perms=read';
    }
    /**
     * {@inheritDoc}
     */
    public function urlTokenCredentials()
    {
        return 'https://www.flickr.com/services/oauth/access_token';
    }
    /**
     * {@inheritDoc}
     */
    public function urlUserDetails()
    {
        return 'https://api.flickr.com/services/rest/?method=flickr.test.login&format=json&nojsoncallback=1';
    }
    /**
     * {@inheritDoc}
     */
    public function userDetails($data, TokenCredentials $tokenCredentials)
    {
        $data = $this->getProfile($data['user']['id']);
        $data = $data['person'];
        $user = new User();
        $user->id = $data['id'];
        $user->nickname = $data['username']['_content'];
        $user->name = $data['realname']['_content'];
        $user->extra = array_diff_key($data, array_flip([
            'id', 'username', 'realname',
        ]));
        return $user;
    }
    /**
     * {@inheritDoc}
     */
    public function userUid($data, TokenCredentials $tokenCredentials)
    {
        return $data['users'][0]['id'];
    }
    /**
     * {@inheritDoc}
     */
    public function userEmail($data, TokenCredentials $tokenCredentials)
    {
        return $data['users'][0]['email'];
    }
    /**
     * {@inheritDoc}
     */
    public function userScreenName($data, TokenCredentials $tokenCredentials)
    {
        return $data['users'][0]['username'];
    }
    /**
     * Get detals about the current user.
     *
     * @param string $userId
     *
     * @return array
     */
    public function getProfile($userId)
    {
        $parameters = [
            'method' => 'flickr.people.getInfo',
            'format' => 'json',
            'nojsoncallback' => 1,
            'user_id' => $userId,
            'api_key' => $this->clientCredentials->getIdentifier(),

        ];
        $url = 'https://api.flickr.com/services/rest/?'.http_build_query($parameters);
        $client = $this->createHttpClient();
        $response = $client->get($url)->send();
        return $response->json();
    }
}