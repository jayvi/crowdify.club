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
                <div class="col-md-7" id="community-page">
                    <div class="feed-panel z1" style="padding: 10px;">
                        <div class="community" style="margin-bottom: 15px; border-bottom: 1px solid #eee;">
                            <a class="community-logo pull-left" style="margin-right: 10px;">
                                <img src="{{ url('assets/images/' . $community->community_logo) }}" alt="community-logo" style="border: 1px solid #eee; padding: 5px;">
                            </a>
                            <div class="community-content" style="overflow: hidden;">
                                <div class="community-heading">
                                    <h4 style="margin-top: inherit;">
                                        {{ $community->name }}
                                    </h4>
                                </div>
                                <div class="community-description">
                                    <p>{!! $community->description !!}</p>
                                </div>
                            </div>
                        </div>

                        <div class="community-post-section">
                            {!! Form::open(['method' => 'POST', 'url' => route('community::communityPost', ['communitySlug' => $community->slug])]) !!}
                                <!-- subject form input -->
                                <div class="form-group">
                                    {!! Form::text('subject', null, ['class' => 'form-control', 'id' => 'subject', 'placeholder' => 'Subject (Optional)']) !!}
                                </div>
                                
                                <!-- communityPost form input -->
                                <div class="form-group">
                                    {!! Form::textarea('communityPost', null, ['class' => 'form-control', 'id' => 'communityPost', 'placeholder' => 'Write something and start the discussion.', 'rows' => 2]) !!}
                                </div>

                                <div class="form-group clearfix">
                                    <button type="submit" class="btn btn-success pull-right">Post</button>
                                </div>
                            {!! Form::close() !!}
                        </div>

                        <div class="panel panel-default" style="border-radius: 0; border: 1px solid #dddddd;">
                            <div class="panel-heading">
                                <div class="text-center community-page-menu">
                                    <a href="{{ route('community::communityPage', ['communitySlug' => $community->slug]) . '?p=a' }}" class="btn btn-default btn-sm" id="a">Recently Active</a>
                                    <a href="{{ route('community::communityPage', ['communitySlug' => $community->slug]) . '?p=c' }}" class="btn btn-default btn-sm" id="c">Recently Posted</a>
                                    <a href="{{ route('community::communityPage', ['communitySlug' => $community->slug]) . '?p=m' }}" class="btn btn-default btn-sm" id="m">My Posts</a>
                                    @if($auth->user()->isAdmin())
                                        <a href="{{ route('community::communityPage', ['communitySlug' => $community->slug]) . '?p=p' }}" class="btn btn-default btn-sm" id="p">Pending Posts</a>
                                    @endif
                                </div>
                            </div>

                            @if( ! $pinnedPost->isEmpty())
                                <div class="panel-body">
                                    <div class="pinned-post-section">
                                        @foreach($pinnedPost as $item)
                                            <div class="alert alert-info" style="padding: 10px 10px 0 10px; position: relative;">
                                                <div class="content-wrp">
                                                    <div class="img-wrp">
                                                        <a href="{{ route('perk::public_profile', ['username' => $item->owner->username]) }}" style="margin-right: 10px;">
                                                            <img src="{{ $item->owner->avatar }}" alt="avatar">
                                                        </a>
                                                    </div>
                                                    <div class="description">
                                                        <h5 style="margin-top: inherit;">
                                                            <a href="{{ route('community::communityPost.each', ['communitySlug' => $community->slug, 'communityPostId' => $item->id]) }}" class="heavy">{{ $item->subject }}</a>
                                                        </h5>
                                                        <p>
                                                            <a href="{{ route('perk::public_profile', ['username' => $item->owner->username]) }}" class="heavy">{{ $item->owner->full_name }}</a> - {{ substr(strip_tags($item->post), 0, 150) }}
                                                        </p>
                                                    </div>
                                                </div>

                                                @if($auth->user()->isAdmin())
                                                    <div class="dropdown dropdown-modify" style="float: right">
                                                        <a class="dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown"
                                                           aria-haspopup="true" aria-expanded="true">
                                                            <i class="fa fa-angle-down"></i>
                                                        </a>
                                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                                            <li>
                                                                <a href="{{ route('community::communityPost.unPin', ['communitySlug' => $community->slug, 'communityPostId' => $item->id]) }}">Unpin Post</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        @foreach($community->communityPosts as $item)
                            <div class="well well-sm" style="background: #ffffff; padding: 10px 10px 0 10px; border-radius: 0; position: relative;">
                                <div class="content-wrp">
                                    <div class="img-wrp">
                                        <a href="{{ route('perk::public_profile', ['username' => $item->owner->username]) }}" style="margin-right: 10px;">
                                            <img src="{{ $item->owner->avatar }}" alt="avatar">
                                        </a>
                                    </div>
                                    <div class="description" style="min-height: 44px;">
                                        @if($item->subject)
                                            <h5>
                                                <a href="{{ route('community::communityPost.each', ['communitySlug' => $community->slug, 'communityPostId' => $item->id]) }}" class="heavy">{{ $item->subject }}</a>
                                            </h5>
                                        @endif
                                        <p>
                                            <a href="{{ route('perk::public_profile', ['username' => $item->owner->username]) }}" class="heavy">{{ $item->owner->full_name }}</a> - {{ substr(strip_tags($item->post), 0, 150) }}
                                        </p>
                                    </div>
                                </div>
                                @if($auth->user()->isAdmin() || $auth->user()->id == $item->owner->id)
                                    <div class="dropdown dropdown-modify" style="float: right; right: 15px; top: 8px; z-index: 99;">
                                        <a class="dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown"
                                           aria-haspopup="true" aria-expanded="true">
                                            <i class="fa fa-angle-down"></i>
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                            @if($item->status != 0)
                                                <li>
                                                    <a href="{{ route('community::communityPost.delete', ['communitySlug' => $community->slug, 'communityPostId' => $item->id]) }}">Delete</a>
                                                </li>
                                                @if($auth->user()->isAdmin())
                                                    <li>
                                                        <a href="{{ route('community::communityPost.pin', ['communitySlug' => $community->slug, 'communityPostId' => $item->id]) }}">Pin Post</a>
                                                    </li>
                                                @endif
                                            @else
                                                <li>
                                                    <a href="{{ route('community::communityPost.approve', ['communitySlug' => $community->slug, 'communityPostId' => $item->id]) }}">Approve</a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                @endif

                                <div style="margin-bottom: 5px;">
                                    <a href="#" data-comment-form-id="{{ '#comment-form-' . $item->id }}" class="heavy comment-btn-click" data-toggle="scroll">Comment</a> • <small>{{ $item->created_at->diffForHumans() }}</small>
                                </div>
                                <div class="comment-section clearfix" style="margin-bottom: 10px;">
                                    @foreach($item->communityPostComments as $comment)
                                        <div class="comment" style="background: #eee; border: 1px solid #ddd; border-radius: 0; padding: 10px 10px 0 10px; margin-bottom: 5px; position: relative;">
                                            <div class="content-wrp">
                                                <div class="img-wrp">
                                                    <a href="{{ route('perk::public_profile', ['username' => $comment->commenter->username]) }}" style="margin-right: 10px;">
                                                        <img src="{{ $comment->commenter->avatar }}" alt="avatar">
                                                    </a>
                                                </div>
                                                <div class="description">
                                                    <p>
                                                        <a href="{{ route('perk::public_profile', ['username' => $comment->commenter->username]) }}">{{ $comment->commenter->full_name }}</a> {!! $comment->comment !!}
                                                    </p>
                                                    <p>
                                                        <small>{{ $comment->created_at->diffForHumans() }}</small>
                                                    </p>
                                                </div>
                                            </div>
                                            @if($auth->user()->isAdmin() || $auth->user()->id == $comment->commenter->id)
                                                <div class="dropdown dropdown-modify" style="float: right">
                                                    <a class="dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown"
                                                       aria-haspopup="true" aria-expanded="true">
                                                        <i class="fa fa-angle-down"></i>
                                                    </a>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                                        <li><a href="{{ route('community::communityPostComment.delete', ['communitySlug' => $community->slug, 'commentId' => $comment->id]) }}">Delete</a></li>
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                    <div class="comment-box clearfix" id="{{ 'comment-form-' . $item->id }}" style="background: #eee; border: 1px solid #ddd; border-radius: 0; padding: 10px">
                                        <div class="row">
                                            <div class="col-md-1">
                                                <a href="{{ route('perk::public_profile', array('username' => $auth->user()->username)) }}">
                                                    <img src="{{ $auth->user()->avatar }}" class="" style="max-width: 35px;">
                                                </a>
                                            </div>
                                            <div class="col-md-11">
                                                {!! Form::open(['method' => 'POST', 'url' => route('community::communityPost.comment', ['communitySlug' => $community->slug, 'communityPostId' => $item->id]), 'class' => 'form-inline', 'style' => 'margin-left: inherit']) !!}
                                                        <!-- comment form input -->
                                                {!! Form::text('comment', null, ['class' => 'form-control', 'placeholder' => 'Write a comment...', 'style' => 'width: 85%']) !!}
                                                <button type="submit" class="btn btn-primary btn-sm">Comment</button>
                                                {!! Form::close() !!}
                                            </div>
                                        </div>
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

@section('styles')
    <style type="text/css">
        #community-page a {
            color: #4286f5;
        }
        .heavy {
            font-weight: 600;
        }
        #community-page .community-page-menu a {
            color: initial;
        }

        .community-post-section {
            background: #eee;
            border: 1px solid #dddddd;
            border-radius: 0;
            padding: 10px;
            margin-bottom: 10px;
        }

        .content-wrp {
            position:relative;
            padding-left : 60px;
        }
        .img-wrp {
            position: absolute;
            left: 0;
            right: 0;
            height: 60px;
            width: 60px;

        }
        .img-wrp img {
            max-width: 100%;
        }
    </style>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            var activeMenu = "<?php echo $activeMenu; ?>";

            $("#"+activeMenu).addClass('btn-info active');

            $(".comment-btn-click").click(function(e) {
                e.preventDefault();
                var commentFormId = $(e.target).data('comment-form-id');

                $('html, body').animate({
                    scrollTop: $(commentFormId).offset().top
                }, 1500);
            });
        })
    </script>
@endsection
