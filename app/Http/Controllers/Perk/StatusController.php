<?php
/**
 * Created by PhpStorm.
 * User: Nathan Senn
 * Date: 10/24/2015
 * Time: 3:49 AM
 */
namespace App\Http\Controllers\Perk;

use App\StatusUpdate;
use App\Repositories\UserMetaRepository;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

use App\Http\Requests;
use Validator;

class StatusController extends BaseController
{
    private $metaRepositiry;
    public function __construct(Guard $auth, UserMetaRepository $metaRepository){
        parent::__construct($auth);
        $this->middleware('auth',array('except' => ['view']));
        $this->middleware('email.check');
        $this->metaRepositiry = $metaRepository;
    }

    public function postCreate(Request $request)
    {
        $hypertext = preg_replace(
            '{\b(?:http://)?(www\.)?([^\s]+)(\.com|\.org|\.net)\b}mi',
            '<a href="http://$1$2$3">$1$2$3</a>',
            $request->status
        );
        $hypertext = preg_replace(
            '!(http|ftp|scp)(s)?:\/\/[a-zA-Z0-9.?%=&_/]+!',
            "<a href=\"\\0\">\\0</a>",
            $hypertext
        );
        if ($hypertext != $request->status){
            return response()->json(array('status' => 400, 'message' => 'Please run a task to share your link!'), 400);;
        }
        $isLimitExceeded = $this->isLimitExceed($this->auth->user()->username);
        if ($isLimitExceeded) {
            if ($request->ajax()) {
                return response()->json(array('status' => 400, 'message' => 'Sorry, you can only update your status once a day'), 400);
            }
            return redirect()->back()->with('error', 'Your limit is exceeded');
        }
        $rules = [
            'status' => 'required'
        ];

        $messages = [
            'status.required' => 'Must enter your status'
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(array('status' => 400, 'message' => json_encode($validator->messages())), 400);
            }
            return redirect()->back()->withInput()->withErrors($validator->messages());
        } else {

            $data = $request->all();
            $data['user_id'] = $this->auth->user()->id;
            $data['username'] = $this->auth->user()->username;
            $entity = StatusUpdate::create($data);
            if ($request->ajax()) {
                $view = view('perk.includes.status', array('status' => $entity, 'auth' => $this->auth))->render();
                return response()->json(array('status' => 200, 'message' => 'Your status has been updated', 'view' => $view), 200);
            } else {
                return redirect()->route('hugs::home')->with('success', 'Your is updated');
            }
        }
    }
    private function isLimitExceed($user){
        $isLimitExceeded = false;
        $lastpost = StatusUpdate::where('username', '=', $user)->orderBy('created_at', 'desc')->first();
        if($lastpost) {
            if (strtotime($lastpost->created_at) < strtotime('-24 hours')) {
                return $isLimitExceeded;
            } else {
                $isLimitExceeded = true;
            }
        }
        return $isLimitExceeded;
    }

    public function view($id){
        $status = StatusUpdate::where('id','=',$id)->first();
        return $this->createView('perk.status', array(
            'status' => $status
        ));
    }
    public function delete($id){
        $status = StatusUpdate::find($id);
        if(!$status){
            return redirect()->back()->with('error','Sorry, but the task may be already deleted');
        }
        if(!$this->auth->user()->isAdmin() && ($status->user_id != $this->auth->id())){
            return redirect()->back()->with('error','You don\'t have permission to delete this Task');
        }
        $status->delete();
        return redirect()->back()->with('success','Successfully Deleted');
    }
}