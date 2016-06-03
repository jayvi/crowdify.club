<div class="modal fade" id="add_dates">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5 class="modal-title" id="myModalLabel">
                    <img src="{{url('assets/images/logo.png')}}" alt=""class="popup-logo">
                    Book Talent
                </h5>
            </div>
            <div class="row time-form">
                <div class="col-md-12">
                    <div class="panel-body">
                        <h1 align="center">{{ $talent->title }}</h1><br>
                        <div class="adddate"></div>
                        {!! Form::label('time', 'Add time *') !!}
                        {!! Form::hidden('title', $talent->title) !!}
                        {!! Form::hidden('date', '',array('class' => 'addval',)) !!}
                        {!! Form::hidden('job_id', $talent->id) !!}
                        {!! Form::time('time','', array('class' => 'form-control',)) !!}
                        <br>
                        <div class="form-group">
                            <button type="button" class="btn btn-default-outline pull-right" id="btn-add-time">Add time</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
