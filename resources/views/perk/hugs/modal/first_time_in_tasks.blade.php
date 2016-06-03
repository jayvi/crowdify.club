<div class="modal fade" id="modal-first-time-in-tasks">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5 class="modal-title" id="myModalLabel">
                    <img src="{{url('assets/images/logo.png')}}" alt=""class="popup-logo">Welcome to Crowdify Tasks
                </h5>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <p>Earn More Crowdify Coins by Completing <a href="{{ route('hugs::home') }}">Tasks</a> </p>
                    <p>You can <a href="{{ route('hugs::create') }}">Create</a>  Your own <a href="{{ route('hugs::home') }}">Task</a> </p>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->