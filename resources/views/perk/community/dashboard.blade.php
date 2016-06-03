@extends('layout.master')

@section('navbar')
    @include('perk.includes.navbar')
@endsection

@section('header')
    @include('perk.includes.header')
@endsection

@section('content')
    <main class="content">
        <div class="container-fluid">
            <div class="row" >
                <div class="col-md-2">
                    @include('perk.includes.home_nav')
                </div>
                <div class="col-md-7">
                    <div class="feed-panel z1" style="padding: 10px;">
                        @foreach($communities as $community)
                            <div class="community" style="margin-bottom: 15px; border-bottom: 1px solid #eee;">
                                <a class="community-logo pull-left" style="margin-right: 10px;">
                                    <img src="{{ url('assets/images/' . $community->community_logo) }}" alt="community-logo" style="border: 1px solid #eee; padding: 5px;">
                                </a>
                                <div class="community-content" style="overflow: hidden;">
                                    <div class="community-heading">
                                        <h4 style="margin-top: inherit;">
                                            <a href="{{ route('community::communityPage', ['communitySlug' => $community->slug]) }}">
                                                {{ $community->name }}
                                            </a>
                                            <a href="{{ route('community::communityPage', ['communitySlug' => $community->slug]) }}" class="btn btn-xs btn-info">View</a>
                                        </h4>
                                    </div>
                                    <div class="community-description">
                                        <p>{{ substr(strip_tags($community->description), 0, 170) }}...</p>
                                        @if($community->latestCommunityPost)
                                            <p><b>Recent Topic:</b></p>
                                            <div class="community-recent-topic well well-sm">
                                                <div class="well well-sm" style="margin: 3px 5px;">
                                                    <a href="{{ route('perk::public_profile', ['username' => $community->latestCommunityPost->owner->username]) }}" class="pull-left" style="margin-right: 10px;">
                                                        <img src="{{ $community->latestCommunityPost->owner->avatar }}" alt="user-avatar">
                                                    </a>
                                                    <div class="latest-community-post-content" style="overflow: hidden;">
                                                        <div class="latest-community-post-content-heading">
                                                            <h5 style="margin-top: inherit;">
                                                                <a href="{{ route('community::communityPost.each', ['communitySlug' => $community->slug, 'communityPostId' => $community->latestCommunityPost->id]) }}">
                                                                    {{ $community->latestCommunityPost->subject }}
                                                                </a>
                                                            </h5>
                                                        </div>
                                                        <div class="latest-community-post-content-description">
                                                            <p>{{ substr(strip_tags($community->latestCommunityPost->post), 0, 170) }}</p>
                                                            <small>{{ $community->latestCommunityPost->created_at->diffForHumans() }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-3">
                    @include('includes.right_block')
                </div>
            </div>
        </div>
    </main>
@endsection

@section('scripts')

@endsection
