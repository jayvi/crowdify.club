<nav class="navbar navbar-fixed-top navbar-custom z2">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#site-nav" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ route('perk::home') }}">Crowdify</a>
        </div>

        <div class="collapse navbar-collapse" id="site-nav">
            <ul class="nav navbar-nav">
                <li><a href="{{ route('blog::home') }}">Home</a></li>
                @if($auth->check())
                    <li><a href="{{ route('blog::create') }}">Create Blogs</a></li>
                    <li><a href="{{ route('blog::my-blogs') }}">My Blogs</a></li>
                @endif
            </ul>

            <ul class="nav navbar-nav navbar-right">
                @if($auth->check())
                    {{--<li><a href="{{ route('leaderboard::home') }}" title="Leaderboard"><i class="fa fa-trophy"></i></a></li>--}}
                    <li class="dropdown">
                        <?php $notifications = $auth->user()->unseenNotifications() ?>
                        <a title="Notifications" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-bell"></i>
                            @if(count($notifications) > 0)
                                <span class="badge">{{count($notifications)}}</span>
                            @endif
                        </a>

                        <ul class="dropdown-menu">
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
                        </ul>
                    </li>

                    <li>
                        <a href="{{ route('perk::public_profile') }}" class="p-y-9"><img src="{{ $auth->user()->avatar ? $auth->user()->avatar : '' }}" class="img-circle img-profile"> {{$auth->user()->username}}</a>
                    </li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('profile::edit') }}">Edit Profile</a></li>
                            <li><a href="{{ route('auth::logout') }}">Sign-out</a></li>
                        </ul>
                    </li>

                @else
                    <li><a href="{{route('auth::register')}}"  class="auth signup" >Sign Up</a></li>
                    <li><a href="{{route('auth::login')}}"  class="auth login" >Login</a></li>
                @endif
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container -->
</nav>