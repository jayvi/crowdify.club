<div class="panel text-center">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-12">
                <a href="{{route('perk::public_profile',array('username' => $user->username))}}">
                    @if($user->avatar_original)
                        <img src="{{$user->avatar_original}}" width="100%" >
                    @else
                        <img src="{{$user->avatar}}" width="100%">
                    @endif
                </a>


                <a href="{{route('perk::public_profile',array('username' => $user->username))}}"><h5>{{$user->username}}</h5></a>
                <p>{{ $user->bio }}</p>
            </div>
        </div>
    </div>
    <div class="panel-body text-center">
        @if($user->id != $auth->id())
            {{--<div class="btn-group" role="group" aria-label="...">--}}
                {{--@if($auth->check())--}}
                    {{--<button class="btn btn-default-outline" id="btn-gift-coin">Gift Crowdcoins</button>--}}
                {{--@else--}}
                    {{--<button class="btn btn-default-outline" id="btn-gift-coin" data-toggle="modal" data-target="#modal-auth">Gift Crowdcoins</button>--}}
                {{--@endif--}}
            {{--</div>--}}
            <div class="btn-group" role="group" aria-label="...">
                @if($auth->check())
                    @if($auth->user()->isFollowing($user->id))
                        <button class="btn btn-success btn-following"  data-user-id="{{$user->id}}">Following</button>
                    @else
                        <button class="btn btn-primary btn-follow" data-user-id="{{$user->id}}">Follow</button>
                    @endif
                @else
                    <button class="btn btn-default-outline" id="btn-follow" data-toggle="modal" data-target="#modal-auth">Follow</button>
                @endif
            </div>
        @endif
    </div>
</div>


