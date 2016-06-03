<div class="panel-body">
    <div class="service connection" id="comment-id-{{$comment->id}}">
        <span ><img class="img-circle" src="{{$comment->user->avatar}}"/></span>
        <span class="network-name"><a href="{{ route('perk::public_profile', array('username' => $comment->user->username)) }}">{{ $comment->user->username }}</a></span>
        <p class="disconnect-button">{{ $comment->created_at->diffForHumans() }}</p>
        <p class="m-t-10 linkify" data-linkify >{{$comment->comment}}</p>
    </div>
</div>