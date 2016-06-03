<div class="panel z1 membership-box">
    <div class="panel-heading text-center">
       <h4>Free</h4>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-10">
              <p>  Have tasks running at any time</p>
            </div>
            <div class="col-md-2 text-center">
                <p>1</p>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-10">
              <p>  Blog once a day in the site.</p>
            </div>
            <div class="col-md-2 text-center">
                <span class="ion-android-close" style="color: red;"></span>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-10">
              <p>  Be an affiliate</p>
            </div>
            <div class="col-md-2 text-center">
                <span class="ion-android-close" style="color: red;"></span>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-10">
              <p>  Get all perks and discounts available</p>
            </div>
            <div class="col-md-2 text-center">
                <span class="ion-android-close" style="color: red;"></span>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-10">
              <p>  Get special discount offers in our shop</p>
            </div>
            <div class="col-md-2 text-center">
                <span class="ion-android-close" style="color: red;"></span>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-10">
              <p>  Be invited to Crowdify events both online webinars and in real life meetups</p>
            </div>
            <div class="col-md-2 text-center">
                <span class="ion-android-close" style="color: red;"></span>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-10">
              <p>  Run crowdfunding campaigns if approved</p>
            </div>
            <div class="col-md-2 text-center">
                <span class="ion-android-close" style="color: red;"></span>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-10">
              <p>  Add jobs to our jobs board</p>
            </div>
            <div class="col-md-2 text-center">
                <span class="ion-android-close" style="color: red;"></span>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-10">
              <p>  Get a founding members badge if they are in the first 2000 members</p>
            </div>
            <div class="col-md-2 text-center">
                <span class="ion-android-close" style="color: red;"></span>
            </div>
        </div>
    </div>
    <div class="panel-footer text-center">
        @if($user->isFreeUser())
            @if(!$user->is_manually_upgraded && !$user->isAdmin())
                <strong>You are currently subscribed for this</strong>
            @endif
        @endif
    </div>
</div>