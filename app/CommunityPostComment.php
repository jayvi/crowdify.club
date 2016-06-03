<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommunityPostComment extends Model
{

    protected $table = 'community_post_comments';

    protected $guarded = ['id'];

    public function commenter()
    {
        return $this->belongsTo(User::class, 'commenter_id');
    }

    public function communityPost()
    {
        return $this->belongsTo(CommunityPost::class, 'community_post_id');
    }
}
