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
            <div class="row" >
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-2">
                            @include('perk.includes.home_nav')
                        </div>
                        <div class="col-md-7">
                            <div class="panel z1">
                                <div class="panel-heading">
                                    <h4>Email Broadcaster</h4>
                                </div>
                                <div class="panel-body">
                                    {!! Form::open(array(
                                'url' => route('broadcaster::broadcast'),
                            'class'=>'myForm')) !!}
                                    <div class="form-group">
                                        <label for="subject">Email Subject</label>
                                        <input type="text" name="subject" class="form-control" value="" id="email-subject"/>
                                        @if($errors->has('subject'))
                                            <label  style="color: red">Please Enter Email Subject</label>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="email-body">Email Body</label>
                                        <textarea class="textarea form-control" placeholder="Enter your text ..." autofocus name="body" id="email-body" ></textarea>
                                        @if($errors->has('body'))
                                            <label  style="color: red">Please Enter Email Body</label>
                                        @endif
                                    </div>

                                    {{--<input type="hidden" name="type" value="general"/>--}}
                                    <br />

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <p>Available Short-Codes for Email body and Subject:</p>
                                                <p>First Name : <code>%%firstname%%</code> </p>
                                                <p>Last Name : <code>%%lastname%%</code> </p>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="submit" value="Send" class="btn btn-primary"/>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@stop

@section('styles')
    <link rel="stylesheet" href="/bower_components/summernote/dist/summernote.css">
@endsection

@section('scripts')
    <script src="/bower_components/summernote/dist/summernote.min.js"></script>
    <script>
        $('.textarea').summernote({
            onImageUpload: function(files) {
                //console.log('image upload:', files);
                // upload image to server and create imgNode...
                var url = '{{ route('image::upload') }}';
//

                var  data = new FormData();
                data.append("file", files[0]);
                var baseUrl = '{{route('perk::home')}}';

                CrowdifyAjaxService.uploadImage(url, data, function(progress){

                }, function(response){
                    $('.textarea').summernote('insertImage', baseUrl+response.file_name);
                }, function(error){

                });
            }
        });

    </script>
@endsection
