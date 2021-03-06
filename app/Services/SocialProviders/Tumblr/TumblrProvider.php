<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 10/5/15
 * Time: 2:00 PM
 */

namespace App\Services\SocialProviders\Tumblr;


use Laravel\Socialite\One\AbstractProvider;
use Laravel\Socialite\One\User;

class TumblrProvider extends AbstractProvider
{

    /**
     * {@inheritDoc}
     */
    public function user()
    {
        if (!$this->hasNecessaryVerifier()) {
            throw new \InvalidArgumentException('Invalid request. Missing OAuth verifier.');
        }
        $user = $this->server->getUserDetails($token = $this->getToken());
        return (new User())->setRaw($user->extra)->map([
            'id' => null, 'nickname' => $user->nickname, 'name' => null,
            'email' => null, 'avatar' => null,
        ])->setToken($token->getIdentifier(), $token->getSecret());
    }
}