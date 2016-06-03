<div class="modal fade" id="accept-bid">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h5 class="modal-title" id="myModalLabel">
                    <img src="{{url('assets/images/logo.png')}}" alt="" class="popup-logo">
                    Accept Bid
                </h5>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel-body">
                        <h1 align="center">{{ $talent->title }}</h1><br>
                        @if($bid)
                            <p>Accept Bid</p>
                            {!! Form::open(array('method' => 'PUT', 'route' => 'talent::accept-bid')) !!}
                            {!! Form::hidden('id', $bid->id) !!}
                            {!! Form::hidden('req', $bid->request_id) !!}
                            {!! Form::submit('Accept Bid',['class' => 'btn btn-default-outline btn-sm pull-right']) !!}
                            {!! Form::close() !!}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.modal-content -->
</div><!-- /.modal-dialog -->
