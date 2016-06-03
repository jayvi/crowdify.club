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
                        <li role="presentation" class="active"><a href="{{ route('perk::perks') }}">Perks</a></li>
                        <li role="presentation"><a href="">Create</a></li>
                    </ul>
                    <?php
                    }
                    ?>
                    @include('perk.includes.perk_nav')
                </div>
                <div class="col-md-7">
                            @foreach($perks as $perk)
                                @include('perk.includes.perk', array('perk' => $perk))
                            @endforeach
                        </div>
                <div class="col-md-3">
                    @include('includes.right_block')
                </div>

            </div>
        </div>
    </main>
@endsection
