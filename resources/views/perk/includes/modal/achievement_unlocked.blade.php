<div class="modal fade" id="achievement-unlocked">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5 class="modal-title" id="myModalLabel">
                    <img src="{{url('assets/images/logo.png')}}" alt=""class="popup-logo">
                </h5>
            </div>
            <div class="modal-body text-center">
                <h2>The free Crowdify Coins just keep on coming!</h2>
                <p>{{$achievement->name}}</p>
                <p>{{$achievement->description}}</p>
            </div>
            <div class="modal-footer">
                <a  href="" class="btn btn-primary">Continue</a>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->