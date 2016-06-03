<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 9/16/15
 * Time: 6:20 PM
 */

namespace App\Services\SocialProviders\Foursquare;


use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\User;

class FoursquareProvider extends AbstractProvider
{

    /**
     * Get the authentication URL for the provider.
     *
     * @param  string $state
     * @return string
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase(
            'https://foursquare.com/oauth2/authenticate', $state
        );
    }

    /**
     * Get the token URL for the provider.
     *
     * @return string
     */
    protected function getTokenUrl()
    {
        return 'https://foursquare.com/oauth2/access_token';
    }

    /**
     * Get the raw user for the given access token.
     *
     * @param  string $token
     * @return array
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get(
            'https://api.foursquare.com/v2/users/self?oauth_token='.$token.'&v=20150214'
        );
        return json_decode($response->getBody()->getContents(), true)['response']['user'];
    }

    /**
     * Map the raw user array to a Socialite User instance.
     *
     * @param  array $user
     * @return \Laravel\Socialite\User
     */
    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id' => $user['id'], 'nickname' => null,
            'name' => $user['firstName'].' '.$user['lastName'],
            'email' => $user['contact']['email'],
            'avatar' => $user['photo']['prefix'].$user['photo']['suffix'],
        ]);

    }

    protected function getTokenFields($code)
    {
        return array_merge(parent::getTokenFields($code), [
            'grant_type' => 'authorization_code',
        ]);
    }
}