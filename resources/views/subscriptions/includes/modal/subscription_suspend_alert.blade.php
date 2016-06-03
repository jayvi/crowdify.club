<div class="modal fade" id="modal-subscription-suspend" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5 class="modal-title" id="myModalLabel">
                    <img src="{{url('assets/images/logo.png')}}" alt=""class="popup-logo">Suspend Subscription!
                </h5>
            </div>
            <div class="modal-body">
                <p>Do you really want to suspend premium subscription?</p>
            </div>
            <div class="modal-footer">
                {!! Form::open(array('url' => route('subscriptions::payment::suspend'))) !!}
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                <input type="submit" class="btn btn-success" value="Yes">
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>