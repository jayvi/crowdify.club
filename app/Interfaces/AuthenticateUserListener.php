<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 8/7/15
 * Time: 6:06 PM
 */
namespace App\Interfaces;

use App\Profile;
use App\Services\AuthenticateUser;
use App\User;
use Illuminate\Http\Request;

interface AuthenticateUserListener
{
    public function userHasLoggedIn(User $user, Request $request);

    public function socialProfileConnected(Profile $profile);

    public function socialProfileConnectedByAnotherUser(Profile $profile);

    public function socialProfileAlreadyConnected(Profile $profile);

    public function setSessionValue(Request $request);

    public function redirectGetFacebookPages();

    public function facebookPageConnected();

    public function userWasBlocked();
}