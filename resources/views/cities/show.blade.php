@extends('layout.master')

@section('navbar')
    @include('perk.includes.navbar')
@stop

@section('header')
    @include('perk.includes.header')
@stop

@section('content')
    <main class="content city-single">
        <div class="container-fluid">
            <div class="row" >
                <div class="col-md-2">
                    @include('perk.includes.home_nav')
                </div>
                <div class="col-md-10">
                    <div class="col-md-8">
                        @if (!$city->user_id)
                            <div class="row z1 notigications">
                                <a href="#" id="notifications-cross-button"><i class="fa fa-times" aria-hidden="true"></i></a>
                                <h4>Would you like to manage this city? Please find out what that means and contact us at crowdify.club/ city managers</h4>
                            </div>
                        @endif
                        <div class="row city-info z1">
                            <div class="col-md-4">
                                <img src="{{$city->city_photo ? $city->city_photo : url('assets/images/placeholder.png')}}" style="width: 100%;">
                            </div>
                            <div class="col-md-8">
                                <div class="panel">
                                    <div class="panel-heading">
                                        <h2>
                                            {{ $city->name }}
                                            @if($auth->check() && $auth->user()->isAdmin())
                                                <a class="pull-right" href="{{ route('cities::edit', ['id' => $city->name]) }}"><span class="ion-edit"></span> Edit</a>
                                            @endif
                                        </h2>
                                    </div>
                                    <div class="panel-body">
                                        <p>{!! $city->description !!} </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row social-share-box z1">
                            <div class="addthis_native_toolbox"></div>
                        </div>
                        @if (count($stocks))
                            <div class="row stocks-box z1">
                                <table class="table">
                                    <?php $rank = 1; ?>
                                    @foreach($stocks as $stock)
                                        <tr>
                                            <td class="stock-avatar"><img src="{{$stock->user_avatar ? $stock->user_avatar : url('assets/images/placeholder.png')}}" style="width: 100%;"></td>

                                            <td>
                                                <a href="{{route('perk::public_profile', array('username'=> $stock->user->username))}}">
                                                <span style="word-wrap: break-word;">
                                                    <span style="color: #000">{{$rank.'.'}}</span> {{$stock->user->first_name.' '.$stock->user->last_name}}
                                                    {{--<strong>({{$stock->user->username}})</strong>--}}
                                                </span>
                                                </a>
                                            </td>

                                            <td class="stock-average">
                                                <span>{{number_format((float)$stock->average, 2, '.', '')}}</span>
                                            </td>
                                        </tr>
                                        <?php $rank++; ?>
                                    @endforeach
                                </table>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <div id="calendar" class="z1"></div>
                        {{--<div class="row">--}}
                            @include('includes.ads.banner_founding_member_2')
                        {{--</div>--}}
                    </div>
                </div>

                {{--<div class="row">--}}
                {{--<div class="item-box col-md-4">--}}
                {{--@include('cities.includes.item_box', array('item' =>$city))--}}
                {{--@if($user)--}}
                {{--@include('cities.includes.manager', array('user' =>$user))--}}
                {{--@endif--}}
                {{----}}
                {{--</div>--}}
                {{--<div class="col-md-8">--}}

                {{--</div>--}}
                {{--</div>--}}
            </div>
        </div>
    </main>
@stop

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ url('bower_components/fullcalendar/dist/fullcalendar.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('bower_components/fullcalendar/dist/fullcalendar.print.css') }}">
@endsection

@section('scripts')
    <script src="{{ url('bower_components/moment/moment.js') }}"></script>
    <script src="{{ url('bower_components/fullcalendar/dist/fullcalendar.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $("#calendar").fullCalendar({
                header: {
                    left: 'prev',
                    right: 'next',
                    center: 'title'
                },
                contentHeight: "auto",
                height: "auto"
            });

            var notification = $(".city-single .notigications");
            var notification_cross_button = $(".city-single .notigications #notifications-cross-button");
            if(notification_cross_button){
                notification_cross_button.on('click', function (e) {
                    e.preventDefault();
                    console.log('clicked');
                    if(notification){
                        notification.toggle('slide');
                    }
                });
            }
        });
    </script>
@endsection