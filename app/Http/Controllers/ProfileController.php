<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 8/21/15
 * Time: 4:10 PM
 */

namespace App\Http\Controllers;

use App\Achievement;
use App\Bank;
use App\Affiliate;
use App\Affrelation;
use App\BlogPost;
use App\Events\SharePurchased;
use App\Hug;
use App\StatusComments;
use App\StatusUpdate;
use App\Item;
use App\Profile;
use App\Talent;
use App\Talentreq;
use App\Notification;
use App\Perk;
use App\PerkType;
use App\Repositories\DataRepository;
use App\Services\Paypal\PaypalService;
use App\Services\TweetService;
use App\Share;
use App\User;
use Carbon\Carbon;
use Validator;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

class ProfileController extends Controller
{

    private $repository;

    public function __construct(Guard $auth, DataRepository $repository){
        parent::__construct($auth);
        $this->repository = $repository;
        $this->middleware('auth',array('except' => ['getPublicProfile']));
        $this->middleware('email.check');
    }

    public function index(){

    }

    public function getEdit(Request $request){

        if($request->has('introComplete')){
            $this->forgetIntroVideoSession($request);
        }

        $cities = $this->repository->getCities(true);
        $interests = $this->repository->getInterests(true);
        $categories = $this->repository->getCategories(true);
        $countries = $this->repository->getCountries(true);
        $affiliates = affrelation::where('affiliate', '=', $this->auth->user()->username)->get();
        return $this->createView('profile', array(
            'user'=> $this->auth->user(),
            'interests' => $interests,
            'categories' => $categories,
            'cities' => $cities,
            'countries' => $countries
        ));
    }

    public function postEdit(Request $request){
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            //'email' => 'required|email:unique:users',
//            'country' => 'required',
            'city' => 'required',
            //'gender' => 'required',
//            'birth_date' => 'required',
//            'bio' => 'required',
            'interest' => 'required',
            'category_1' => 'required',
            'category_2' => 'required'
        ];
        if($request->has('email')){
            $rules['email'] = 'required|email:unique:users';
        }
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return redirect()->back()->withInput($request->all())->withErrors($validator->getMessageBag());
        }
        if($request->has('email') && $request->get('email') ){
            $except = array('avatar');
        }else{
            $except = array('avatar','email');
        }
        $data = $request->except($except);
        $data['is_email'] = true;
        $data['youtube'] = str_replace('https://www.youtube.com/watch?v=', '',$data['youtube']);


        $signature_url = null;

        if($request->has('signature') && $request->input('signature')){
            try {
                $base64_content = $request->input('signature');
                if (preg_match('/data:image\/(gif|jpeg|png);base64,(.*)/i', $base64_content, $matches)) {
                    $imageType = $matches[1];
                    $imageData = base64_decode($matches[2]);

                    $base_path = public_path('uploads/signatures/');
                    $file_path = $this->auth->id() . '_' . time() . '.png';
                    $signature_url = 'uploads/signatures/'.$file_path;
                    file_put_contents($base_path.$file_path, $imageData);
                    $data['signature'] = $signature_url;
                } else {
                    throw new \Exception('Invalid data URL.');
                }
            }catch (\Exception $e){

            }
        }

        $this->auth->user()->update($data);


        $itemsValues = array('main'=>'Main',
            'city'=> $data['city'],
            'category_1'=> $data['category_1'], 'category_2'=> $data['category_2'], 'interest'=> $data['interest']);
        $items = $this->auth->user()->items()->where('key','!=', 'my_idea')->get();
        for($i = 0; $i < count($items); $i++){
            $items[$i]->value = $itemsValues[$items[$i]->key];
            $items[$i]->update();
        }



        if($request->hasFile('avatar')){
            $file = $request->file('avatar');
            $destinationPath =public_path()."/uploads/avatar";
            $fileName = rand(1, 100000).strtotime(date('Y-m-d H:i:s')).$request->user()->id.".".$file->getClientOriginalExtension();
            $file->move($destinationPath, $fileName);

            //$fileName = $destinationPath.$fileName;
            $fileName = "/uploads/avatar/".$fileName;
            $this->auth->user()->update(['avatar' => $fileName, 'avatar_original' => $fileName]);
        }

        $route = route('perk::home');
        if($this->getProfileEditSession($request)){
            $bank = $this->auth->user()->bank;
            if(!$bank){
                $bank = new Bank();
                $bank->user_id = $this->auth->id();
                $bank->crowd_coins = 0;
            }
            $bank->crowd_coins += 10000;
            $bank->save();

            $achievement = new Achievement();
            $achievement->name = 'Congratulations';
            $achievement->description = 'You have Earned 10000 crowdify coins.';
            $achievement->user_id = $this->auth->id();
            $achievement->save();

            $this->forgetProfileEditSession($request);
            $route.='?profileEditComplete=true';
            return redirect()->to($route);
        }
       return redirect()->back()->with('success', 'Successfully Updated');
    }

    public function postUploadAvatar(Request $request){
        if($request->hasFile('avatar')){
            $file = $request->file('avatar');
            $destinationPath =public_path()."/uploads/avatar";
            $fileName = rand(1, 100000).strtotime(date('Y-m-d H:i:s')).$request->user()->id.".".$file->getClientOriginalExtension();
            $file->move($destinationPath, $fileName);

            //$fileName = $destinationPath.$fileName;
            $fileName = "/uploads/avatar/".$fileName;
            $this->auth->user()->update(['avatar' => $fileName]);
            return redirect()->back()->with('success','Successfully Updated');
        }
    }

    public function getConfirmSettings(){

        $interests = $this->repository->getInterests(true);
        $cities = $this->repository->getCities(true);
        $categories = $this->repository->getCategories(true);


        return $this->createView('perk.confirm_setting',array(
            'user' => $this->auth->user(),
            'interests' => $interests,
            'cities' => $cities,
            'categories' => $categories
        ));
    }

    public function postConfirmSettings(Request $request){
        $rules =[
            'city' => 'required',
            'interest' => 'required',
            'category_1' => 'required',
            'category_2' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return redirect()->back()->withInput()->withErrors($validator->getMessageBag());
        }

        $data = $request->all();
        $this->auth->user()->update($data);

        $itemsValues = array('main'=>'Main',
            'city'=> $data['city'],
            'category_1'=> $data['category_1'], 'category_2'=> $data['category_2'], 'interest'=> $data['interest']);
        $items = $this->auth->user()->items()->where('key','!=','my_idea')->get();
        for($i = 0; $i < count($items); $i++){
            $items[$i]->value = $itemsValues[$items[$i]->key];
            $items[$i]->update();
        }

        return redirect(route('perk::home'));
    }

    public function getPublicProfile(Request $request, $username){
        $user = User::where('username','=', $username)->first();

        if($user){
            if($this->auth->id() != $user->id){
                $item = $user->items()->where('key','=','main')->first();
                if($item->share_price < 90) {
                    $item->share_price = (float)$item->share_price += 0.01;
                    $item->save();
                }
            }
        }

        $user = User::where('username','=', $username)->with(array('items'=>function($query){
            $query->whereIn('key',array('main','category_1','category_2'))->with(array('soldShares' => function($query){
                $query->where('investor_id','=', $this->auth->id());
            }));
        }))->first();
        $twitterProfile = false;
        $showCrowdCoinsGiftedModal = false;
        $notification = null;
        if($this->auth->check()){
            if($request->has('n')){
                $notification = Notification::with(array('sender','recipient'))->find($request->get('n'));
                if($notification && $notification->recipient_id == $this->auth->id()){
                    if(!$notification->seen){
                        if($notification->type == 'CROWD-COIN-GIFT'){
                            $showCrowdCoinsGiftedModal = true;
                        }
                        $notification->seen = true;
                        $notification->update();
                    }
                }
            }
            if(!$user){
                
                return redirect(route('perk::public_profile', array('username' => $this->auth->user()->username)));
            }
            $twitterProfile = $user->profiles()->where('provider','=','twitter')->first();
        }else{
            if(!$user){
                return redirect()->route('perk::home');
            }
        }
        
        $myIdea = $this->createOrFindMyIdeaBox($user);
        $tasks = $user->hugs()->with(array('completers'))->where('status', '=', 'Active')->where('expired_date','>=', Carbon::now())->orderBy('created_at','desc')->get();
        $blogPosts = $user->blogs()->with(['categories', 'tags'])->where('status', '=', 'Published')->orderBy('created_at', '=', 'desc')->take(3)->get();
        $status = StatusUpdate::where('user_id', '=', $user->id)->where('created_at' ,'>', Carbon::now()->subHours(24))->orderBy('created_at','desc')->take(3)->get();
        $talent = Talent::where('user_id', '=', $user->id)->orderBy('created_at', 'desc')->get();
        $talentreq = Talentreq::where('user_id', '=', $user->id)->orderBy('created_at', 'desc')->get();
//        $shares = collect([]);
//        $shares = $shares->merge($user->username);
//        $shares = $shares->merge($user->category_1);
//        $shares = $shares->merge($user->category_2);
//        $items = collect([]);
//        $items = $items->merge($user->interest);
//        $items = $items->merge($user->city);

        $items = $user->items()->whereIn('key',array('city','interest'))->get();

        $entities = collect([]);
        $entities = $entities->merge($status);
        $entities = $entities->merge($talent);
        $entities = $entities->merge($talentreq);
        $entities = $entities->merge($tasks);
        $entities = $entities->merge($blogPosts);
        $entities = $entities->sort(function($entity_1, $entity_2){
            if($entity_1->created_at < $entity_2->created_at){
                return 1;
            }else if($entity_1->created_at == $entity_2->created_at){
                return 0;
            }else{
                return -1;
            }
        })->slice(0, 30);



        $membericon = getimage($user);
        return $this->createView('perk.public_profile',
            compact('membericon','items','tasks','entities', 'user','myIdea','status', 'twitterProfile','notification','showCrowdCoinsGiftedModal','blogPosts'));
    }

    public function getPublicStatus($username, $statusId)
    {
        $status = StatusUpdate::with(['user', 'comments.commenter'])->find($statusId);

        if ($status)
        {
            return $this->createView('perk.publicStatus', compact('status'));
        }
        else
        {
            return redirect()->route('perk::home');
        }
    }

    public function postPublicStatusComment(Request $request, $username, $statusId)
    {
        $comment = $request->input('comment');

        if ( ! $comment) return redirect()->back()->with('error', 'You cannot post an empty comment!');

        $statusComment = new StatusComments();
        $statusComment->commenter_id = $this->auth->user()->id;
        $statusComment->status_id = $statusId;
        $statusComment->comment = $comment;
        $statusComment->save();

        return redirect()->back()->with('success', 'Your comment has been successfully posted.');
    }

    private function createOrFindMyIdeaBox($user){
        $myIdea = $user->items()->where('key' ,'=', 'my_idea')->first();
        if(!$myIdea){
            $myIdea = new Item();
            $myIdea->key = 'my_idea';
            $myIdea->value = 'My Big Idea';
            $myIdea->user()->associate($user);
            $myIdea->save();
        }
        return $myIdea;
    }

    public function invest(Request $request, TweetService $tweetService){

        $item = Item::with(array('user','soldShares'))->find($request->get('item_id'));
        if($item){
            if ($request->get('amount') > 250) {
                return response()->json(array('success' => false, 'message' => "You can only buy a max of 250 shares"));
            }
            if($item->key == 'city' || $item->key == 'interest'){
                return response()->json(array('success' => false, 'message' => "You can not Invest on this"));
            }
            $share = $this->auth->user()->boughtShares()->where('item_id','=',$item->id)->whereBetween('created_at', array(Carbon::now()->startOfMonth(),Carbon::now()))->first();
            if($share){
                if($share->refunded){
                    return response()->json(array('success' => false, 'message' => 'Sorry you can only buy shares in a user once every calendar month right now. We are interested in your ideas for a better system!'));
                }
                return response()->json(array('success' => false, 'message' => 'Sorry you can only buy shares in a user once every calendar month right now. We are interested in your ideas for a better system!'));
            }else{
                $bank = $this->auth->user()->bank;
                $totalPrice = $request->get('amount') * $item->share_price;
                if( (int)$bank->crowd_coins < (int)$totalPrice){
                    return response()->json(array('success' => false, 'message' => "Sorry you do not have enough Crowdify Coins"));
                }
                $share = new Share();
                $share->investor_id = $this->auth->id();
                $share->item_id = $item->id;
                $share->amount = $request->get('amount');
                $share->invested_at_price = $item->share_price; //previous price
                $share->save();

                $item->share_price = $this->calculateSharePrice($item->share_price,$share->amount);
                $item->update();

                $bank->crowd_coins -= $totalPrice;
                $bank->update();
                $twitterProfile = $this->auth->user()->profiles()->where('provider','=','twitter')->first();
                if(!$twitterProfile){
                    return response()->json(array('success' => false, 'message' => 'Your Twitter not connected. Connect Your Twitter Account'));
                }
                $tweetService->tweet($twitterProfile, $request->get('tweet'));

                $this->fireEvent(new SharePurchased($this->auth->user(), $item->user, $share->amount, $item));
                $item->soldShares = array($share);

                $view = view('perk.includes.item_box', array('auth' => $this->auth,'item' => $item,'user' => $item->user))->render();

                return response()->json(array('success' => true, 'view'=> $view, 'bank' => $bank));
            }
        }else{
            return response()->json(array('success' => false, 'message' => 'Something Wrong'));
        }

    }

    public function sell(Request $request){
        if($request->has('item_id')){
            $item = Item::with(array('user','soldShares'))->find($request->get('item_id'));
            if($item){
                $share =  $item->soldShares()->where('investor_id','=',$this->auth->id())->first();
                if($share->refunded){
                    return response()->json(array('success'=> false, 'message' => "You don't have share to sell"));
                }
                if($share){
                    $bank = $this->auth->user()->bank;
                    $bank->crowd_coins += $this->refundShare($item->share_price, $share->amount);
                    $bank->update();
                    $item->soldShares()->where('investor_id','=',$this->auth->id())->update(['refunded' => true]);
                    $item = Item::with(array('user','soldShares'))->find($item->id);
                    $view = view('perk.includes.item_box', array('auth' => $this->auth,'item' => $item, 'user' => $item->user))->render();
                    return response()->json(array('success'=> true, 'view' => $view, 'bank' =>$bank));
                }
            }
        }
        return response()->json(array('success'=> false, 'message' => 'Something is wrong. Please try again'));
    }

    public function tweet(Request $request, TweetService $tweetService){
        $twitterProfile = $this->auth->user()->profiles()->where('provider','=','twitter')->first();
        if(!$twitterProfile){
            return response()->json(array('status' => 400, 'message' => 'Your Twitter not connected. Connect Your Twitter Account'),400);
        }
        if(!$request->get('tweet')){
            return response()->json(array('status' => 400, 'message' => 'Tweet is empty'),400);
        }
        if($tweetService->tweet($twitterProfile, $request->get('tweet'))){
            return response()->json(array('status' => 200, 'message' => 'Successfully Tweeted'),200);
        }else{
            if($tweetService->getErrorMessage()){
                return response()->json(array('status' => 400, 'message' => $tweetService->getErrorMessage()),400);
            }
            return response()->json(array('status' => 400, 'message' => $tweetService->getErrorMessage()),400);
        }

        return response()->json(array('status' => 400, 'message' => 'Twitter authentication Failed. Please login with your twitter account again.'),400);

    }

    public function getSponsorPublicProfile(Request $request, $username){
        $user = User::where('username','=',$username)->first();
        if(!$user){
            return redirect()->route('sponsor::public_profile', array('username' => $this->auth->user()->username));
        }

        if($user->isFreeUser()){
            return redirect()->route('perk::public_profile', array('username' => $user->username));
        }

        $twitterProfile = $user->profiles()->where('provider','=','twitter')->first();
        $currentPerks = $user->perks()->orderBy('id','desc')->take(5)->get();
        //$currentPerks = Perk::orderBy('created_at','desc')->take(2)->get();
        $previousPerks = $user->perks()->orderBy('id','desc')->skip(5)->take(5)->get();
        //$previousPerks = Perk::orderBy('created_at','desc')->take(2)->get();

        $perkTypes= PerkType::all()->lists('type','id');

        return $this->createView('perk.sponsor.public_profile', compact('user','twitterProfile', 'currentPerks','previousPerks','perkTypes'));
    }

    public function getOptOut(){
        return $this->createView('opt_out',array());
    }

    public function postOptOut(Request $request, PaypalService $paypalService){
        $rules = [
            'opt_out_reason' => 'required'
        ];
        $message = [
            'opt_out_reason.required' => 'Please tell us why you want to leave Crowdify'
        ];
        $validator = Validator::make($request->all(), $rules, $message);

        if($validator->fails()){
            return redirect()->back()->withInput()->withErrors($validator->getMessageBag());
        }

        $paypalService->optOutAgreement($this->auth->user());

        $this->auth->user()->delete();
        return redirect()->route('auth::login')->with('success','Thanks, you have successfully opted out of Crowdify');
    }

    public function uploadItemPhoto(Request $request, $item_id){

        $item = Item::find($item_id);
        if(!$item){
            return response()->json(array('status' => 400, 'message' => 'Sorry, something goes wrong.Please tryagain'),400);
        }
        if($item->user_id != $this->auth->id()){
            return response()->json(array('status' => 400, 'message' => "You don't have the permission to do this operation"),400);
        }
        $photoUrl = null;
        if($request->hasFile('photo')){
            $photoUrl = $this->uploadPhoto($request->file('photo'),'/uploads/items/');
        }
        if($photoUrl){
            $item->photo =$photoUrl;
            $item->update();
        }
        return response()->json(array('status' => 200, 'photo' => $item->photo),200);
    }

    public function portfolio(){
        $result = $this->auth->user()->boughtShares()->with(['item' => function($q){
            $q->with(array('user'));
        }])->whereBetween('created_at', array(Carbon::now()->startOfMonth(), Carbon::now()))->whereHas('item', function($q){
            $q->where('key','=','main');
        })->get();


//        Item::where('key', '=', 'main')->where('user_id', '=', $this->auth->id());

//        return $result;

        return $this->createView('perk.portfolio', array(
            'user' => $this->auth->user(),
            'shares' => $result
        ));
    }

    public function getFollowers(){
        return $this->createView('perk.followers', array(
           // 'followers' => User::orderBy('id','desc')->take(40)->get(),
            'totalCount' => $this->auth->user()->followers()->count(),
            'followers' => $this->auth->user()->followers()->paginate(30)
           ));
    }

    public function follow(Request $request){
        $userId = $request->get('userId');
        $user = User::find($userId);
        if($user){
            if(!$this->auth->user()->isFollowing($user->id)){
                $this->auth->user()->following()->attach($user->id);
            }
            $followerCount = count($user->followers);
            return response()->json(array('status'=> 200,'user' => $user, 'followerCount' => $followerCount),200);

        }else{
            return response()->json(array('status' => 400,'message' => 'Sorry, Something went wrong when following'),400);
        }
    }

    public function unfollow(Request $request){
        $userId = $request->get('userId');
        $user = User::find($userId);
        if($user){
            if($this->auth->user()->isFollowing($user->id)){
                $this->auth->user()->following()->detach($user->id);
            }
            $followerCount = count($user->followers);
            return response()->json(array('status'=> 200, 'user' => $user,'followerCount' => $followerCount),200);

        }else{
            return response()->json(array('status' => 400,'message' => 'Sorry, Something went wrong when un-following'),400);
        }
    }

    public function block(Request $request)
    {
        $userId = $request->get('userId');
        $user = User::find($userId);
        if($user){
            $user->update(['is_blocked' => true]);
            return response()->json(array('status'=> 200, 'user' => $user,),200);

        }else{
            return response()->json(array('status' => 400,'message' => 'Sorry, Something went wrong when blocking'),400);
        }
    }

    public function unblock(Request $request)
    {
        $userId = $request->get('userId');
        $user = User::find($userId);
        if($user){
            $user->update(['is_blocked' => false]);
            return response()->json(array('status'=> 200, 'user' => $user,),200);

        }else{
            return response()->json(array('status' => 400,'message' => 'Sorry, Something went wrong when un-blocking'),400);
        }
    }
}
function getimage($user){
    if($user->usertype_id < 2){
        $image = 'walker.png';
    }
    elseif ($user->usertype_id > 1 && $user->usertype_id < 9){
        $image = 'founder.png';
    }
    elseif ($user->usertype_id = 9){
        $image = 'biker.png';
    }
    elseif ($user->usertype_id = 10){
        $image = 'driver.png';
    }
    elseif ($user->usertype_id = 11){
        $image = 'pilot.png';
    }
    elseif ($user->usertype_id = 12){
        $image = 'astronaut.png';
    }
    return $image;
}