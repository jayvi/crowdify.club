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
                            @foreach($videos as $video)
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel z1 text-center">

                                            <iframe width="100%" height="" src="https://www.youtube.com/embed/{{$video->videoid}}" frameborder="0" allowfullscreen></iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @if($auth->check() && $auth->user()->isAdmin())
                            {!! Form::open(array(
                                'url' => route('howto::edit'),
                                'files' => true
                             )) !!}
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel z1 text-center">
                                            <h3>Add Video</h3>
                                            <label for="youtube">YouTube Video URL</label>
                                            <input type="text" name="videoid" class="form-control" id="videoid" value=""><br>
                                            <input type="submit" class="btn btn-default-outline" value="Update">
                                        </div>
                                    </div>
                                </div>
                            </div>
                                {!! Form::close() !!}
                                @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@stop

