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
                        {!! Form::open(array('method' => 'put', 'files' => true)) !!}
                        <div class="panel z1">
                            <div class="panel-body">
                                <div class="form-group">
                                    {!! Form::label('title', 'Title *') !!}
                                    {!! Form::text('title', $talent->title, ['class' => 'form-control','placeholder' => 'Title']) !!}
                                    @if($errors->has('title'))
                                        {!! Form::label('error', $errors->get('title')[0], ['class' => 'text-danger']) !!}
                                    @endif
                                </div>
                                <span class="lf" itemprop="category" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; float: left; background: transparent;"><a class="js-gtm-event-auto" data-gtm-action="Click" data-gtm-category="New Gig Page" data-gtm-label="Category Link" href="http://talent.crowdify.tech/talent/{{$talent->category_2}}" itemprop="url" style="color: rgb(0, 105, 140); margin: 0px; padding: 0px; vertical-align: baseline; text-transform: capitalize; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;">{{$talent->category_2}}</a></span>
                                <span class="rf" style="margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; float: right; background: transparent;"><span class="fa fa-clock-o" style="font-size: 18px; margin: 0px 3px 0px 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: middle; background: transparent;"></span>
                                    <div class="form-group">
                                        {!! Form::label('days', 'Days On Average') !!}
                                        {!! Form::number('days', $talent->days, array('class' => 'form-control',)) !!}
                                    </div>
                                   </span>
                                <div style="text-align: center;">
                                    <img src="{!! $talent->talent_photo !!}" style="max-width: 90%">
                                </div>
                                <div class="form-group">
                                    {!! Form::label('talent_photo', 'talent Photo') !!}
                                    <input type="file" name="talent_photo" class="form-control" id="talent_photo" accept="image/*">
                                </div>
                                <div class="form-group">
                                    {!! Form::label('metatag', 'Short Description *') !!}
                                    {!! Form::textarea('metatag', $talent->metatag, ['class' => 'form-control']) !!}
                                    @if($errors->has('metatag'))
                                        {!! Form::label('error', $errors->get('metatag')[0], ['class' => 'text-danger']) !!}
                                    @endif
                                </div>
                                <div class="form-group">
                                    {!! Form::label('description', 'Description *') !!}
                                    {!! Form::textarea('description', $talent->description ,array('id' => 'summernote', 'class' => 'form-control summernote note-editable', 'placeholder' => 'Description')) !!}
                                    @if($errors->has('description'))
                                        {!! Form::label('error', $errors->get('description')[0], ['class' => 'text-danger']) !!}
                                    @endif
                                </div>

                            </div>
                        </div>
                        <div class="panel z1">
                            @foreach($ratings as $rating)
                                <div class="panel-body">
                                    <span> <a href="{{ route('perk::public_profile', array('username' => $rating->user->username)) }}"> <img class="img-circle profile-avatar" src="{{$rating->user->avatar}}"/></a></span>
                                    {{$rating->feedback}}
                                    <p>{{$rating->star_rating}} Stars</p>
                                </div>
                            @endforeach
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
                                <div class="form-group">
                                    {!! Form::label('bitcoins', 'Bitcoins *') !!}
                                    {!! Form::number('bitcoins', $talent->bitcoins, array( 'step' => 'any', 'class' => 'form-control',)) !!}

                                </div>
                                <div class="form-group">
                                    {!! Form::label('crowdcoins', 'Crowdcoin *') !!}
                                    {!! Form::number('crowdcoins', $talent->crowdcoins, array('class' => 'form-control',)) !!}
                                    @if($errors->has('crowdcoins'))
                                        {!! Form::label('error', $errors->get('bitcoin')[0]) !!}
                                    @endif
                                </div>
                                </p>
                                <br>
                                <p>Click days to add times you are available for people to book your talent</p>
                                <div id='calendar'></div>
                                <br>
                                <div class="form-group">
                                    {!! Form::submit('Edit',['class' => 'btn btn-default-outline btn-sm pull-right']) !!}
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @include('talent.includes.modal.add_dates', array('user' => $talent))

@stop
@section('scripts')
    <script src="/bower_components/summernote/dist/summernote.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>
    <script src="{{url('bower_components/moment/min/moment.min.js')}}"></script>
    <script src="{{url('bower_components/fullcalendar/dist/fullcalendar.min.js')}}"></script>


    <script>
        $(document).ready(function(){
            var $summernote = $('#summernote');
            $summernote.summernote({

                onImageUpload: function(files) {
                    console.log('image upload:', files);
                    // upload image to server and create imgNode...
                    var url = '{{ route('image::upload') }}';
//

                    var  data = new FormData();
                    data.append("file", files[0]);


                    $.ajax({
                        url:url,
                        method:'POST',
                        data:data,
                        processData: false,  // tell jQuery not to process the data
                        contentType: false,
                        success: function ( data ) {

                            $('.summernote').summernote('insertImage', data.file_name);

                            //editor.insertImage(welEditable, data.file_name);

//                            var imgNode = '<img src="'+data.file_name+'" />';
//                            $summernote.summernote('insertNode', imgNode);
                            console.log(data);
                        },
                        error: function(data)
                        {
                            console.log(data);
                        }
                    });





//                    CrowdifyAjaxService.makeRequest(url, 'POST', data,function(response){
//                        console.log(response);
//                    }, function(error){
//                        console.log(error);
//                    });

                }
            });

            $('#calendar').fullCalendar({
                eventClick: function(calEvent, jsEvent, view) {
                    alert('Event: ' + calEvent.title);
                },
                dayClick: function(date, jsEvent, view, resourceObj) {
                    var url = '{{ route('talent::getdates') }}';
                    $('#add_dates').modal('show');
                    $('.adddate').text('Add available time for ' + date.format());
                    $('.addval').val(date.format());
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
            $('#btn-add-time').click(function(e){
                var data = getTimeData($(this).closest('.time-form'));
                console.log(data);

                var taskForm = $(this).closest('.time-form');

                var url = '{{route('talent::addtime')}}';
                CrowdifyAjaxService.makeRequest(url,'POST' ,data, function(response){
                    $('#tasks').prepend(response.view);
                    $('#add_dates').modal('hide');
                    $('#calendar').fullCalendar( 'refetchEvents' )
                }, function(error){

                });
            });
            function getTimeData(form){
                return {
                    title: form.find('input[name=title]').val(),
                    time: form.find('input[name=time]').val(),
                    date: form.find('input[name=date]').val(),
                    job_id: form.find('input[name=job_id]').val(),
                }
            }
        });
    </script>
@endsection

@section('styles')
    <style>
        .note-editable{
            min-height: 350px !important;
        }
    </style>
    <link rel="stylesheet" href="/bower_components/summernote/dist/summernote.css">
    <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css" rel="stylesheet" />
@endsection