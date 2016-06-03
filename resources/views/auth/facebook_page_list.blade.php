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
            <div class="panel z1">
                <div class="panel-heading">
                    <h5>Select a page to connect</h5>
                </div>
                <div class="panel-body">
                    @foreach($pages as $page)
                        @if($items = $page->all())
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-1">
                                            <h5>
                                                <img src="{{'https://graph.facebook.com/'.$items['id'].'/picture'}}" width="100%">
                                            </h5>
                                        </div>
                                        <div class="col-md-11">
                                            <a href="{{'https://www.facebook.com/pages/'.$items['name'].'/'.$items['id']}}"><h5>{{ $items['name'] }}</h5></a>
                                            @if(isset($items['about']))
                                                <p>{{ $items['about'] }}</p>
                                            @endif
                                            <p><b>Category: </b>{{ $items['category'] }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-right">
                                    {!! Form::open(array(
                                        'url' => route('auth::social::connect-facebook-page', array('page_id' => $items['id']))
                                    )) !!}
                                    <input type="hidden" name="name" value="{{ $items['name'] }}">
                                    <input type="hidden" name="page_access_token" value="{{ $items['access_token'] }}">
                                    <input type="submit" value="Connect" class="btn btn-success">
                                    {!! Form::close() !!}
                                </div>
                            </div>

                        @endif
                            <hr />
                    @endforeach
                </div>
                <div class="panel-footer text-right">
                    <a href="{{route('profile::edit')}}" class="btn btn-default-outline">Cancel</a>
                </div>
            </div>
        </div>
    </main>
@stop



@section('scripts')

    <script>
        $(document).ready(function(){

        });
    </script>
@endsection

