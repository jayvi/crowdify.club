@extends('layout.master')

@section('navbar')
    @include('perk.includes.navbar')
@stop

@section('header')
    @include('perk.includes.header')
@stop

@section('meta')
    <meta property="og:url" content="{{ Request::url() }}" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{ $user->username }}" />
    <meta property="og:image" content="{{ $user->avatar_original }}" />
    <meta property="og:description" content="{{ $user->bio }}" />
@endsection

@section('content')

    <main class="content">
        <div class="col-md-12">
        <div class="col-md-12 panel z1 text-center">

    <div class="col-md-2">
        <?php
        use Jenssegers\Agent\Agent as Agent;
        $Agent = new Agent();
        ?>
        <?php
        if ($Agent->isMobile()) {

        }
        else {
            ?>

                    <h4>Latest Tweet</h4>
                    @if(!$twitterProfile)
                        @if($user->id == $auth->id())
                            <a href="{{ route('auth::social::connect', array('provider' =>'twitter')) }}">Connect Twitter Account</a>
                        @else
                            <p>Twitter not connected</p>
                        @endif
                    @endif
                    <div id="timeline"></div>
            <?php } ?>
    </div>
                <div class="col-md-8">
            @if($user->avatar_original)
                <img src="{{$user->avatar_original}}" style="max-width: 100%">
            @else
                <img src="{{$user->avatar}}">
            @endif
                @include('perk.includes.social_networks', array('user'=>$user))
                <div class="addthis_native_toolbox" style="margin: 10px"></div>
                <h2>{{ $user->first_name }} {{ $user->last_name }}  <img src="{{url('assets/images/'.$membericon.'')}}" width="40px"></h2>
                <h3>
                    @if($user->interest)
                        {{ $user->interest }},
                    @endif
                    @if($user->category_1)
                        {{$user->category_1}},
                    @endif
                    @if($user->category_2)
                        {{$user->category_2}},
                    @endif
                    @if($user->city)
                        {{$user->city}}
                    @endif
                </h3>
                <h4>{{ $user->bio }}</h4>
                <div class="row">
                @if($user->id != $auth->id())
                @if($auth->check())
                    <button class="btn btn-default-outline btn-gift" id="btn-gift-points">Gift CrowdifyPoints</button>
                @else
                    <button class="btn btn-default-outline btn-gift" id="btn-gift-points" data-toggle="modal" data-target="#modal-auth">Gift CrowdifyPoints</button>
                @endif
                @if($auth->check())
                    <button class="btn btn-default-outline btn-gift" id="btn-gift-coin">Gift CrowdifyCoin</button>
                @else
                    <button class="btn btn-default-outline btn-gift" id="btn-gift-coin" data-toggle="modal" data-target="#modal-auth">Gift CrowdifyCoin</button>
                @endif

                @if($auth->check())
                    <button class="btn btn-default-outline btn-gift" id="btn-gift-bitcoin">Gift Bitcoin</button>
                @else
                    <button class="btn btn-default-outline btn-gift" id="btn-gift-bitcoin" data-toggle="modal" data-target="#modal-auth">Gift Bitcoin</button>
                @endif
                @if($auth->check())
                    @if($auth->user()->isFollowing($user->id))
                        <button class="btn btn-success btn-following"  data-user-id="{{$user->id}}">Following</button>
                    @else
                        <button class="btn btn-primary btn-follow" data-user-id="{{$user->id}}">&nbsp;&nbsp;Follow&nbsp;&nbsp;&nbsp;&nbsp;</button>
                    @endif
                @else
                    <button class="btn btn-default-outline" id="btn-follow" data-toggle="modal" data-target="#modal-auth">&nbsp;&nbsp;Follow&nbsp;&nbsp;&nbsp;&nbsp;</button>
                @endif

                <button class="btn btn-default btn-follower-count">{{count($user->followers)}}</button>
                @if($auth->check())
                    @if($auth->user()->isAdmin() && $auth->user()->id != $user->id)
                        @if($user->is_blocked)
                            <button class="btn btn-danger btn-un-block"  data-user-id="{{$user->id}}">Unblock</button>
                        @else
                            <button class="btn btn-default btn-do-block"  data-user-id="{{$user->id}}">Block</button>
                        @endif

                    @endif
                @endif
                @endif
                </div>
                <div class="row">
                <div class="image-upload col-md-4">
                    <h3>{{ $myIdea->value }}</h3>
                    <img class="item-image" src="{{$myIdea->photo ? $myIdea->photo : '/assets/images/placeholder.png'}}" alt="">
                    @if($auth->id() == $user->id)
                        <a href="" class="upload-link">{{$myIdea->photo ? 'Change Photo':'Upload Photo'}}</a>
                        <input type="file" class="hidden" data-item-id="{{$myIdea->id}}" accept="image/*">
                        <div class="progress hidden">
                            <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                                0%
                            </div>
                        </div>
                    @endif
                </div>
                @foreach($items as $item)
                    <div class="col-md-4">
                        @include('perk.includes.item_box', array('item' => $item))
                    </div>
                @endforeach
                </div>
                <?php
                if ($Agent->isMobile()) { ?>
                <h4>Latest Tweet</h4>

                @if(!$twitterProfile)
                    @if($user->id == $auth->id())
                        <a href="{{ route('auth::social::connect', array('provider' =>'twitter')) }}">Connect Twitter Account</a>
                    @else
                        <p>Twitter not connected</p>
                    @endif
                @endif
                <div id="timeline"></div>
                <?php
                }
                ?>
                @if($user->id == $auth->id())
                    <div class="panel z1 status-form" >
                        <div class="panel-body">
                            <textarea name="status" id="status-textarea" rows="1" class="form-control" placeholder="Whats new today?"></textarea>
                            <button type="button" class="btn btn-default-outline pull-right" id="btn-create-status">Post</button>
                        </div>
                    </div>
                @endif
                <div id="entities" class="align-left margin-top10">
                    @foreach($entities as $entity)
                        @if($entity instanceof \App\Perk)
                            @include('perk.includes.perk', array('perk' => $entity))
                        @elseif($entity instanceof \App\Statusupdate)
                            @include('perk.includes.status', array('status' => $entity))
                        @elseif($entity instanceof \App\Hug)
                            @include('perk.includes.hug', array('hug' => $entity))
                        @elseif($entity instanceof \App\Event)
                            @include('event.includes.event', array('event' => $entity))
                        @elseif($entity instanceof \App\BlogPost)
                            @include('blog.includes.blog_post', array('blog' => $entity))
                        @endif
                    @endforeach
            </div>
        </div>
            <div class="col-md-2">
                @foreach($user->items as $item)
                    <div class="item-box">
                        @include('perk.includes.item_box', array('item' => $item))
                    </div>
                    <br>
                @endforeach
                    @if($user->youtube)



                        <iframe width="100%" height=""
                                src="https://www.youtube.com/embed/{{$user->youtube}}"
                                frameborder="0" allowfullscreen></iframe>


                    @endif
            </div>

    </div>
        </div>



    </main>
    @if($auth->id() != $user->id)
        @include('perk.includes.modal.send_points', array('user' => $user))
        @include('perk.includes.modal.send_gift', array('user' => $user))
        @include('perk.includes.modal.send_bitcoin', array('user' => $user))
    @endif
    @if($auth->check())
        @include('perk.includes.modal.invest')
        @include('perk.includes.modal.sell')
    @endif

    @if($notification && $showCrowdCoinsGiftedModal)
        @include('perk.includes.modal.tweet_to_thank_gifter', array('sender'=>$notification->sender))
    @endif

@stop

@section('scripts')

    @include('perk.includes.scripts.status')
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
                        tweetLimit: 3
                    }).then(function (el) {
                        console.log("Embedded a timeline.")
                    });
            @endif
            function limitCharacter(id){
                console.log('limit chgaracter');
                remainingCharacters = 140 - twttr.txt.getTweetLength($('#'+id).val());
                var text = $('.remaining-character');
                if(remainingCharacters < 0){
                    text.addClass('text-danger');
                    text.removeClass('text-info');
                }else{
                    text.removeClass('text-danger');
                    text.addClass('text-info');
                }
                text.text('Remaining characters '+ remainingCharacters);
            }

            @if($notification && $showCrowdCoinsGiftedModal)
                $('#modal-thank-you-gift-tweet').modal('show');
            limitCharacter('thank-you-tweet');
            @endif

           function makeAjaxRequest(url, data, method, successCallback, errorCallback){
                $.ajax({
                    url: url,
                    data: data,
                    method: method
                }).done(function(response){
                    successCallback(response);
                }).fail(function(response){
                    errorCallback(response);
                });
            }

//            var ctx = $("#chart").get(0).getContext("2d");
//            var data = {
//                labels: ["January", "February", "March", "April", "May", "June", "July"],
//                datasets: [
//                    {
//                        label: "My First dataset",
//                        fillColor: "rgba(220,220,220,0.2)",
//                        strokeColor: "rgba(220,220,220,1)",
//                        pointColor: "rgba(220,220,220,1)",
//                        pointStrokeColor: "#fff",
//                        pointHighlightFill: "#fff",
//                        pointHighlightStroke: "rgba(220,220,220,1)",
//                        data: [65, 59, 80, 81, 56, 55, 40]
//                    },
//                    {
//                        label: "My Second dataset",
//                        fillColor: "rgba(151,187,205,0.2)",
//                        strokeColor: "rgba(151,187,205,1)",
//                        pointColor: "rgba(151,187,205,1)",
//                        pointStrokeColor: "#fff",
//                        pointHighlightFill: "#fff",
//                        pointHighlightStroke: "rgba(151,187,205,1)",
//                        data: [28, 48, 40, 19, 86, 27, 90]
//                    }
//                ]
//            };
//            var myLineChart = new Chart(ctx).Line(data,  {
//                bezierCurve: false
//            });

            var itemId;
            var box;
            var remainingCharacters = 140;
            var shareAmount;
            var itemId;

            function getInputs(isTweet){
                var data = {
                    item_id: itemId,
                    amount: $('#amount').val(),
                }
                if(isTweet){
                    data.tweet = $('#tweet').val()
                }
                return data;
            }



            $('#tweet').on('input',function(e){
                limitCharacter('tweet');
            });
            $('#thank-you-tweet').on('input',function(e){
                limitCharacter('thank-you-tweet');
            });
            $('#gift-tweet').on('input',function(e){
                limitCharacter('gift-tweet');
            });
            $('#bitcoin-gift-tweet').on('input',function(e){
                limitCharacter('bitcoin-gift-tweet');
            });

//            Invest and sell
            $('.item-box').on('click','.btn-invest',function(e){
                itemId = $(this).data('item-id');
                var price = $(this).data('price');
                box = $(this).closest('.item-box');
                $('#invest-header').text('Invest in {{ $user->username }} at '+price );
                $('#modal-invest').modal('show');
                limitCharacter('tweet');
            });

            $('#thank-you-tweet-btn').click(function(){
                var data = {
                    tweet: $('#thank-you-tweet').val()
                };
                if(!data.tweet){
                    toastr.warning('Please enter tweet text')
                    return;
                }
                if(140 - twttr.txt.getTweetLength(data.tweet) < 0){
                    toastr.warning('Sorry that tweet is too long please remove some characters.');
                    return;
                }
                var url = '{{ route('profile::tweet') }}';

                CrowdifyAjaxService.makeRequest(url, 'POST', data, function(reswponse){
                    $('#modal-thank-you-gift-tweet').modal('hide');
                }, function(error){})

            });

            $('#buy-share, #btn-tweet').click(function(e){
                var data, isTweet = false;
                if($(this).hasClass('tweet')){
                    isTweet = true;
                }else{
                    isTweet = false;
                }
                data = getInputs(isTweet);
                if(isTweet && !data.tweet){
                    toastr.warning('Please enter tweet text')
                    return;
                }
                if(isTweet){
                    if(140 - twttr.txt.getTweetLength($('#tweet').val()) < 0){
                        toastr.warning('Sorry that tweet is too long please remove some characters');
                        return;
                    }
                }


                var url = '{{ route('profile::item::invest') }}';

                makeAjaxRequest(url, data, 'POST', function(response){
                    if(response.success){
                        toastr.success('Successfully invested','Investment');
//                       box.find('.share-price').text(response.item.share_price + ' Crowdify Coins');
//                       box.find('.invest-btn').replaceWith('<button class="btn btn-default-outline btn-invested item-toast-info" data-info="You have already invested on this item. Try next month.">Invested</button>');
//                       box.find('.sell-btn').replaceWith('<button class="btn btn-default-outline btn-sell sell-btn" data-amount="'+response.share.amount+'" data-item-id="'+response.item.id+'">Sell</button>');
                        box.html(response.view);
                        $('#modal-invest').modal('hide');
                        updateCrowdCoin(response.bank.crowd_coins);
                    }else{
                        toastr.error(response.message);
                    }
                }, function(response){
                    toastr.warning('Bad request','Check your internet connection');
                });
            });


            $('.item-box').on('click','.btn-sell',function(e){

                shareAmount = $(this).data('amount');
                itemId = $(this).data('item-id');
                box = $(this).closest('.item-box');
                $('#sold-share-amount-text').text('Are you sure you want to sell '+shareAmount+ ' Share?')
                $('#modal-sell').modal('show');
            });

            $('#sell-btn-modal').click(function(){

                var url = '{{ route('profile::item::sell') }}';
                var data = {
                    item_id: itemId,
                    share_amount: shareAmount
                }

                makeAjaxRequest(url, data, 'POST', function(response){
                    if(response.success){
                        toastr.success('Successfully Sold', 'Your share has been successfully sold');
                        box.html(response.view);
                        $('#modal-sell').modal('hide');
                        updateCrowdCoin(response.bank.crowd_coins);

                    }else{
                        toastr.error('Error', response.message);
                    }
                }, function(response){
                    toastr.error('Error','Something wrong');
                })
            });

            // gift coin
            @if($auth->check())
                    $('#btn-gift-points').click(function(e){
                        $('#modal-send-points').modal('show');
                        limitCharacter('gift-tweet');
                    });
                    $('#btn-gift-coin').click(function(e){
                        $('#modal-send-gift').modal('show');
                        limitCharacter('gift-tweet');
                    });
                    $('#btn-gift-bitcoin').click(function(e){
                        $('#modal-send-bitcoin').modal('show');
                        limitCharacter('gift-tweet');
                    });
            @endif
            $('#button-send-point, #btn-gift-tweet').click(function(){
                var data = {};
                if($(this).hasClass('tweet')){
                    data = {
                        username : $('#send-point-username').val(),
                        amount : $('#send-point-amount').val(),
                        tweet: $('#gift-tweet').val()
                    }
                    if(!data.tweet){
                        toastr.warning('Please Enter Tweet Text');
                        return;
                    }
                    if(140 - twttr.txt.getTweetLength(data.tweet) < 0){
                        toastr.warning('Sorry that tweet is too long please remove some characters.');
                        return;
                    }
                }else{
                    data = {
                        username : $('#send-point-username').val(),
                        amount : $('#send-point-amount').val()
                    }
                }
                if(!data.amount){
                    toastr.warning('Please enter gift amount.');
                    return;
                }

                var url = '{{ route('profile::sendPoints') }}';

                CrowdifyAjaxService.makeRequest(url, 'POST', data, function(response){
                            $('#modal-send-point').modal('hide');
                            console.log(response.bank);
                            updateCrowdCoin(response.bank.crowd_coins);
                        },
                        function(error){

                        });
            });
            $('#button-send-gift, #btn-gift-tweet').click(function(){
                        var data = {};
                        if($(this).hasClass('tweet')){
                            data = {
                                username : $('#send-gift-username').val(),
                                amount : $('#send-gift-amount').val(),
                                tweet: $('#gift-tweet').val()
                            }
                            if(!data.tweet){
                                toastr.warning('Please Enter Tweet Text');
                                return;
                            }
                            if(140 - twttr.txt.getTweetLength(data.tweet) < 0){
                                toastr.warning('Sorry that tweet is too long please remove some characters.');
                                return;
                            }
                        }else{
                            data = {
                                username : $('#send-gift-username').val(),
                                amount : $('#send-gift-amount').val()
                            }
                        }
                        if(!data.amount){
                            toastr.warning('Please enter gift amount.');
                            return;
                        }

                        var url = '{{ route('profile::sendGift') }}';

                        CrowdifyAjaxService.makeRequest(url, 'POST', data, function(response){
                                    $('#modal-send-gift').modal('hide');
                                    console.log(response.bank);
                                    updateCrowdCoin(response.bank.crowd_coins);
                                },
                                function(error){

                                });
                    });
            $('#button-send-bitcoin, #btn-bitcoin-tweet').click(function(){
                var data = {};
                if($(this).hasClass('tweet')){
                    data = {
                        username : $('#send-bitcoin-username').val(),
                        amount : $('#send-bitcoin-amount').val(),
                        tweet: $('#bitcoin-gift-tweet').val()
                    }
                    if(!data.tweet){
                        toastr.warning('Please Enter Tweet Text');
                        return;
                    }
                    if(140 - twttr.txt.getTweetLength(data.tweet) < 0){
                        toastr.warning('Sorry that tweet is too long please remove some characters.');
                        return;
                    }
                }else{
                    data = {
                        username : $('#send-bitcoin-username').val(),
                        amount : $('#send-bitcoin-amount').val()
                    }
                }
                if(!data.amount){
                    toastr.warning('Please enter gift amount.');
                    return;
                }

                var url = '{{ route('bitcoin::sendGift') }}';

                CrowdifyAjaxService.makeRequest(url, 'POST', data, function(response){
                            $('#modal-send-bitcoin').modal('hide');
                           window.location.reload();
                        },
                        function(error){

                        });
            });

            $('.image-upload').on('click','.upload-link', function(event){
                event.preventDefault();
                $(this).closest('.image-upload').find('input[type=file]').trigger('click');
            });
            $('.image-upload').on('change','input[type=file]' ,uploadImage);

            function uploadImage(event){

                var imageUpload = $(this).closest('.image-upload');

                var itemId = $(this).data('item-id');
                var files = event.target.files;
                console.log(files);
                var data = new FormData();
                $.each(files, function(key, value)
                {
                    data.append('photo', value);
                });

                console.log(data);
                var progressDiv = imageUpload.find('.progress');

                var url = '{{route('perk::home')}}'+'/profile/'+itemId+'/upload-photo';
                CrowdifyAjaxService.uploadImage(url, data,function(progress){

                    progressDiv.removeClass('hidden');
                    progressDiv.children().css('width',Math.floor(progress * 100)+'%');
                    progressDiv.children().text(Math.floor(progress * 100)+'%')

                } ,function(response){
                    progressDiv.children().css('width', 100+'%');
                    progressDiv.children().text('100%');
                    progressDiv.addClass('hidden');
                    console.log(response);
                    imageUpload.find('img').attr('src', response.photo);
                }, function(error){
                    console.log(error);
                });
            }


        });
    </script>
@endsection

