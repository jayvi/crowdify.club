<div class="modal fade" id="delete-blog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            {!! Form::open(['url' => route('blog::delete'), 'method' => 'delete']) !!}
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5 class="modal-title" id="myModalLabel">
                    <img src="{{url('assets/images/logo.png')}}" alt=""class="popup-logo">Delete Blog!
                </h5>
            </div>
            <div class="modal-body">
                <p>Do you really want to delete this blog?</p>
                {!! Form::hidden('blog_id', 0, ['id' => 'delete-modal-blog-id']) !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                <button type="submit" class="btn btn-primary">Yes</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>