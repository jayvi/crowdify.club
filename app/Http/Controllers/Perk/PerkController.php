<?php

namespace App\Http\Controllers\Perk;

use App\Http\Requests\Perk\PerkRequest;
use App\Perk;
use App\PerkType;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PerkController extends BaseController
{
    public function __construct(Guard $auth){
        parent::__construct($auth);
        $this->middleware('auth', ['except' => ['index']]);
        $this->middleware('email.check');
    }

    public function index(){
        $perks = Perk::with(['user', 'perkType'])
            ->orderBy('created_at','desc')
            ->get();
        return $this->createView('perk.perks_list', compact('perks'));
    }

    public function perk($id){
        $perk = Perk::where('id', '=', $id)->first();
        if(!$perk){
            if($this->auth->user()->isFreeUser()){
                return redirect()->route('perk::perks');
            }
            return redirect()->route('sponsor::public_profile',array('username' => $this->auth->user()->username));
        }
        return $this->createView('perk.perk', compact('perk'));
    }

    public function create(PerkRequest $request){
        $logo_url = null;
        if($request->hasFile('logo')){
            $logo_url = $this->uploadPhoto($request->file('logo'),'/uploads/perks/');
        }
        $data = $request->except('logo');
        $data['user_id'] = $this->auth->id();
        $data['logo_url'] =$logo_url;
        if(!isset($data['value']) || !$data['value']){
            $data['value'] = 0;
        }
        $perk = Perk::create($data);
        return redirect()->back()->with('success','Successfully Created');

    }

    public function getEdit(Request $request,$id){


        if(!$request->ajax()){
            if($this->auth->user()->isFreeUser()){
                return redirect()->route('perk::perks');
            }
            return redirect()->route('sponsor::public_profile',array('username'=> $this->auth->user()->username));
        }

        $perk = Perk::find($id);
        if(!$perk){
            return response()->json(array('status' => 400, 'message' => 'Sorry, this perk is no longer available'),400);
        }
        if($perk->user_id != $this->auth->id()){
            return response()->json(array('status' => 400, 'message' => "Sorry, You don't have the permission to edit this perk"),400);
        }

        $perkTypes= PerkType::all()->lists('type','id');

        $view = view('perk.includes.perk_form', compact('perk','perkTypes'))->render();
        return response()->json(array('status' => 200, 'view' => $view),200);
    }

    public function postEdit(Request $request, $id){
        $perk = Perk::find($id);
        if(!$perk){
            return redirect()->back()->with('error','Sorry This perk is deleted');
        }
        if($perk->user_id != $this->auth->id()){
            return redirect()->back()->with('error',"Sorry, You don't have the permission to edit this perk");
        }
        $logo_url = $perk->logo_url;
        if($request->hasFile('logo')){
            $logo_url = $this->uploadPhoto($request->file('logo'),'/uploads/perks/');
        }
        $data = $request->except('logo');
        $data['logo_url'] =$logo_url;
        $perk->update($data);
        return redirect()->back()->with('success','Successfully Updated');
    }

    public function delete($id){
        $perk = Perk::find($id);
        if($perk){
            if($perk->user_id != $this->auth->id()){
                return response()->json(array('status' => 400, 'message' => "Sorry, You don't have the permission to delete this perk"),400);
            }
            $perk->delete();
            return response()->json(array('status' => 200,'message' => 'Successfully Deleted'), 200);
        }else{
            return response()->json(array('status' => 400, 'message' => 'Sorry, this perk is already been deleted'),400);
        }
    }
}
