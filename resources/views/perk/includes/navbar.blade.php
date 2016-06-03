<?php
if ($auth->check()) {
    $notifications = $auth->user()->unseenNotifications();
} else {
    $notifications = null;
}
?>
<?php
use Jenssegers\Agent\Agent as Agent;
$Agent = new Agent();
?>
<?php
if ($Agent->isMobile()) {
    ?>
<nav class="navbar navbar-custom2">
    <div class="navbar-header">

        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#site-nav" aria-expanded="false">
            <span class="glyphicon glyphicon-align-justify" aria-hidden="true"></span>
        </button>
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#site-nav2" aria-expanded="false">
            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
        </button>
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#site-nav3"
                aria-expanded="false">
            <span class="glyphicon glyphicon-bell" aria-hidden="true">
                @if(count($notifications) > 0)
                    <span class="badge">{{count($notifications)}}</span>
                @endif
            </span>
        </button>
        <a class="navbar-brand" href="{{ route('perk::home') }}"><img src="{{url('assets/images/logowhite.png')}}"></a>
    </div>

    <div class="collapse navbar-collapse navbar-mobile" id="site-nav3">
        <ul class="nav navbar-nav">
            @if($auth->check())
            @foreach($notifications as $notification)
                <li>
                    @if($notification->type == 'COMMENT')
                        <a href="{{$notification->target_link}}">
                            <p><strong>{{ $notification->title }}
                                    :</strong>&nbsp;{{ str_replace('%%username%%', $notification->sender->username, $notification->description) }}
                            </p>
                        </a>
                    @else
                        <a href="{{route('perk::public_profile', array('username'=> $notification->sender->username)).'?n='.$notification->id}}">
                            <p><strong>{{ $notification->title }}
                                    :</strong>&nbsp;{{ str_replace('%%username%%', $notification->sender->username, $notification->description) }}
                            </p>
                        </a>
                    @endif
                </li>
            @endforeach
            @endif
        </ul>
    </div>
    <div class="collapse navbar-collapse navbar-mobile" id="site-nav2">
        <ul class="nav navbar-nav">
            @if($auth->check())
                @include('includes.search_box')
            @endif
        </ul>
    </div>
    <div class="collapse navbar-collapse navbar-mobile" id="site-nav">
        <ul class="nav navbar-nav">
            @if($auth->check())
                <li>
                    <a href="{{ route('profile::bank') }}" class="crowd-coin-link bold" data-toggle="tooltip" data-placement="bottom" title="Available Crowdify Point Balance: {{ $auth->user()->bank()->first()->crowd_coins }}">
                        @if($auth->user()->bank()->first()->crowd_coins < 10000000)
                            <span id="crowd-point" class="crowd-coin bold">&nbsp{{ round(($auth->user()->bank()->first()->crowd_coins/1000), 1) }}K</span>
                        @else
                            <span id="crowd-point" class="crowd-coin bold">&nbsp{{ round(($auth->user()->bank()->first()->crowd_coins/1000000), 1) }}M</span>
                        @endif
                    </a>
                </li>
                <hr class="navhr"></hr>
                <li>
                    <a href="{{ route('profile::bank') }}" class="crowd-coin-link bold" data-toggle="tooltip" data-placement="bottom" title="Available Crowdify Coin Balance: {{ $auth->user()->bank()->first()->seed_coins }}">
                            <span id="crowd-coin" class="crowd-coin bold">&nbsp{{$auth->user()->bank()->first()->seed_coins}}</span>
                    </a>
                </li>
                <hr class="navhr"></hr>
                <li>
                    <a href="{{ route('profile::bank') }}" class="bit-coin-link bold" data-toggle="tooltip" data-placement="bottom" title="Available Bit Coin Balance: {{ $auth->user()->wallet ? ($auth->user()->wallet->balance.' BTC') : '0.00 BTC' }}">
                        <span id="bit-coin" class="bit-coin">
                            @if($auth->user()->wallet)
                                <?php echo '&nbsp'.$auth->user()->wallet->balance ?>
                            @else
                                <?php echo "&nbsp0.00" ?>
                            @endif
                        </span>

                    </a>
                </li>
                <hr class="navhr"></hr>
                <li>
                    <a href="{{ route('perk::public_profile',array('username' => $auth->user()->username)) }}"><img src="{{ $auth->user()->avatar ? $auth->user()->avatar : '' }}" class="img-circle img-profile">&nbsp {{$auth->user()->username}}</a>
                </li>
                <hr class="navhr"></hr>
                <li><span class="glyphicon-nav glyphicon glyphicon-home" aria-hidden="true"><a class="nav-text" href="{{ route('perk::home') }}">&nbspHome</a></li>
            @endif
                <hr class="navhr"></hr>
                <li><span class="glyphicon-nav glyphicon glyphicon-picture" aria-hidden="true"><a class="nav-text" href="{{ route('talent::home') }}">&nbspTalent</a></li>

                <hr class="navhr"></hr>
                <li><span class="glyphicon-nav glyphicon glyphicon-blackboard" aria-hidden="true"><a class="nav-text" href="{{ route('perk::tasks') }}">&nbspTasks</a></span></li>
                <hr class="navhr"></hr>
                <li><span class="glyphicon-nav glyphicon glyphicon-book" aria-hidden="true"><a class="nav-text" href="{{ route('blog::home') }}">&nbspBlog</a></li>

                <hr class="navhr"></hr>
                <li><span class="glyphicon-nav glyphicon glyphicon-star" aria-hidden="true"><a class="nav-text" href="{{ route('perk::perks') }}">&nbspPerks</a></li>

                <hr class="navhr"></hr>
                <li><span class="glyphicon-nav glyphicon glyphicon-flag" aria-hidden="true"><a class="nav-text" href="{{ route('event::home') }}">&nbspEvents</a></li>

                <hr class="navhr"></hr>
                <li><span class="glyphicon-nav glyphicon glyphicon-road" aria-hidden="true"><a class="nav-text" href="{{route('places::home')}}">&nbspCoworking </a></li>

                <hr class="navhr"></hr>
                <li><span class="glyphicon-nav glyphicon glyphicon-globe" aria-hidden="true"><a class="nav-text" href="{{route('cities::home')}}">&nbspCities </a></li>

                <hr class="navhr"></hr>
                <li><span class="glyphicon-nav glyphicon glyphicon-wrench" aria-hidden="true"><a class="nav-text" href="{{route('Tools::home')}}">&nbspTools</a></li>

                <hr class="navhr"></hr>
                <li><span class="glyphicon-nav glyphicon glyphicon-bitcoin" aria-hidden="true"><a class="nav-text" href="http://shop.crowdify.tech">&nbspTech Shop</a></li>
                <hr class="navhr"></hr>

                <li><span class="glyphicon-nav glyphicon glyphicon glyphicon-comment" aria-hidden="true"><a class="nav-text" href="{{ route('subscriptions::home') }}">Become a Premium Member</></li>
                <hr class="navhr"></hr>
                <li><span class="glyphicon-nav glyphicon glyphicon-question-sign" aria-hidden="true"><a class="nav-text" href="{{ route('howto::videos') }}">&nbspHow to</a></li>
                <hr class="navhr"></hr>
                @if($auth->check())
                    <li><span class="glyphicon-nav glyphicon glyphicon-edit" aria-hidden="true"><a class="nav-text"
                                                                                                   href="{{ route('profile::edit') }}">&nbspEdit
                                Profile</a></li>
                    <hr class="navhr"></hr>
                    <li><span class="glyphicon-nav glyphicon glyphicon-remove" aria-hidden="true"><a class="nav-text"
                                                                                                     href="{{ route('profile::opt-out') }}">&nbspDelete
                                My Account</a></li>
                    <hr class="navhr"></hr>
                    <li><span class="glyphicon-nav glyphicon glyphicon-off" aria-hidden="true"><a class="nav-text"
                                                                                                  href="{{ route('auth::logout') }}">&nbspSign-out</a>
                    </li>
                @else
                    <li><span class="glyphicon-nav glyphicon glyphicon-user" aria-hidden="true"><a class="nav-text"
                                                                                                   href="{{route('auth::register')}}"
                                                                                                   class="auth signup">&nbspSign
                                Up</a></li>
                    <hr class="navhr"></hr>
                    <li><span class="glyphicon-nav glyphicon glyphicon-log-in" aria-hidden="true"><a class="nav-text"
                                                                                                     href="{{route('auth::login')}}"
                                                                                                     class="auth login">&nbspLogin</a>
                    </li>
                @endif
        </ul>


    </div>
</nav>
    <?php
}
else {
    ?>
<nav class="navbar navbar-custom">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#site-nav" aria-expanded="false">
            <span class="glyphicon glyphicon-align-justify" aria-hidden="true"></span>
        </button>
        <a class="navbar-brand" href="{{ route('perk::home') }}"><img class="navlogo" src="{{url('assets/images/logowhite.png')}}"></a>
    </div>

    <div class="collapse navbar-collapse" id="site-nav">
        <ul class="nav navbar-nav">
            @if($auth->check())
                @include('includes.search_box')
                <li>
                    <a href="{{ route('profile::bank') }}" class="crowd-coin-link bold" data-toggle="tooltip" data-placement="bottom" title="Available Crowdify Points Balance: {{ $auth->user()->bank()->first()->crowd_coins }}">
                        @if($auth->user()->bank()->first()->crowd_coin < 10000000)
                            <span id="crowd-coin" class="crowd-point bold">{{ round(($auth->user()->bank()->first()->crowd_coins/1000), 1) }}K</span>
                        @else
                            <span id="crowd-coin" class="crowd-point bold">{{ round(($auth->user()->bank()->first()->crowd_coins/1000000), 1) }}M</span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('profile::bank') }}" class="crowd-coin-link bold" data-toggle="tooltip" data-placement="bottom" title="Available Crowdify Coins Balance: {{ $auth->user()->bank()->first()->seed_coins }}">
                            <span id="crowd-coin" class="crowd-coin bold">{{ $auth->user()->bank()->first()->seed_coins }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('profile::bank') }}" class="bit-coin-link bold" data-toggle="tooltip" data-placement="bottom" title="Available Bit Coin Balance: {{ $auth->user()->wallet ? ($auth->user()->wallet->balance.' BTC') : '0.00 BTC' }}">
                        <span id="bit-coin" class="bit-coin bold">
                            @if($auth->user()->wallet)
                                {{ $auth->user()->wallet->balance }}
                            @else
                                0.00
                            @endif
                        </span>

                    </a>
                </li>
                <li>
                    <a href="{{ route('perk::public_profile',array('username' => $auth->user()->username)) }}" style="display: inline; padding: 0px;"> <span class="navpro" style="background:url({{ $auth->user()->avatar ? $auth->user()->avatar : url('assets/images/default_profile_pic.jpg') }}) no-repeat center"> </span></a>
                    <a class="bold navprohref" href="{{ route('perk::public_profile',array('username' => $auth->user()->username)) }}"> {{$auth->user()->username}}</a>
                </li>
            @endif
        </ul>

        @include('includes.user_links')

    </div>
</nav>
    <?php
}
?>



