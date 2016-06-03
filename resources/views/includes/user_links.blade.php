<ul class="nav navbar-nav navbar-right">
    <li><a href="{{ route('perk::home') }}"><img class="invert" src="{{url('assets/images/fahome.png')}}"></a></li>
    @if($auth->check())
        {{--<li><a href="{{ route('leaderboard::home') }}" title="Leaderboard"><i class="fa fa-trophy"></i></a></li>--}}
        <li class="dropdown">
            <?php $notifications = $auth->user()->unseenNotifications() ?>
            <a title="Notifications" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><img class="invert" src="{{url('assets/images/fabell.png')}}">
                @if(count($notifications) > 0)
                    <span class="rednote"></span>
                @endif
            </a>

            <ul class="dropdown-menu">
                @if( count($notifications) > 0)
                    @foreach($notifications as $notification)
                        <li>
                            @if($notification->type == 'COMMENT')
                                <a href="{{$notification->target_link}}">
                                    <p ><strong>{{ $notification->title }}:</strong>&nbsp;{{ str_replace('%%username%%', $notification->sender->username, $notification->description) }}</p>
                                </a>
                            @else
                                <a href="{{route('perk::public_profile', array('username'=> $notification->sender->username)).'?n='.$notification->id}}">
                                    <p ><strong>{{ $notification->title }}:</strong>&nbsp;{{ str_replace('%%username%%', $notification->sender->username, $notification->description) }}</p>
                                </a>
                            @endif
                        </li>
                    @endforeach
                @else
                    <li>
                        <a href="#"><p><strong>No events at this time</strong></p></a>
                    </li>
                @endif
            </ul>
        </li>


        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                <img class="invert" src="{{url('assets/images/facog.png')}}">
            </a>
            <ul class="dropdown-menu">
                <li><a href="{{ route('profile::edit') }}">Edit Profile</a></li>
                <li><a href="{{ route('profile::opt-out') }}">Delete My Account</a></li>
                <li><a href="{{ route('auth::logout') }}">Sign-out</a></li>
            </ul>
        </li>

        @else
            <li><a href="{{route('auth::register')}}"  class="auth signup" >Sign Up</a></li>
            <li><a href="{{route('auth::login')}}"  class="auth login" >Login</a></li>
        @endif
</ul>