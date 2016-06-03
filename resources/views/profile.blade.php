
@extends('layout.master')

@section('navbar')
    @include('perk.includes.navbar')
@stop

@section('header')
    @include('perk.includes.header')
@stop

@section('content')


    <?php
        $providers = $auth->user()->profiles()->lists('provider')->toArray();
    ?>

    <main class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-2">
                    @include('perk.includes.home_nav')
                </div>
                <div class="col-md-7">
                    {!! Form::open(array(
                                                 'url' => route('profile::edit'),
                                                 'files' => true
                                             )) !!}
                    <div class="row">
                        <div class="col-md-4">
                            <div class="panel z1">
                                <div class="panel-heading">
                                    <figure class="image-upload">
                                        <img src="{{$auth->user()->avatar_original}}" class="profile-avatar-big img-responsive center-block img-circle" style="max-width: 180px;">
                                        <div class="overlay">
                                            <span class="valign-center"><i class="fa fa-camera"></i> Upload Photo</span>
                                        </div>
                                    </figure>
                                    <h5 class="text-center">{{ $user->first_name }} {{ $user->last_name }}</h5>
                                    <div class="form-group sr-only">
                                        {{--<input type="file" name="avatar" class="form-control">--}}
                                    </div>

                                    <div class="form-group">
                                        <label>Upload Photo</label>
                                        <input type="file" name="avatar" class="form-control">
                                    </div>

                                    <div class="form-group m-signature-pad" id="signature-pad" style="margin-bottom: 30px;padding-bottom: 5px;">
                                        <label>Signature</label>
                                        @if ($auth->user()->signature != null)
                                            <div class="signature-preview">
                                                <img src="{{ url($auth->user()->signature) }}" style="width: 100%;border: 1px solid #ccc;padding: 5px;margin-top: 10px;">
                                                <button type="button" class="signature-edit" style="float:right;padding: 0px 15px;border: 1px solid #ccc;border-radius: 3px;background-color: white;margin-top: 10px;margin-bottom: 10px;">Edit</button>
                                            </div>
                                            <div class="signature-edit-panel hidden">
                                                <div class="m-signature-pad--body">
                                                    <canvas style="min-height: 150px;border: 1px solid rgb(204, 204, 204); width: 100%; touch-action: none;" width="348" height="151"></canvas>
                                                </div>
                                                <div class="m-signature-pad--footer" style="text-align: left;">
                                                    <label class="description" style="font-weight: normal;color: #949494;float: left;">Sign above</label>
                                                    <button type="button" class="button clear" data-action="clear" style="float: right;border-radius: 3px;background-color: white;border: 1px solid #ccc;padding: 0px 15px;">Clear</button>
                                                    <button type="button" class="button save" data-action="save" style="float: right;border-radius: 3px;background-color: white;border: 1px solid #ccc;padding: 0px 15px;">Confirm</button>
                                                </div>
                                                <input id="signature" type="hidden" name="signature" value="">
                                            </div>
                                            @else
                                            <div class="signature-edit-panel">
                                                <div class="m-signature-pad--body">
                                                    <canvas style="min-height:150px;border: 1px solid rgb(204, 204, 204); width: 100%; touch-action: none;" width="348" height="151"></canvas>
                                                </div>
                                                <div class="m-signature-pad--footer" style="text-align: left;">
                                                    <label class="description" style="font-weight: normal;color: #949494;float: left;">Sign above</label>
                                                    <button type="button" class="button clear" data-action="clear" style="float: right;border-radius: 3px;background-color: white;border: 1px solid #ccc;padding: 0px 15px;">Clear</button>
                                                    <button type="button" class="button save" data-action="save" style="float: right;border-radius: 3px;background-color: white;border: 1px solid #ccc;padding: 0px 15px;">Confirm</button>
                                                </div>
                                                <input id="signature" type="hidden" name="signature" value="">
                                            </div>
                                        @endif
                                        <div class="instructions" style="display: block;width: 100%;margin-top: 30px;padding-left: 20px;padding-top: 20px;">
                                            <ol>
                                                <li>Click on Edit button.</li>
                                                <li>Take the pointer on the rectangle.</li>
                                                <li>Press left button of the mouse.</li>
                                                <li>Move the mouse pointer while keep the left button pressed.</li>
                                                <li>Click on Confirm button while done.</li>
                                                <li>Click on Update button to complete.</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>

                                @include('perk.includes.social_switch', array('label' => 'Twitter', 'provider' => 'twitter', 'providers', $providers))
                                <hr class="m-a-0">
                                @include('perk.includes.social_switch', array('label' => 'Facebook', 'provider' => 'facebook', 'providers', $providers))
                                <hr class="m-a-0">
                                @include('perk.includes.social_switch', array('label' => 'Facebook Page', 'provider' => 'facebookPage', 'providers', $providers))
                                <hr class="m-a-0">
                                @include('perk.includes.social_switch', array('label' => 'Google Plus', 'provider' => 'google', 'providers', $providers))
                                <hr class="m-a-0">
                                @include('perk.includes.social_switch', array('label' => 'Linkedin', 'provider' => 'linkedin', 'providers', $providers))
                                <hr class="m-a-0">
                                @include('perk.includes.social_switch', array('label' => 'Foursquare', 'provider' => 'foursquare', 'providers', $providers))
                                <hr class="m-a-0">
                                @include('perk.includes.social_switch', array('label' => 'Flickr', 'provider' => 'flickr', 'providers', $providers))
                                <hr class="m-a-0">
                                @include('perk.includes.social_switch', array('label' => 'Instagram', 'provider' => 'instagram', 'providers', $providers))
                                <hr class="m-a-0">
                                @include('perk.includes.social_switch', array('label' => 'Youtube', 'provider' => 'youtube', 'providers', $providers))
                                <hr class="m-a-0">
                                {{--@include('perk.includes.social_switch', array('label' => 'Blogger', 'provider' => 'blogger', 'providers', $providers))--}}
                                {{--<hr class="m-a-0">--}}
                                @include('perk.includes.social_switch', array('label' => 'Tumblr', 'provider' => 'tumblr', 'providers', $providers))
                            </div>
                        </div><!-- col-md-4 -->

                        <div class="col-md-8">
                            <div class="panel z1">
                                <div class="panel-body">
                                    <div class="col-md-6">
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
                                        {{--<div class="form-group">--}}
                                        {{--<label for="email">Add New Email</label>--}}
                                        {{--<input type="email" name="email" id="email" class="form-control" value="">--}}
                                        {{--@if($errors->has('email'))--}}
                                        {{--<p class=" text-danger">{{ $errors->get('email')[0] }}</p>--}}
                                        {{--@endif--}}
                                        {{--</div>--}}
                                        {{--<div class="form-group">--}}
                                        {{--<label for="country">Country</label>--}}
                                        {{--{!! Form::select('country',$countries, $user->country, array('class' => 'form-control', 'id'=>'country')) !!}--}}
                                        {{--@if($errors->has('country'))--}}
                                        {{--<p class=" text-danger">{{ $errors->get('country')[0] }}</p>--}}
                                        {{--@endif--}}
                                        {{--</div>--}}
                                        <div class="form-group">
                                            <label for="city">Closest City</label>
                                            {!! Form::select('city',$cities, $user->city, array('class' => 'form-control','id'=>'city','required'=> true)) !!}
                                            @if($errors->has('city'))
                                                <p class=" text-danger">{{ $errors->get('city')[0] }}</p>
                                            @endif
                                        </div>
                                        {{--<div class="form-group">--}}
                                        {{--<label for="gender">Gender</label>--}}
                                        {{--{!! Form::select('gender',array('Male'=>'Male','Female'=>'Female'), $user->gender, array('class' => 'form-control', 'id'=>'gender')) !!}--}}
                                        {{--@if($errors->has('gender'))--}}
                                        {{--<p class=" text-danger">{{ $errors->get('gender')[0] }}</p>--}}
                                        {{--@endif--}}
                                        {{--</div>--}}
                                        {{--<div class="form-group">--}}
                                        {{--<label for="birth-date">Birth Date</label>--}}
                                        {{--<input type="text" name="birth_date" class="form-control date-picker" id="birth-date" value="{{$user->birth_date}}">--}}
                                        {{--@if($errors->has('birth_date'))--}}
                                        {{--<p class=" text-danger">{{ $errors->get('birth_date')[0] }}</p>--}}
                                        {{--@endif--}}
                                        {{--</div>--}}
                                        <div class="form-group">
                                            <label for="website">Website (include http://)</label>
                                            <input type="text" name="website" class="form-control" id="website" value="{{$user->website}}">
                                            @if($errors->has('website'))
                                                <p class=" text-danger">{{ $errors->get('website')[0] }}</p>
                                            @endif
                                            <p class="help-block">Do you have any website?</p>
                                        </div>
                                        <div class="form-group">
                                            <label for="youtube">YouTube Video URL</label>
                                            <input type="text" name="youtube" class="form-control" id="youtube" value="{{$user->youtube}}">
                                            @if($errors->has('first_name'))
                                                <p class=" text-danger">{{ $errors->get('first_name')[0] }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        {{--<div class="form-group">--}}
                                        {{--<label for="bio">Add Bio</label>--}}
                                        {{--<textarea name="bio" rows="6" class="form-control" id="bio">{{$user->bio}}</textarea>--}}
                                        {{--</div>--}}

                                        <div class="form-group">
                                            <label for="interest">Select Interest</label>
                                            {!! Form::select('interest', $interests, $user->interest, array('class' => 'form-control','id'=>'interest','required'=> true)) !!}
                                        </div>
                                        <div class="form-group">
                                            <label for="category_1">Select Category</label>
                                            {!! Form::select('category_1',$categories, $user->category_1, array('class' => 'form-control', 'id'=> 'category_1','required'=> true)) !!}
                                        </div>
                                        <div class="form-group">
                                            <label for="category_2">Select Category</label>
                                            {!! Form::select('category_2',$categories, $user->category_2, array('class' => 'form-control', 'id'=> 'category_2','required'=> true)) !!}
                                        </div>
                                        <input type="submit" class="btn btn-default-outline" value="Update">
                                    </div>



                                </div>
                                <div class="panel-footer">
                                    <div class="text-right">
                                        <a class="btn btn-danger" href="{{ route('profile::opt-out') }}">Delete My Account</a>
                                    </div>
                                </div>

                            </div>
                            <div class="panel z1">
                                <div class="panel-body">
                                    <h2>Your affiliate link</h2>
                                    <a href="http://crowdify.tech/affiliate/{{$auth->user()->username}}">http://crowdify.tech/affiliate/{{$auth->user()->username}}</a>
                                </div>
                                </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
                <div class="col-md-3">
                    @include('includes.right_block')
                </div>
            </div>

        </div>
    </main>
@stop



@section('scripts')
    <script src="{{ url('bower_components/signature_pad/signature_pad.min.js') }}"></script>
    <script>
        $(document).ready(function(){
//            $('.date-picker').datetimepicker({
//                format:'Y-m-d'
//            });

            $('.social-switch').change(function(e){
                var provider = $(this).data('provider');
                if(provider=='twitter'){
                    if(!$(this).is(':checked')){
                        $(this).prop('checked', true);
                        toastr.error("Sorry you can't unlink your twitter account");
                    }
                }else{
                    if($(this).is(':checked')){
                        //console.log(window.location.pathname);
                        window.location = '{{url('/')}}'+'/auth/'+provider+'/connect';
                    }else{
                        var url = '{{url('/')}}'+'/auth/'+provider+'/disconnect';
                        CrowdifyAjaxService.post(url, {}, function(response){
                            window.location.reload();
                        }, function(error){
                            window.location.reload();
                        });
                    }
                }

            });

            $(".signature-edit").on('click', function () {
                $('.signature-preview').addClass("hidden");
                $('.signature-edit-panel').removeClass("hidden");
                signature_pad_initialization();
            });

            function signature_pad_initialization(wrapper) {
                // signature pad section
                var wrapper = document.getElementById("signature-pad");
                if(wrapper == null) return;
                var clearButton = wrapper.querySelector("[data-action=clear]"),
                        saveButton = wrapper.querySelector("[data-action=save]"),
                        canvas = wrapper.querySelector("canvas"),
                        signaturePad, signatureUrl;
                function resizeCanvas() {
                    var ratio =  Math.max(window.devicePixelRatio || 1, 1);
                    canvas.width = canvas.offsetWidth * ratio;
                    canvas.height = canvas.offsetHeight * ratio;
                    canvas.getContext("2d").scale(ratio, ratio);
                }

                window.onresize = resizeCanvas;
                resizeCanvas();

                signaturePad = new SignaturePad(canvas);

                clearButton.addEventListener("click", function (event) {
                    signaturePad.clear();
                    $("button.save").removeClass('hidden');
                });

                saveButton.addEventListener('click', function (event) {
                    if(signaturePad.isEmpty()){
                        alert('Please provide your signature');
                    }
                    else{
                        signatureUrl = signaturePad.toDataURL();
                        $("input#signature").val(signatureUrl);
                        $("button.save").addClass('hidden');
                    }
                });
            }

            signature_pad_initialization();
        });
    </script>
@endsection

