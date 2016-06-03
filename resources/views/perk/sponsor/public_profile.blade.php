@extends('layout.master')

@section('navbar')
    @include('perk.includes.navbar')
@stop

@section('header')
    @include('perk.includes.header')
@stop

@section('meta')
    <meta property="og:title" content="{{ $user->username }}" />
    <meta property="og:image" content="{{ $user->avatar_original }}" />
    <meta property="og:description" content="{{ $user->bio }}" />
@endsection

@section('content')

    <main class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-2">
                    @include('perk.includes.home_nav')
                </div>
                <div class="col-md-7">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="panel z1 text-center">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-md-12" style="border-right: 1px solid #ddd">
                                            @if($user->avatar_original)
                                                <img src="{{$user->avatar_original}}" class="profile-avatar-big img-circle">
                                            @else
                                                <img src="{{$user->avatar}}" class="profile-avatar-big img-circle">
                                            @endif

                                            <h5>{{ $user->first_name }} {{ $user->last_name }}</h5>
                                            <p>{{ $user->bio }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body text-center">
                                    {{--<div id="fb-root"></div>--}}
                                    {{--<script>(function(d, s, id) {--}}
                                    {{--var js, fjs = d.getElementsByTagName(s)[0];--}}
                                    {{--if (d.getElementById(id)) return;--}}
                                    {{--js = d.createElement(s); js.id = id;--}}
                                    {{--js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4&appId=1480160395612910";--}}
                                    {{--fjs.parentNode.insertBefore(js, fjs);--}}
                                    {{--}(document, 'script', 'facebook-jssdk'));</script>--}}

                                    <!-- Your like button code -->
                                    {{--<div class="fb-like"--}}
                                    {{--data-href="{{ route('perk::public_profile', array('username'=> $user->username)) }}"--}}
                                    {{--data-layout="standard"--}}
                                    {{--data-action="like"--}}
                                    {{--data-show-faces="true">--}}
                                    {{--</div>--}}
                                    {{----}}
                                    {{--<script src="https://apis.google.com/js/platform.js" async defer></script>--}}
                                    {{--<div class="g-plusone" data-annotation="inline"  data-width="120" data-href="{{ route('perk::public_profile', array('username'=> $user->username)) }}"></div>--}}
                                    {{----}}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-12">
                                    @if($auth->id() == $user->id)
                                        <div class="text-right" style="margin-bottom: 10px;">
                                            <button class="btn btn-primary text-right" data-toggle="modal" data-target="#create-perk">Create Perk</button>
                                        </div>
                                    @else
                                        <div class="panel z1">
                                            <div class="panel-heading text-center">
                                                <h5>Thank you for taking our perks or discounts</h5>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    @foreach($currentPerks as $perk)
                                        @include('perk.sponsor.perk', array('perk' => $perk, 'buyButtons' => $perk->user_id != $auth->id(), 'owner'=>$auth->id() == $perk->user_id))
                                    @endforeach
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    @include('perk.includes.social_networks', array('user'=>$user))
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="panel z1">
                                        <div class="panel-heading">
                                            <h5>Latest Tweet</h5>
                                        </div>
                                        <div class="panel-body">
                                            @if(!$twitterProfile)
                                                @if($user->id == $auth->id())
                                                    <a href="{{ route('auth::social::connect', array('provider' =>'twitter')) }}">Connect Twitter Account</a>
                                                @else
                                                    <p>Twitter not connected</p>
                                                @endif
                                            @endif
                                            <div id="timeline"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="panel z1">
                                        <div class="panel-heading">
                                            <h5>Previous Perks or Discount</h5>
                                        </div>
                                        <div class="panel-body">
                                            @foreach($previousPerks as $perk)
                                                @include('perk.sponsor.perk', array('perk' => $perk, 'buyButtons' => false, 'owner' => $auth->id() == $perk->user_id))
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- col-md-9 -->
                </div>
            </div>

        </div><!-- .container -->
    </main>

    @include('perk.includes.modal.create_perk', array('perkTypes' => $perkTypes, 'perk' => new \App\Perk()))
    @include('perk.includes.modal.edit_perk')
    @include('perk.includes.modal.delete_perk')

@stop

@section('scripts')


    <script src="{{url('bower_components/Chart.js/Chart.min.js')}}"></script>
    <script>
        Chart.defaults.global.responsive = true;
        Chart.defaults.global.animation = false;
        Chart.defaults.global.maintainAspectRatio = true;
    </script>

    <script src="https://platform.twitter.com/widgets.js">

    </script>

    <script>
        $(document).ready(function(){

            @if($twitterProfile)
                twttr.widgets.createTimeline(
                    '638275064203337728',
                    document.getElementById('timeline'),
                    {
                        userId : '{{ $twitterProfile->provider_id }}',
                        screenName: '{{ $twitterProfile->username }}',
//                        width: '450',
                        height: '400',
                        chrome: 'nofooter,noheader',
                        related: 'twitterdev,twitterapi',
                        tweetLimit: 1
                    }).then(function (el) {
                        console.log("Embedded a timeline.")
                    });
            @endif

            $('.edit-link').click(function(e){
                var id = $(this).data('perk-id');
                var url = '{{route('perk::home')}}'+'/perks/'+id+'/edit';
                CrowdifyAjaxService.makeRequest(url, 'GET', {}, function(response){
                    console.log(response);
                    var editPerkModal = $('#edit-perk');
                    editPerkModal.find('.modal-content').html(response.view);
                    editPerkModal.modal('show');
                }, function(error){
                    console.log(error);
                });
            });



            var deleteId = 0;

            $('.delete-link').click(function(e){
                deleteId = $(this).data('perk-id');
            });
            $('.perk-delete-button').click(function(e){

                var url = '{{route('perk::home')}}'+'/perks/'+deleteId;
                if(deleteId){
                    CrowdifyAjaxService.makeRequest(url, 'DELETE', {}, function(response){
                        location.reload();

                    }, function(error){

                    });
                }

            });

            $('#create-perk, #edit-perk').on('change','.perk-type-input', function(e){
                var perkType =  $(this).find('option:selected').text();
                if(perkType == 'Paid'){
                    $(this).closest('.modal').find('.perk-value').removeClass('hidden');
                }else{
                    $(this).closest('.modal').find('.perk-value').addClass('hidden');
                }
            })
        });
    </script>
@endsection

