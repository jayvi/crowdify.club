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
                    <div class="panel z1">
                        <div class="panel-heading">
                            <h4><i>Edit tool</i></h4>
                        </div>
                        <div class="panel-body">
                            {!! Form::open(array('method' => 'put', 'files' => true)) !!}

                            <div class="form-group">
                                {!! Form::label('name', 'City Name *') !!}
                                {!! Form::text('name', $city->title, ['class' => 'form-control']) !!}
                                @if($errors->has('name'))
                                    {!! Form::label('error', $errors->get('name')[0], ['class' => 'text-danger']) !!}
                                @endif
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label for="">City Photo</label>
                                    <div class="image-upload" data-image-url="{{$city->city_photo}}">
                                    </div>
                                    <input type="hidden" name="city_photo" id="city-photo" value="{{$city->city_photo}}">
                                    @if($errors->has('city_photo'))
                                        {!! Form::label('error', $errors->get('city_photo')[0], ['class' => 'text-danger']) !!}
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('description', 'City Description *') !!}
                                <textarea name="description" id="summernote" class="form-control summernote note-editable" placeholder="Edit tool">{{ $city->description }}</textarea>
                                @if($errors->has('description'))
                                    {!! Form::label('error', $errors->get('description')[0], ['class' => 'text-danger']) !!}
                                @endif
                            </div>


                            <div class="form-group">
                                {!! Form::submit($action.' Cities', ['class' => 'btn bt-default btn-default-outline']) !!}
                            </div>
                            {!! Form::close() !!}
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
        $(document).ready(function(){


            function initializeImageUpload(imageUploadDiv){
                imageUploadDiv.imageUpload({
                    defaultImageUrl:'/assets/images/placeholder.png',
                    url : '{{route('image::upload')}}',
                    onUploaded:function(response){
                        $('#city-photo').val(response.file_name);
                    },
                    onError:function(error){

                    }
                });
            }

           initializeImageUpload($('.image-upload'));

            var $summernote = $('#summernote');
            $summernote.summernote({
                onImageUpload: function(files) {
                    console.log('image upload:', files);
                    // upload image to server and create imgNode...
                    var url = '{{ route('image::upload') }}';
//

                    var  data = new FormData();
                    data.append("file", files[0]);


                    $.ajax({
                        url:url,
                        method:'POST',
                        data:data,
                        processData: false,  // tell jQuery not to process the data
                        contentType: false,
                        success: function ( data ) {

                            $('.summernote').summernote('insertImage', data.file_name);

                            //editor.insertImage(welEditable, data.file_name);

//                            var imgNode = '<img src="'+data.file_name+'" />';
//                            $summernote.summernote('insertNode', imgNode);
                            console.log(data);
                        },
                        error: function(data)
                        {
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

            $('#blog_post_category').select2({
                placeholder : 'Select Category'
            });

            $('#blog_post_tag').select2({
                placeholder : 'Add tags'
            });

            $('#status').select2({
                placeholder : ''
            });

            $('.remove-photo').click(function(e){
                e.preventDefault();
                $(this).closest('.photo-section').remove();
            });
        });
    </script>
@endsection

@section('styles')
    <style>
        .note-editable{
            min-height: 350px !important;
        }
    </style>
    <link rel="stylesheet" href="/bower_components/summernote/dist/summernote.css">
    <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css" rel="stylesheet" />
@endsection