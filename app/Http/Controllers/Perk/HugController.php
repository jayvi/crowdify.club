<?php

namespace App\Http\Controllers\Perk;

use App\Comment;
use App\Events\CommentAdded;
use App\Events\HugCompleted;
use App\Events\HugCompletionApproved;
use App\Helpers\DataUtils;
use App\Hug;
use App\HugType;
use App\Repositories\UserMetaRepository;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

use App\Http\Requests;
use Validator;

class HugController extends BaseController
{
    private $hugVisitReward = 10;
    private $metaRepositiry;
    public function __construct(Guard $auth, UserMetaRepository $metaRepository){
        parent::__construct($auth);
        $this->middleware('auth');
        $this->middleware('email.check');
        $this->metaRepositiry = $metaRepository;
    }

    public function index(Request $request){
        $firstTimeInTasks = $this->isFirstTimeInTasks($request);
        if($firstTimeInTasks){
            $this->forgetFirstTimeInTasks($request);
        }

        $completedHugIds = $this->auth->user()->completedHugs->lists('id');

        $hugs = Hug::where('status', '=', 'Active')
            ->where('expired_date', '>=', Carbon::now())
            ->whereNotIn('id',$completedHugIds)
            ->orderBy('created_at','desc')
            ->get();

        $activeTab = 'hugs';
        return $this->createView('perk.hugs.hugs', compact('hugs', 'activeTab','firstTimeInTasks'));
    }
    public function taskRerun($id){
        $hug = Hug::find($id);
        if(!$hug){
            return redirect()->back()->with('error','Sorry, but the task may be deleted');
        }
        $hug->expired_date = Carbon::now()->addDays($this->metaRepositiry->getTaskTimeToLive());
        $hug->update();
        return redirect()->back()->with('success','Successfully Rerun');
    }
    public function getCreate(){
        $hug = New Hug();
        $action = 'Create';
        $activeTab = 'create-hug';
        $isLimitExceeded = $this->isHugLimitExceed($this->auth->user());
        return $this->createView('perk.hugs.create', compact('hug', 'action', 'activeTab','isLimitExceeded'));
    }

    public function postCreate(Request $request){

        $isLimitExceeded = $this->isHugLimitExceed($this->auth->user());
        if($isLimitExceeded){
            if($request->ajax()){
                return response()->json(array('status' =>400, 'message' => 'Sorry, but your active task limit is exceeded'), 400);
            }
            return redirect()->back()->with('error','Your active tasks limit is exceeded');
        }

        $rules = [
            'title' => 'required',
            'link'  => 'required',
            'description' => 'required',
            'total_amount' => 'required|integer',
            'reward' => 'required|integer',
//            'status' => 'required',
//            'expired_date' => 'required'
        ];

        $messages = [
            'hugs_required.required' => 'The total tasks required field is required',
            'link.required' => 'The task link field is required'
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if($validator->fails())
        {
            if($request->ajax()){
                return response()->json(array('status' =>400, 'message' => json_encode($validator->messages())), 400);

            }
            return redirect()->back()->withInput()->withErrors($validator->messages());
        } else{

            if(!$request->ajax()){
                $data = $request->except(array('photo','photo_url'));
                $data['photo'] = $this->savePhoto($request);
            }else{
                $data = $request->all();
            }
            $bank = $this->auth->user()->bank;
            if((int)$data['total_amount'] > (int)$bank->crowd_coins){
                if($request->ajax()){
                    return response()->json(array('status' => 400, 'message' => "Sorry you do not have enough Crowdify Coins. Please complete some tasks and come back!"),400);
                }
                return redirect()->back()->withInput()->with('error',"Sorry you do not have enough Crowdify Coins. Please complete some tasks and come back!");
            }
            if((int)$data['total_amount'] < (int)$data['reward']){
                if($request->ajax()){
                    return response()->json(array('status' => 400, 'message' => "Total amount must be greater than reward"),400);
                }
                return redirect()->back()->withInput()->with('error',"Total amount must be greater than reward");
            }

            $data['approved'] = 1;
            $data['user_id'] = $this->auth->id();
            $data['hug_type_id'] = HugType::where('hug_type', '=', 'Visit Url')->first()->id;
            $data['status'] = 'Active';
            $data['expired_date'] = Carbon::now()->addDays($this->metaRepositiry->getTaskTimeToLive());
            $task = Hug::create($data);
            if($request->ajax()){
                $view = view('perk.includes.hug',array('hug' => $task, 'auth' => $this->auth))->render();
                return response()->json(array('status' =>200, 'view' => $view), 200);
            }else{
                return redirect()->route('perk::tasks')->with('success', 'Your Task Is Successfully Created');
            }
        }
    }

    public function getShow(Request $request,$hug_id){
        $this->readNotification($request);

        $hug = Hug::with(array('comments'=> function($query){
            $query->orderBy('created_at','desc');
        }))->find($hug_id);

        if($hug){
            $isExpired = $this->isHugExpired($hug);
//            if($this->isHugExpired($hug)){
//                return redirect()->route('hugs::home');
//            }
            if(!$hug->completers()->where('completer_id','=', $this->auth->id())->exists()){
                if($this->auth->id() != $hug->user_id){
                    $this->sendHugVisitCoins($this->auth->user()->bank, $this->hugVisitReward);
                }
            }
            $activeTab = 'show-hug';
            $hug_completed = $this->auth->user()->completedHugs()->where('hug_id', '=', $hug->id)->exists();
            return $this->createView('perk.hugs.show', compact('hug', 'hug_completed','activeTab','isExpired'));
        }
        return redirect()->route('perk::tasks');
    }

    private function isHugExpired($hug){
        if($hug->status != 'Active' || $hug->expired_date < Carbon::now()){
            return true;
        }
        return false;
    }

    public function getEdit($hug_id){
        $hug = Hug::findOrFail($hug_id);
        if(!$hug){
            return redirect()->route('perk::tasks');
        }
        if(!$this->auth->user()->isAdmin() && ($hug->user_id != $this->auth->id())){
            return redirect()->route('perk::tasks');
        }

        $action = 'Update';
        $activeTab = 'create';
        $isLimitExceeded = false;
        return $this->createView('perk.hugs.create', compact('hug', 'action', 'activeTab','isLimitExceeded'));
    }

    public function postEdit(Request $request, $hug_id){

        $hug = Hug::find($hug_id);
        if(!$hug){
            return redirect()->route('perk::tasks');
        }

        if(!$this->auth->user()->isAdmin() && ($hug->user_id != $this->auth->id())){
            return redirect()->route('perk::tasks');
        }

        $rules = [
            'title' => 'required',
            'link'  => 'required',
            'description' => 'required',
            'total_amount' => 'required|integer',
            'reward' => 'required|integer',
//            'status' => 'required',
//            'expired_date' => 'required'
        ];

        $messages = [
            'hugs_required.required' => 'The total tasks required field is required',
            'link.required' => 'The hug link field is required'
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if($validator->fails())
        {
            return redirect()->back()->withInput()->withErrors($validator->messages());
        } else {



            if($request->get('status') == 'Active'){
                $isLimitExceeded = $this->isHugLimitExceed($this->auth->user());
                if($isLimitExceeded && $hug->status == 'In-Active'){
                    return redirect()->back()->with('error','Your active tasks limit is exceeded');
                }
            }

            $data = $request->except(array('photo','photo_url'));
            $data['photo'] = $this->savePhoto($request);

            $bank = $this->auth->user()->bank;
            if((int)$data['total_amount'] > (int)$bank->crowd_coins){
                return redirect()->back()->withInput()->with('error',"Sorry you do not have enough Crowdify Coins. Please complete some tasks and come back!");
            }
            if((int)$data['total_amount'] < (int)$data['reward']){
                return redirect()->back()->withInput()->with('error',"Total amount must be greater than reward");
            }


            $data['approved'] = 1;
            $data['user_id'] = $this->auth->id();
            $data['hug_type_id'] = HugType::where('hug_type', '=', 'Visit Url')->first()->id;
            $hug->update($data);
//            return $hug;
            return redirect()->route('perk::tasks')->with('success', 'Your Task Is Successfully Edited');
        }
    }

    public function delete($id)
    {

        $hug = Hug::find($id);
        if(!$hug){
            return redirect()->back()->with('error','Sorry, but the task may be already deleted');
        }
        if(!$this->auth->user()->isAdmin() && ($hug->user_id != $this->auth->id())){
            return redirect()->back()->with('error','You don\'t have permission to delete this Task');
        }
        $hug->delete();
        return redirect()->back()->with('success','Successfully Deleted');

    }

    public function completion($hug_id){
        $hug = Hug::findOrFail($hug_id);
        if($hug){
            if($this->isHugExpired($hug)){
                return response()->json(['success' => false, 'message' => 'This Task is expired']);
            }

            if((int)$hug->user->bank->crowd_coins < (int)$hug->reward ){
                $this->sethugInActive($hug);
                return response()->json(['success' => false, 'message' => 'Sorry, The owner of the Task are out of crowdify coins']);
            }

            $hug->completers()->attach($this->auth->id());
            $bank = $this->auth->user()->bank;
            $this->setHugCompletionReward($bank, $hug->reward);
            $this->revokeCompletionReward($hug->user->bank, $hug->reward);
            if((int)$hug->user->bank->crowd_coins < (int)$hug->reward){
                $this->sethugInActive($hug);
            }
            $this->fireEvent(new HugCompleted($hug, $this->auth->user()));
            return response()->json(['success' => true]);
        }else{
            return response()->json(['success' => false, 'message' => 'This Task may be deleted or expired']);
        }

    }

    private function sethugInActive($hug){
        $hug->status = 'In-Active';
        $hug->update();
    }

    public function hugDashboard(){
        $hugs = $this->auth->user()->hugs()->with(array('completers'))->where('status', '=', 'Active')->where('expired_date','>=', Carbon::now())->orderBy('created_at','desc')->get();
        $pasthugs = $this->auth->user()->hugs()->with(array('completers'))->orderBy('created_at', 'desc')->get();
        $activeTab = 'dashboard';
        return $this->createView('perk.hugs.hug_dashboard', compact('hugs', 'pasthugs', 'activeTab'));
    }

    public function hugCompleters(Request $request){
        $hug_id = $request->get('hug_id');
        $hug = Hug::with(array('completers'))->find($hug_id);
        $allApproved = false;
        if(count($hug->completers()->wherePivot('approved', false)->get()) <= 0){
            $allApproved = true;
        }


        $view = view('perk.includes.hug_completer_details', compact('hug','allApproved'))->render();

        return response()->json(array('success' => true, 'view' => $view));
    }

    public function revokeHugCompletion(Request $request){
        $completer_id = $request->get('completer_id');
        $hug_id = $request->get('hug_id');
        $hug = Hug::find($hug_id);

        $completer = User::find($completer_id);

        $this->revokeCompletionReward($completer->bank, $hug->reward);
        $this->setHugCompletionReward($this->auth->user()->bank, $hug->reward);

        //$hug->completers()->where('completer_id', '=', $completer_id)->updateExistingPivot($hug_id, array('approved' => true), false);
        $hug->completers()->detach($completer_id);
        $this->fireEvent(new HugCompletionApproved($hug, array($completer)));
        return response()->json(array('success' => true));
    }

    public function approveAllHugCompletion(Request $request){
        $hug_id = $request->get('hug_id');
        $hug = Hug::find($hug_id);
        $completers = $hug->completers;

        foreach($completers as $completer){
            $this->setHugCompletionReward($completer->bank, $hug->reward);
        }

        $hug->completers()->updateExistingPivot($hug_id, array('approved' => true), false);
        $this->fireEvent(new HugCompletionApproved($hug, $completers));
        return response()->json(array('success' => true));
    }


    private function setHugCompletionReward($bank, $reward){
        $bank->crowd_coins += $reward;
        $bank->update();
    }

    private function revokeCompletionReward($bank, $reward){
        $bank->crowd_coins -= $reward;
        $bank->update();
    }

    private function isHugLimitExceed($user){
        $activeHugsCount = $user->hugs()->where('status','=', 'Active')->where('expired_date', '>=', Carbon::now())->count();
        $isLimitExceeded = false;
        if($activeHugsCount >= $this->metaRepositiry->getActiveHugsLimit($user->usertype->role)){
            $isLimitExceeded = true;
        }

        return $isLimitExceeded;
    }

    public function getCompletionHistory(){

        //completion history not needed. redirect to home page
        return redirect()->route('perk::tasks');


        $hugs = $this->auth->user()->completedHugs()->get();
        $activeTab = 'completion-history';
        return $this->createView('perk.hugs.completion_history', compact('hugs','activeTab'));
    }

    public function postComment(Request $request, $hug_id){

        if(!DataUtils::COMMENT_ENABLE){
            return response()->json(array('status'=> 400,'message'=> 'This feature is not enabled'), 400);
        }
        $validator = Validator::make($request->all(), ['comment' => 'required']);
        if($validator->fails()){
            return response()->json(array('status' => 400,'message' => 'Please Enter a comment'), 400);
        }
        $hug = Hug::find($hug_id);
        if($hug && $hug->status == 'Active'){
            $comment = new Comment();
            $comment->user()->associate($this->auth->user());
            $comment->hug()->associate($hug);
            $comment->comment = $request->input('comment');
            $comment->save();


            $this->fireEvent(new CommentAdded($this->auth->user(), $hug, $comment));

            $view = view('perk.comment', compact('comment'))->render();
            return response()->json(array('status'=> 200,'view'=> $view), 200);
        }else{
            return response()->json(array('status' => 400,'message' => 'This Task is no longer active'), 400);
        }
    }

    private function sendHugVisitCoins($bank, $amount){
        $bank->crowd_coins += $amount;
        $bank->update();
    }

    private function savePhoto(Request $request){
        if($request->hasFile('photo')){
            $file = $request->file('photo');
            $destinationPath =public_path()."/uploads/tasks";
            $fileName = rand(1, 100000).strtotime(date('Y-m-d H:i:s')).$request->user()->id.".".$file->getClientOriginalExtension();
            $file->move($destinationPath, $fileName);

            //$fileName = $destinationPath.$fileName;
            $fileName = "/uploads/tasks/".$fileName;
            return $fileName;
        }else if($request->has('photo_url')){
            return $request->get('photo_url');
        }

        return null;
    }
}
