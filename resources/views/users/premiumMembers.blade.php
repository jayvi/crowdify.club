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
                        <div class="col-md-7">
                            <div class="panel z1">
                                <div class="panel-heading">
                                    <h4>Premium Members</h4>
                                </div>
                                <div class="panel-body">
                                    <table class="table table-striped">
                                        <thead>
                                            <th>Member</th>
                                            <th>Payment Method</th>
                                            <th>Expire Date</th>
                                        </thead>
                                        <tbody>
                                            @foreach($users as $user)
                                                @include('users.includes.premiumMemberInfo',array('user' => $user))
                                            @endforeach
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                            {!! $users->render() !!}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>


@stop



