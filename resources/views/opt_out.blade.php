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
            {!! Form::open(array(
                  'url' => route('profile::opt-out'),
            )) !!}
            <div class="row">
                <div class="col-md-12">
                    <div class="panel z1">
                        <div class="panel-heading">
                            <h5>Opt Out</h5>
                            <p class="" style="font-size: small;">If you would like to delete your account</p>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="">Reason you're leaving</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input type="radio" name="opt_out_reason" id="radio1">
                                        <label class="text-muted" for="radio1">I don't find Crowdify useful</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="radio" name="opt_out_reason" id="radio2">
                                        <label class="text-muted" for="radio2">I am concerned with privacy</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="radio" name="opt_out_reason" id="radio3">
                                        <label class="text-muted" for="radio3">I don't understand how to use Crowdify</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="radio" name="opt_out_reason" id="radio4">
                                        <label class="text-muted" for="radio4">I expected better perks</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="radio" name="opt_out_reason" id="radio5">
                                        <label class="text-muted" for="radio5"> This is temporary. I'll be back</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="radio" name="opt_out_reason" id="radio6">
                                        <label class="text-muted" for="radio6"> Other</label>
                                    </div>
                                    @if($errors->has('opt_out_reason'))
                                        <p class="text-danger">{{ $errors->get('opt_out_reason')[0] }}</p>
                                    @endif
                                </div>
                            </div>
                            <hr />
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="">Further explanation</label>
                                </div>
                                <div class="col-md-8">
                                    {!! Form::textarea('explanation','', array('class'=> 'form-control', 'rows' => 4)) !!}

                                </div>
                            </div>
                            <hr />
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="">Are you sure you'd like to opt out of Crowdify?</label>
                                </div>
                                <div class="col-md-8">
                                    <label for="" class="text-muted">Once you opt-out you will be removed from crowdify.tech.</label>
                                </div>
                            </div>
                            <hr />
                            <div class="row">
                                <div class="col-md-8 col-md-offset-4">
                                    <div class="text-right">
                                        <a class="btn btn-default-outline" href="{{route('perk::home')}}">Cancel</a>
                                        <input type="submit" value="Submit" class="btn btn-primary">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </main>
@stop


@section('styles')
    <link rel="stylesheet" href="{{ url('/bower_components/iCheck/skins/all.css') }}">
@endsection

@section('scripts')
    <script src="{{ url('/bower_components/iCheck/icheck.min.js') }}"></script>

    <script>
        $(document).ready(function(){
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
              //  increaseArea: '20%' // optional
            });
        });
    </script>
@endsection

