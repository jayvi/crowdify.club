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
                    @include('perk.includes.hug_nav')
                </div>
                <div class="col-md-10">
                    <br>
                <div class="col-md-8">
                    @if($hugs && count($hugs) > 0)
                        <div class="panel">
                            <div class="panel-heading">
                                <h5>Your Tasks</h5>
                            </div>
                        </div>
                        @foreach($hugs as $hug)
                            @include('perk.includes.hug')
                        @endforeach
                    @else
                        <div class="panel z1">
                            <div class="panel-body">
                                <p>Currently You don't have any active Task</p>
                            </div>
                        </div>
                    @endif
                        @foreach($pasthugs as $hug)
                            @include('perk.includes.hug')
                        @endforeach
                </div>
            </div>
                </div>
        </div>
    </main>

    {{--hug completion details modal--}}
    @include('perk.includes.modal.hug_completers')


@stop


@section('scripts')
    @include('perk.includes.scripts.hug')
@endsection

