<div class="modal fade" id="bid-talent">
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
                        {!! Form::open(array('method' => 'PUT')) !!}
                        <div class="form-group">
                            <label for="msg">Bid this job</label>
                            {!! Form::textarea('msg', '',['class' => 'form-control','placeholder' => 'Why would you be best to do this job?','id' => 'msg' ,'required']) !!}
                        </div>
                        {!! Form::submit('Bid',['class' => 'btn btn-default-outline pull-right']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.modal-content -->
</div><!-- /.modal-dialog -->
