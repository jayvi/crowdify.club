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
                    @include('perk.includes.events_nav')
                </div>
                <div class="col-md-10">
                            <div class="row">

                                {!! Form::open(array(
                                      'url' => route('event::home'),
                                      'class'=> 'form-inline'
                                  )) !!}
                                <div class="form-group">
                                    <input name="title" type="search" class="form-control event-search" placeholder="Search for events" autocomplete="off" >
                                    <input name="location" type="search" class="form-control event-search" placeholder="Enter city or location">
                                    <select name="date" class="form-control date-select event-search"><i class="icon ion-home"></i>
                                        <option value="all" selected="selected">All Dates <span></span></option>
                                        <option value="today">Today</option>
                                        <option value="tomorrow">Tomorrow</option>
                                        <option value="this_week">This Week</option>
                                        <option value="this_weekend">This Weekend</option>
                                        <option value="next_week">Next Week</option>
                                        <option value="next_month">Next Month</option>
                                    </select>
                                <button type="submit" class="btn custom-btn btn-default event-search">Search</button>
                                </div>
                                {!! Form::close() !!}

                            </div>
                </div>
                <div class="col-md-7">
                    @if($location)
                        <div class="user-location">
                            <div class="container">
                                <span class="hidden-xs">Showing events near</span>
                                <a href="{{route('event::home').'?location='.$location}}">{{$location}}</a>
                            </div>
                        </div>
                    @endif
                    <div class="row">
                        @if($events && count($events) > 0)
                            @include('event.includes.events', array('events' => $events))
                        @endif
                    </div>
                </div>
                <div class="col-md-3">
                    @include('includes.right_block')
                </div>
            </div>
        </div>
    </main>
@stop
