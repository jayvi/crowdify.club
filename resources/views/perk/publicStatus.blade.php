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
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-2">
                            @include('perk.includes.home_nav')
                        </div>
                        <div class="col-md-7">
                            {{--status section--}}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="feed-panel z1">
                                        <div class="status-section" style="padding: 10px;">
                                            <div class="row">
                                                <div class="col-md-1">
                                                    <a href="{{ route('perk::public_profile', array('username' => $status->user->username)) }}">
                                                        <img src="{{$status->user->avatar}}" class="img-circle">
                                                    </a>
                                                </div>
                                                <div class="col-md-10">
                                                    <a href="#">{{ $status->username }}</a>
                                                    <br>
                                                    <small>{{ $status->created_at->diffForHumans() }}</small>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="status" style="margin-top: 10px;">
                                                        {{ $status->status }}
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            {{--comments section--}}
                                            @foreach($status->comments as $comment)
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="comment" style="padding-bottom: 5px; padding-left: 25px;">
                                                            <div class="comment-text linkify" data-linkify style="margin-bottom: 5px;">
                                                                <a href="{{ route('perk::public_profile', array('username' => $comment->commenter->username)) }}">
                                                                    <img src="{{$comment->commenter->avatar}}" style="max-width: 20px;">
                                                                </a>
                                                                <a href="#">{{ $comment->commenter->username }}</a>
                                                                {{ $comment->comment }}
                                                            </div>
                                                            <div class="comment-time" style="border-bottom: 1px solid #eee; padding-bottom: 5px;">
                                                                <small>{{ $comment->created_at->diffForHumans() }}</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{--comment box--}}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="feed-panel z1" style="padding: 10px;">
                                        <div class="row">
                                            <div class="col-md-1">
                                                <a href="{{ route('perk::public_profile', array('username' => $auth->user()->username)) }}">
                                                    <img src="{{ $auth->user()->avatar }}" class="img-circle" style="max-width: 35px;">
                                                </a>
                                            </div>
                                            <div class="col-md-11">
                                                {!! Form::open(['method' => 'POST', 'url' => route('perk::publicStatus', ['username' => $status->username, 'statusId' => $status->id]), 'class' => 'form-inline', 'style' => 'margin-left: inherit']) !!}
                                                    <!-- comment form input -->
                                                    {!! Form::text('comment', null, ['class' => 'form-control', 'id' => 'comment', 'placeholder' => 'Write a comment...', 'style' => 'width: 85%']) !!}
                                                    <button type="submit" class="btn btn-primary btn-sm" id="commentBtn">Comment</button>
                                                {!! Form::close() !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            @include('includes.right_block')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('scripts')

@endsection
