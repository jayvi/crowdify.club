<div class="modal fade" id="modal-send-gift">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5 class="modal-title" id="myModalLabel">
                    <img src="{{url('assets/images/logo.png')}}" alt=""class="popup-logo">Send CrowdifyCoin
                </h5>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="hidden" id="send-gift-username" value="{{ $user->username }}" name="username">
                                    <input type="number" name="amount" value="" class="form-control" id="send-gift-amount" placeholder="Enter Amount">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="button" class="btn btn-default-outline btn-sm pull-right" id="button-send-gift" value="Send">
                            </div>
                        </div>
                        @if($twitterProfile)
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="tweet">Tweet to get extra Crowdify Coins</label>
                                        <textarea name="tweet" id="gift-tweet"  rows="4" class="form-control"></textarea>
                                        <p class="text-info remaining-character" id="remaining-character"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-default-outline btn-sm tweet pull-right" id="btn-gift-tweet">Send & Tweet</button>
                                    <a href="#" id="cancel-gift-coin " class="btn btn-default-outline btn-sm pull-right" style="margin-right: 5px;" data-dismiss="modal">Cancel</a>
                                </div>
                            </div>

                        @else
                            <hr />
                            <p><i><a href="{{ route('auth::social::connect', array('provider' => 'twitter')) }}">Connect Twitter</a> and tweet about the gift to get extra Crowdify Coins</i></p>
                        @endif
                    </div>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->