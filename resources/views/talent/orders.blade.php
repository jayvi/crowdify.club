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
            <div class="row">
                <div class="col-md-2">
                    <?php
                    use Jenssegers\Agent\Agent as Agent;
                    $Agent = new Agent();
                    ?>
                    <?php
                    if ($Agent->isMobile()) {
                    ?>
                    <ul class="nav nav-tabs">
                        <li role="presentation"><a href="{{ route('talent::home') }}">Home</a></li>
                        <li role="presentation"><a href="{{ route('talent::post') }}">Create</a></li>
                        <li role="presentation" class="active"><a href="{{ route('talent::manage') }}">Manage</a></li>
                    </ul>
                    <?php
                    }
                    ?>
                    @include('perk.includes.talent_nav')
                </div>
                <div class="col-md-10">
                    <div class="col-md-8">
                        @if(count($orders))
                            <div class="panel panel-default">
                                <!-- Default panel contents -->
                                <div class="panel-heading">My Orders</div>
                                <ul class="list-group">
                            @foreach($orders as $order)
                            @include('talent.includes.order', array('order' => $order, 'talent' => $talent[$order->talent_id]))
                        @endforeach
                                </ul>
                            </div>
                        @endif
                        @if(count($purchases))
                            <div class="panel panel-default">
                                <!-- Default panel contents -->
                                <div class="panel-heading">My Purchases</div>
                                <ul class="list-group">
                            @foreach($purchases as $purchase)
                            @include('talent.includes.purchase', array('order' => $purchase, 'talent' => $ptalent[$purchase->talent_id]))
                        @endforeach
                                </ul>
                            </div>
                        @endif
                        @if(count($talents))
                                <div class="panel panel-default">
                                    <!-- Default panel contents -->
                                    <div class="panel-heading">My Talents</div>
                                    <ul class="list-group">
                                @foreach($talents as $talent)
                            @include('talent.includes.manage', array('talent' => $talent))
                        @endforeach
                                    </ul>
                                </div>
                        @endif
                        @if(count($talentreq))
                            <div class="panel panel-default">
                                <!-- Default panel contents -->
                                <div class="panel-heading">My Request</div>
                                        <ul class="list-group">
                            @foreach($talentreq as $talent)
                                @include('talent.includes.manage', array('talent' => $talent))
                            @endforeach
                                        </ul>

                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>
@stop