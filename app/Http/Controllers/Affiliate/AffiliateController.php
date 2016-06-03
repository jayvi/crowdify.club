<?php
/**
 * Created by PhpStorm.
 * User: nathan
 * Date: 10/15/2015
 * Time: 5:27 PM
 */
namespace App\Http\Controllers\Affiliate;
use App\Affiliate;
use App\User;
use App\Afftree;
use Carbon\Carbon;
use Cookie;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AffiliateController extends Controller
{



    public function __construct(Guard $auth){
        parent::__construct($auth);
        $this->middleware('guest', ['only' => ['index']]);
        $this->middleware('auth', ['except' => ['index']]);
       // $this->middleware('user.paid', ['except' => ['index']]);
        $this->middleware('admin', ['only' => ['getAdminAffiliateDashboard','getTopAffiliates','getNewAffiliates']]);


    }

    public function index($id)
    {
        return redirect()->route('perk::home')->withCookie(cookie('affiliate', $id, 60));
    }

    public function getDashboard($username){
        $options = null;
        $userselect = User::where('is_email','=',1)->get();
        $userremove = Afftree::where('parent_id','=', $this->auth->user()->id)->get();
        foreach ($userremove as $remove){

        }
        if($this->auth->user()->isAdmin()) {
            foreach ($userselect as $usero) {
                $options = $options . '<option value="' . $usero->id . '">' . $usero->username . '</option>';
            }
        }
        else{
            
        }
        $parent = User::Where('username','=',$username)->first();
        $html = null;
        $html = '<div class="tree">
                    <ul>
                        <li>
                           
                            <a href="#">'.$username.'  <img src="'.url('assets/images/'.getimage($parent).'').'" width="20px"></a>
                           
                               
                            
                                <ul>';
        $html = $html. usertree($parent, $options,0, $this->auth->user()->id, $parent->username);
        $html = $html. ' </ul>
                        </li>
                    </ul>
                </div>';
        $affiliate = Affiliate::where('username','=',$this->auth->user()->username)
            ->with(array('user','references' => function($query){
                $query->with(array('referenceUser','earnings'));
            }))->first();

        return $this->createView('affiliate.user_dashboard',compact('affiliate','html'));
    }

    private function getSelectionCriteria(){
        return [
            route('affiliates::admin-dashboard') => 'All Affiliates',
            route('affiliates::top') => 'Top Affiliates',
            route('affiliates::new') => 'New Affiliates This Month'
        ];
    }

    public function getAdminAffiliateDashboard(Request $request){

        $selectionCriteria = $this->getSelectionCriteria();
        $affiliates = Affiliate::with(array('user','references' => function($query){
                $query->with(array('referenceUser','earnings'));
            }))->get();

        //return $affiliates;
        if($request->ajax()){
            $auth = $this->auth;
            $view = view('affiliate.includes.affiliates',compact('affiliates','auth'))->render();
            return response()->json(array('status' => 200, 'view' => $view) , 200);
        }
        return $this->createView('affiliate.admin_dashboard',compact('affiliates','selectionCriteria'));
    }
    public function postaff(Request $request){
        $check = Afftree::where('user_id', '=', $request->userid)->get();
        if(count($check) < 1) {
            $data['parent_id'] = $request->parent;
            $data['sub_parent_id'] = $this->auth->user()->id;
            $data['user_id'] = $request->userid;
            $data['side'] = $request->side;
            if (Afftree::create($data)) {
                return redirect()->route('money::tree', array('username' => $request->basepage))->with('success', 'Successfully added');
            } else {
                return redirect()->route('money::tree', array('username' => $request->basepage))->with('error', 'Error in post');
            }
        }
        else {
            return redirect()->route('money::tree', array('username' => $request->basepage))->with('error', 'Has already been added to tree');
        }
    }
    public function getTopAffiliates(){
        $affiliates = Affiliate::orderBy('total_earnings','desc')->with(array('user','references' => function($query){
            $query->with(array('referenceUser','earnings'));
        }))->get();
        
        if($affiliates){
            $affiliates = $affiliates->sort(function($affiliate_1, $affiliate_2){
                if($affiliate_1->total_earnings < $affiliate_2->total_earnings){
                    return 1;
                }
                if($affiliate_1->total_earnings == $affiliate_2->total_earnings){
                    if(count($affiliate_1->references) < count($affiliate_2->references)){
                        return 1;
                    }
                    else if(count($affiliate_1->references) == count($affiliate_2->references)){
                        return 0;
                    }else{
                        return -1;
                    }
                }else{
                    return -1;
                }


            })->slice(0, 5);
        }
        $auth = $this->auth;
        $view = view('affiliate.includes.affiliates',compact('affiliates','auth'))->render();
        return response()->json(array('status' => 200, 'view' => $view) , 200);
    }

    public function getNewAffiliates(){
        $affiliates = Affiliate::where('created_at','>=', Carbon::now()->startOfMonth())->with(array('user','references' => function($query){
            $query->with(array('referenceUser','earnings'));
        }))->get();

        $auth = $this->auth;

        $view = view('affiliate.includes.affiliates',compact('affiliates','auth'))->render();

        return response()->json(array('status' => 200, 'view' => $view) , 200);

    }
}
function usertree($parent, $options, $i, $auth, $basepage){
    $htmlL = null;
    $htmlR = null;
    if($i < 3) {
        $htmlL = treel($parent, $options, $i, $auth, $basepage);

        $htmlR = treer($parent, $options, $i, $auth, $basepage);
    }
    else {
        $html = '</li>';
        return $html;
    }
    $html = $htmlL.$htmlR;
    return $html;
}
function treel($parent, $options, $i, $auth, $basepage){
    $i = $i + 1;

    $html = null;
    $userl = Afftree::Where('parent_id', '=', $parent->id)->where('side','=','1')->first();
    if($i < 3) {
    if ($userl) {
            $userl = User::Where('id', '=', $userl->user_id)->first();
            $html = '<li>
                            <a href="'.$userl->username.'">' . $userl->username .'  <img src="'.url('assets/images/'.getimage($userl).'').'" width="20px"></a>
                          
                            
                                <ul>';
            $html2 = usertree($userl, $options, $i, $auth, $basepage);
            $html = $html . $html2 . '</ul>';

        } else {
            $html = '<li>
                            <div>
                            <strong style="color:#00aaa4">Open Position</strong>
                            <br>RIGHT
						    <br><br>
						    <form method="POST" action="postaff">
						        <input type="hidden" name="side" value="1">
                               <input type="hidden" name="parent" value="' . $parent->id . '">
                               <input type="hidden" name="basepage" value="' . $basepage . '">
                                <select name="userid" style="width:100%" class="f13">
                                    <option name="userid" value="" class="f13">Select User</option>
                                    								' . $options . '
                                </select>
							<br>
							    <input onclick="return confirm(\'Are you sure you want to place this member here?\');" class="btn btn-sm btn-success" style="margin-top:5px;" name="submit" value="Add Member" type="submit">
						    </form>
						    </div>
                        </li>';
        }
        }
    elseif($userl) {
        $userl = User::Where('id', '=', $userl->user_id)->first();
        $html = '<li>
                            <a href="'.$userl->username.'">' . $userl->username .'  <img src="'.url('assets/images/'.getimage($userl).'').'" width="20px"></a>
                                
                            
                            </li>';
    } else {
        $html = '<li>
                            <div>
                            <strong style="color:#00aaa4">Open Position</strong>
                            <br>RIGHT
						    <br><br>
						    <form method="POST" action="postaff">
						        <input type="hidden" name="side" value="1">
                               <input type="hidden" name="parent" value="' . $parent->id . '">
                               <input type="hidden" name="basepage" value="' . $basepage . '">
                                <select name="userid" style="width:100%" class="f13">
                                    <option name="userid" value="" class="f13">Select User</option>
                                    								' . $options . '
                                </select>
							<br>
							    <input onclick="return confirm(\'Are you sure you want to place this member here?\');" class="btn btn-sm btn-success" style="margin-top:5px;" name="submit" value="Add Member" type="submit">
						    </form>
						    </div>
                        </li>';
    }
    return $html;
}
function treer($parent, $options, $i, $auth, $basepage){
    $i = $i + 1;
    $html = null;
    $userr = Afftree::Where('parent_id', '=', $parent->id)->where('side', '=', '2')->first();
    if($i < 3) {
        if ($userr) {
            $userr = User::Where('id', '=', $userr->user_id)->first();
            $html = '<li>
                            
                            
                            <a href="'.$userr->username.'">' . $userr->username.'  <img src="'.url('assets/images/'.getimage($userr).'').'" width="20px"></a>
                                
                            
                                <ul>';
            $html2 = usertree($userr, $options, $i, $auth, $basepage);
            $html = $html . $html2 . '</ul>';
        } else {
            $html = ' <li>
                            <div>
                            <strong style="color:#00aaa4">Open Position</strong>
                          
						    <br><br>
						    <form method="POST" action="postaff">
						        <input type="hidden" name="parent" value="' . $parent->id . '">			     
						        <input type="hidden" name="side" value="2">
						        <input type="hidden" name="basepage" value="' . $basepage . '">
                                <select name="userid" style="width:100%" class="f13">
                                    <option value="" class="f13">Select User</option>
                                    ' . $options . '								
                                </select>
							<br>
							    <input onclick="return confirm(\'Are you sure you want to place this member here?\');" class="btn btn-sm btn-success" style="margin-top:5px;" name="submit" value="Add Member" type="submit">
						    </form>
							</div></li>';
        }
    }
    elseif ($userr) {
        $userr = User::Where('id', '=', $userr->user_id)->first();
        $html = '<li>
                           
                            <a href="'.$userr->username.'">' . $userr->username . '  <img src="'.url('assets/images/'.getimage($userr).'').'" width="20px"></a>
                               
                           
                                </li>';

    } else {
        $html = ' <li>
                            <div>
                            <strong style="color:#00aaa4">Open Position</strong>
                           
						    <br><br>
						    <form method="POST" action="postaff">
						        <input type="hidden" name="parent" value="' . $parent->id . '">			     
						        <input type="hidden" name="side" value="2">
						        <input type="hidden" name="basepage" value="' . $basepage . '">
                                <select name="userid" style="width:100%" class="f13">
                                    <option value="" class="f13">Select User</option>
                                    ' . $options . '								
                                </select>
							<br>
							    <input onclick="return confirm(\'Are you sure you want to place this member here?\');" class="btn btn-sm btn-success" style="margin-top:5px;" name="submit" value="Add Member" type="submit">
						    </form>
							</div>
							</li>';
    }
    return $html;
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



