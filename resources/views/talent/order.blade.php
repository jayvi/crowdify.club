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
                                <span class="lf" itemprop="category" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; float: left; background: transparent;"><a class="js-gtm-event-auto" data-gtm-action="Click" data-gtm-category="New Gig Page" data-gtm-label="Category Link" href="http://talent.crowdify.tech/talent/{{$talent->category_2}}" itemprop="url" style="color: rgb(0, 105, 140); margin: 0px; padding: 0px; vertical-align: baseline; text-transform: capitalize; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;">{{$talent->category_2}}</a></span>
                                <span class="rf" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; float: right; background: transparent;"><span class="fa fa-clock-o" style="font-size: 18px; margin: 0px 3px 0px 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"></span>{{$talent->days}} Days On Average</span>
                                <div style="text-align: center;">
                                    <img src="{!! $talent->talent_photo !!}" style="max-width: 90%">
                                </div>
                                <p>{!! $talent->description !!}</p>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 talent">
                        <div class="panel z1">
                            <div class="panel-body">
                                <h3 >{{ $user->first_name }} {{ $user->last_name }}</h3>
                                <img src="{{ $user->avatar ? $user->avatar_original : '' }}" class="profile-avatar-big img-circle">
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
                                <span class="label label-deafult-outline"><i class="crowd-coin"></i> {{ $talent->crowdcoins }}</span>
                                <span class="label label-deafult-outline"><i class="bit-coin"></i> {{ $talent->bitcoins }}</span>
                                </p>
                                <br>
                                <div id='calendar'></div>
                                <br>
                                @if($order->status == 0)
                                    <button class="btn btn-default-outline" id="btn-work-talent" data-toggle="modal" data-target="#modal-auth">Mark Done</button>
                                @elseif($order->status == 1)
                                    <p>Waiting for buyer</p>
                                @else
                                    <p>Job in Finished</p>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @include('talent.includes.modal.work_talent', array('user' => $talent))

@stop
@section('scripts')
    <script>
        $(document).ready(function(){
            $('#btn-work-talent').click(function(e){
                $('#work-talent').modal('show');

            });
            $('.rate_widget').each(function(i) {
                var widget = this;
                var out_data = {
                    widget_id : $(widget).attr('id'),
                    fetch: 1
                };
                $.post(
                        '../rating/{{$talent->id}}',
                        out_data,
                        function(INFO) {
                            $(widget).data( 'fsr', INFO );
                            set_votes(widget);
                        },
                        'json'
                );
            });

            $('#calendar').fullCalendar({
                eventClick: function(calEvent, jsEvent, view) {
                    alert('Event: ' + calEvent.title);
                },

                defaultDate: '{{date("Y-m-d H:i:s")}}',
                editable: true,
                eventLimit: true, // allow "more" link when too many events
                events: {
                    url: '../../dates/get/{{$talent->id}}',
                    type: 'POST',
                    error: function() {
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
                $(widget).find('.total_votes').text( votes + ' votes recorded (' + exact + ' stars)' );
            }
        });
    </script>
@stop