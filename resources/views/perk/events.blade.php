


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
                            @foreach($events as $event)
                                <div class="panel z1">
                                    <div class="panel-body">
                                        @include('event.includes.event',array('event' => $event))
                                    </div>
                                </div>
                            @endforeach
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
