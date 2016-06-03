<div class="modal fade" id="release-btc">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h5 class="modal-title" id="myModalLabel">
                    <img src="{{url('assets/images/logo.png')}}" alt="" class="popup-logo">
                    Book Talent
                </h5>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel-body">
                        <h1 align="center">{{ $talent->title }}</h1><br>

                        <p>Release bitcoins as payment for completion of this talent request this can not be undo!</p>
                        {!! Form::open(array('method' => 'PUT', 'route' => 'talent::release-btc')) !!}
                        {!! Form::hidden('id', $bid->id) !!}
                        {!! Form::hidden('req', $bid->request_id) !!}
                        {!! Form::hidden('bidder', $bid->bidder_id) !!}
                        {!! Form::submit('Release BTC',['class' => 'btn btn-default-outline btn-sm pull-right']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.modal-content -->
</div><!-- /.modal-dialog -->
