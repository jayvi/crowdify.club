<div class="modal fade" id="profile-edit-complete">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5 class="modal-title" id="myModalLabel">
                    <img src="{{url('assets/images/logo.png')}}" alt=""class="popup-logo"></h3>
                </h5>
            </div>
            <div class="modal-body text-center">
                <h2>Thank you for Editing your Profile</h2>
                <br />
                <h5><i>Have 10,000 free Crowdify Coins</i></h5>
                <br />
                <h5><i>Grab some Perks and Discount</i></h5>
                <a href="{{ route('perk::perks') }}"><i>Perks</i></a>
                <h5><i>Earn More Coins</i></h5>
                <a href="#"><i>Earn Coins</i></a>
                <h5><i>Invest in the Stock Market</i></h5>
            </div>
            <div class="modal-footer">
                <a  href="{{route('profile::confirm-settings')}}" class="btn btn-primary">Continue</a>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->