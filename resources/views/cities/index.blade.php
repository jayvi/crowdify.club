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
                    <div class="panel z1">
                        <div class="panel-heading">
                            <h4>City List</h4>
                        </div>
                        <div class="panel-body">

                            @foreach($cities as $city)
                                <div class="row">
                                    <div class="col-md-1">
                                        <a href="{{route('cities::city', array('name' => $city->name)) }}">
                                        <img src="{{ $city->city_photo ? $city->city_photo : '/assets/images/placeholder.png' }}" alt="" width="100%">
                                        </a>
                                    </div>
                                    <div class="col-md-11">
                                        <h5><a href="{{route('cities::city', array('name' => $city->name)) }}">{{$city->name}}</a></h5>
                                        @if($city->description)
                                            <p>{{substr(strip_tags($city->description),0,140).'...' }}</p>
                                        @endif
                                    </div>
                                </div>


                                <hr>
                            @endforeach
                        </div>
                    </div>
                    {!! $cities->render() !!}
                </div>
                <div class="col-md-3">
                    @include('includes.right_block')
                </div>

            </div>
        </div>
    </main>
@stop