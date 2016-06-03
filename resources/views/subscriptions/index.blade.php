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
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-2">
                            @include('perk.includes.home_nav')
                        </div>
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel z1">
                                        <div class="panel-heading">
                                            <h4>Membership</h4>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-15 col-sm-3">
                                                    @include('subscriptions.includes.membership_box_walker',array('user'=>$user))
                                                </div>
                                                <div class="col-md-15 col-sm-3">
                                                    @include('subscriptions.includes.membership__box_cyclist',array('user'=>$user))
                                                </div>
                                                <div class="col-md-15 col-sm-3">
                                                    @include('subscriptions.includes.membership__box_driver',array('user'=>$user))
                                                </div>
                                                <div class="col-md-15 col-sm-3">
                                                    @include('subscriptions.includes.membership__box_pilot',array('user'=>$user))
                                                </div>
                                                <div class="col-md-15 col-sm-3">
                                                    @include('subscriptions.includes.membership__box_astronaut',array('user'=>$user))
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>


    @include('subscriptions.includes.modal.subscription_suspend_alert')
    @include('subscriptions.includes.modal.subscription_cancel_alert')
@stop

@section('scripts')
    <script>
        $(document).ready(function(){

        });
    </script>
@endsection