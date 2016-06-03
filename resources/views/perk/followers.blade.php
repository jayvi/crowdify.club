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
                    @include('perk.includes.home_nav')
                </div>
                <div class="col-md-7">

                    @if($followers && count($followers) > 0)
                        <div class="panel z1">
                            <div class="panel-heading">
                                <h4>Followers ( Total : {{$totalCount}})</h4>
                            </div>
                            <div class="panel-body">
                                @include('users.includes.users',array('users' => $followers))
                            </div>
                        </div>
                        {!! $followers->render() !!}
                    @else
                        <div class="panel">
                            <div class="panel-heading">
                                <h4>No Followers Yet</h4>
                            </div>
                        </div>
                    @endif


                </div>
                <div class="col-md-3">
                    @include('includes.right_block')
                </div>
            </div>

        </div>
    </main>

@endsection

@section('scripts')
    <script>
        $(document).ready(function(){

            {{--$(document).on( "mouseenter mouseleave", '.btn-following', function(e){--}}
                {{--if(e.type == 'mouseenter'){--}}
                    {{--$(this).removeClass('btn-success');--}}
                    {{--$(this).addClass('btn-danger');--}}
                    {{--$(this).text('Unfollow');--}}
                {{--}else{--}}
                    {{--$(this).removeClass('btn-danger');--}}
                    {{--$(this).addClass('btn-success');--}}
                    {{--$(this).text('Following');--}}
                {{--}--}}
            {{--});--}}

            {{--$(document).on( "click", '.btn-following, .btn-follow', function(e){--}}
                {{--console.log('wfe');--}}
                {{--var button = $(this);--}}
                {{--var data = {--}}
                    {{--userId : button.data('user-id')--}}
                {{--}--}}
                {{--if($(this).hasClass('btn-follow')){--}}
                    {{--var url = '{{route('profile::follow')}}';--}}
                    {{--CrowdifyAjaxService.makeRequest(url, 'POST', data, function(response){--}}
                        {{--button.replaceWith('<button class="btn btn-success btn-following"  data-user-id="'+response.user.id+'">Following</button>');--}}
                    {{--},function(error){--}}

                    {{--})--}}
                {{--}else{--}}
                    {{--var url = '{{route('profile::unfollow')}}';--}}
                    {{--CrowdifyAjaxService.makeRequest(url, 'POST', data, function(response){--}}
                        {{--button.replaceWith(' <button class="btn btn-primary btn-follow" data-user-id="'+response.user.id+'">Follow</button>');--}}
                    {{--},function(error){--}}

                    {{--})--}}
                {{--}--}}
            {{--});--}}
        });


    </script>
@endsection