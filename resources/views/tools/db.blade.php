@extends('layout.master')

@section('navbar')
    @include('perk.includes.navbar')
@stop

@section('header')
    @include('perk.includes.header')
@stop

@section('content')
    <main class="homecontent">
        <div class="ccontainer">
            <div class="row" >
                <div class="col-md-12">

                    <div class="row">

                        <div class="col-md-2">
                            @include('perk.includes.home_nav')



                        </div>
                        <div class="col-md-7">
                            <div class="panel z1">
                                <div class="panel-heading">
                                    <h4><i>Db moved</i></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection