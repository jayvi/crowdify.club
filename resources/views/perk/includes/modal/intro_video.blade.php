<div class="modal fade" id="intro-video">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5 class="modal-title" id="myModalLabel">
                    <img src="{{url('assets/images/logo.png')}}" alt=""class="popup-logo">Learn How Perkfluence Can Help You
                </h5>
            </div>
            <div class="modal-body">
                <video id="vid1" src="" class="video-js vjs-default-skin" controls preload="auto" width="100%" height="500" data-setup='{ "techOrder": ["youtube"], "src": "https://www.youtube.com/watch?v=eHJM0_aHWBo" }'>
                </video>
            </div>
            <div class="modal-footer">
                <a  href="{{route('profile::edit').'?introComplete=true'}}" class="btn btn-primary">Continue</a>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->