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
                <div class="col-md-7">
                    <div class="panel">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-md-4">
                                    <h5>My Events</h5>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-inline text-right">
                                        <input type="text" class="form-control" id="search-text" placeholder="Search for events and attendees">
                                        <button id="event-search" class="btn btn-default-outline">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="my-events">
                        @if($myEvents && count($myEvents) > 0)
                            @foreach($myEvents as $myEvent)
                                <div class="col-md-4">
                                    <div class="panel z1">
                                        <div class="panel-body">
                                            @include('event.includes.event',array('event' => $myEvent))
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else

                        @endif
                    </div>
                </div>
                <div class="col-md-3">
                    @include('includes.right_block')
                </div>
            </div>
        </div>
    </main>

    @include('event.includes.modal.delete_event')

@stop

@section('styles')
    <style>

    </style>
@stop

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function(){
            $('#event-search').on('click', function(e) {
                e.preventDefault();
                console.log($('#search-text').val());
                var search_text = $('#search-text').val();
                var url = '{{ route('event::my-event-search') }}';
                var data = {search_text: search_text};
                CrowdifyAjaxService.makeRequest(url, 'POST', data, function (response) {
                    $('#my-events').html(response.view);
                }, function (error) {
                });
            });

        });
    </script>
@stop