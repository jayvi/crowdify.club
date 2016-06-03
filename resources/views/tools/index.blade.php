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
                <div class="col-md-2">
                    @include('perk.includes.home_nav')
                </div>

                <div class="col-md-7">
                    <br>
                    <img src="/assets/images/webtools.png">
                    <br>
                    <br>
                    <div class="panel z1">
                        @if($auth->check())
                            @if($auth->user()->isAdmin())
                                <div class="panel-heading text-right">
                                    <h4>
                                        <a class="btn btn-primary" href="{{ route('Tools::create') }}">Add new tool</a>
                                    </h4>
                                </div>
                            @endif
                        @endif
                        <div class="panel-body">
                            @foreach($posts as $post)
                                <h5><a href="{{ route('Tools::webpost', array('id' => $post->name)) }}">{{$post->title}}</a></h5>
                                <p>{{substr(strip_tags($post->description),0,140).'...' }}</p>
                                <hr>
                            @endforeach
                        </div>
                    </div>
                    {!! $posts->render() !!}
                </div>
                <div class="col-md-3">
                    @include('includes.right_block')
                </div>

            </div>
        </div>
    </main>
@stop