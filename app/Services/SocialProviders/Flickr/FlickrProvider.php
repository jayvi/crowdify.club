<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 9/16/15
 * Time: 7:09 PM
 */

namespace App\Services\SocialProviders\Flickr;


use Illuminate\Http\Request;
use Laravel\Socialite\One\AbstractProvider;
use Laravel\Socialite\One\User;

class FlickrProvider extends AbstractProvider
{
    /**
     * {@inheritDoc}
     */

    public function __construct(Request $request, Server $server)
    {
        $this->server = $server;
        $this->request = $request;
    }

    public function user()
    {
        if (!$this->hasNecessaryVerifier()) {
            throw new \InvalidArgumentException('Invalid request. Missing OAuth verifier.');
        }
        $user = $this->server->getUserDetails($token = $this->getToken());
        return (new User())->setRaw($user->extra)->map([
            'id' => $user->id, 'nickname' => $user->nickname,
            'name' => $user->name, 'email' => null, 'avatar' => null,
        ])->setToken($token->getIdentifier(), $token->getSecret());
    }
}