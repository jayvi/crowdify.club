@extends('layout.master')

@section('navbar')
    @include('perk.includes.navbar')
@stop

@section('content')
    <div class="content signature">
        <div class="container-fluid">
            <div class="form-wrapper text-center">
                <div class="form-inner-wrapper">
                @include('includes.notify')
                {!! Form::open(array('method' => 'post', 'files'  => true, 'id' => 'signature-form')) !!}
                    <h2>Signature</h2>
                    <div class="form-group m-signature-pad" id="signature-pad">
                        <div class="m-signature-pad--body">
                            <canvas width="348" height="151"></canvas>
                        </div>
                        <div class="m-signature-pad--footer">
                            {!! Form::label('sign_above', 'Sign above') !!}
                            {!! Form::button('Confirm', array('type' => 'button', 'class' => 'button save', 'data-action' => 'save')) !!}
                            {!! Form::button('Clear', array('type' => 'button', 'class' => 'button clear', 'data-action' => 'clear')) !!}
                        </div>
                        {!! Form::input('hidden','signature','', array('id' => 'signature')) !!}
                    </div>
                        {!! Form::input('submit', 'submit', 'Submit', array('class' => 'btn btn-default-outline')) !!}
                {!! Form::close() !!}

                    <div class="instructions">
                        <ol>
                            <li>Take the pointer on the rectangle.</li>
                            <li>Press left button of the mouse.</li>
                            <li>Move the mouse pointer while keep the left button pressed.</li>
                            <li>Click on Confirm button while done.</li>
                            <li>Click on Submit button to complete.</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ url('bower_components/signature_pad/signature_pad.min.js') }}"></script>
    <script>
        $(document).ready(function () {

            // signature pad section
            var wrapper = document.getElementById("signature-pad"),
                    clearButton = wrapper.querySelector("[data-action=clear]"),
                    saveButton = wrapper.querySelector("[data-action=save]"),
                    canvas = wrapper.querySelector("canvas"),
                    signaturePad, signatureUrl;
            console.log(wrapper);
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

            $("#signature-form").on('submit', function (e) {
                if(signaturePad.isEmpty()){
                    e.preventDefault();
                    alert('Please provide your signature');
                }
            });
        });

    </script>
@endsection