<div class="feed-panel z1">
    {{--<div class="card-block">--}}
    <div class="home-feed">
        <span> <a href="{{ route('perk::public_profile', array('username' => $hug->user->username)) }}"> <img
                        class="profile-avatar" src="{{$hug->user->avatar}}"/> {{$hug->user->username}}</a> Posted a task</span>
        <span> {{ $hug->published_at ? $hug->published_at->diffForHumans() : $hug->created_at->diffForHumans() }}</span>
        <br><a href="{{ route('hugs::show', array('hug_id' => $hug->id)) }}">{{ $hug->title }}</a>

        <p>{!! substr(strip_tags($hug->description), 0, 150).( strlen(strip_tags($hug->description)) > 150 ? '...' : '') !!}</p>
        <span class="label label-deafult-outline"><i class="crowd-coin"></i> {{ $hug->reward }}</span>
        @if($auth->check())
            @if($hug->user_id == $auth->id() || $auth->user()->isAdmin())
                <div class="dropdown dropdown-modify">
                    <a class="dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                        <li><a href="{{ route('hugs::rerun', ['id' => $hug->id]) }}">Rerun</a></li>
                        <li><a href="{{ route('hugs::edit', ['id' => $hug->id]) }}">Edit</a></li>
                        <li><a class="completion-details" data-hug-id="{{ $hug->id }}">Completion Details</a></li>
                        <li><a href="{{ route('hugs::delete', ['id' => $hug->id]) }}">Delete</a></li>
                    </ul>
                </div>
            @endif
        @endif
    </div>

</div>