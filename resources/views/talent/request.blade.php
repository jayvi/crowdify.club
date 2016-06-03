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
                    @include('perk.includes.talent_nav')
                </div>
                <div class="col-md-7">
                    <div class="panel z1">
                        <div class="panel-heading">
                            <h4><i>Request Tallent</i></h4>

                            <div class="panel-body">
                                @if($auth->user()->isFreeUser())
                                    <br>
                                    <p>Must be a paid member to use the Talent area. Join <a
                                                href="{{ route('subscriptions::home') }}">here</a></p>
                                @else
                                    {!! Form::open(array('method' => 'put', 'files' => true)) !!}

                                    <div class="form-group">
                                        {!! Form::label('title', 'Title *') !!}
                                        {!! Form::text('title', '', ['class' => 'form-control','placeholder' => 'Title']) !!}
                                        @if($errors->has('title'))
                                            {!! Form::label('error', $errors->get('title')[0], ['class' => 'text-danger']) !!}
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('talent_photo', 'talent Photo') !!}
                                        <input type="file" name="talent_photo" class="form-control" id="talent_photo"
                                               accept="image/*">
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('metatag', 'Short Description *') !!}
                                        {!! Form::textarea('metatag', 'Meta description', ['class' => 'form-control']) !!}
                                        @if($errors->has('metatag'))
                                            {!! Form::label('error', $errors->get('metatag')[0], ['class' => 'text-danger']) !!}
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('description', 'Description *') !!}
                                        {!! Form::textarea('description','',array('id' => 'summernote', 'class' => 'form-control summernote note-editable', 'placeholder' => 'Description')) !!}
                                        @if($errors->has('description'))
                                            {!! Form::label('error', $errors->get('description')[0], ['class' => 'text-danger']) !!}
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('category_1', 'Select Category') !!}
                                        {!! Form::select('category_1', $categories) !!}

                                        <label for="category_2">Select Category 2</label>
                                        <select id="category_2" name="category_2">
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('bitcoins', 'Bitcoins *') !!}
                                        {!! Form::number('bitcoins','',array( 'step' => 'any', 'class' => 'form-control',)) !!}

                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('crowdcoins', 'Crowdcoin *') !!}
                                        {!! Form::number('crowdcoins','',array('class' => 'form-control',)) !!}
                                        @if($errors->has('crowdcoins'))
                                            {!! Form::label('error', $errors->get('bitcoin')[0]) !!}
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        {!! Form::submit('Talent', ['class' => 'btn bt-default btn-default-outline']) !!}
                                    </div>
                                    {!! Form::close() !!}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('scripts')
    <script src="/bower_components/summernote/dist/summernote.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>


    <script>
        $(document).ready(function () {
            $("#category_1").change(function () {
                $.getJSON("../talent/category/" + $("#category_1").val(), function (data) {
                    var $stations = $("#category_2");
                    $stations.empty();
                    $.each(data, function (index, value) {
                        $stations.append('<option value="' + index + '">' + value + '</option>');
                    });
                    $("#category_2").trigger("change");
                    /* trigger next drop down list not in the example */
                });
            });
            var $summernote = $('#summernote');
            $summernote.summernote({

                onImageUpload: function (files) {
                    console.log('image upload:', files);
                    // upload image to server and create imgNode...
                    var url = '{{ route('image::upload') }}';
//

                    var data = new FormData();
                    data.append("file", files[0]);


                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: data,
                        processData: false,  // tell jQuery not to process the data
                        contentType: false,
                        success: function (data) {

                            $('.summernote').summernote('insertImage', data.file_name);

                            //editor.insertImage(welEditable, data.file_name);

//                            var imgNode = '<img src="'+data.file_name+'" />';
//                            $summernote.summernote('insertNode', imgNode);
                            console.log(data);
                        },
                        error: function (data) {
                            console.log(data);
                        }
                    });


//                    CrowdifyAjaxService.makeRequest(url, 'POST', data,function(response){
//                        console.log(response);
//                    }, function(error){
//                        console.log(error);
//                    });

                }
            });

        });
    </script>
@endsection

@section('styles')
    <style>
        .note-editable {
            min-height: 350px !important;
        }
    </style>
    <link rel="stylesheet" href="/bower_components/summernote/dist/summernote.css">
    <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css" rel="stylesheet"/>
@endsection

