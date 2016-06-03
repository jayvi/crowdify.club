<div class="modal fade" id="work-talent">
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
                        <p>If you are finished with the job; Mark finished</p>
                        {!! Form::open(array('method' => 'PUT')) !!}
                        {!! Form::hidden('status', 1) !!}
                        {!! Form::submit('Mark Finished',['class' => 'btn btn-default-outline btn-sm pull-right']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
