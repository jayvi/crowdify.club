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
                'url' => route('profile::confirm-settings')
            )) !!}
            <div class="row" >
                <div class="col-md-6 col-md-offset-3">
                    <div class="panel z1">
                        <div class="panel-heading">
                            <h3>Please Confirm Your Place and Topics</h3>
                            <p class="help-block">This information can be altered any time.</p>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="city">Closest City</label>
                                    {!! Form::select('city', $cities, $user->city, array('class' => 'form-control','required'=> true)) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="interests">Interests</label>
                                {!! Form::select('interest', $interests, $user->interest, array('class' => 'form-control','required'=> true)) !!}
                            </div>

                            <div class="form-group">
                                <label for="category_1">Category 1</label>
                                {!! Form::select('category_1', $categories, $user->category_1, array('class' => 'form-control','required'=> true)) !!}
                            </div>

                            <div class="form-group">
                                <label for="category_2">Category 2</label>
                                {!! Form::select('category_2', $categories, $user->category_2, array('class' => 'form-control','required'=> true)) !!}
                            </div>

                            <input type="submit" class="btn btn-default-outline" value="Confirm">
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </main>


    @if(isset($achievement) && $achievement)
        @include('perk.includes.modal.achievement_unlocked')
    @endif

@stop


@section('scripts')
    @if(isset($showIntroVideo) && $showIntroVideo)
        @include('perk.includes.scripts.video')
    @endif

    <script>
        $(document).ready(function(){
            @if(isset($achievement) && $achievement)
                $('#achievement-unlocked').modal('show');
            @endif

        });
    </script>
@endsection

