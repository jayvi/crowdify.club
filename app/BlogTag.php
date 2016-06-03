<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlogTag extends Model
{

    protected $table ='blog_tags';

    public function blogs(){
        return $this->belongsToMany(BlogPost::class, 'blog_post_tag', 'blog_tag_id', 'blog_post_id');
    }
}
