@extends('layout.master')

@section('navbar')
    @include('perk.includes.navbar')
@stop

@section('header')
    @include('perk.includes.header')
@stop

@inject('auth','Illuminate\Contracts\Auth\Guard')
@section('content')
    <main class="content">
        <div class="container-fluid">
            <div class="col-md-2">
                @include('perk.includes.events_nav')
            </div>
            <div class="col-md-10">
                <div class="row">
                    <div class="col-md-8">
                        <div>
                            <div class="panel">
                                <h1 style="z-index: 2; position: absolute; text-align: center; width: 100%; top: -10px;">{{ $event->title }}</h1>
                                <img src="{{url($event->logo)}}" style="width: 100%;" alt="Event Image"/>
                                <img class="imgheader" src="{{url('assets/images/header.png')}}">
                                <img class="imgprice" src="{{url('assets/images/Shape16.png')}}">
                                <img class="imgbr1" src="{{url('assets/images/redbox.png')}}">
                                <img class="imgbr2" src="{{url('assets/images/bluebox.png')}}">
                                <img class="imgbr1 invert" style="right: 37px; bottom: 6px;" src="{{url('assets/images/faheart.png')}}">
                                <img class="imgbr2 invert" style="right: 7px; bottom: 6px;" src="{{url('assets/images/fashare.png')}}">

                            </div>
                        </div>

                        <div class="panel z1">
                            <nav class="navbar navbar-default">
                                <ul class="nav navbar-nav nav-justified event-nav">
                                    <li class="active"><a href="">HOME</a></li>
                                    <li><a href="">MEMBERS</a></li>
                                    <li><a href="">SPONSORS</a></li>
                                    <li><a href="">PHOTOS</a></li>
                                    <li><a href="">DISCUSSION</a></li>
                                    <li id="btn-buy-ticket" style="background-color: #018FFB;"><a style="color: white; font-weight: bold;">REGISTER</a></li>
                                </ul>
                            </nav>
                            <div class="panel-body">
                                <p>Event Description: {!!  $event->description  !!}</p>
                                <p><img class="" src="{{url('assets/images/dates.png')}}">&nbsp&nbsp&nbsp{{ date('M dS Y g:ia',strtotime($event->start_date))}}</p>
                                <p><img class="" src="{{url('assets/images/pin.png')}}">&nbsp&nbsp&nbsp<a href="{{url('/'.'?location='.$event->location)}}">{{ $event->location }}</a></p>
                            </div>
                            <div class="event-category-box">
                                @foreach($event->categories as $category)
                                    <a class="event-category-link" href="{{route('event::home').'?category='.$category->id}}">#{{ $category->name }}</a>
                                @endforeach
                            </div>
                        </div>

                        @if($auth->check())
                            @if(($auth->id() == $event->user_id) || $auth->user()->isAdmin())
                                @if($event->type == 'Paid')
                                    <div class="panel z1">
                                        <div class="panel-heading">
                                            <h3>Event Tickets</h3>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#create-ticket"  id="add-ticket-button">Add New Ticket</button>
                                                </div>
                                            </div>
                                            <table class="table table-striped">
                                                <thead>
                                                    <th>Name</th>
                                                    <th>Price (USD)</th>
                                                    <th>Ticket Sold</th>
                                                    <th></th>
                                                </thead>
                                                <tbody id="event-tickets">
                                                    @foreach($event->tickets as $ticket)
                                                        <tr class="ticket">
                                                            <td>{{$ticket->name}}</td>
                                                            <td>{{$ticket->price}}</td>
                                                            <td>{{$ticket->number_of_ticket_sold}}</td>
                                                            <td>
                                                                <button class="btn btn-danger delete-ticket" data-ticket-id="{{$ticket->id}}">Delete</button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot></tfoot>
                                            </table>
                                        </div>
                                    </div>
                                @endif

                                <div class="panel z1">
                                    <div class="panel-heading">
                                        <h3>Peoples who registered for this event</h3>
                                    </div>
                                    <div class="panel-body">
                                        @if($event->registrants && count($event->registrants))
                                            <table class="table table-bordered table-responsive">
                                                <thead>
                                                    <th class="text-center">Email</th>
                                                    @if($event->type == 'Paid')
                                                        <th>Ticket Information</th>
                                                    @endif

                                                </thead>
                                                <tbody>
                                            @foreach($event->registrants as $registrant)
                                                    <tr>
                                                        <td>{{$registrant->email}}</td>
                                                        @if($event->type=='Paid')
                                                            <td>
                                                                @if($registrant->ticket)
                                                                    {{ $registrant->ticket->name }}({{ $registrant->number_of_tickets.' '}} Tickets)
                                                                @endif
                                                            </td>
                                                        @endif


                                                    </tr>
                                            @endforeach
                                                </tbody>
                                                <tfoot>
                                                </tfoot>
                                            </table>
                                        @else
                                            <p>No people registered yet</p>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endif

                    </div>
                    <div class="col-md-4">
                        <div class="panel z1">
                                <div id="map" style="width: 100%; height: 300px;"></div>
                            <div class="panel-heading">
                                <p><img class="" src="{{url('assets/images/dates.png')}}">&nbsp&nbsp&nbsp{{ date('M dS Y g:ia',strtotime($event->start_date))}}</p>
                                <p><img class="" src="{{url('assets/images/pin.png')}}">&nbsp&nbsp&nbsp<a href="{{url('/'.'?location='.$event->location)}}">{{ $event->location }}</a></p>
                                <!-- Buttons start here. Copy this ul to your document. -->
                                <ul class="rrssb-buttons clearfix">
                                    <li class="rrssb-facebook">
                                        <!--  Replace with your URL. For best results, make sure you page has the proper FB Open Graph tags in header: https://developers.facebook.com/docs/opengraph/howtos/maximizing-distribution-media-content/ -->
                                        <a href="https://www.facebook.com/sharer/sharer.php?u=http://kurtnoble.com/labs/rrssb/index.html" class="popup">
                                            <span class="rrssb-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 29 29"><path d="M26.4 0H2.6C1.714 0 0 1.715 0 2.6v23.8c0 .884 1.715 2.6 2.6 2.6h12.393V17.988h-3.996v-3.98h3.997v-3.062c0-3.746 2.835-5.97 6.177-5.97 1.6 0 2.444.173 2.845.226v3.792H21.18c-1.817 0-2.156.9-2.156 2.168v2.847h5.045l-.66 3.978h-4.386V29H26.4c.884 0 2.6-1.716 2.6-2.6V2.6c0-.885-1.716-2.6-2.6-2.6z"/></svg></span>
                                            <span class="rrssb-text">facebook</span>
                                        </a>
                                    </li>
                                    <li class="rrssb-linkedin">
                                        <!-- Replace href with your meta and URL information -->
                                        <a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=http://kurtnoble.com/labs/rrssb/index.html&amp;title=Ridiculously%20Responsive%20Social%20Sharing%20Buttons&amp;summary=Responsive%20social%20icons%20by%20KNI%20Labs" class="popup">
          <span class="rrssb-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 28 28"><path d="M25.424 15.887v8.447h-4.896v-7.882c0-1.98-.71-3.33-2.48-3.33-1.354 0-2.158.91-2.514 1.802-.13.315-.162.753-.162 1.194v8.216h-4.9s.067-13.35 0-14.73h4.9v2.087c-.01.017-.023.033-.033.05h.032v-.05c.65-1.002 1.812-2.435 4.414-2.435 3.222 0 5.638 2.106 5.638 6.632zM5.348 2.5c-1.676 0-2.772 1.093-2.772 2.54 0 1.42 1.066 2.538 2.717 2.546h.032c1.71 0 2.77-1.132 2.77-2.546C8.056 3.593 7.02 2.5 5.344 2.5h.005zm-2.48 21.834h4.896V9.604H2.867v14.73z"/></svg>
          </span>
                                            <span class="rrssb-text">linkedin</span>
                                        </a>
                                    </li>
                                    <li class="rrssb-twitter">
                                        <!-- Replace href with your Meta and URL information  -->
                                        <a href="https://twitter.com/intent/tweet?text=Ridiculously%20Responsive%20Social%20Sharing%20Buttons%20by%20%40dbox%20and%20%40joshuatuscan%3A%20http%3A%2F%2Fkurtnoble.com%2Flabs%2Frrssb%20%7C%20http%3A%2F%2Fkurtnoble.com%2Flabs%2Frrssb%2Fmedia%2Frrssb-preview.png"
                                           class="popup">
          <span class="rrssb-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 28 28"><path d="M24.253 8.756C24.69 17.08 18.297 24.182 9.97 24.62a15.093 15.093 0 0 1-8.86-2.32c2.702.18 5.375-.648 7.507-2.32a5.417 5.417 0 0 1-4.49-3.64c.802.13 1.62.077 2.4-.154a5.416 5.416 0 0 1-4.412-5.11 5.43 5.43 0 0 0 2.168.387A5.416 5.416 0 0 1 2.89 4.498a15.09 15.09 0 0 0 10.913 5.573 5.185 5.185 0 0 1 3.434-6.48 5.18 5.18 0 0 1 5.546 1.682 9.076 9.076 0 0 0 3.33-1.317 5.038 5.038 0 0 1-2.4 2.942 9.068 9.068 0 0 0 3.02-.85 5.05 5.05 0 0 1-2.48 2.71z"/></svg>
          </span>
                                            <span class="rrssb-text">twitter</span>
                                        </a>
                                    </li>
                                    <li class="rrssb-googleplus">
                                        <!-- Replace href with your meta and URL information.  -->
                                        <a href="https://plus.google.com/share?url=Check%20out%20how%20ridiculously%20responsive%20these%20social%20buttons%20are%20http://kurtnoble.com/labs/rrssb/index.html" class="popup">
          <span class="rrssb-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M21 8.29h-1.95v2.6h-2.6v1.82h2.6v2.6H21v-2.6h2.6v-1.885H21V8.29zM7.614 10.306v2.925h3.9c-.26 1.69-1.755 2.925-3.9 2.925-2.34 0-4.29-2.016-4.29-4.354s1.885-4.353 4.29-4.353c1.104 0 2.014.326 2.794 1.105l2.08-2.08c-1.3-1.17-2.924-1.883-4.874-1.883C3.65 4.586.4 7.835.4 11.8s3.25 7.212 7.214 7.212c4.224 0 6.953-2.988 6.953-7.082 0-.52-.065-1.104-.13-1.624H7.614z"/></svg>            </span>
                                            <span class="rrssb-text">google+</span>
                                        </a>
                                    </li>
                                </ul>

                                <!-- Buttons end here -->
                            </div>
                        </div>
                            @if($auth->id() == $event->user_id)
                            <div class="panel z1">
                                <div class="panel-heading text-center">
                                    @if($event->user_id == $auth->id())
                                        {!! Form::open(array(
                                            'url' => route('event::'. ($event->status == 'Published' ? 'un-publish' : 'publish'), ['id' => $event->id]),
                                            'class' => 'form-inline'
                                        ))  !!}
                                        <input type="submit" name="submit" value="{{$event->status == 'Published' ? 'Un-publish Event' : 'Publish Event'}}" class=" btn btn-default-outline">
                                        {!! Form::close() !!}
                                        <li><a href="{{ route('event::edit', ['id' => $event->id]) }}">Edit</a></li>
                                        <li><a class="delete-link" data-toggle="modal" data-target="#delete-event" data-event-id="{{ $event->id }}">Delete</a></li>
                                    @endif
                                </div>
                                <div class="panel-body text-center">
                                    @if($event->status == 'Published')
                                        <h5>This Event is published</h5>
                                    @else
                                        <h5>Event is not published yet</h5>
                                    @endif
                                </div>
                            </div>
                        @endif


                        <div class="panel z1">
                            <div class="panel-body">
                                <h3>Organizer:</h3>
                                @if($event->user->avatar_original)
                                    <img src="{{$event->user->avatar_original}}" class="event-avatar">
                                @else
                                    <img src="{{$event->user->avatar}}" class="event-avatar">
                                @endif
                                <h4>{{ $event->user->username }}</h4>
                                <p>{!! $event->user->bio !!}</p>
                                <?php $profiles = $event->user->profiles; ?>
                                @if($profiles)
                                    <?php
                                    $providers = $profiles->lists('provider')->toArray();
                                    ?>
                                    <ul class="list-inline">
                                        @foreach($profiles as $profile)
                                            @if($profile->provider== 'twitter')
                                                <li><a title="Twitter" target="_blank" href="{{ $profile->social_profile_url ?  $profile->social_profile_url : '#'}}"><span class="ion-social-twitter social-icon twitter"></span></a></li>
                                            @elseif($profile->provider== 'facebook')
                                                <li><a title="Facebook" target="_blank"  href="{{ $profile->social_profile_url ?  $profile->social_profile_url : '#'}}"><span class="ion-social-facebook social-icon facebook"></span></a></li>
                                            @elseif($profile->provider== 'google')
                                                <li><a title="Google+" target="_blank"  href="{{ $profile->social_profile_url ?  $profile->social_profile_url : '#'}}"><span class="ion-social-googleplus social-icon googleplus"></span></a></li>
                                            @elseif($profile->provider== 'linkedin')
                                                <li><a title="Linkedin" target="_blank"  href="{{ $profile->social_profile_url ?  $profile->social_profile_url : '#'}}"><span class="ion-social-linkedin-outline social-icon linkedin"></span></a></li>
                                            @elseif($profile->provider== 'foursquare')
                                                <li><a title="Foursquare" target="_blank"  href="{{ $profile->social_profile_url ?  $profile->social_profile_url : '#'}}"><span class="ion-social-foursquare-outline social-icon foursquare"></span></a></li>
                                            @elseif($profile->provider== 'flickr')
                                                <li><a title="Flickr" target="_blank"  href="{{ $profile->social_profile_url ?  $profile->social_profile_url : '#'}}"><span class="icon-flickr social-icon flickr"></span></a></li>
                                            @elseif($profile->provider == 'instagram')
                                                <li><a title="Instagram" target="_blank"  href="{{ $profile->social_profile_url ?  $profile->social_profile_url : '#'}}"><span class="ion-social-instagram-outline social-icon instagram"></span></a></li>
                                            @elseif($profile->provider == 'youtube')
                                                <li><a title="Youtube" target="_blank"  href="{{ $profile->social_profile_url ?  $profile->social_profile_url : '#'}}"><span class="ion-social-youtube-outline social-icon youtube"></span></a></li>
                                                {{--@elseif($profile->provider == 'blogger')--}}
                                                {{--<li><a title="Blogger" target="_blank"  href="{{ $profile->social_profile_url ?  $profile->social_profile_url : '#'}}"><span class="icon-blogger social-icon blogger"></span></a></li>--}}
                                            @elseif($profile->provider == 'facebookPage')
                                                <li><a title="Facebook Page" target="_blank"  href="{{ $profile->social_profile_url ?  $profile->social_profile_url : '#'}}"><span class="ion-flag social-icon facebook-page"></span></a></li>
                                            @elseif($profile->provider == 'tumblr')
                                                <li><a title="Tumblr" target="_blank"  href="{{ $profile->social_profile_url ?  $profile->social_profile_url : '#'}}"><span class="ion-social-tumblr social-icon tumblr"></span></a></li>
                                            @endif
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    @if($auth->id() == $event->user_id))
        @include('event.includes.modal.create_ticket', ['event' => $event])
        @include('event.includes.modal.delete_event')
    @endif
    @include('event.includes.modal.buy_ticket')
@stop

@section('scripts')
    <script>
            $('#btn-buy-ticket').click(function(e){
                $('#buy-ticket').modal('show');

            });     $(document).ready(function () {
            $('#button-join').click(function(e) {
                $('#event-registration').removeClass('hidden');
                $(this).closest('.join-btn-div').remove();
            });

            $('.delete-link').on('click', function(e){
                e.preventDefault();
                var delete_id = $(this).data('event-id');
                $('#delete-modal-event-id').val(delete_id);
            });
            $('#create-ticket-form').on('submit', function(e){
                e.preventDefault();
                var form = $(this).serializeArray();
                var url = '{{route('event::tickets::create',['event_id' => $event->id])}}';
                CrowdifyAjaxService.post(url, form, function(response){
                    var html = '<tr class="ticket">' +
                            '<td>' + response.ticket.name +'</td>' +
                            '<td>' + response.ticket.price +'</td>' +
                            '<td><button class="btn btn-danger delete-ticket" data-ticket-id="'+response.ticket.id+'">Delete</button></td>' +
                            '</tr>'
                    $('#event-tickets').append(html);
                    $('#create-ticket').modal('hide');
                }, function(error){

                })
            });

            $('#event-tickets').on('click', '.delete-ticket', function(e){
                e.preventDefault();
                var ticketId = $(this).data('ticket-id');
                var url = '{{route('event::tickets::delete',['event_id' => $event->id, 'ticket_id' => 'ticket_id'])}}';
                url = url.replace('ticket_id',ticketId);
                console.log(url);
                CrowdifyAjaxService.delete(url,{},function(response){
                    $(this).closest('.ticket').remove();
                }.bind(this), function(error){
                })
            })
        });
    </script>
    {{-- google map api--}}
    <script>
        // tokyo location -- 35.689487, 139.691706
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
                        center = results[0].geometry.location;
                        map.setCenter(center);
                        var marker = new google.maps.Marker({
                            map: map,
                            position: center
                        });
                    } else {

                    }
                } else {
                }
            });
        }
        function initMap() {
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

            google.maps.event.addDomListener(window, 'resize', function() {
                map.setCenter(center);
            });

        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBB0cGGFl0rSov-OMll0p4VxeaMi7OPAF0&libraries=places&callback=initMap"
            async defer></script>
@stop