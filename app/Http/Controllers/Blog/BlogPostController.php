<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 9/17/15
 * Time: 12:47 PM
 */

namespace App\Http\Controllers\Blog;

use App\BlogCategory;
use App\BlogPost;
use App\BlogTag;
use App\Category;
use App\Http\Requests\BlogRequest;
use Carbon\Carbon;
use Illuminate\Auth\Guard;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class BlogPostController extends BaseController
{

    public function __construct(Guard $auth){
        parent::__construct($auth);
        $this->middleware('auth',['except'=> ['index', 'show']]);
    }

    public function index(Request $request){
        if($request->get('category')){
            $category = BlogCategory::where('name', '=', $request->get('category'))->first();
            $blogs = $category->blogs()->where('status', '=', 'Published')->orderBy('published_at', '=', 'desc')->paginate(15);
        }
        elseif($request->get('search_text')){
            $blogs = BlogPost::with(['categories', 'tags'])
                ->where('title', 'like', '%'.$request->get('search_text').'%')
                ->orWhere('description', 'like', '%'.$request->get('search_text').'%')
                ->orderBy('published_at', '=', 'desc')
                ->paginate(15);
        }
        elseif($request->get('tag')){
            $tag = BlogTag::where('name', '=', $request->get('tag'))->first();
            $blogs = $tag->blogs()->where('status', '=', 'Published')->orderBy('published_at', '=', 'desc')->paginate(15);
        }
        else{
            $blogs = BlogPost::with(['categories', 'tags'])->where('status', '=', 'Published')->orderBy('published_at', '=', 'desc')->paginate(15);
        }
        $active_tab_home = true;
//        return $blogs;
        return $this->createView('blog.index', compact('blogs'));
    }

    public function getCreate(){
        $blog = new BlogPost();
        $action = 'Create';
        $blog_post_categories = BlogCategory::lists('name', 'id')->toArray();
        $blog_post_tags = BlogTag::lists('name', 'id')->toArray();
        $selected_categories_id = [];
        $selected_tags_id = [];
        $active_tab_create_blog = true;

        return $this->createView('blog.create', compact('blog', 'action', 'blog_post_categories', 'blog_post_tags', 'selected_categories_id', 'selected_tags_id', 'active_tab_create_blog'));
    }

    public function postCreate(BlogRequest $request){
        
        // premium member blog once a day
        if(!$this->auth->user()->isAdmin() && !$this->auth->user()->isPilot() && !$this->auth->user()->isAstronaut()){
            $lastPublishedBlog = $this->auth->user()->blogs()->orderBy('published_at','desc')->first();
            if($lastPublishedBlog){
                if(Carbon::now()->subHours(24)->lt($lastPublishedBlog->published_at)){
                    return redirect()->route('blog::home')->with('error','Sorry, you can publish blog once a day');
                }
            }
        }

        $data = $request->except(['tags','categories', 'cover_photo', 'photo_url','files']);
        $data['cover_photo'] = $this->savePhoto($request);
        $data ['user_id'] = $this->auth->id();
        $data['published_at'] = Carbon::now();

        $blog = BlogPost::create($data);

        $categoryIds = $request->get('categories');
        if($categoryIds && count($categoryIds) > 0){
            $blog->categories()->attach($categoryIds);
        }

        $tagIDs = $request->get('tags');
        if($tagIDs && count($tagIDs) > 0){
            $blog->tags()->attach($tagIDs);
        }

        return redirect()->route('blog::my-blogs')->with('success','Successfully created');
    }

    public function myBlogs(Request $request){
        if($request->get('status')){
            $myBlogs = $request->user()->blogs()->where('status', '=', $request->get('status'))->orderBy('published_at', '=', 'desc')->paginate(15);
        }else{
            $myBlogs = $request->user()->blogs()->orderBy('published_at', '=', 'desc')->paginate(15);
        }
        $active_tab_my_blog = true;
        return $this->createView('blog.myBlogs',compact('myBlogs', 'active_tab_my_blog'));
    }

    public function show($blog_id){
        $blog = BlogPost::with(['categories', 'tags'])->find($blog_id);
        if(!$blog){
            return redirect()->route('blog::home');
        }
//        return $blog;

        return $this->createView('blog.singleBlogView', compact('blog'));
    }

    public function getEdit($blog_id){
        $blog = BlogPost::find($blog_id);
        if($this->auth->user()->id != $blog->user_id)
        {
            return redirect()->route('blog::my-blogs')->with('error', "You do not have permission to edit this post!");
        }
        else {
            $action = 'Update';
            $blog_post_categories = BlogCategory::lists('name', 'id')->toArray();
            $blog_post_tags = BlogTag::lists('name', 'id')->toArray();
            $selected_categories_id = $blog->categories->lists('id')->toArray();
            $selected_tags_id = $blog->tags->lists('id')->toArray();;
            return $this->createView('blog.create', compact('blog', 'action', 'blog_post_categories', 'blog_post_tags', 'selected_categories_id', 'selected_tags_id'));
        }
    }

    public function postEdit($blog_id, BlogRequest $request){
        $blog = BlogPost::find($blog_id);

        $data = $request->except(['tags', 'categories', 'cover_photo', 'photo_url','files']);
        $data['cover_photo'] = $this->savePhoto($request);

        $categoryIds = $request->get('categories');
        if($categoryIds && count($categoryIds) > 0){
            $blog->categories()->sync($categoryIds);
        }

        $tagIDs = $request->get('tags');
        if($tagIDs && count($tagIDs) > 0){
            $blog->tags()->sync($tagIDs);
        }

        if($blog->status == 'Draft'){
            if($request->get('status') == 'Published'){
                $data['published_at'] = Carbon::now();
            }
        }

        $blog->update($data);

        return redirect()->route('blog::my-blogs')->with('success','Successfully updated');
    }

    public function delete(Request $request)
    {
        $current_user_id = $this->auth->user()->id;
        $blog_id = $request->get('blog_id');
        $blog = BlogPost::where('id', $blog_id)->where('user_id', $current_user_id)->first();

        if($blog)
        {
            $blog->delete();
            return redirect()->back()->with('success','Successfully deleted');
        }
        else
        {
            return redirect()->back()->with('error','You don\'t have permission to delete this post');
        }
    }

    private function savePhoto(Request $request){
        if($request->hasFile('cover_photo')){
            $file = $request->file('cover_photo');
            $destinationPath =public_path()."/uploads/blogs/images/original/";
            $fileName = rand(1, 100000).strtotime(date('Y-m-d H:i:s')).$request->user()->id.".".$file->getClientOriginalExtension();
            $file->move($destinationPath, $fileName);

            //$fileName = $destinationPath.$fileName;
            $savedFileName = "/uploads/blogs/images/original/".$fileName;

            return $savedFileName;
        }else if($request->has('photo_url')){
            return $request->get('photo_url');
        }
        return null;
    }

    public function publish($blog_id){
        $blog = BlogPost::findOrFail($blog_id);
        if($blog->user_id == $this->auth->user()->id)
        {
            $blog->status = 'Published';
            $blog->published_at = Carbon::now();
            $blog->update();
            return redirect()->back()->with('success', "The blog has been successfully published!");
        }
        else{
            return redirect()->back()->with('error', 'You do not have permission to publish this blog!');
        }
    }

    public function unPublish($blog_id){
        $blog = BlogPost::findOrFail($blog_id);
        if($blog->user_id == $this->auth->user()->id)
        {
            $blog->status = 'Draft';
            $blog->update();
            return redirect()->back()->with('success', "The blog has been successfully un-published!");
        }
        else{
            return redirect()->back()->with('error', 'You do not have permission to un-publish this blog!');
        }
    }
}