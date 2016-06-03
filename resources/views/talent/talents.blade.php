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
                    @include('perk.includes.talent_nav')
                </div>
                @if($talents)
                <div class="col-md-10">
                    <h2>Talent for hire</h2>
                    <div class="col-md-8">
                        @foreach($talents as $talent)
                            @include('talent.includes.talent', array('talent' => $talent))
                        @endforeach
                    </div>
                </div>
                @endif
                @if($talentsreq)
                    <div class="col-md-10">
                        <h2>Talent Requested</h2>

                        <div class="col-md-8">
                            @foreach($talentsreq as $talent)
                                @include('talent.includes.request', array('talent' => $talent))
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </main>
@stop