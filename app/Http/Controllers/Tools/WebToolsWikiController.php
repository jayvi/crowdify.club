<?php

namespace App\Http\Controllers\Tools;

use App\User;
use Illuminate\Auth\Guard;
use App\Http\Requests\PostRequest;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\wp_post;
use App\ToolsPost;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class WebToolsWikiController extends Controller
{

    public function __construct(Guard $auth){
        parent::__construct($auth);
        $this->middleware('auth',['except'=> ['index', 'post']]);

    }

    public function index(){
        $posts = ToolsPost::orderBy('created_at','desc')->paginate(15);
        return $this->createView('tools.index', compact('posts'));
    }
    public function post($id){
        $posts = ToolsPost::where('name', '=', $id)->first();
        return $this->createView('tools.post', compact('posts'));
    }
    public function getEdit($id){
        $post = ToolsPost::where('name', '=', $id)->first();
        $action = 'Update';
        return $this->createView('tools.edit', compact('post', 'action'));
    }
    public function postEdit(Request $request, $id){
        $rules = [
            'title' => 'required',
            'description' => 'required|min:100',
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return redirect()->back()->withInput()->withErrors($validator->messages());
        }

        $post = ToolsPost::where('name', '=', $id)->first();
        $data = $request->only(array('title','description'));
        $post->update($data);
        return redirect()->route('Tools::edit', ['id' => $id])->with('success','Successfully updated');
    }
    public function getCreate(){
        $action = 'Create';
        return $this->createView('tools.create', compact('action'));
    }
    public function postCreate(PostRequest $request){
        if(!$request->has('name') || !$request->get('name')){
            return redirect()->back()->withInput()->with('error','Web tool name is required');
        }
        $checkname = ToolsPost::where('name', '=', $request->name)->first();
        if($checkname){
            return redirect()->back()->with('error','Web tool name in use');
        }
        $data['user_id'] = $this->auth->id();
        $data['name'] = $request->name;
        $data['title'] = $request->title;
        $data['description'] = $request->description;
        if(ToolsPost::create($data)){
            return redirect()->route('Tools::home')->with('success','Successfully added');
        }
        else {
            return redirect()->back()->with('error','Error in post');
        }

    }
    public function dbmove(){
        return redirect()->route('Tools::home');
        $posts = wp_post::all();
        foreach($posts as $post){
            $tools = New ToolsPost();
            $tools->description = $post->post_content;
            $tools->name = $post->post_name;
            $tools->title = $post->post_title;
            $tools->save();
        }
        return $this->createView('tools.db');
    }
    public function fixlistly()
    {
        $posts = ToolsPost::all();
        foreach($posts as $postu) {
            $post = $postu->description;
            $find = strpos($post, '[listly');
            if($find) {
                $findl = strpos($post, '"]');
                $findl = $findl + 2;
                $spost = substr($post, 0, $find);
                $lpost = substr($post, $findl);
                $findc = $find + 12;
                $cut = substr($post, $findc);
                $code = substr($cut, 0, 3);
                $newcode = '<div style="text-align:left" id="ly_wrap_' . $code . '"><strong id="ly_wrap_' . $code . '_t" style="display:block;margin:10px 0 4px"><a href="//list.ly/list/' . $code . '", title="" target="_blank"> </a></strong><script type="text/javascript" src="https://list.ly/plugin/show?list=' . $code . '&key=70a3d7877e8c692597f9&layout=full&show_header=true&show_author=true&show_tools=true&show_sharing=true&per_page=25"></script><div class="ly_wrap_f" style="padding:4px 0 10px"> View more <a href="http://list.ly/" target="_blank">lists</a> from <a href="", target="_blank"> </a></div></div>';
                $postfix = $spost . ' ' . $newcode . ' ' . $lpost;
                $postu->description = $postfix;
                $postu->save();
            }
        }
    }
}