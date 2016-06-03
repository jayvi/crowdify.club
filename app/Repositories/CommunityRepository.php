<?php


namespace App\Repositories;


use App\Community;
use App\CommunityPost;
use App\CommunityPostComment;
use Illuminate\Auth\Guard;
use Illuminate\Http\Request;

class CommunityRepository {

    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function communities()
    {
        return Community::with(['latestCommunityPost.owner'])->get();
    }

    public function community($communitySlug)
    {
        return Community::with(
            [
                'communityPosts' => function ($q)
                {
                    $q->where('status', 1)->orderBy('updated_at', 'desc');
                },
                'communityPosts.CommunityPostComments.commenter',
                'communityPosts.owner',
            ]
        )->where('slug', '=', $communitySlug)->first();
    }

    public function communityWithoutAdditionalData($communitySlug)
    {
        return Community::where('slug', '=', $communitySlug)->first();
    }

    public function communityWithSinglePost($communitySlug, $communityPostId)
    {
        return Community::with(
            [
                'communityPosts' => function ($q) use ($communityPostId)
                {
                    $q->where('id', $communityPostId);
                },
                'communityPosts.CommunityPostComments.commenter',
                'communityPosts.owner',
            ]
        )->where('slug', '=', $communitySlug)->first();
    }

    public function saveComment($communityPostId, $comment, $commenterId)
    {
        $communityPostComment = new CommunityPostComment();
        $communityPostComment->community_post_id = $communityPostId;
        $communityPostComment->commenter_id = $commenterId;
        $communityPostComment->comment = $comment;

        return $communityPostComment->save();
    }

    public function saveCommunityPost(Request $request, $communityId, $ownerId)
    {
        $communityPost = new CommunityPost();
        $communityPost->community_id = $communityId;
        $communityPost->owner_id = $ownerId;
        $communityPost->status = 0;

        if ($this->auth->user()->isAdmin())
            $communityPost->status = 1;

        $communityPost->pinned = 0;
        $communityPost->subject = $request->input('subject');
        $communityPost->post = $request->input('communityPost');

        if ($communityPost->save()) return $communityPost;
        else return null;
    }

    public function communityWithRecentlyPostedPosts($communitySlug)
    {
        return Community::with(
            [
                'communityPosts' => function ($q)
                {
                    $q->where('status', 1)->orderBy('created_at', 'desc');
                },
                'communityPosts.CommunityPostComments.commenter',
                'communityPosts.owner',
            ]
        )->where('slug', '=', $communitySlug)->first();
    }

    public function communityWithMyPosts($communitySlug, $ownerId)
    {
        return Community::with(
            [
                'communityPosts' => function ($q) use ($ownerId)
                {
                    $q->where('status', 1)->where('owner_id', $ownerId)->orderBy('created_at', 'desc');
                },
                'communityPosts.CommunityPostComments.commenter',
                'communityPosts.owner',
            ]
        )->where('slug', '=', $communitySlug)->first();
    }

    public function communityWithPendingPosts($communitySlug)
    {
        return Community::with(
            [
                'communityPosts' => function ($q)
                {
                    $q->where('status', 0)->orderBy('created_at', 'desc');
                },
                'communityPosts.CommunityPostComments.commenter',
                'communityPosts.owner',
            ]
        )->where('slug', '=', $communitySlug)->first();
    }

    public function communityPost($communityPostId)
    {
        return CommunityPost::find($communityPostId);
    }

    public function deleteCommunityPost($communityPostId)
    {
        return CommunityPost::destroy($communityPostId);
    }

    public function communityPostComment($commentId)
    {
        return CommunityPostComment::find($commentId);
    }

    public function deleteCommunityPostComment($commentId)
    {
        return CommunityPostComment::destroy($commentId);
    }

    public function pinCommunityPost($communityPostId)
    {
        return CommunityPost::where('id', $communityPostId)->update(['pinned' => 1]);
    }

    public function unPinCommunityPost($communityPostId)
    {
        return CommunityPost::where('id', $communityPostId)->update(['pinned' => 0]);
    }

    public function approveCommunityPost($communityPostId)
    {
        return CommunityPost::where('id', $communityPostId)->update(['status' => 1]);
    }
}
