<div class="panel z1 text-center">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-12" style="border-right: 1px solid #ddd">
                <h3>City Manager</h3>
                <h4>{{ $user->first_name }} {{ $user->last_name }}</h4>
                <div class="row">
                    <div class="col-md-5">
                        @if($user->avatar_original)
                            <img src="{{$user->avatar_original}}" class="profile-avatar-big img-circle">
                        @else
                            <img src="{{$user->avatar}}" class="profile-avatar-big img-circle">
                        @endif
                    </div>
                    <div class=" item-box col-md-7">
                        <div class="panel-body">
                            <p class="share-price"><span class="crowd-coin">{{ sprintf('%0.2f',3.7) }}</span></p>
                            <div class="btn-group" role="group" aria-label="...">
                                <button class="btn btn-default-outline btn-invest invest-btn">Invest</button>
                                <button class="btn btn-default-outline btn-sell sell-btn">Sell</button>
                            </div>
                        </div>
                    </div>
                </div>
                <p>{{ $user->bio }}</p>
                <a href="{{ $user->website }}">{{ $user->website }}</a>
            </div>
        </div>
    </div>
    <div class="panel-body text-center">
        @if($user->id != $auth->id())
            <div class="btn-group" role="group" aria-label="...">
                @if($auth->check())
                    <button class="btn btn-default-outline" id="btn-gift-coin">Gift Crowdcoins</button>
                @else
                    <button class="btn btn-default-outline" id="btn-gift-coin" data-toggle="modal" data-target="#modal-auth">Gift Crowdcoins</button>
                @endif
            </div>
        @else
            <h3><a href="">Buy this Spot</a></h3>
        @endif

    </div>
</div>