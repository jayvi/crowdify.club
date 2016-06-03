<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    
    protected $table = 'communities';

    protected $guarded = ['id'];

    public function communityPosts()
    {
        return $this->hasMany(CommunityPost::class, 'community_id');
    }

    public function latestCommunityPost()
    {
        return $this->hasOne(CommunityPost::class, 'community_id')->latest();
    }
}
