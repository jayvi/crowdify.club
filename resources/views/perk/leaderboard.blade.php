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
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h5>Leaderboard</h5>
                </div>
                <div class="panel-body" style="background: white;">
                        @foreach($banks as $bank)
                            <div class="service connection">
                                {{--<span class="custom-twitter-style ion-social-twitter"></span>--}}
                                <img src="{{ $bank->user->avatar }}" class="img-circle img-responsive" >
                                <span class="network-name"><a href="{{ route('perk::public_profile', array('username' => $bank->user->username)) }}">{{ $bank->user->username }}</a></span>
                                {{--<a class="disconnect-button">Disconnect Twitter</a>--}}
                                <p class="label label-info pull-right" style="padding: 5px; font-size: 1.3em;">{{ $bank->crowd_coins }}</p>

                                {{--<a class="disconnect-button">Connect Twitter</a>--}}
                            </div>
                        @endforeach

                </div>
            </div>
        </div>
    </main>
@stop


@section('scripts')
    <script>
        $(document).ready(function(){
            $('.date-picker').datetimepicker({
                format:'Y-m-d'
            });
        });
    </script>
@endsection