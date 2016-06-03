<div class="feed-panel z1">
    <div class="home-feed">
        <span>
            <a href="{{ route('perk::public_profile', array('username' => $blog->user->username)) }}">
                @if($blog->user->avatar_original)
                    <img src="{{$blog->user->avatar_original}}" class="profile-avatar">
                @else
                    <img src="{{$blog->user->avatar}}" class="profile-avatar">
                @endif
                {{$blog->user->username}}
            </a> Posted a Blog
        </span>
        <span> {{ $blog->published_at ? $blog->published_at->toFormattedDateString() : $blog->created_at->toFormattedDateString() }}</span>
        <br><a href="{{ route('blog::show', ['id' => $blog->id]) }}">{{ $blog->title }}</a>
        <p>{!! substr(strip_tags($blog->description), 0, 150).( strlen(strip_tags($blog->description)) > 150 ? '...' : '') !!}</p>
    </div>
</div>