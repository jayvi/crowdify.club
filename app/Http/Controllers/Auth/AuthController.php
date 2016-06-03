<?php

namespace App\Http\Controllers\Auth;

use App\Bank;
use App\Events\UserRegistrationCompleted;
use App\Helpers\DataUtils;
use App\Interfaces\AuthenticateUserListener;
use App\Item;
use App\MailConfirmation;
use App\Profile;
use App\Services\Affiliate\AffiliateService;
use App\Services\AuthenticateUser;
use App\Services\EmailChecker;
use App\Services\EmailService;
use App\User;
use App\UserType;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Illuminate\Cache\RateLimiter;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller implements AuthenticateUserListener
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;


    private $mailService;




    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct( EmailService $mailService, Guard $auth)
    {
        parent::__construct($auth);
        $this->mailService = $mailService;
        $this->middleware('guest', ['except' => ['getLogout','socialConnect','socialDisconnect','socialLoginCallback',
            'confirmEmail','getPasswordSettings','postPasswordSettings','resendConfirmation','getFacebookPages','postConnectFacebookPage']]);
        $this->middleware('auth',['only'=> ['connectSocialProfile','socialConnect','socialDisconnect','confirmEmail',
            'getPasswordSettings', 'postPasswordSettings','resendConfirmation','getFacebookPages','postConnectFacebookPage']]);
        $this->middleware('email.check', ['except' => ['getLogout','socialConnect','socialDisconnect','socialLoginCallback',
            'confirmEmail','getPasswordSettings','postPasswordSettings','resendConfirmation']]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }


    /**
     * @return array|\Illuminate\View\View|mixed
     */
    public function getLogin()
    {

        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {
        $validator = Validator::make($request->all(), ['email' => 'required|email', 'password' => 'required']);
        if($validator->fails()){
            if($request->ajax()){
                return response()->json(array('message' => 'Please Enter Credentials Correctly'), 400);
            }
           return redirect()->back()->withInput()->withErrors($validator->getMessageBag());
        }
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.

        $throttles = $this->isUsingThrottlesLoginsTrait();

        if ($throttles && $this->hasTooManyLoginAttempts($request)) {
            $seconds = app(RateLimiter::class)->availableIn(
                $request->input($this->loginUsername()).$request->ip()
            );
            if($request->ajax()){
                return response()->json(array('message' => $this->getLockoutErrorMessage($seconds)), 400);
            }
            return redirect()->back()->with('loginError', $this->getLockoutErrorMessage($seconds));
        }

        $credentials = $this->getCredentials($request);

        if ($this->auth->attempt($credentials, $request->has('remember'))) {
            if ($throttles) {
                $this->clearLoginAttempts($request);
            }

            if($request->ajax()){
                return response()->json(array(), 200);
            }
            return redirect()->intended(route('perk::home'));
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        if ($throttles) {
            $this->incrementLoginAttempts($request);
        }
        if($request->ajax()){
            return response()->json(array('message' => $this->getMessage('auth.failed')), 400);
        }
        return redirect()->back()->with('loginError', $this->getMessage('auth.failed'));
//        return response()->json(array('success' => false, 'message' => $this->getMessage('auth.failed')));

//        return redirect($this->loginPath())
//            ->withInput($request->only($this->loginUsername(), 'remember'))
//            ->withErrors([
//                $this->loginUsername() => $this->getFailedLoginMessage(),
//            ]);
    }


    public function getRegister()
    {
        return redirect()->route('auth::login');
        return view('auth.register');
    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function postRegister(Request $request)
    {
        return redirect()->route('auth::login');
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->getMessageBag());
        }

        $user = $this->create($request->all());
        $this->auth->login($user);
        $request->session()->put('email',$user->email);
        $this->setSessionValue($request);
        // fire the user registration completed event
        $this->fireEvent(new UserRegistrationCompleted($user));


        return redirect(route('perk::home'));
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $usertype = UserType::where('role','=','Free')->first();
        $user =  User::create([
            'usertype_id' => $usertype->id,
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'username' => rand().'_'.time(),
            'is_email' => true
        ]);

        foreach($this->items as $key => $value){
            $item = new Item();
            $item->key = $key;
            $item->value = $value;
            $item->user_id = $user->id;
            $item->save();
        }


        $bank = new Bank();
        $bank->crowd_coins = DataUtils::INITIAL_CROWD_COINS;
        $bank->user_id = $user->id;
        $bank->save();

        return $user;
    }


    /**
     * @param AuthenticateUser $authenticateUser
     * @param Request $request
     * @param $provider
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function socialLogin(AuthenticateUser $authenticateUser, Request $request, $provider)
    {
        if($provider != 'twitter'){
            return redirect()->back();
        }
        $this->forgetSession($request);
        return $authenticateUser->execute(false,$provider ,$this, $request, $this->items, null);
    }

    public function socialConnect(AuthenticateUser $authenticateUser, Request $request, $provider){
        $profileExists = $this->auth->user()->profiles()->where('provider','=',$provider)->exists();

        if($profileExists){
            return redirect()->route('profile::edit')->with('error','Sorry, but you have already connected this profile');
        }
        $this->forgetSession($request);
        return $authenticateUser->execute(false,$provider ,$this, $request, $this->items, null);
    }

    public function socialDisconnect(Request $request, $provider)
    {
        if($provider == 'twitter'){
            return response()->json(['message' => "Sorry You can't unlink your twitter account."],400);
        }
        $profile = $this->auth->user()->profiles()->where('provider','=',$provider)->first();
        if($profile){
            $profile->delete();
        }
        return response()->json(['message' => "Successfully unlinked your ".$provider." account"], 200);
    }

    /**
     * @param AuthenticateUser $authenticateUser
     * @param Request $request
     * @param $provider
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function socialLoginCallback(AuthenticateUser $authenticateUser, AffiliateService $affiliateService ,Request $request, $provider)
    {
        return $authenticateUser->execute(true,$provider ,$this, $request, $this->items, $affiliateService);
    }

//    /**
//     * @param AuthenticateUser $authenticateUser
//     * @param Request $request
//     * @param $provider
//     * @return mixed
//     */
//    public function socialProfileConnect(AuthenticateUser $authenticateUser, Request $request, $provider)
//    {
//        return $authenticateUser->execute(false,$provider ,$this, $request);
//    }
//
//    /**
//     * @param AuthenticateUser $authenticateUser
//     * @param Request $request
//     * @param $provider
//     * @return \Symfony\Component\HttpFoundation\RedirectResponse
//     */
//    public function socialProfileConnectCallback(AuthenticateUser $authenticateUser, Request $request, $provider)
//    {
//        return $authenticateUser->execute(true,$provider ,$this, $request);
//    }



    public function confirmEmail(Request $request){
       $emailConfirmation = MailConfirmation::where('user_id','=', $this->auth->id())->first();
        if($emailConfirmation){
            if($emailConfirmation->confirmation_code == $request->get('code')){
                if(User::where('email','=',$emailConfirmation->email)->exists()){
                    MailConfirmation::where('email','=',$emailConfirmation->email)->delete();
                    return redirect(route('auth::password::settings'))->with('error','The email is already been taken. Please set another email');
                }
                $this->auth->user()->update(array('email'=> $emailConfirmation->email,'is_email' => true,'verified'=> true));
                MailConfirmation::where('user_id','=',$this->auth->id())->delete();
                return redirect(route('auth::password::settings'));
            }else{
                return redirect(route('auth::password::settings'))->with('error',"Confirmation Code doesn't match");
            }
        }else{
            return redirect(route('auth::password::settings'));
        }
        return redirect(route('perk::home'));
    }



    /**
     * @param $user
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function userHasLoggedIn(User $user, Request $request){
        $this->forgetSession($request);
        $request->session()->put('show_affiliate_popup',true);
        return redirect()->intended(route('perk::home'));
    }

    /**
     * @param Profile $profile
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @internal param User $user
     */
    public function socialProfileConnected(Profile $profile)
    {
        return redirect()->route('profile::edit')->with('success','Successfully connected');;
    }

    /**
     * @param Profile $profile
     * @return \Illuminate\Http\RedirectResponse
     */
    public function socialProfileConnectedByAnotherUser(Profile $profile)
    {
        return redirect()->route('profile::edit')->with('error','Sorry but this profile has already been connected by somebody else');
    }

    /**
     * @param Profile $profile
     * @return \Illuminate\Http\RedirectResponse
     */
    public function socialProfileAlreadyConnected(Profile $profile)
    {
        return redirect()->route('profile::edit')->with('error','This social account already connected');
    }




    /**
     * @param Request $request
     */
    private function forgetSession(Request $request)
    {

        $request->session()->forget('oauth.temp');
        $request->session()->forget('socialProfile');
        $request->session()->forget('show_affiliate_popup');
    }



    public function getPasswordSettings(){
        if($this->auth->user()->is_email){
            return redirect(route('perk::home'));
        }

        $emailConfirmation = MailConfirmation::where('user_id','=', $this->auth->id())->first();

        if($emailConfirmation){
            if(User::where('email','=',$emailConfirmation->email)->exists()){
                MailConfirmation::where('email','=', $emailConfirmation->email)->delete();
                $emailConfirmation = null;
            }
        }
        $user = $this->auth->user();

        return $this->createView('password_settings',compact('user', 'emailConfirmation'));
    }

    public function postPasswordSettings(Request $request){

        if($this->auth->user()->is_email){
            return redirect(route('perk::home'));
        }
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return redirect()->back()->withInput($request->all())->withErrors($validator->getMessageBag());
        }

        $signature_url = null;

        try {
            $base64_content = $request->input('signature');
            if (preg_match('/data:image\/(gif|jpeg|png);base64,(.*)/i', $base64_content, $matches)) {
                $imageType = $matches[1];
                $imageData = base64_decode($matches[2]);

                $base_path = public_path('uploads/signatures/');
                $file_path = $this->auth->id() . '_' . time() . '.png';
                $signature_url = 'uploads/signatures/'.$file_path;
                file_put_contents($base_path.$file_path, $imageData);
            } else {
                throw new \Exception('Invalid data URL.');
            }
        }catch (\Exception $e){
            
        }

        $this->auth->user()->update([
//            'email' => $request->get('email'),
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'password' => bcrypt($request->get('password')),
            'signature' => $signature_url,
           // 'is_email' => true
        ]);

        $mailConfirmation = new MailConfirmation();
        $mailConfirmation->user_id = $this->auth->id();
        $mailConfirmation->email = $request->get('email');
        $mailConfirmation->confirmation_code = str_random(32);
        $mailConfirmation->save();

        $user = $this->auth->user();

        $this->mailService->sendWithView('mail.account_confirmation',
            compact('user', 'mailConfirmation'),
            null,
            $request->get('email'),
            'Account Confirmation');

        return redirect()->back();

       // return redirect(route('perk::home'));

    }

    public function resendConfirmation(Request $request){
        $email = $request->get('email');
        MailConfirmation::where('user_id', '=', $this->auth->id())->delete();
        if(User::where('email','=', $email)->exists()){
            return redirect()->route('auth::password::settings')->with('error', 'The email has been already taken');
        }

        $mailConfirmation = new MailConfirmation();
        $mailConfirmation->user_id = $this->auth->id();
        $mailConfirmation->email = $request->get('email');
        $mailConfirmation->confirmation_code = str_random(32);
        $mailConfirmation->save();

        $user = $this->auth->user();

        $this->mailService->sendWithView('mail.account_confirmation',
            compact('user', 'mailConfirmation'),
            null,
            $request->get('email'),
            'Account Confirmation');

        return redirect()->back();

    }

    public function setSessionValue(Request $request)
    {
        $this->setSessionValues($request);
    }

    public function redirectGetFacebookPages()
    {
        return redirect()->route('auth::social::facebook-page-list');
    }

    public function getFacebookPages(Request $request){

        if(!$request->session()->has('socialProfile')){
            return redirect()->route('profile::edit')->with('error','Sorry, Something goes wrong while connecting. Please try again');
        }

        $socialProfile = json_decode($request->session()->get('socialProfile'));

        $config = Config::get('services');
        $fb = new Facebook([
            'app_id' => $config['facebookPage']['client_id'],
            'app_secret' => $config['facebookPage']['client_secret'],
            'default_graph_version' => 'v2.2',
        ]);

        // Sets the default fallback access token so we don't have to pass it to each request
        $fb->setDefaultAccessToken($socialProfile->token);

        try {
            $response = $fb->get('/me/accounts?fields=access_token,name,about,category,description');
            $pages = $response->getGraphEdge()->all();
          //  dd($pages);
            return $this->createView('auth.facebook_page_list', compact('pages'));

        } catch(FacebookResponseException $e) {
            // When Graph returns an error
            return 'Graph returned an error: ' . $e->getMessage();
            return redirect()->route('profile::edit')->with('error','Sorry, Something goes wrong while connecting. Please try again');
        } catch(FacebookSDKException $e) {
            // When validation fails or other local issues
            return 'Facebook SDK returned an error: ' . $e->getMessage();
            return redirect()->route('profile::edit')->with('error','Sorry, Something goes wrong while connecting. Please try again');
        }
    }

    public function postConnectFacebookPage(AuthenticateUser $authenticateUser,Request $request, $page_id){
        $profileExists = $this->auth->user()->profiles()->where('provider','=','facebookPage')->exists();
        $socialProfile = json_decode($request->session()->get('socialProfile'));
        $this->forgetSession($request);
        if($profileExists){
            return redirect()->route('profile::edit')->with('error','Sorry, but you have already a facebook page connected');
        }

        if(!$socialProfile){
            return redirect()->route('profile::edit')->with('error','Sorry, Something goes wrong while connecting. Please try again');
        }

        if(Profile::where('provider_id','=', $page_id)->where('provider','=','facebookPage')->exists()){
            return redirect()->route('profile::edit')->with('error','Sorry but this page has already been connected by somebody else');
        }

        $page = $request->all();
        $page['id'] = $page_id;
        return $authenticateUser->addFacebookFage($this, $socialProfile, $page);

    }

    public function facebookPageConnected()
    {
        return redirect()->route('profile::edit')->with('success','Facebook page successfully connected');
    }

    public function getLogout(Request $request)
    {
        $this->auth->logout();
        $request->session()->forget('show_affiliate_popup');

        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }

    public function userWasBlocked()
    {
        return redirect()->route('auth::login')->with('error','Sorry, Your account is Currently blocked');
    }
}
