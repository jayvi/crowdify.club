<div class="panel z1 membership-box">
    <div class="panel-heading text-center">
        <h4>Astronaut ($4997)</h4>
        <h4>Free life membership</h4>
        <img src="{{url('assets/images/astronaut.png')}}">
        <h6>Edge of universe is limit</h6>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-10">
                <p>10% Monthly return + Driver</p>
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
                <span class="ion-checkmark" style="color: green;"></span>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-10">
                <p>Blog post per day about tech</p>
            </div>
            <div class="col-md-2 text-center">
                <h4>∞</h4>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-10">
                <p>Run task about tech at once</p>
            </div>
            <div class="col-md-2 text-center">
                <p>3</p>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-10">
                <p>Post talents</p>
            </div>
            <div class="col-md-2 text-center">
                <h4>∞</h4>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-10">
                <p>Can post or apply for jobs</p>
            </div>
            <div class="col-md-2 text-center">
                <h4>∞</h4>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-10">
                <p>Create paid event</p>
            </div>
            <div class="col-md-2 text-center">
                <h4>∞</h4>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-10">
                <p>Crowdfunding campaigns</p>
            </div>
            <div class="col-md-2 text-center">
                <h4>∞</h4>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-10">
                <p>All shop discounts and perks</p>
            </div>
            <div class="col-md-2 text-center">
                <span class="ion-checkmark" style="color: green;"></span>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-10">
                <p>Can sell Crowdify tools + products</p>
            </div>
            <div class="col-md-2 text-center">
                <span class="ion-checkmark" style="color: green;"></span>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-10">
                <p>Can attend all Crowdify Events</p>
            </div>
            <div class="col-md-2 text-center">
                <span class="ion-checkmark" style="color: green;"></span>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-10">
                <p>Award of 50000 Coins</p>
            </div>
            <div class="col-md-2 text-center">
                <span class="ion-checkmark" style="color: green;"></span>
            </div>
        </div>
    </div>
    <div class="panel-footer text-center">
        @if($user->isAstronaut())
            <strong>You are currently subscribed for this.</strong>
        @elseif($user->isAdmin())
            <strong>You are Admin User</strong>
        @elseif($user->isFounder())
            <h4>You are a Founding member</h4>
        @else
            <p>$4997 = {{$bitcoin['astronaut']}}BTC</p>
            <div id="wrapper" style="text-align: center">
                <div class="input-group">
                    <div class="input-group-btn">
                        {!! Form::open(array('url' => route('subscriptions::bitcoin'))) !!}
                        <input type="hidden" name="plan" value="astronaut">
                        <input type="submit" class="btn btn-primary margin5" value="Pay with Bitcoin">
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
            <div id="wrapper" style="text-align: center">
                <div class="input-group">
                    <div class="input-group-btn">
                        {!! Form::open(array('url' => route('subscriptions::payment'))) !!}
                        <input type="hidden" name="plan" value="astronaut">
                        <input type="submit" class="btn btn-success margin5" value="Pay with Paypal">
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>