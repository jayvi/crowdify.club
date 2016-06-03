<div class="panel z1 membership-box">
    <div class="panel-heading text-center">
       <h4>Hiker (Free)</h4>
        <img src="{{url('assets/images/walker.png')}}">
        <h6>Can progress</h6>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-10">
              <p>Can trial all site features</p>
            </div>
            <div class="col-md-2 text-center">
                <p></p>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-10">
              <p>Can sponsor</p>
            </div>
            <div class="col-md-2 text-center">
                <span class="ion-android-close" style="color: red;"></span>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-10">
              <p>Blog post per day about tech</p>
            </div>
            <div class="col-md-2 text-center">
                <p>1</p>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-10">
              <p>Run task about tech per week</p>
            </div>
            <div class="col-md-2 text-center">
                <p>1</p>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-10">
              <p>Post talents</p>
            </div>
            <div class="col-md-2 text-center">
                <p>1</p>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-10">
              <p>Can post or apply for jobs</p>
            </div>
            <div class="col-md-2 text-center">
                <span class="ion-android-close" style="color: red;"></span>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-10">
              <p>Create free event</p>
            </div>
            <div class="col-md-2 text-center">
                <p>1</p>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-10">
              <p>Crowdfunding campaigns</p>
            </div>
            <div class="col-md-2 text-center">
                <p>1</p>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-10">
              <p>Some shop discounts and  perks</p>
            </div>
            <div class="col-md-2 text-center">
                <span class="ion-android-close" style="color: red;"></span>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-10">
                <p>Can sell Crowdify tools + products</p>
            </div>
            <div class="col-md-2 text-center">
                <span class="ion-android-close" style="color: red;"></span>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-10">
                <p>Can attend Crowdify Events</p>
            </div>
            <div class="col-md-2 text-center">
                <p>1</p>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-10">
                <p>Award of Coins</p>
            </div>
            <div class="col-md-2 text-center">
                <span class="ion-android-close" style="color: red;"></span>
            </div>
        </div>
    </div>
    <div class="panel-footer text-center">
        @if($user->isFreeUser())
            <strong>You are currently subscribed for this</strong>
        @endif
    </div>
</div>