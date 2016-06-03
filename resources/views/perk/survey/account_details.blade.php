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
                       'url' => route('profile::edit'),
                       'files' => true
                   )) !!}
            <div class="row  profile-box" >
                <div class="col-md-6" style="padding-right: 50px;">

                    <div class="form-group">
                        <label for="first-name">First Name</label>
                        <input type="text" name="first_name" class="form-control" id="first-name" value="{{$user->first_name}}">
                        @if($errors->has('first_name'))
                            <p class=" text-danger">{{ $errors->get('first_name')[0] }}</p>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="last-name">Last Name</label>
                        <input type="text" name="last_name" class="form-control" id="last-name" value="{{$user->last_name}}">
                        @if($errors->has('last_name'))
                            <p class=" text-danger">{{ $errors->get('last_name')[0] }}</p>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="email">Add New Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="">
                        @if($errors->has('email'))
                            <p class=" text-danger">{{ $errors->get('email')[0] }}</p>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="country">Country</label>
                        {!! Form::select('country',array('Bangladesh'), $user->country, array('class' => 'form-control', 'id'=>'country')) !!}
                        @if($errors->has('country'))
                            <p class=" text-danger">{{ $errors->get('country')[0] }}</p>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="city">City</label>
                        {!! Form::select('city',array('Dhaka'), $user->city, array('class' => 'form-control','id'=>'city')) !!}
                        @if($errors->has('city'))
                            <p class=" text-danger">{{ $errors->get('city')[0] }}</p>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        {!! Form::select('gender',array('Male'=>'Male','Female'=>'Female'), $user->gender, array('class' => 'form-control', 'id'=>'gender')) !!}
                        @if($errors->has('gender'))
                            <p class=" text-danger">{{ $errors->get('gender')[0] }}</p>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="birth-date">Birth Date</label>
                        <input type="text" name="birth_date" class="form-control date-picker" id="birth-date" value="{{$user->birth_date}}">
                        @if($errors->has('birth_date'))
                            <p class=" text-danger">{{ $errors->get('birth_date')[0] }}</p>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="website">Website (include http://)</label>
                        <input type="text" name="website" class="form-control" id="website" value="{{$user->website}}">
                        @if($errors->has('website'))
                            <p class=" text-danger">{{ $errors->get('website')[0] }}</p>
                        @endif
                        <p class="help-block">Do you have any website?</p>
                    </div>
                </div>

                <div class="col-md-6" style="padding-left: 50px;">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Profile Picture</label>
                        </div>

                        <div class="col-md-6 text-center">
                            <img src="{{$auth->user()->avatar}}" class="profile-avatar img-circle"><br />
                            <p class="label label-info">Current Profile Picture</p>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <p >Upload Profile Picture</p>
                                <input type="file" name="avatar" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="from-group">
                                <label for="bio">Add Bio</label>
                                <textarea name="bio" rows="6" class="form-control" id="bio">{{$user->bio}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="interest">Select Interest</label>
                        {!! Form::select('interest', $interests, $user->interest, array('class' => 'form-control','id'=>'interest')) !!}
                    </div>
                    <div class="form-group">
                        <label for="category_1">Select Category</label>
                        {!! Form::select('category_1',$categories, $user->category_1, array('class' => 'form-control', 'id'=> 'category_1')) !!}
                    </div>
                    <div class="form-group">
                        <label for="category_2">Select Category</label>
                        {!! Form::select('category_2',$categories, $user->category_2, array('class' => 'form-control', 'id'=> 'category_2')) !!}
                    </div>
                    <input type="submit" class="btn btn-primary" value="Update">
                </div>
            </div>
            {!! Form::close() !!}
            <div class="row">
                <div class="col-md-12 profile-box">
                    <h5>Connect Your Social Network</h5>
                    @if($profiles = $user->profiles)
                        <div class="row">
                            @foreach($profiles as $profile)
                                <div class="col-md-2 text-center">
                                    <img src="{{$profile->avatar}}" class="img-thumbnail"><br />
                                    <p class="label label-info">
                                        @if($profile->provider == 'twitter')
                                            <i class="ion-social-twitter modal-icons"></i>{{$profile->username}}
                                        @endif
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <?php
                    $providers = $profiles->lists('provider')->toArray();
                    ?>
                    <div class="row">
                        @if(!in_array('twitter', $providers))
                            <div class="col-md-4">
                                <a href='{{route('auth::social::connect', array('provider'=> 'twitter'))}}' class="btn btn-default twitter"> <i class="ion-social-twitter modal-icons"></i> Connect Twitter </a>
                            </div>
                        @endif
                        @if(!in_array('facebook', $providers))
                            <div class="col-md-4">
                                <a href='{{route('auth::social::connect', array('provider'=> 'facebook'))}}' class="btn btn-default facebook"> <i class="ion-social-facebook modal-icons"></i> Connect Facebook </a>
                            </div>
                        @endif
                        @if(!in_array('google', $providers))
                            <div class="col-md-4">
                                <a href='{{route('auth::social::connect', array('provider'=> 'google'))}}' class="btn btn-default google"> <i class="ion-social-googleplus modal-icons"></i> Connect Google </a>
                            </div>
                        @endif
                        @if(!in_array('linkedin', $providers))
                            <div class="col-md-4">
                                <a href='{{route('auth::social::connect', array('provider'=> 'linkedin'))}}' class="btn btn-default linkedin"> <i class="ion-social-linkedin modal-icons"></i> Connect LinkedIn </a>
                            </div>
                        @endif
                    </div>
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

