<?php
/**
 * Created by PhpStorm.
 * User: Nathan Senn
 * Date: 11/20/2015
 * Time: 7:51 PM
 */
?>
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
            <div class="row">
                <div class="col-md-2">
                    @include('perk.includes.talent_nav')
                </div>
                <div class="col-md-10">
                    <br>

                    <div class="col-md-8">

                        <div class="panel z1">
                            <div class="panel-body">
                                <h1 align="center">{{ $talent->title }}</h1><br>
                                <span class="lf" itemprop="category"
                                      style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; float: left; background: transparent;"><a
                                            class="js-gtm-event-auto" data-gtm-action="Click"
                                            data-gtm-category="New Gig Page" data-gtm-label="Category Link"
                                            href="http://talent.crowdify.tech/talent/{{$talent->category_2}}"
                                            itemprop="url"
                                            style="color: rgb(0, 105, 140); margin: 0px; padding: 0px; vertical-align: baseline; text-transform: capitalize; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;">{{$talent->category_2}}</a></span>
                                <span class="rf"
                                      style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; float: right; background: transparent;"><span
                                            class="fa fa-clock-o"
                                            style="font-size: 18px; margin: 0px 3px 0px 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"></span>{{$talent->days}}
                                    Days On Average</span>

                                <div style="text-align: center;">
                                    <img src="{!! $talent->talent_photo !!}" style="max-width: 90%">
                                </div>
                                <p>{!! $talent->description !!}</p>

                            </div>
                        </div>
                        <div class="panel z1">
                            @foreach($bids as $bid)
                                <div class="panel-body">
                                    <span> <a href="{{ route('perk::public_profile', array('username' => $bid->user->username)) }}">
                                            <img class="profile-avatar" src="{{$bid->user->avatar}}"/>{{$bid->user->username}}</a></span>
                                        <span>{{$bid->created_at->diffForHumans()}}</span>
                                    <p>{{$bid->msg}}</p>
                                    @if($auth->user()->username == $talent->user->username)
                                        @if($bid->status == 0)
                                            {!! Form::open(array('method' => 'PUT', 'route' => 'talent::accept-bid')) !!}
                                            {!! Form::hidden('id', $bid->id) !!}
                                            {!! Form::hidden('req', $bid->request_id) !!}
                                            {!! Form::submit('Accept Bid',['class' => 'btn btn-default-outline btn-sm pull-right']) !!}
                                            {!! Form::close() !!}
                                        @else
                                            <h3>Release bitcoins as payment for completion of this talent request this can not be undo!</h3>
                                            {!! Form::open(array('method' => 'PUT', 'route' => 'talent::release-btc')) !!}
                                            {!! Form::hidden('id', $bid->id) !!}
                                            {!! Form::hidden('req', $bid->request_id) !!}
                                            {!! Form::hidden('bidder', $bid->bidder_id) !!}
                                            {!! Form::submit('Release BTC',['class' => 'btn btn-default-outline btn-sm pull-right']) !!}
                                            {!! Form::close() !!}
                                        @endif
                                    @endif
                                    @if($auth->user()->id == $bid->bidder_id)

                                        @if($bid->status == 0)

                                        @elseif($bid->status == 1)
                                            <h3>Cancel this job this cant be undone</h3>
                                            {!! Form::open(array('method' => 'PUT', 'route' => 'talent::cancel-job')) !!}
                                            {!! Form::hidden('id', $bid->id) !!}
                                            {!! Form::hidden('req', $bid->request_id) !!}
                                            {!! Form::hidden('bidder', $bid->bidder_id) !!}
                                            {!! Form::submit('Cancel Job',['class' => 'btn btn-default-outline btn-sm pull-right']) !!}
                                            {!! Form::close() !!}
                                        @endif

                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-4 talent">
                        <div class="panel z1">
                            <div class="panel-body">
                                <h3>{{ $user->first_name }} {{ $user->last_name }}</h3>
                                <img src="{{ $user->avatar ? $user->avatar_original : '' }}" class="profile-avatar-big">

                                <div class='movie_choice'>
                                    Rating
                                    <div id="r1" class="rate_widget">
                                        <div class="star_1 ratings_stars"></div>
                                        <div class="star_2 ratings_stars"></div>
                                        <div class="star_3 ratings_stars"></div>
                                        <div class="star_4 ratings_stars"></div>
                                        <div class="star_5 ratings_stars"></div>
                                        <div class="total_votes">Total ratings</div>
                                    </div>
                                </div>
                                <p>{{ $user->bio }}</p>
                                <span class="label label-deafult-outline"><i
                                            class="crowd-coin"></i> {{ $talent->crowdcoins }}</span>
                                <span class="label label-deafult-outline"><i
                                            class="bit-coin"></i> {{ $talent->bitcoins }}</span>
                                </p>
                                <br>

                                <div id='calendar'></div>
                                <br>
                                @if($auth->user()->username != $talent->user->username)
                                <button
                                        class="btn btn-default-outline"
                                        id="btn-bid-talent"
                                        data-toggle="modal"
                                        data-target="#modal-auth">
                                    Submit Bid Now
                                </button>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @include('talent.includes.modal.bid_talent', array('talent' => $talent))
    {{--@include('talent.includes.modal.accept-bid', array('talent' => $talent))--}}
    {{--@include('talent.includes.modal.release-btc', array('talent' => $talent))--}}
    {{--@include('talent.includes.modal.cancel-job', array('talent' => $talent))--}}
@stop
@section('scripts')
    <script src="{{url('bower_components/moment/min/moment.min.js')}}"></script>
    <script src="{{url('bower_components/fullcalendar/dist/fullcalendar.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('#btn-bid-talent').click(function (e) {
                $('#bid-talent').modal('show');

            });
            $('#btn-accept-bid').click(function (e) {
                $('#accept-bid').modal('show');

            });
            $('#btn-release-btc').click(function (e) {
                $('#release-btc').modal('show');

            });
            $('#btn-cancel-job').click(function (e) {
                $('#cancel-job').modal('show');

            });
            $('.rate_widget').each(function (i) {
                var widget = this;
                var out_data = {
                    widget_id: $(widget).attr('id'),
                    fetch: 1
                };
                $.post(
                        '../talent/rating/{{$talent->id}}',
                        out_data,
                        function (INFO) {
                            $(widget).data('fsr', INFO);
                            set_votes(widget);
                        },
                        'json'
                );
            });

            $('#calendar').fullCalendar({
                eventClick: function (calEvent, jsEvent, view) {
                    alert('Event: ' + calEvent.title);
                },
                dayClick: function (date, jsEvent, view, resourceObj) {
                    $('#modal-book-talent').modal('show');
                    $('.bookdate').text('Book for ' + date.format());
                },

                defaultDate: '{{date("Y-m-d H:i:s")}}',
                editable: true,
                eventLimit: true, // allow "more" link when too many events
                events: {
                    url: '../../dates/get/{{$talent->id}}',
                    type: 'POST',
                    error: function () {
                        $('#script-warning').show();
                    }
                }
            });
            function set_votes(widget) {

                var avg = $(widget).data('fsr').whole_avg;
                var votes = $(widget).data('fsr').number_votes;
                var exact = $(widget).data('fsr').dec_avg;

                window.console && console.log('and now in set_votes, it thinks the fsr is ' + $(widget).data('fsr').number_votes);

                $(widget).find('.star_' + avg).prevAll().andSelf().addClass('ratings_vote');
                $(widget).find('.star_' + avg).nextAll().removeClass('ratings_vote');
                $(widget).find('.total_votes').text(votes + ' votes recorded (' + exact + ' stars)');
            }
        });
    </script>
@stop