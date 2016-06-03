<div class="modal fade" id="modal-invest">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5 class="modal-title" id="myModalLabel">
                    <img src="{{url('assets/images/logo.png')}}" alt=""class="popup-logo"><span id="invest-header"></span>
                </h5>
            </div>
            <div class="modal-body">
                <input type="hidden" name="item" value="" id="item">
                <div class="row">
                    <div class="col-md-8">
                        {!! Form::number('amount', 10, array('class' => 'form-control','id' => 'amount')) !!}
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-success btn-sm" id="buy-share">Invest</button>
                    </div>
                </div>
                @if($twitterProfile)
                    <div class="form-group">
                        <label for="tweet">Tweet to get extra Crowdify Coins</label>
                        <textarea name="tweet" id="tweet"  rows="4" class="form-control"></textarea>
                        <p class="text-info remaining-character" id="remaining-character"></p>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-success tweet" id="btn-tweet">Invest & Tweet</button>
                        </div>
                    </div>
                @else
                    <hr />
                    <p><i><a href="{{ route('auth::social::connect', array('provider' => 'twitter')) }}">Connect Twitter</a> and tweet about the invest to get extra Crowdify Coins</i></p>
                @endif
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->