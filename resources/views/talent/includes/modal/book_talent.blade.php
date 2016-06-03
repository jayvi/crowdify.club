<div class="modal fade" id="modal-book-talent">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5 class="modal-title" id="myModalLabel">
                    <img src="{{url('assets/images/logo.png')}}" alt=""class="popup-logo">
                    Book Talent
                </h5>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel-body">
                        <h1 align="center">{{ $talent->title }}</h1><br>
                        <div class="bookdate">test</div><br>
                        {{ $talent->title }}
                        <span class="label label-deafult-outline"><i class="bit-coin"></i> {{ $talent->bitcoins }}</span><br><br>
                        + Network Fee
                        <span class="label label-deafult-outline"><i class="bit-coin"></i> {{ .0004 }}</span><br><br>
                        Total
                        <span class="label label-deafult-outline"><i class="bit-coin"></i> {{ $talent->bitcoins +.0004 }}</span>
                        <span class="label label-deafult-outline"><i class="crowd-coin"></i> {{ $talent->crowdcoins }}</span><br>
                        @if($auth->user()->isFreeUser())
                            <br>
                            <p>Must be a paid member to use the Talent area. Join <a href="{{ route('subscriptions::home') }}">here</a></p>
                        @elseif($auth->user()->wallet)
                            @if($auth->user()->wallet->balance >= $talent->bitcoins +.0004)
                                {!! Form::open(array('method' => 'PUT')) !!}
                                {!! Form::hidden('seller', $talent->user_id) !!}
                                {!! Form::hidden('bitcoins', $talent->bitcoins + .0002) !!}
                                {!! Form::submit('Book',['class' => 'btn btn-default-outline btn-sm pull-right']) !!}
                                {!! Form::close() !!}
                            @else
                                <br>
                                <p>Add bitcoins <a href="{{ route('profile::bank') }}">here</a> to buy this talent</p>
                            @endif
                        @else
                            <br>
                            <p>Add bitcoins <a href="{{ route('profile::bank') }}">here</a> to buy this talent</p>
                        @endif

                        </div>
                </div>
            </div>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
