<?php

namespace App\Http\Controllers\Perk;

use App\Events\CommunityPostAdded;
use App\Events\CommunityPostCommentAdded;
use App\Repositories\CommunityRepository;
use App\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

class CommunityController extends BaseController {

    private $communityRepository;

    public function __construct(Guard $auth, CommunityRepository $communityRepository)
    {
        parent::__construct($auth);

        $this->communityRepository = $communityRepository;
    }

    public function communities()
    {
        $communities = $this->communityRepository->communities();

        return $this->createView('perk.community.dashboard', compact('communities'));
    }

    public function community($communitySlug)
    {
        list($community, $activeMenu) = $this->getCommunity($communitySlug);

        $pinnedPost = $community->communityPosts->where('pinned', 1);

        return $this->createView('perk.community.communityPage', compact('community', 'pinnedPost', 'activeMenu'));
    }

    public function postComment(Request $request, $communitySlug, $communityPostId)
    {
        $comment = $request->input('comment');

        if ( ! $comment) return redirect()->back()->with('error', 'You cannot post an empty comment!');

        if ($this->communityRepository->saveComment($communityPostId, $comment, $this->auth->user()->id))
        {
            $community = $this->communityRepository->communityWithSinglePost($communitySlug, $communityPostId);

            $this->fireEvent(new CommunityPostCommentAdded($this->auth->user()->id, $community->communityPosts[0]->owner->id, $community, $community->communityPosts[0]));

            return redirect()->back()->with('success', 'Your comment has been successfully posted.');
        }
        else
            return redirect()->back()->with('error', 'Sorry! Database error, please try again later.');
    }

    public function postCommunityPost(Request $request, $communitySlug)
    {
        $communityPost = $request->input('communityPost');

        if ( ! $communityPost) return redirect()->back()->with('error', 'You cannot start an empty discussion!');

        $community = $this->communityRepository->communityWithoutAdditionalData($communitySlug);

        if ($communityPost = $this->communityRepository->saveCommunityPost($request, $community->id, $this->auth->user()->id))
        {
            if ($this->auth->user()->isAdmin())
            {
                $successMessage = 'Your post has been successfully posted.';
            } else
            {
                $recipients = User::admin()->get();

                $this->fireEvent(new CommunityPostAdded($this->auth->user(), $recipients, $community, $communityPost));

                $successMessage = 'Your post has been submitted and is pending approval by an admin.';
            }

            return redirect()->back()->with('success', $successMessage);
        } else
            return redirect()->back()->with('error', 'Sorry! Database error, please try again.');
    }

    public function deleteCommunityPost($communitySlug, $communityPostId)
    {
        $communityPost = $this->communityRepository->communityPost($communityPostId);

        if ( ! $this->auth->user()->isAdmin() || $this->auth->user()->id != $communityPost->owner_id)
            return redirect()->back()->with('error', 'You don\'t have permission to delete this post!');

        if ($this->communityRepository->deleteCommunityPost($communityPostId))
            return redirect()->back()->with('success', 'The post has been successfully deleted.');
        else
            return redirect()->back()->with('error', 'Sorry! Database error, please try again later.');
    }

    public function deleteCommunityPostComment($communitySlug, $commentId)
    {
        $communityPostComment = $this->communityRepository->communityPostComment($commentId);

        if ( ! $this->auth->user()->isAdmin() || $this->auth->user()->id != $communityPostComment->commenter_id)
            return redirect()->back()->with('error', 'You don\'t have permission to delete this comment!');

        if ($this->communityRepository->deleteCommunityPostComment($commentId))
            return redirect()->back()->with('success', 'The comment has been successfully deleted.');
        else
            return redirect()->back()->with('error', 'Sorry! Database error, please try again later.');
    }

    public function pinCommunityPost($communitySlug, $communityPostId)
    {
        if ($this->communityRepository->pinCommunityPost($communityPostId))
            return redirect()->back()->with('success', 'The post has been successfully pinned.');
        else
            return redirect()->back()->with('error', 'Sorry! Database error, please try again.');
    }

    public function unPinCommunityPost($communitySlug, $communityPostId)
    {
        if ($this->communityRepository->unPinCommunityPost($communityPostId))
            return redirect()->back()->with('success', 'The post has been successfully unpinned.');
        else
            return redirect()->back()->with('error', 'Sorry! Database error, please try again.');
    }

    public function approveCommunityPost($communitySlug, $communityPostId)
    {
        if ($this->communityRepository->approveCommunityPost($communityPostId))
            return redirect()->back()->with('success', 'The post has been successfully approved.');
        else
            return redirect()->back()->with('error', 'Sorry! Database error, please try again.');
    }

    private function getCommunity($communitySlug)
    {
        if (request()->get('p') == 'c') // recently created
        {
            $community = $this->communityRepository->communityWithRecentlyPostedPosts($communitySlug);
            $activeMenu = 'c';
        } elseif (request()->get('p') == 'm') // my posts
        {
            $community = $this->communityRepository->communityWithMyPosts($communitySlug, $this->auth->user()->id);
            $activeMenu = 'm';
        } elseif (request()->get('p') == 'p') // pending posts
        {
            $community = $this->communityRepository->communityWithPendingPosts($communitySlug);
            $activeMenu = 'p';
        } else
        {
            $community = $this->communityRepository->community($communitySlug);
            $activeMenu = 'a';
        }

        return [$community, $activeMenu];
    }

    public function getEachCommunityPost($communitySlug, $communityPostId)
    {
        $community = $this->communityRepository->communityWithSinglePost($communitySlug, $communityPostId);

        $activeMenu = '';

        $pinnedPost = $community->communityPosts->where('pinned', 1);

        return $this->createView('perk.community.communityPage', compact('community', 'pinnedPost', 'activeMenu'));
    }
}
