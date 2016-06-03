<li class="list-group-item">
        <span> <a href="{{ route('perk::public_profile', array('username' => $talent->user->username)) }}">
                @if($talent->user->avatar_original)
                    <img src="{{$talent->user->avatar_original}}" class="profile-avatar">
                @else
                    <img src="{{$talent->user->avatar}}" class="profile-avatar">
                @endif
                {{$talent->user->username}}</a> Added a Talent</span>
        <span>  {{ $talent->published_at ? $talent->published_at->diffInMinutes() : $talent->created_at->diffInMinutes() }}
            mins</span><br>
    <a href="{{ route('talent::edit', array('id' => $talent->id)) }}">{{ $talent->title }}</a>

    <p class="card-text">{{ substr($talent->metatag, 0, 50).( strlen($talent->metatag) > 50 ? '...' : '')}}</p>
    <span class="label label-deafult-outline"><i class="crowd-coin"></i> {{ $talent->crowdcoins }}</span>
    <span class="label label-deafult-outline"><i class="bit-coin"></i> {{ $talent->bitcoins }}</span>
</li>