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
                    @if($action == 'Update')
                        {!! Form::open(array('files'=> true, 'method' => 'put')) !!}
                    @else
                        {!! Form::open(array('files'=> true)) !!}
                    @endif
                    <div class="panel z1 event-create-form">
                        <div class="panel-heading">
                            {{ $action }} Event
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('title', 'Event Title *') !!}
                                        {!! Form::text('title', $event->title, ['class' => 'form-control', 'placeholder' => 'Give it a short description','required']) !!}
                                        @if($errors->has('title'))
                                            <label class="text-danger">{{$errors->get('title')[0]}}</label>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{--Event Date-Picker--}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Event Logo *</label>
                                                <div class="image-upload" data-image-url="{{$event->logo}}">
                                                </div>
                                                <input type="hidden" name="logo" id="logo" value="{{$event->logo}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group event-date">
                                                {!! Form::label('start_date', 'STARTS: *') !!}
                                                <input type="text" value="{{ $event->start_date }}" name="start_date" class="datetimepicker form-control" required>
                                                @if($errors->has('start_date'))
                                                    <label class="text-danger">{{$errors->get('start_date')[0]}}</label>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group event-date">
                                                {!! Form::label('end_date', 'ENDS: *') !!}
                                                <input type="text" name="end_date" value="{{ $event->end_date }}" class="datetimepicker form-control" required>
                                                @if($errors->has('end_date'))
                                                    <label class="text-danger">{{$errors->get('end_date')[0]}}</label>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                {!! Form::label('location', 'Location *') !!}
                                                @if($errors->has('location'))
                                                    <label class="text-danger">{{$errors->get('location')[0]}}</label>
                                                @endif
                                                {!! Form::text('location', $event->location, ['class' => 'form-control controls', 'placeholder' => 'Specify where it\'s held', 'id' => 'pac-input','required']) !!}
                                                <input type="hidden" id="event-latitude" name="latitude" value="{{$event->latitude}}">
                                                <input type="hidden" id="event-longitude" name="longitude" value="{{$event->longitude}}">
                                            </div>
                                            <div id="map" style="width: 100%; height: 200px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('description', 'EVENT DESCRIPTION *') !!}
                                        <textarea name="description" class="form-control event-description summernote" placeholder="Tell people what's special about this event">{{ $event->description }}</textarea>
                                        {{--{!! Form::textarea('description', null, ['class' => 'form-control event-description', 'placeholder' => 'Tell people what\'s special about this event']) !!}--}}
                                        @if($errors->has('description'))
                                            <label class="text-danger">{{$errors->get('description')[0]}}</label>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="">Categories *</label>
                                        {!! Form::select('categories[]',$categories, $ids, array('class'=> 'form-control', 'multiple', 'id'=> 'category-select')) !!}
                                        @if($errors->has('categories'))
                                            <label class="text-danger">{{$errors->get('categories')[0]}}</label>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <input type="checkbox" name="publish_social" id="social_check" value="true">
                                        <label for="social_check">Publish facebook and twitter link</label>
                                    </div>
                                    <div class="row hidden" id="social_div">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="facebook-link">Facebook</label>
                                                <input type="text" name="facebook_link" class="form-control" id="facebook-link">
                                            </div>
                                            <div class="form-group">
                                                <label for="twitter-link">Twitter</label>
                                                <input type="text" name="twitter_link" class="form-control" id="twitter-link">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="event-type">Event Type *</label>
                                        {!! Form::select('type', $types, $event->type, array('class'=> 'form-control', 'id'=> 'type-select', 'required')) !!}
                                        @if($errors->has('type'))
                                            <label class="text-danger">{{$errors->get('type')[0]}}</label>
                                        @endif
                                        <p id="paid-event-hint" class="text-info hidden">Go ahead and save the event. You will get the option to add event tickets and amount after saving the event.</p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <input type="submit" class="btn btn-default-outline" value="{{ $action }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    {!! Form::close() !!}
                </div>
                <div class="col-md-3">
                    @include('includes.right_block')
                </div>
            </div>

        </div>
    </main>
@stop



@section('scripts')
    {{--<script src="/bower_components/dropzone/dist/min/dropzone.min.js"></script>--}}

    <script src="/bower_components/iCheck/icheck.js"></script>
    <script src="/bower_components/summernote/dist/summernote.min.js"></script>

    <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>


    <script>
        $(document).ready(function(){
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square',
                increaseArea: '20%' // optional
            });
            $('input').on('ifChecked',function(event){
                $('#social_div').removeClass('hidden');
            });

            $('input').on('ifUnchecked',function(event){
                $('#social_div').addClass('hidden');
            });

            $('#category-select').select2({
                placeholder : 'Select Category',
                //tags: true
            });
            $('#type-select').select2({
                placeholder : 'Select Event Type'
            }).on("change", function(e) {
                var val = $(this).val();
                if(val == 'Paid'){
                    $('#paid-event-hint').removeClass('hidden');
                }else{
                    $('#paid-event-hint').addClass('hidden');
                }
            });
            $('.datetimepicker').datetimepicker({
                format: "Y-m-d H:i"
            });


            $('.summernote').summernote({
                minHeight: 300,
            });

            function initializeImageUpload(imageUploadDiv){
                imageUploadDiv.imageUpload({
                    defaultImageUrl:'/assets/images/placeholder.png',
                    url : '{{route('image::upload')}}',
                    onUploaded:function(response){
                        $('#logo').val(response.file_name);
                    },
                    onError:function(error){

                    }
                });
            }

            initializeImageUpload($('.image-upload'))

        });
    </script>
    <script>

        var map;
        var center = { lat : 35.689487, lng : 139.691706};

        function initMapByLatLng(location){
            map = new google.maps.Map(document.getElementById('map'), {
                center: location,
                zoom: 13
            });
            var marker = new google.maps.Marker({
                map: map,
                position: location
            });
            map.setOptions({styles: GOOGLE_MAP_STYLES});
        }
        function initMapByAddress(address){
            var geocoder = new google.maps.Geocoder;
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 8,
                center: center
            });
            map.setOptions({styles: GOOGLE_MAP_STYLES});
            geocodeAddress(geocoder, map, address)
        }
        function geocodeAddress(geocoder, map, address) {
            geocoder.geocode({'address': address}, function(results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        console.log(results[0]);
                        map.setZoom(11);
                        map.setCenter(results[0].geometry.location);
                        var marker = new google.maps.Marker({
                            map: map,
                            position: results[0].geometry.location
                        });
                    } else {
                        window.alert('No results found');
                    }
                } else {
                    window.alert('Geocoder failed due to: ' + status);
                }
            });
        }
        function initMap() {
            @if($action == 'Update')
                @if($event->latitude && $event->longitude)
                    var lat = {{$event->latitude}};
                    var lng = {{$event->longitude}};
                    var location = { lat : lat, lng : lng};
                    center = location;
                    initMapByLatLng(center);
                 @else
                    var address = '{{$event->location}}';
                    if(!address){
                        address = 'Tokyo'
                    }
                    initMapByAddress(address);
                @endif
            @else
                initMapByLatLng(center);
            @endif
            var input = (document.getElementById('pac-input'));
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
            var autocomplete = new google.maps.places.Autocomplete(input);
            autocomplete.bindTo('bounds', map);

            var infowindow = new google.maps.InfoWindow();
            var marker = new google.maps.Marker({
                map: map,
                anchorPoint: new google.maps.Point(0, -29)
            });

            google.maps.event.addDomListener(window, 'resize', function() {
                map.setCenter(center);
            });

            autocomplete.addListener('place_changed', function() {
                infowindow.close();
                marker.setVisible(false);
                var place = autocomplete.getPlace();
                if (!place.geometry) {
                    return;
                }

                // If the place has a geometry, then present it on a map.
                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17);  // Why 17? Because it looks good.
                }
                marker.setIcon(/** @type {google.maps.Icon} */({
                    url: place.icon,
                    size: new google.maps.Size(71, 71),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(17, 34),
                    scaledSize: new google.maps.Size(35, 35)
                }));
                marker.setPosition(place.geometry.location);
                marker.setVisible(true);
                center = place.geometry.location;
                $('#event-latitude').val(place.geometry.location.lat());
                $('#event-longitude').val(place.geometry.location.lng());
                var address = '';
                if (place.address_components) {
                    address = [
                        (place.address_components[0] && place.address_components[0].short_name || ''),
                        (place.address_components[1] && place.address_components[1].short_name || ''),
                        (place.address_components[2] && place.address_components[2].short_name || '')
                    ].join(' ');
                }

                infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
                infowindow.open(map, marker);

            });
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBB0cGGFl0rSov-OMll0p4VxeaMi7OPAF0&libraries=places&callback=initMap"
            async defer></script>
@stop

@section('styles')
    <link rel="stylesheet" href="/bower_components/dropzone/dist/min/dropzone.min.css">
    <link rel="stylesheet" href="/bower_components/summernote/dist/summernote.css">
    <link rel="stylesheet" href="/bower_components/iCheck/skins/all.css">
    <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css" rel="stylesheet" />

    <style>
        .trumbowyg-box, .trumbowyg-editor{
            margin: 0px;
        }
        .controls {
            margin-top: 10px;
            border: 1px solid transparent;
            border-radius: 2px 0 0 2px;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            height: 32px;
            outline: none;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        }

        #pac-input {
            background-color: #fff;
            font-family: Roboto;
            font-size: 15px;
            font-weight: 300;
            margin-left: 12px;
            padding: 0 11px 0 13px;
            text-overflow: ellipsis;
            width: 50%;
        }

        #pac-input:focus {
            border-color: #4d90fe;
        }
    </style>
@stop