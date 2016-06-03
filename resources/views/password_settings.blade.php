@extends('layout.master')

@section('navbar')
    @include('perk.includes.navbar')
@stop

@section('header')
    @include('perk.includes.header')
@stop

@section('content')
    <div class="wrapper-table" style="padding-top: 10%;">
        <div class="align-center">
            <div class="auth-box">
                <div class="auth-body">
                    @if(!$emailConfirmation)
                        <h5>Please set your information here</h5>
                        {!! Form::open(array(
                          'url' => route('auth::password::settings'), 'files'  => true, 'id' => 'registration_confirm_form'
                      )) !!}
                        <div class="form-group">
                            <input type="text" name="first_name" id="first_name" class="form-control" value="{{$user->first_name}}" placeholder="First Name">
                            @if($errors->has('first_name'))
                                <p class="text-danger">{{ $errors->get('first_name')[0] }}</p>
                            @endif
                        </div>
                        <div class="form-group">
                            <input type="text" name="last_name" id="last_name" class="form-control" value="{{$user->last_name}}" placeholder="Last Name">
                            @if($errors->has('last_name'))
                                <p class="text-danger">{{ $errors->get('last_name')[0] }}</p>
                            @endif
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" id="email" class="form-control" value="" placeholder="Email">
                            @if($errors->has('email'))
                                <p class=" text-danger">{{ $errors->get('email')[0] }}</p>
                            @endif
                        </div>

                        <div class="form-group">
                            <input type="password" name="password" id="password" class="form-control" value="" placeholder="Password">
                            @if($errors->has('password'))
                                <p class=" text-danger">{{ $errors->get('password')[0] }}</p>
                            @endif
                        </div>

                        <div class="form-group">
                            <input type="password" name="password_confirmation" id="password-confirmation" class="form-control" value="" placeholder="Confirm Password">
                            @if($errors->has('password_confirmation'))
                                <p class=" text-danger">{{ $errors->get('password_confirmation')[0] }}</p>
                            @endif
                        </div>

                        <div class="form-group m-signature-pad" id="signature-pad" style="padding-bottom: 20px;">
                            <div class="m-signature-pad--body">
                                <canvas style="border: 1px solid rgb(204, 204, 204); width: 100%; touch-action: none; border-radius: 5px;" width="348" height="151"></canvas>
                            </div>
                            <div class="m-signature-pad--footer" style="text-align: left;">
                                <label class="description" style="font-weight: normal;color: #949494;float: left;">Sign above</label>
                                <button type="button" class="button clear" data-action="clear" style="float: right;border-radius: 3px;background-color: white;border: 1px solid #ccc;padding: 0px 15px;">Clear</button>
                                <button type="button" class="button save" data-action="save" style="float: right;border-radius: 3px;background-color: white;border: 1px solid #ccc;padding: 0px 15px;">Confirm</button>
                            </div>
                            <input id="signature" type="hidden" name="signature" value="">
                            <div class="instructions" style="text-align:left;display: block;width: 100%;margin-top: 30px;padding-left: 20px;padding-top: 20px;">
                                <ol>
                                    <li>Take the pointer on the rectangle.</li>
                                    <li>Press left button of the mouse.</li>
                                    <li>Move the mouse pointer while keep the left button pressed.</li>
                                    <li>Click on Confirm button while done.</li>
                                    <li>Click on Submit button to complete.</li>
                                </ol>
                            </div>
                        </div>

                        <input type="submit" class="btn btn-default-outline modal-login-btn" value="Submit"/>
                        @if(Session::has('loginError'))
                            <label class="text-danger">{{ Session::get('loginError') }}</label>
                        @endif

                        {!! Form::close() !!}
                    @else
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <h5>A confirmation email has been sent to <code>{{ $emailConfirmation->email}}</code></h5>
                                <p><i>Please Check your email to confirm</i></p>
                                {!! Form::open(array('url' => route('auth::confirmation::resend'))) !!}
                                <input type="hidden" name="email" value="{{$emailConfirmation->email}}">
                                <input type="submit" value="Re-send Confirmation Mail" class="btn btn-default-outline form-control">
                                {!! Form::close() !!}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script src="{{ url('bower_components/signature_pad/signature_pad.min.js') }}"></script>
    <script>
        $(document).ready(function(){
            $('.date-picker').datetimepicker({
                format:'Y-m-d'
            });

            // signature pad section
            var wrapper = document.getElementById("signature-pad"),
                    clearButton = wrapper.querySelector("[data-action=clear]"),
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
            })

            $("#registration_confirm_form").on('submit', function (e) {
                if(signaturePad.isEmpty()){
                    e.preventDefault();
                    alert('Please provide your signature');
                }
            });
        });
    </script>
@endsection

