<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 5/23/16
 * Time: 1:11 PM
 */

namespace App\Services\SocialProviders\Paypal;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\User;


class PaypalProvider extends AbstractProvider
{
    /**
     * Unique Provider Identifier.
     */
    const IDENTIFIER = 'PAYPAL';
    /**
     * {@inheritdoc}
     */
    protected $scopes = ['openid', 'profile', 'email'];
    /**
     * {@inheritdoc}
     */
    protected $scopeSeparator = ' ';
    /**
     * {@inheritdoc}
     */
    private function getModeUrl(){
        return env('PAYPAL_MODE','live') == 'sandbox' ? ".sandbox" : '';
    }

    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase(
            'https://www'.$this->getModeUrl().'.paypal.com/webapps/auth/protocol/openidconnect/v1/authorize', $state
        );
    }
    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return 'https://api'.$this->getModeUrl().'.paypal.com/v1/identity/openidconnect/tokenservice';
    }
    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get(
            'https://api'.$this->getModeUrl().'.paypal.com/v1/identity/openidconnect/userinfo/?schema=openid', [
            'headers' => [
                'Authorization' => 'Bearer '.$token,
            ],
        ]);
        return json_decode($response->getBody()->getContents(), true);
    }
    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id' => str_replace('https://www.paypal.com/webapps/auth/identity/user/', '', $user['user_id']),
           // 'id' => $user['user_id'],
            'nickname' => null, 'name' => $user['name'],
            'email' => $user['email'], 'avatar' => null,
        ]);
    }
    /**
     * {@inheritdoc}
     */
    public function getAccessToken($code)
    {
        $response = $this->getHttpClient()->post($this->getTokenUrl(), [
            'headers' => ['Accept' => 'application/json'],
            'auth' => [$this->clientId, $this->clientSecret],
            'form_params' => $this->getTokenFields($code),
        ]);
        $this->credentialsResponseBody = json_decode($response->getBody(), true);
        return $this->parseAccessToken($response->getBody());
    }
    /**
     * {@inheritdoc}
     */
    protected function getTokenFields($code)
    {
        return array_merge(parent::getTokenFields($code), [
            'grant_type' => 'authorization_code',
        ]);
    }
}