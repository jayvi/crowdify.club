<div class="feed-panel z1">
    {{--<div class="card-block">--}}
    <div class="home-feed">
        <span> <a href="{{ route('perk::public_profile', array('username' => $status->user->username)) }}">
                @if($status->user->avatar_original)
                    <img src="{{$status->user->avatar_original}}" class="profile-avatar">
                @else
                    <img src="{{$status->user->avatar}}" class="profile-avatar">
                @endif
                {{$status->user->username}}</a> Updated Status</span>
        <span>  {{$status->created_at->diffForHumans() }}
            </span>

        <p linkify>{{ $status->status }}</p>

        {{--tweet button section--}}
        <a href="https://twitter.com/share" class="twitter-share-button" data-url="{{ route('perk::publicStatus', ['username' => $status->user->username, 'statusId' => $status->id]) }}" data-text="{{ substr($status->status, 0, 90) }}" data-via="{{ $status->user->username }}"></a>
        {{--end tweet button--}}

        {{--comment count button--}}
        <a href="{{ route('perk::publicStatus', ['username' => $status->user->username, 'statusId' => $status->id]) }}" style="color: grey; float: right; font-size: 12px;">{{ $status->comments->count() > 1 ? $status->comments->count() . ' comments' : $status->comments->count() . ' comment' }}</a>
        {{--end comment count--}}

        @if($auth->check())
            @if($status->user_id == $auth->id() || $auth->user()->isAdmin())
                <div class="dropdown dropdown-modify">
                    <a class="dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="true">
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                        <li><a href="{{ route('status::delete', ['id' => $status->id]) }}">Delete</a></li>
                    </ul>
                </div>
            @endif
        @endif
    </div>

</div>
