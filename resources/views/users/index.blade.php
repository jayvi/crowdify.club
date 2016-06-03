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
                            <div class="panel z1">
                                <div class="panel-heading">
                                    <h4>Users ( Total : {{ $totalCount }})</h4>
                                </div>
                                <div class="panel-body">
                                    @include('users.includes.users',array('users' => $users))
                                </div>
                            </div>
                            <div class="pagination-wrapper">
                                {!! $users->render() !!}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>


@stop



