<div class="panel z1 membership-box">
    <div class="panel-heading text-center">
        <h4>Premium( $12/Month)</h4>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-10">
            <p>    Have tasks running at any time</p>
            </div>
            <div class="col-md-2 text-center">
                <p>3</p>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-10">
            <p>    Blog once a day in the site.</p>
            </div>
            <div class="col-md-2 text-center">
                <span class="ion-checkmark" style="color: #0A99FF;"></span>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-10">
            <p>    Be an affiliate</p>
            </div>
            <div class="col-md-2 text-center">
                <span class="ion-checkmark" style="color: #0A99FF;"></span>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-10">
            <p>    Get all perks and discounts available</p>
            </div>
            <div class="col-md-2 text-center">
                <span class="ion-checkmark" style="color: #0A99FF;"></span>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-10">
            <p>    Get special discount offers in our shop</p>
            </div>
            <div class="col-md-2 text-center">
                <span class="ion-checkmark" style="color: #0A99FF;"></span>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-10">
            <p>    Be invited to Crowdify events both online webinars and in real life meetups</p>
            </div>
            <div class="col-md-2 text-center">
                <span class="ion-checkmark" style="color: #0A99FF;"></span>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-10">
            <p>    Run crowdfunding campaigns if approved</p>
            </div>
            <div class="col-md-2 text-center">
                <span class="ion-checkmark" style="color: #0A99FF;"></span>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-10">
            <p>Add jobs to our jobs board</p>
            </div>
            <div class="col-md-2 text-center">
                <span class="ion-checkmark" style="color: #0A99FF;"></span>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-10">
            <p>Get a founding members badge if they are in the first 2000 members</p>
            </div>
            <div class="col-md-2 text-center">
                <span class="ion-checkmark" style="color: #0A99FF;"></span>
            </div>
        </div>
    </div>
    <div class="panel-footer text-center">
        @if(!$user->subscription)
            @if($user->isFreeUser())

                <p>Discounted price of $10 when you use bitcoin</p>
                <p>$10 = {{$bitcoin}}BTC</p>
                <div id="wrapper" style="text-align: center">
                    <div class="input-group">
                        <div class="input-group-btn">

                            {!! Form::open(array('url' => route('subscriptions::payment'), 'class' => 'floatleft')) !!}
                            <input type="submit" class="btn btn-primary margin5" value="Pay with paypal">
                <br>
                {!! Form::close() !!}
                            {!! Form::open(array('url' => route('subscriptions::bitcoin'), 'class' => 'floatleft')) !!}
                            <input type="submit" class="btn btn-primary margin5" value="Pay with Bitcoin">
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}

            @else
                @if($user->isAdmin())
                    <strong>You are Admin User</strong>
                @elseif($user->isFounder())
                    <h4>You are a Founding member</h4>
                @endif
            @endif
        @elseif($user->subscription->state == 'Pending' || $user->subscription->state == 'Active')
            <h4>You are subscribed as a premium Member</h4>
            <div class="row">
                <div class="col-md-6">
                    <button class="btn btn-success btn-suspend" data-toggle="modal" data-target="#modal-subscription-suspend">Suspend</button>
                </div>
                <div class="col-md-6">
                    <button class="btn btn-success btn-cancel" data-toggle="modal" data-target="#modal-subscription-cancel">Cancel</button>
                </div>
            </div>
        @elseif($user->subscription->state == 'Suspended')
            <h4>Your subscription is currently Suspended</h4>
            <div class="row">
                <div class="col-md-6">
                    {!! Form::open(array('url' => route('subscriptions::payment::reactivate'))) !!}
                    <input type="submit" class="btn btn-primary" value="Re-Activate">
                    {!! Form::close() !!}
                </div>
                <div class="col-md-6">
                    <button class="btn btn-success btn-cancel" data-toggle="modal" data-target="#modal-subscription-cancel">Cancel</button>
                </div>
            </div>
        @elseif($user->isFounder())
            <h4>Your subscription Founding member</h4>
            <div class="row">
                <div class="col-md-6">
                    {!! Form::open(array('url' => route('subscriptions::payment::reactivate'))) !!}
                    <input type="submit" class="btn btn-primary" value="Re-Activate">
                    {!! Form::close() !!}
                </div>
                <div class="col-md-6">
                    <button class="btn btn-success btn-cancel" data-toggle="modal"
                            data-target="#modal-subscription-cancel">Cancel
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>