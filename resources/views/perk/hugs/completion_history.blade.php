@extends('layout.master')

@section('navbar')
    @include('perk.includes.navbar')
@stop

@section('header')
    @include('perk.includes.header')
@stop

@section('content')
    <main class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    @include('perk.includes.hug_nav')
                </div>

                <div class="col-md-8">
                    @if($hugs && count($hugs)> 0)
                        <div class="panel">
                            <div class="panel-heading">
                                <h5>Your Completed Tasks</h5>
                            </div>
                        </div>
                        @foreach($hugs as $hug)
                           @include('perk.includes.hug')
                        @endforeach
                    @else
                        <div class="panel">
                            <div class="panel-body">
                                <p>Currently You don't have any Completed Task</p>
                                <p>Find <a href="{{ route('hugs::home') }}">Tasks</a> and complete to earn more crowdify coins</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
@stop


@section('scripts')

@endsection

