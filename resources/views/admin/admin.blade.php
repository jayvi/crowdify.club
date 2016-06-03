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
                            @include('perk.includes.home_nav')
                        </div>
                        <div class="col-md-7">
                            <div class="row">
                                <div class="col-md-12" id="entities">
                                    @foreach($users as $user)
                                        <div class="col-md-12" id="entities">
                                            <div class="feed-panel z1">
                                                <div class="home-feed">
                                                    <div class="row">
                                                        <div class="col-md-2 perk-image">
                                                            <img src="{{ $user->avatar }}" alt="" width="100%">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p>{{$user->username}}</p>
                                                            <p>{{$user->email}}</p>
                                                            <p>{{$user->first_name}} {{$user->last_name}} </p>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                {!! $users->render() !!}
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>


@stop



