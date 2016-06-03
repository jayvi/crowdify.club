@extends('layout.master')

@section('navbar')
    @include('perk.includes.navbar')
@stop

@section('header')
    @include('perk.includes.header')
@stop

@section('content')
    <main class="content">
        <div class="container-fluid">
            <div class="row" >
                <div class="col-md-2">
                    @include('perk.includes.home_nav')
                </div>

                <div class="col-md-10">
                    <div class="panel z1">
                        <div class="panel-heading">
                            <h4>Affiliate Dashboard</h4>
                        </div>
                        @if($affiliate)
                            @include('affiliate.includes.affiliate', array('affiliate' => $affiliate))
                        @else
                            @if($auth->user()->isPaidMember() || $auth->user()->isAdmin())
                                <div class="panel-body">
                                <p>"Please see our compensation plan page to learn more about how to be rewarded for introducing people to Crowdify. We are paying out 50% to all of you every week"
                                    Then add the link to the Compensation Plan Page. If it is not added to the site yet please link it o this blog post <a href="http://blog.crowdify.tech/35/show">http://blog.crowdify.tech/35/show</a></p>

                                    <div style="width: 1500px">{!! $html !!}</div>
                                </div>
                            @else
                                <div class="panel-body">
                                        <h3>You are not currently premium member. To become an sponsor please
                                        <a href="{{route('subscriptions::home')}}">Upgrade</a> your account.</h3>
                                    <p>"Please see our compensation plan page to learn more about how to be rewarded for introducing people to Crowdify. We are paying out 50% to all of you every week"
                                    Then add the link to the Compensation Plan Page. If it is not added to the site yet please link it o this blog post <a href="http://blog.crowdify.tech/35/show">http://blog.crowdify.tech/35/show</a></p>

                                    <div style="width: 1500px">{!! $html !!}</div>
                                </div>

                            @endif

                        @endif
                    </div>
                </div>

            </div>
        </div>
    </main>
@stop