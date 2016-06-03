<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 8/20/15
 * Time: 4:56 PM
 */

namespace App\Http\Controllers\Perk;

use App\BlogPost;
use App\City;
use App\Event;
use App\Hug;
use App\Perk;
use App\StatusUpdate;
use App\ToolsPost;
use App\User;
use App\Talent;
use App\Talentreq;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class WelcomeController extends BaseController
{

    public function __construct(Guard $auth){
        parent::__construct($auth);
        $this->middleware('auth', ['except' => ['index']]);
        $this->middleware('email.check');
        $this->middleware('check_for_signature');
    }

    public function index(Request $request){

       // dd($request->session()->get('show_affiliate_popup',false));
        if ($this->auth->user()) {
            if (!$request->has('profileEditComplete')) {
                $achievement = $this->auth->user()->achievements()->where('seen', '=', false)->get()->first();
                if ($achievement) {
                    $achievement->seen = true;
                    $achievement->update();
                }
            }
            if (!isset($achievement)) {
                $achievement = null;
            }

            $showIntroVideo = $this->getIntroVideoSession($request);
            $showExplanationVideo = $this->getExplanationVideoSession($request);
            if ($showExplanationVideo && !$achievement && !$request->has('profileEditComplete') && !$showIntroVideo) {
                $this->forgetExplanationVideoSession($request);
            }

            $completedHugIds = $this->auth->user()->completedHugs->lists('id');
        } else {
            $showIntroVideo = null;
            $achievement = null;
            $showExplanationVideo = null;
            $completedHugIds = null;
        }
        $entities = collect([]);
        $status = StatusUpdate::with('comments')->where('created_at', '>', Carbon::now()->subHours(96))->get();
        $tasks = Hug::where('status', '=', 'Active')
            ->where('expired_date', '>=', Carbon::now())
            ->whereNotIn('id',$completedHugIds)
          //  ->orderBy('created_at','desc')
            ->get();


        $perks = Perk::with(array('user','perkType'))
            //->orderBy('created_at','desc')
            ->get();
        //$events = Event::where('status','=','Published')->orderBy('created_at','desc')->take(10)->get();
        $events = array();
        $blogPosts = BlogPost::where('status','=','Published')->orderBy('published_at','desc')->take(10)->get();
        $talent = Talent::orderBy('created_at', 'desc')->take(10)->get();
        $talentreq = Talentreq::orderBy('created_at', 'desc')->take(10)->get();
        $entities = $entities->merge($status);
        $entities = $entities->merge($talent);
        $entities = $entities->merge($talentreq);
        $entities = $entities->merge($tasks);
        $entities = $entities->merge($perks);
        $entities = $entities->merge($events);
        $entities = $entities->merge($blogPosts);
        $entities = $entities->sort(function($entity_1, $entity_2){
            $firstDate = $entity_1->published_at ? $entity_1->published_at : $entity_1->created_at;
            $secondDate = $entity_2->published_at ? $entity_2->published_at : $entity_2->created_at;
            if($firstDate < $secondDate){
                return 1;
            }else if($firstDate == $secondDate){
                return 0;
            }else{
                return -1;
            }
        })->slice(0, 30);

        return $this->createView('perk.index',array(
            'showIntroVideo' => $showIntroVideo,
            'profileEditComplete' => $request->get('profileEditComplete'),
            'achievement'=> $achievement,
            'showExplanationVideo' => $showExplanationVideo,
            'entities' => $entities,
        ));
    }

    public function getTasks(){
        if ($this->auth->user()) {
            $completedHugIds = $this->auth->user()->completedHugs->lists('id');
        } else {
            $completedHugIds = null;
        }
        $tasks = Hug::where('status', '=', 'Active')
            ->where('expired_date', '>=', Carbon::now())
            ->whereNotIn('id',$completedHugIds)
            ->orderBy('created_at','desc')
            ->get();
        return $this->createView('perk.tasks',array(

            // 'tasks' => $tasks,
            // 'perks' => $perks,
            // 'events' => $events,
            //  'blogPosts' => $blogPosts,
            'tasks' => $tasks,
        ));
    }

    public function getPerks(){
        $perks = Perk::with(array('user','perkType'))
            ->orderBy('created_at','desc')
            ->get();
        return $this->createView('perk.perks',array(

            // 'tasks' => $tasks,
            // 'perks' => $perks,
            // 'events' => $events,
            //  'blogPosts' => $blogPosts,
            'perks' => $perks,
        ));
    }

    public function getEvents(){
        $events = Event::where('status','=','Published')->orderBy('created_at','desc')->take(10)->get();
        return $this->createView('perk.events',array(
            'events' => $events,
        ));
    }

    public function search(Request $request){

//        $data = new \stdClass();
//        $data->words = array();
//        $word = new \stdClass();
//        $word->name = 'Test name';
//        $word->image = 'http://pbs.twimg.com/profile_images/558876536791523329/HkCFV9Q0_normal.jpeg';
//        array_push($data->words, $word);

//        $data->suggests = new \stdClass();
//        $data->suggests->headline_1 = array();
//
//        $result = new \stdClass();
//        $result->name = 'test name 1';
//        $result->image = 'http://pbs.twimg.com/profile_images/558876536791523329/HkCFV9Q0_normal.jpeg';
//        $result->link = 'http://pbs.twimg.com/profile_images/558876536791523329/HkCFV9Q0_normal.jpeg';
//        array_push($data->suggests->headline_1, $result);
//        $result = new \stdClass();
//        $result->name = 'test name 2';
//        $result->image = 'http://pbs.twimg.com/profile_images/558876536791523329/HkCFV9Q0_normal.jpeg';
//        $result->link = 'http://pbs.twimg.com/profile_images/558876536791523329/HkCFV9Q0_normal.jpeg';
//        array_push($data->suggests->headline_1, $result);
        //return response()->json($data);

        $search = trim($request->get('search'));
        $searchQuery = '%'.$search.'%';
        if($search && strlen($search) >= 3){
            $users = User::where('username','like',$searchQuery)->orWhere('first_name','like',$searchQuery)->orWhere('last_name','like',$searchQuery)->take(5)->get();
            $tasks = Hug::where('status','=','Active')->where('title','like',$searchQuery)->take(5)->get();
            $events = Event::where('status','=','Published')->where('title','like',$searchQuery)->take(5)->get();
            $blogs = BlogPost::where('status','=','Published')->where('title','like',$searchQuery)->take(5)->get();
            $cities = City::where('name','like',$searchQuery)->take(5)->get();
            $tools = ToolsPost::where('title','like',$searchQuery)->take(5)->get();
        }else{
            $users = array();
            $tasks = array();
        }


        $data = new \stdClass();
        $data->words = array();
        $word = new \stdClass();
        $word->name = '';
        array_push($data->words, $word);

        $data->suggests = new \stdClass();

    //  users
        $heading_1 = "People";
        $data->suggests->$heading_1 = array();
        foreach($users as $user){
            $userData = new \stdClass();
            $status = StatusUpdate::where('user_id', '=', $user->id)->orderBy('created_at', 'desc')->first();
            $userData->name = $user->username;
            $userData->description = $user->first_name.' '.$user->last_name;
            if($status){
                $userData->status = $status->status;
            }
            $userData->image = $user->avatar;
            $userData->link = route('perk::public_profile', array('username' => $user->username));
            array_push($data->suggests->$heading_1, $userData);
        }

        //  tasks
        $heading_2 = "Tasks";
        $data->suggests->$heading_2 = array();
        foreach($tasks as $task){
            $taskData = new \stdClass();
            $taskData->name = $task->title;
            $taskData->description = substr(strip_tags($task->description), 0, 50);
            $taskData->image = $task->user->avatar;
            $taskData->link = route('hugs::show', array('id'=> $task->id));
            array_push($data->suggests->$heading_2, $taskData);
        }

        //  tasks
        $heading_3 = "Events";
        $data->suggests->$heading_3 = array();
        foreach($events as $event){
            $taskData = new \stdClass();
            $taskData->name = $event->title;
            $taskData->description = substr(strip_tags($event->description), 0, 50);
            $taskData->image = $event->logo;
            $taskData->link = route('event::show', array('id'=> $event->id));
            array_push($data->suggests->$heading_3, $taskData);
        }

        $heading_4 = "Blogs";
        $data->suggests->$heading_4 = array();
        foreach($blogs as $blog){
            $taskData = new \stdClass();
            $taskData->name = $blog->title;
            $taskData->description = substr(strip_tags($blog->description), 0, 50);
            $taskData->image = $blog->cover_photo;
            $taskData->link = route('blog::show', array('id'=> $blog->id));
            array_push($data->suggests->$heading_4, $taskData);
        }

        $heading_5 = "Cities";
        $data->suggests->$heading_5 = array();
        foreach($cities as $city){
            $taskData = new \stdClass();
            $taskData->name = $city->name;
            $taskData->description = substr(strip_tags($city->description), 0, 50);
            $taskData->image = $city->city_photo;
            $taskData->link = route('cities::city', array('id'=> $city->name));
            array_push($data->suggests->$heading_5, $taskData);
        }
        $heading_6 = "Web Tools";
        $data->suggests->$heading_6 = array();
        foreach($tools as $tool){
            $taskData = new \stdClass();
            $taskData->name = $tool->title;
            $taskData->description = substr(strip_tags($tool->description), 0, 50);
            $taskData->image = $tool->cover_photo;
            $taskData->link = route('Tools::webpost', array('id'=> $tool->id));
            array_push($data->suggests->$heading_6, $taskData);
        }


        return response()->json(['results' => $data],200);

    }


}
