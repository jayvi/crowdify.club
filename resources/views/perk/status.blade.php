@extends('layout.master')

@section('meta')
    <meta property="og:url" content="http://crowdify.tech/status/{{$status->id}}" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{ $status->user->username }}" />
    <meta property="og:image" content="{{ $status->user->avatar }}" />
    <meta property="og:description" content="{{ $status->status }}" />
@endsection

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
                            @include('perk.includes.status', array('status' => $status))
                        </div>
                        <div class="col-md-3">
                            @include('includes.right_block')
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>