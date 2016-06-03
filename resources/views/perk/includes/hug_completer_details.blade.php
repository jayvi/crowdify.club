
<div class="panel z1">
    <div class="panel-heading">
        Completion Details:
        <p>Reward: {{ $hug->reward }}</p>
        {{--<p>Total Completers: {{ count($hug->completers) }}</p>--}}
    </div>
    <div class="panel-body">
        @if(count($hug->completers) > 0)
            @foreach($hug->completers as $completer)
                <div class="row hug-completer-div">
                    <div class="col-md-6">
                        <span><img class="img-circle profile-avatar" src="{{ $completer->avatar }}" alt=""><a href="{{ route('perk::public_profile', array('username' => $completer->username)) }}">{{ $completer->username }}</a></span>
                    </div>
                    <div class="col-md-6 text-right">
                        <button class="btn btn-default-outline revoke-button" data-completer-id="{{ $completer->id }}" data-hug-id="{{ $hug->id }}">Revoke</button>
                    </div>
                    <hr />
                </div>
            @endforeach
        @else
            <h5>No Completer Yet</h5>
        @endif
    </div>
</div>

