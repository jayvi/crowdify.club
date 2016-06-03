<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{

    protected $table = 'blog_categories';


    public function blogs(){
        return $this->belongsToMany(BlogPost::class, 'blog_post_category', 'blog_category_id', 'blog_post_id');
    }

}
