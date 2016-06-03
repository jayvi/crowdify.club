@extends('layout.master')

@section('navbar')
    @include('perk.includes.navbar')
@stop

@section('content')
    <main class="content">
        <div class="container">
            <div class="panel z1">
                <div class="panel-heading text-center">
                    <h5>Perks Market</h5>
                </div>
            </div>
            @foreach($perks as $perk)
                @include('perk.includes.perk', array('perk' => $perk))
            @endforeach
        </div><!-- container -->
    </main>
@stop


@section('scripts')
@endsection

