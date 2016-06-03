@extends('layout.master')

@section('navbar')
    @include('perk.includes.navbar')
@stop

@section('header')
    @include('perk.includes.header')
@stop

@section('content')
    <main class="content">
        <div class="container-fluid">
            <div class="row" >
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-2">
                            <?php
                            use Jenssegers\Agent\Agent as Agent;
                            $Agent = new Agent();
                            ?>
                            <?php
                            if ($Agent->isMobile()) {
                            ?>
                            <ul class="nav nav-tabs">
                                <li role="presentation" class="active"><a href="{{ route('blog::home') }}">Blogs</a></li>
                                <li role="presentation"><a href="{{ route('blog::create') }}">Create</a></li>
                                <li role="presentation"><a href="{{ route('blog::my-blogs') }}">My Blogs</a></li>
                            </ul>
                            <?php
                            }
                                ?>
                            @include('perk.includes.blog_nav')
                        </div>
                        <div class="col-md-7">
                            @if($blogs && count($blogs) > 0)
                                @foreach($blogs as $blog)
                                    @include('blog.includes.blog_post')
                                @endforeach
                                {!! $blogs->render() !!}
                            @else
                                <div class="panel z1">
                                    <div class="panel-body">
                                        <p>There is nothing to show right now!</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-3">
                            @include('includes.right_block')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@stop
