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
                    <?php
                    use Jenssegers\Agent\Agent as Agent;
                    $Agent = new Agent();
                    ?>
                    <?php
                    if ($Agent->isMobile()) {
                    ?>
                    <ul class="nav nav-tabs">
                        <li role="presentation"><a href="{{ route('blog::home') }}">Blogs</a></li>
                        <li role="presentation" class="active"><a href="{{ route('blog::create') }}">Create</a></li>
                        <li role="presentation"><a href="{{ route('blog::my-blogs') }}">My Blogs</a></li>
                    </ul>
                    <?php
                    }
                        ?>
                    @include('perk.includes.blog_nav')
                </div>
                <div class="col-md-7">
                    <div class="panel z1">
                        <div class="panel-heading">
                            <h5><i>Create your blog post</i></h5>
                        </div>
                        <div class="panel-body">
                            @if($action == 'Update')
                                {!! Form::open(array('method' => 'put', 'files' => true)) !!}
                            @else
                                {!! Form::open(array('files' => true)) !!}
                            @endif
                                <div class="form-group">
                                    {!! Form::label('cover_photo', 'Feature Photo') !!}
                                    <input type="file" name="cover_photo" class="form-control" id="cover_photo" accept="image/*">
                                </div>
                                @if($action == 'Update')
                                    @if($blog->cover_photo)
                                        <div class="photo-section" href="{{ $blog->cover_photo }}" target="_blank" style="padding: 10px;">
                                            <div class="row">
                                                <div class="col-sm-6 col-md-12 pull-left">
                                                    <a class="remove-photo pull-right" href="" style="margin-right: -12px; margin-top: -15px;"><span class="ion-ios-close-outline" style="font-size: x-large"></span> </a>
                                                    <div class="thumbnail">
                                                        <img src="{{ $blog->cover_photo }}" alt="" width="100%">
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="photo_url" value="{{ $blog->cover_photo }}"/>
                                        </div>
                                    @endif
                                @endif

                                <div class="form-group">
                                    {!! Form::label('title', 'Title *') !!}
                                    {!! Form::text('title', $blog->title, ['class' => 'form-control']) !!}
                                    @if($errors->has('title'))
                                        {!! Form::label('error', $errors->get('title')[0], ['class' => 'text-danger']) !!}
                                    @endif
                                </div>

                                <div class="form-group">
                                    {!! Form::label('description', 'Description *') !!}
                                    <textarea name="description" id="summernote" class="form-control summernote note-editable" placeholder="Tell people what's this blog about">{{ $blog->description }}</textarea>
                                @if($errors->has('description'))
                                        {!! Form::label('error', $errors->get('description')[0], ['class' => 'text-danger']) !!}
                                    @endif
                                </div>

                                <div class="form-group">
                                    {!! Form::label('categories', 'Categories') !!}
                                    {!! Form::select('categories[]', $blog_post_categories, $selected_categories_id, ['class' => 'form-control', 'id' => 'blog_post_category', 'multiple']) !!}
                                </div>

                                <div class="form-group">
                                    {!! Form::label('tags', 'Tags') !!}
                                    {!! Form::select('tags[]', $blog_post_tags, $selected_tags_id, ['class' => 'form-control', 'id' => 'blog_post_tag', 'multiple']) !!}
                                </div>

                                <div class="form-group">
                                    {!! Form::label('status', 'Status') !!}
                                    {!! Form::select('status', array('Draft' => 'Draft', 'Published' => 'Published'), $blog->status, ['class' => 'form-control', 'id' => 'status']) !!}
                                </div>

                                <div class="form-group">
                                    {!! Form::submit($action.' Blog Post', ['class' => 'btn bt-default btn-default-outline']) !!}
                                </div>
                                {!! Form::close() !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    @include('includes.right_block')
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

            var $summernote = $('#summernote');
            $summernote.summernote({
                onImageUpload: function(files) {
                    console.log('image upload:', files);
                    // upload image to server and create imgNode...
                    var url = '{{ route('image::upload') }}';
//

                    var  data = new FormData();
                    data.append("file", files[0]);

                    CrowdifyAjaxService.uploadImage(url, data, function(progress){

                    }, function(response){
                        $('.summernote').summernote('insertImage', response.file_name);
                    }, function(error){

                    });

//                    $.ajax({
//                        url:url,
//                        method:'POST',
//                        data:data,
//                        processData: false,
//                        contentType: false,
//                        success: function ( data ) {
//                            console.log(data);
//                            $('.summernote').summernote('insertImage', data.file_name);
//
//                          console.log(data);
//                        },
//                        error: function(data)
//                        {
//                            console.log(data);
//                        }
//                    });





//                    CrowdifyAjaxService.makeRequest(url, 'POST', data,function(response){
//                        console.log(response);
//                    }, function(error){
//                        console.log(error);
//                    });

                }
            });

            $('#blog_post_category').select2({
                placeholder : 'Select Category',
                width : '100%'
            });

            $('#blog_post_tag').select2({
                placeholder : 'Add tags',
                width : '100%'
            });

            $('#status').select2({
                placeholder : '',
                width : '100%'
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