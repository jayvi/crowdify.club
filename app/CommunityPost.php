<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommunityPost extends Model
{

    protected $table = 'community_posts';

    protected $guarded = ['id'];

    public function community()
    {
        return $this->belongsTo(Community::class, 'community_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function communityPostComments()
    {
        return $this->hasMany(CommunityPostComment::class, 'community_post_id');
    }
}
