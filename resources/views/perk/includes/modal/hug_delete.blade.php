<div class="modal fade" id="delete-hug" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            {!! Form::open(['url' => route('hugs::delete', array('id'=> $hug->id) ), 'method' => 'delete']) !!}
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5 class="modal-title" id="myModalLabel">
                    <img src="{{url('assets/images/logo.png')}}" alt=""class="popup-logo">Delete Task!
                </h5>
            </div>
            <div class="modal-body">
                <p>Do you really want to delete this task?</p>
                {!! Form::hidden('hug_id', 0, ['id' => 'delete-modal-hug-id']) !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                <button type="submit" class="btn btn-primary">Yes</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>