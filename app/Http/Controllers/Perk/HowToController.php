<?php

namespace App\Http\Controllers\Perk;

use App\HowToVid;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Guard;

class HowToController extends BaseController
{
    public function __construct(Guard $auth){
        parent::__construct($auth);
        $this->middleware('auth',['except'=> ['index']]);
        $this->middleware('admin',['except'=> ['index']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $videos = HowToVid::all();
        return $this->createView('perk.howto',array(
        'videos' => $videos,
        ));
    }
    public function postEdit(Request $request){
        $newvideo = new HowToVid();
        $data = $request->all();
        $data['videoid'] = str_replace('https://www.youtube.com/watch?v=', '',$data['videoid']);
        $newvideo->videoid = $data['videoid'];
        $newvideo->save();
        return redirect()->back()->with('success', 'Successfully Updated');
    }

}