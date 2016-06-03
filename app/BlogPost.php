<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{

    protected $table = 'blog_posts';

    protected $guarded = ['id'];

    protected $dates = ['published_at'];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function categories(){
        return $this->belongsToMany(BlogCategory::class, 'blog_post_category', 'blog_post_id', 'blog_category_id');
    }

    public function tags(){
        return $this->belongsToMany(BlogTag::class, 'blog_post_tag', 'blog_post_id', 'blog_tag_id');
    }
}
