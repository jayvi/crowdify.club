<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <link rel="shortcut icon" type="image/png" href="{{url('assets/images/favicon_48x48.png')}}"/>
        <link href="{{url('bower_components/fullcalendar/dist/fullcalendar.css')}}" rel='stylesheet' />
        <link href="{{url('bower_components/fullcalendar/dist/fullcalendar.print.css')}}" rel='stylesheet' media='print' />
        <meta name="google-site-verification" content="h3bRaeeQyvRtdEW0wABndIVZ4WWx__SyiETDmbFORfU" />
        @yield('meta')

        <title>Crowdify</title>

        <link rel="stylesheet" type="text/css" href="{{ elixir("css/all.css") }}">
        <link rel="stylesheet" type="text/css" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <link type="text/css" href="{{url('bower_components/video.js/dist/video-js/video-js.min.css')}}" rel="stylesheet">

        @yield('styles')
                <!-- Go to www.addthis.com/dashboard to customize your tools -->

        <!-- Go to www.addthis.com/dashboard to customize your tools -->
        <!-- Go to www.addthis.com/dashboard to customize your tools -->
        <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-563988d1466507ce" async="async"></script>
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-71960478-1', 'auto');
            ga('send', 'pageview');

        </script>

        {{--<script type="text/javascript">var switchTo5x=true;</script>--}}
        {{--<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>--}}
        {{--<script type="text/javascript" src="http://s.sharethis.com/loader.js"></script>--}}
        <div id="fb-root"></div>
        <script>(function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5&appId=714438975253479";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));</script>

    </head>
    <body style="background-color: #F7F7F7" id="body">


    @yield('navbar')

    @yield('header')

    <div id="main-content">
        @yield('content')
    </div>

    @include('includes.footer')

    @if(!$auth->check())
        @include('perk.includes.modal.login')
    @endif

    @if($auth->check() && Session::has('show_affiliate_popup') && Session::get('show_affiliate_popup',false))
        @include('perk.includes.modal.affiliate_popup');
    @endif

    @if(isset($showCrowdCoinPopup) && $showCrowdCoinPopup )
        @include('perk.includes.modal.random_crowd_coin',array('crowdCoinAmount', $crowdCoinAmount))
    @endif

            <!-- Start of crowdify Zendesk Widget script -->
        <script>/*<![CDATA[*/window.zEmbed||function(e,t){var n,o,d,i,s,a=[],r=document.createElement("iframe");window.zEmbed=function(){a.push(arguments)},window.zE=window.zE||window.zEmbed,r.src="javascript:false",r.title="",r.role="presentation",(r.frameElement||r).style.cssText="display: none",d=document.getElementsByTagName("script"),d=d[d.length-1],d.parentNode.insertBefore(r,d),i=r.contentWindow,s=i.document;try{o=s}catch(c){n=document.domain,r.src='javascript:var d=document.open();d.domain="'+n+'";void(0);',o=s}o.open()._l=function(){var o=this.createElement("script");n&&(this.domain=n),o.id="js-iframe-async",o.src=e,this.t=+new Date,this.zendeskHost=t,this.zEQueue=a,this.body.appendChild(o)},o.write('<body onload="document._l();">'),o.close()}("//assets.zendesk.com/embeddable_framework/main.js","crowdify.zendesk.com");
            /*]]>*/</script>
        <!-- End of crowdify Zendesk Widget script -->
    <script src="{{ elixir("js/all.js") }}"></script>
    <script>


        function setPageTitle(title){
            document.title = title;
        }
        function showNotificationIfHas(){
            @if(Session::has('success'))
                toastr.success("{{Session::get('success')}}", '');
            @elseif(Session::has('error'))
                toastr.error("{{Session::get('error')}}", '');
            @elseif(Session::has('warning'))
                toastr.warning("{{Session::get('warning')}}", '');
            @endif
        }

       function showAffiliatePopupIfNeeded(){
           @if(Session::has('show_affiliate_popup') && Session::get('show_affiliate_popup',false) && URL::current() != route('perk::home'))
                @if(($randNum = rand(0, 100)) > 80)
                    $('#affiliate-popup').modal('show');
                    <?php Session::forget('show_affiliate_popup') ?>
                @endif
           @endif
       }

        function showCrowdCoinPopUpIfNeeded(){
            @if(isset($showCrowdCoinPopup) && $showCrowdCoinPopup )
               $('#random-crowd-coins').modal('show');
           @endif
       }



        function updateCrowdCoin(crowdCoins){
            var crowdCoinSpan = $('#crowd-coin');
            crowdCoinSpan.text( parseFloat(Math.round((crowdCoins / 1000000) * 100) / 100).toFixed(1) +'M');
            crowdCoinSpan.closest('.crowd-coin-link').attr('data-original-title','Available Crowdify Coin Balance: '+crowdCoins);
            $('[data-toggle="tooltip"]').tooltip();
        }

        function updateBitCoins(bitCoins){
            var bitCoinSpan = $('#bit-coin');
            bitCoinSpan.text(bitCoins);
            bitCoinSpan.closest('.bit-coin-link').attr('data-original-title','Available Bit Coin Balance: '+bitCoins);
            $('[data-toggle="tooltip"]').tooltip();
        }

        function updateBothCoins(crowdCoins,bitCoins){
            updateCrowdCoin(crowdCoins);
            updateBitCoins(bitCoins);
        }

        function linkify(element){
            element.linkify({
                target: "_blank"
            });
        }

        $(document).ready(function(){

            showNotificationIfHas();
            showAffiliatePopupIfNeeded();
            showCrowdCoinPopUpIfNeeded();


            $(document).on('click','.item-toast-info', function(e){
                e.preventDefault();
                toastr.info( $(this).data('info'),'Caution');
            });

            function getLoginInput(){
                return {
                    email: $('#modal-login-email').val(),
                    password : $('#modal-login-password').val()
                }
            }

            $('#modal-login-btn').click(function(){
                var data = getLoginInput();
                if(!data.email || !data.password){
                    toastr.error( 'Please enter credentials Correctly','Input filed Required');
                    return;
                }
                else if(!validateEmail(data.email)){
                    toastr.error( 'Please enter a valid Email Address','Email Required');
                    return;
                }else{
                    var url = '{{ route('auth::login') }}';
                    CrowdifyAjaxService.makeRequest(url, 'POST', data, function(){
                        location.reload();
                    }, function(){});
                }
            });

            //Tooltip
            $(function () {
                $('[data-toggle="tooltip"]').tooltip();
            })

            $(function() {

                var settings = {
                    // these are required:
                    suggestUrl: '{{route('site::search').'?search='}}', // the URL that provides the data for the suggest
                    // these are optional:
                    instantVisualFeedback: 'all', // where the instant visual feedback should be shown, 'top', 'bottom', 'all', or 'none', default: 'all'
                    throttleTime: 300, // the number of milliseconds before the suggest is triggered after finished input, default: 300ms
                    extraHtml: undefined, // extra HTML code that is shown in each search suggest
                    highlight: true, // whether matched words should be highlighted, default: true
                    queryVisualizationHeadline: '', // A headline for the image visualization, default: empty
                    animationSpeed: 300, // speed of the animations, default: 300ms
                    enterCallback: undefined, // callback on what should happen when enter is pressed, default: undefined, meaning the link will be followed
                    minChars: 3, // minimum number of characters before the suggests shows, default: 3
                    maxWidth: 400 // the maximum width of the suggest box, default: as wide as the input box
                };

                // apply the settings to an input that should get the unibox
           //     var search = $("#searchInput");
//                search.unibox(settings);
//
//                var suggestBox = $('#unibox-suggest-box');
//                var borderSize = suggestBox.css('border-width').replace('px','');
//                suggestBox.css('min-width', 2.5 * search.outerWidth()-2*borderSize);
//                suggestBox.css('max-width', 2.5 * settings.maxWidth-2*borderSize);
            });

            $(document).on( "mouseenter mouseleave", '.btn-following', function(e){
                if(e.type == 'mouseenter'){
                    $(this).removeClass('btn-success');
                    $(this).addClass('btn-danger');
                    $(this).html('&nbsp;Unfollow&nbsp;');
                }else{
                    $(this).removeClass('btn-danger');
                    $(this).addClass('btn-success');
                    $(this).text('Following');
                }
            });

            $(document).on( "click", '.btn-following, .btn-follow', function(e){
                var button = $(this);
                var data = {
                    userId : button.data('user-id')
                }
                if($(this).hasClass('btn-follow')){
                    var url = '{{route('profile::follow')}}';
                    CrowdifyAjaxService.makeRequest(url, 'POST', data, function(response){
                        button.closest('.btn-group').find('.btn-follower-count').text(response.followerCount);
                        button.replaceWith('<button class="btn btn-success btn-following"  data-user-id="'+response.user.id+'">Following</button>');

                    },function(error){

                    })
                }else{
                    var url = '{{route('profile::unfollow')}}';
                    CrowdifyAjaxService.makeRequest(url, 'POST', data, function(response){
                        button.closest('.btn-group').find('.btn-follower-count').text(response.followerCount);
                        button.replaceWith(' <button class="btn btn-primary btn-follow" data-user-id="'+response.user.id+'">&nbsp;&nbsp;Follow&nbsp;&nbsp;&nbsp;&nbsp;</button>');
                    },function(error){

                    })
                }
            });

            $(document).on( "click", '.btn-un-block, .btn-do-block', function(e){
                var button = $(this);
                var data = {
                    userId : button.data('user-id')
                }

                if($(this).hasClass('btn-do-block')){
                    var url = '{{route('profile::block')}}';
                    CrowdifyAjaxService.makeRequest(url, 'POST', data, function(response){
                        button.replaceWith('<button class="btn btn-danger btn-un-block"  data-user-id="'+response.user.id+'">Unblock</button>');
                    },function(error){

                    })
                }else{
                    var url = '{{route('profile::un-block')}}';
                    CrowdifyAjaxService.makeRequest(url, 'POST', data, function(response){
                        button.replaceWith('<button class="btn btn-default btn-do-block"  data-user-id="'+response.user.id+'">Block</button>');
                    },function(error){

                    })
                }

            });

            linkify($('[data-linkify]'));


        })
    </script>

    <script src="/js/search.js"></script>
    {{--<script type="text/javascript">stLight.options({publisher: "a851fb8e-fb4e-47da-a3d5-3559cd02bf60", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>--}}
    {{--<script>--}}
        {{--var options={ "publisher": "a851fb8e-fb4e-47da-a3d5-3559cd02bf60", "position": "left", "ad": { "visible": false, "openDelay": 5, "closeDelay": 0}, "chicklets": { "items": ["facebook", "twitter", "googleplus", "linkedin", "pinterest", "email", "sharethis"]}};--}}
        {{--var st_hover_widget = new sharethis.widgets.hoverbuttons(options);--}}
    {{--</script>--}}
    @yield('scripts')




    </body>
</html>
