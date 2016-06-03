<?php

namespace App\Http\Controllers\Event;

use App\Interfaces\ShareInterface;
use App\Services\ShareService;
use Illuminate\Auth\Guard;
use Illuminate\Http\Request;

use App\Http\Requests;



class SocialShareController extends BaseController implements ShareInterface
{

    public function __construct(Guard $auth){
        parent::__construct($auth);
        $this->middleware('auth');
    }

    public function share( ShareService $shareManager,Request $request,$event_id, $provider){
        return $shareManager->share($request, $event_id, $this, $provider);
    }

    public function onShareComplete($data){
        return redirect()->back()->with($data);
    }

    public function onShareFailed($data)
    {
        return redirect()->back()->with($data);
    }
}
