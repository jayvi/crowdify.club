<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 8/6/15
 * Time: 3:31 PM
 */

namespace App\Services;

use App\Interfaces\AuthenticateUserListener;
use App\MailConfirmation;
use App\Profile;
use App\Repositories\UserRepository;
use App\Services\Affiliate\AffiliateService;
use App\User;
use Facebook\Facebook;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Session\SessionInterface;
use Laravel\Socialite\Contracts\Factory as Socialite;
use Validator;


class AuthenticateUser {

    private $auth;
    private $socialite;
    private $users;
    private $mailService;

    public function __construct(UserRepository $users,Socialite $socialite, Guard $auth, EmailService $mailService){
        $this->users = $users;
        $this->socialite = $socialite;
        $this->auth = $auth;
        $this->mailService = $mailService;
    }

    public function execute($isCallback, $provider,AuthenticateUserListener $listener, Request $request, $items, AffiliateService $affiliateService = null){


        if(!$isCallback){
            return $this->getAuthorizationFirst($provider);
        }

        $socialProfile = $this->getSocialProfile($provider);
       // dd($socialProfile);



        $profile = Profile::where('provider_id', '=', $socialProfile->id)->where('provider','=',$provider)->first();

        if($provider == 'facebookPage'){
            if($this->auth->check()){
                $request->session()->put('socialProfile', json_encode($socialProfile));
                return $listener->redirectGetFacebookPages();
            }else{
                return redirect()->route('auth::login');
            }
        }

        if($this->auth->check()){

            if($profile){
                if($this->isConnectedByAnotherUser($profile, $this->auth->user())){
                    return $listener->socialProfileConnectedByAnotherUser($profile);
                }else{
                    if($provider == 'twitter' && (is_null($profile->token) || is_null($profile->token_secret) )){
                        $listener->setSessionValue($request);
                    }
                    $profile = $this->users->updateProfile($profile, $socialProfile);
                    return $listener->socialProfileAlreadyConnected($profile);
                }
            }else{
                $profile = $this->users->addProfile($this->auth->user(), $socialProfile ,$provider,$affiliateService);
                return $listener->socialProfileConnected($profile);
            }

        }else{
            if($profile){
                if($profile->is_manual && !$profile->token){
                    $listener->setSessionValue($request);
                    if($affiliateService){
                        $affiliateService->addAffiliateIfNeeded($profile);
                    }
                }
                $profile = $this->users->updateProfile($profile, $socialProfile);
                if($profile->user->is_blocked){
                    return $listener->userWasBlocked();
                }

                return $listener->userHasLoggedIn($this->setUserLoggedIn($profile->user),$request);
            }else{
                //$user = User::where('email','=', $provider == 'twitter' ? '' : $socialProfile->email)->first();
//                if($user){
//                    $request->session()->put('socialProfile', json_encode($socialProfile));
//                    $request->session()->put('provider', $provider);
//                    return $listener->showAddProfileToUser($socialProfile);
//                }else{

                    $user = $this->users->addUserAndProfile($socialProfile,$provider, $items, $affiliateService);


                    $listener->setSessionValue($request);
//                    if($provider != 'twitter'){
//                        $this->mailService->sendWithView(
//                            'mail.registration',
//                            array('firstName' => $user->first_name),
//                            $from = null,
//                            $user->email,
//                            $sub = "Welcome to Crowdify");
//                    }
                    return $listener->userHasLoggedIn($this->setUserLoggedIn($user),$request);
                //}
            }
        }
    }

    private function setUserLoggedIn(User $user){
        $this->auth->login($user);
        return $user;
    }

    private function getAuthorizationFirst($provider)
    {
        if($provider == 'facebook'){
            return $this->socialite->driver($provider)->scopes(array('email','publish_actions','user_posts'))->redirect();
        }elseif($provider == 'linkedin'){
//            ->scopes(array('r_fullprofile','r_emailaddress','w_share'))
            return $this->socialite->driver($provider)->redirect();

        }else if($provider == 'facebookPage'){
            return $this->socialite->driver($provider)->scopes(array('email','publish_actions','user_posts','manage_pages'))->redirect();
        }
        return $this->socialite->driver($provider)->redirect();
    }

    private function getSocialProfile($provider)
    {
        return $this->socialite->driver($provider)->user();
    }

    public function isConnectedByAnotherUser($profile, $authUser){
        return $profile->user_id != $authUser->id;
    }

    public function sendConfirmationEmail(Request $request, $type ,AuthenticateUserListener $listener, $emailNew = null)
    {

        if($type == 'TwitterEmail' || $type == 'LinkTwitter'){
            $mailConfirmation = new MailConfirmation();
            $mailConfirmation->email = $email =  $emailNew;
            $mailConfirmation->confirmation_code = str_random(16);
            $mailConfirmation->type = $type;
            $mailConfirmation->save();

            $this->mailService->sendWithView(
                'mail.add_profile_confirmation',
                array('confirmation_code' => $mailConfirmation->confirmation_code,'type'=>$type),
                $from = null,
                $email,
                $sub = 'Email Confirmation');

//            $this->mailer->queue('mail.add_profile_confirmation', array('confirmation_code' => $mailConfirmation->confirmation_code,'type'=>$type), function($message) use ($email){
//                $message->to($email);
//                $message->subject('Email Confirmation');
//            });

            return $listener->confirmationMailSent($request, array('status'=> 'success','message'=>'A confirmation email hasbeen sent to your email. Please check your email to confirm'));
        }

        $socialProfile = json_decode($request->session()->get('socialProfile'));

        $mailConfirmation = new MailConfirmation();
        $mailConfirmation->email = $email =  $emailNew ? $emailNew : $socialProfile->email;
        $mailConfirmation->confirmation_code = str_random(16);
        $mailConfirmation->type = $type;
        $mailConfirmation->save();

        $this->mailService->sendWithView(
            'mail.add_profile_confirmation',
            array('confirmation_code' => $mailConfirmation->confirmation_code,'type'=>$type),
            $from = null,
            $email,
            $sub = 'Email Confirmation');

//        $this->mailer->queue('mail.add_profile_confirmation', array('confirmation_code' => $mailConfirmation->confirmation_code,'type'=>$type), function($message) use ($email){
//            $message->to($email);
//            $message->subject('Email Confirmation');
//        });

        if($type == 'CreateUser'){
            $request->session()->put('newEmail', $request->get('email'));
        }


        return $listener->confirmationMailSent($request, 'A confirmation email hasbeen sent to your email address. Please check your email to confirm.');

    }

    public function addFacebookFage(AuthenticateUserListener $listener, $socialProfile, $page){
        $this->users->addFacebookPage($socialProfile,'facebookPage', $page, $this->auth->user());
        return $listener->facebookPageConnected();
    }
//
//    public function confirmEmail(Request $request, AuthenticateUserListener $listener, $type)
//    {
//        if($request->session()->has('socialProfile')){
//            $socialProfile = json_decode($request->session()->get('socialProfile'));
//            $provider = $request->session()->get('provider');
//            $confirmation = MailConfirmation::where('email','=',$socialProfile->email)->where('type','=',$type)->get()->last();
//            if($type == 'CreateUser'){
//                $email = $request->session()->get('newEmail');
//                $user = User::where('email','=',$email)->first();
//                if($user){
//                    return $listener->emailConfirmationFailed($request, 'This email is already exists');
//                }else{
//                    $profile = Profile::where('provider_id','=',$socialProfile->id)->where('provider','=',$provider)->first();
//                    if($profile){
//                        return $listener->emailConfirmationFailed($request, 'This Social account is already connected');
//                    }else{
//                        $socialProfile->email = $email;
//                        $user = $this->users->addUserAndProfile($socialProfile,$provider, $items);
//                        $this->mailService->sendWithView(
//                            'mail.registration',
//                            array('firstName' => $user->first_name),
//                            $from = null,
//                            $user->email,
//                            $sub = "Welcome to Crowdify");
//                        $this->setUserLoggedIn($user);
//                        return $listener->emailConfirmed($request, $user->email);
//                    }
//                }
//            }else{
//                if($confirmation){
//                    if($request->get('code') == $confirmation->confirmation_code){
//
//                        $user = User::where('email','=',$socialProfile->email)->first();
//                        if($user){
//                            $profile = $this->users->addProfile($user, $socialProfile ,$provider);
//                            $this->setUserLoggedIn($user);
//                            return $listener->emailConfirmed($request, $user->email);
//                        }else{
//                            return $listener->emailConfirmationFailed($request,"Account not found");
//                        }
//                    }else{
//                        return $listener->emailConfirmationFailed($request,"Confirmation Code doesn't match");
//                    }
//                }else{
//                    return $listener->emailConfirmationFailed($request, 'Email Confirmation Failed');
//                }
//            }
//        }
//        else{
//            return $listener->emailConfirmationFailed($request,'Email Confirmation Failed');
//        }
//
//    }


}