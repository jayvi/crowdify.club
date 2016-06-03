<div class="feed-panel z1">
    <div class="home-feed">
        <span>
            <a href="{{ route('perk::public_profile', array('username' => $perk->user->username)) }}">
                @if($perk->user->avatar_original)
                    <img src="{{$perk->user->avatar_original}}" class="profile-avatar">
                @else
                    <img src="{{$perk->user->avatar}}" class="profile-avatar">
                @endif
                {{$perk->user->username}}
            </a> Posted a Perk
        </span>
        <span> {{ $perk->published_at ? $perk->published_at->toFormattedDateString() : $perk->created_at->toFormattedDateString() }}</span>

        <h3 class="hm5"><a href="{{ route('perks::perk', ['id' => $perk->id]) }}">{{ $perk->title }}</a></h3>
        <img class="perkimg" src="{{ $perk->logo_url }}" alt="">

        <p>
            {!! substr(strip_tags($perk->description), 0, 250).( strlen(strip_tags($perk->description)) > 250 ? '...' : '') !!}
        </p>
    </div>
</div>