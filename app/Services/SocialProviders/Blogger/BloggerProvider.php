<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 10/4/15
 * Time: 12:10 PM
 */

namespace App\Services\SocialProviders\Blogger;


use Laravel\Socialite\Two\GoogleProvider;
use Laravel\Socialite\Two\User;

class BloggerProvider extends GoogleProvider
{

    protected $scopes = ['https://www.googleapis.com/auth/blogger'];

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get(
            'https://www.googleapis.com/blogger/v3/users/self', [
            'query' => [
                'prettyPrint' => 'false',
            ],
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$token,
            ],
        ]);
        return json_decode($response->getBody(), true);
    }

    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'id' => $user['id'], 'nickname' => null, 'name' => $user['displayName'],
            'email' => null, 'avatar' => null,
        ]);
    }
}