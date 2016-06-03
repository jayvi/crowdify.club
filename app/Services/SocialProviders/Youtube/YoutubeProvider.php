<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 10/3/15
 * Time: 6:49 PM
 */

namespace App\Services\SocialProviders\Youtube;


use Laravel\Socialite\Two\GoogleProvider;
use Laravel\Socialite\Two\User;

class YoutubeProvider extends GoogleProvider
{

    protected $scopes = ['https://www.googleapis.com/auth/youtube'];

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get(
            'https://www.googleapis.com/youtube/v3/channels?part=snippet&mine=true', [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$token,
            ],
        ]);
        return json_decode($response->getBody()->getContents(), true)['items'][0];
    }

    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id' => $user['id'], 'nickname' => $user['snippet']['title'],
            'name' => null, 'email' => null,
            'avatar' => $user['snippet']['thumbnails']['high']['url'],
        ]);
    }


}