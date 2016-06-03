<div class="modal fade" id="affiliate-popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5 class="modal-title" id="myModalLabel">
                    <img src="{{url('assets/images/logo.png')}}" alt=""class="popup-logo">
                </h5>
            </div>
            <div class="modal-body">
                @include('includes.ads.affiliate_banner')
            </div>
        </div>
    </div>
</div>