<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 10/13/15
 * Time: 12:29 PM
 */

namespace App\Http\Controllers\Places;


use App\Place;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Validator;

class PlacesController extends BaseController
{
    public function __construct(Guard $auth){
        parent::__construct($auth);
        $this->middleware('auth',['except'=> ['index', 'show']]);
        $this->middleware('admin',['except'=> ['index', 'show']]);
    }

    public function index(){

        if($this->auth->check()){
            if($this->auth->user()->isAdmin()){
                $places = Place::orderBy('created_at','desc')->get();
            }else{
                $places = Place::where('status','=','Published')->orderBy('created_at','desc')->get();
            }
        }
        else{
            $places = Place::where('status','=','Published')->orderBy('created_at','desc')->get();
        }
        return $this->createView('places.index',compact('places'));
    }

    public function show($id){
        $place = Place::find($id);
        if(!$place){
            return redirect()->route('places::home');
        }
        return $this->createView('places.show',compact('place'));
    }

    public function getCreate(){
        $view = view('places.includes.place_form', array('place' => new Place()))->render();
        return response()->json(array('status' => 200, 'view' => $view), 200);
    }

    public function postCreate(Request $request){
        $data = $request->all();
        $rules = [
            'cover_photo' => 'required',
            'title' => 'required',
            'description' => 'required',
            'status' => 'required'
        ];
        $validator = Validator::make($data, $rules);
        if($validator->fails()){
            return response()->json(array('status'=> 400, 'message' => 'Failed to create post, Please try again'),400);
        }
        $data['user_id'] = $this->auth->id();
        $place = Place::create($data);

        $view = view('places.includes.place',array('place' => $place, 'auth' => $this->auth))->render();
        return response()->json(array('status' => 200, 'view' => $view),200);
    }

    public function getEdit($id){
        $place = Place::find($id);
        $view = view('places.includes.place_form',array('place' => $place, 'auth' => $this->auth))->render();
        return response()->json(array('status' => 200, 'view' => $view),200);
    }

    public function postEdit(Request $request, $id){
        $data = $request->all();
        $rules = [
            'cover_photo' => 'required',
            'title' => 'required',
            'description' => 'required',
            'status' => 'required'
        ];
        $validator = Validator::make($data, $rules);
        if($validator->fails()){
            return response()->json(array('status'=> 400, 'message' => 'Failed to create post, Please try again'),400);
        }
        $place = Place::find($id);
        $place->update($data);

        $view = view('places.includes.place',array('place' => $place, 'auth' => $this->auth))->render();
        return response()->json(array('status' => 200, 'view' => $view),200);
    }

    public function delete($id){
        $place = Place::find($id);
        if(!$place){
            return response()->json(array('status' => 400, 'message' => 'The post has been already removed'), 400);
        }
        $place->delete();
        return response()->json(array('status' => 200, 'message' => 'Successfully removed'),200);
    }


}