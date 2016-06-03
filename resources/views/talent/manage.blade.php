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
                        @foreach($talents as $talent)
                            @include('talent.includes.manage', array('talent' => $talent))
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </main>
@stop