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
                    @if($auth->check() && $auth->user()->isAdmin())
                        <button  type="button" id="button-create-post" class="btn btn-primary pull-right" style="margin-bottom: 10px;">Create Post</button>
                    @endif
                    <div class="row">
                        <div class="col-md-12" id="place-post-form">

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12" id="places-div">
                            @if(count($places)>0)
                                @foreach($places as $place)
                                    @include('places.includes.place',array('place' => $place))
                                @endforeach
                            @else
                                <div class="panel z1 empty-div">
                                    <div class="panel-heading">
                                        <h4>No Post found</h4>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
                <div class="col-md-3">
                    @include('includes.right_block')
                </div>
            </div>
        </div>
    </main>
    @include('places.includes.modal.delete_place')
@stop

@section('scripts')
    <script src="/bower_components/summernote/dist/summernote.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>
    <script>
        $(document).ready(function(){



            var deleteId = 0;
            var deleteDiv = null;

            $('#places-div').on('click','.delete-link', function(e){
                deleteId = $(this).data('place-id');
                deleteDiv = $(this).closest('.single-place-post');
                $('#delete-place').modal('show');
            });
            $('.delete-place-button').click(function(e){
                if(deleteId){
                    var url = '{{route('places::delete', array('id' => 'place_place_holder'))}}';
                    url = url.replace('place_place_holder', ''+deleteId);
                    CrowdifyAjaxService.makeRequest(url, 'DELETE', {}, function(response){
                       if(deleteDiv){
                           deleteDiv.remove();
                       }
                        deleteId = 0;
                        deleteDiv = null;
                    }, function(error){

                    })
                }
            });



            $('#button-create-post').click(function(e){
                var url = '{{route('places::create')}}';
                var button = $(this);
                CrowdifyAjaxService.makeRequest(url, 'GET', {}, function(response){
                    var placeFormDiv = $('#place-post-form');
                    placeFormDiv.html(response.view);
                    initializeImageUpload(placeFormDiv.find('.image-upload'));
                    initializeSummerNote(placeFormDiv.find('.summernote'));
                    initilizeCreate(placeFormDiv.find('.btn-create'));
                    initializeCreateClose(placeFormDiv.find('.close'));
                    button.addClass('hidden');
                }, function(error){

                })
            });



            $('#places-div').on('click','.edit-link', function(e){
                e.preventDefault();

                var url = '{{route('places::edit', array('id' => 'place_place_holder'))}}'
                var placeId = $(this).data('place-id');
                var url = url.replace('place_place_holder',''+placeId);
                //console.log(url);
                var placePostDiv =$(this).closest('.single-place-post');
                //console.log(placePostDiv);

                CrowdifyAjaxService.makeRequest(url, 'GET', {}, function(response){
                    //console.log(response);
                    placePostDiv.addClass('hidden');
                    placePostDiv.before(response.view);

                    var placeFormDiv = placePostDiv.prev();
                    initializeImageUpload(placeFormDiv.find('.image-upload'));
                    initializeSummerNote(placeFormDiv.find('.summernote'));
                    var buttonEdit = placeFormDiv.find('.btn-edit');
                    buttonEdit.data('place-id',placeId);
                    initializeEdit(buttonEdit);
                    initilizeEditClose(placeFormDiv.find('.close'));
                }, function(error){

                })
            });

            function initializeEdit(editButton){
                editButton.click(function(e){
                    var data = getFormInput($(this).closest('.place-form'));
                    if(!validateInput(data)){
                        toastr.warning('Please fill up the value');
                        return;
                    }

                    var url = '{{route('places::edit', array('id' => 'place_place_holder'))}}'
                    var placeId = $(this).data('place-id');
                    url = url.replace('place_place_holder',''+placeId);
                    //console.log(url);
                    var placeFormDiv = $(this).closest('.place-form');

                    CrowdifyAjaxService.makeRequest(url, 'POST', data, function(response){
                        var previousPlace = placeFormDiv.next();
                        var offset = previousPlace.offset().top;
                        previousPlace.replaceWith(response.view);
                        var offset = placeFormDiv.next().offset().top - placeFormDiv.height() - 200;
                        placeFormDiv.remove();

                        $('html,body').animate({
                            scrollTop: offset}, 'slow');
                    }, function(error){

                    });
                });
            }



            function initilizeCreate(createButton){
                createButton.click(function(e){
                    var data = getFormInput($(this).closest('.place-form'));
                    if(!validateInput(data)){
                        //console.log('not valid');
                        toastr.warning('Please fill up the value');
                        return;
                    }
                    var url = '{{route('places::create')}}';
                    CrowdifyAjaxService.makeRequest(url, 'POST', data, function(response){
                        var placesDiv = $('#places-div');
                        var emptyDiv = placesDiv.find('.empty-div');
                        if(emptyDiv){
                            emptyDiv.remove();
                        }
                        placesDiv.prepend(response.view);
                        revertCreateView(createButton.closest('.place-form'));

                    }, function(error){

                    });
                });

            }

            function initializeCreateClose(createCloseButton){
                createCloseButton.click(function(e){
                    e.preventDefault();
                    revertCreateView(createCloseButton.closest('.place-form'));
                });

            }
            function initilizeEditClose(editCloseButton){
                editCloseButton.click(function(e){
                    e.preventDefault();
                    revertEditView(editCloseButton.closest('.place-form'));
                });

            }

            function revertCreateView(placeCreateForm){
                $("html,body").animate({ scrollTop: 0 }, "slow");
                placeCreateForm.remove();
                $('#button-create-post').removeClass('hidden');
            }
            function revertEditView(placeCreateForm){
                var placeDIv = placeCreateForm.next();

                placeDIv.removeClass('hidden');
                placeCreateForm.remove();

            }

            function validateInput(data){
                var isValid = true;
                for(var key in data) {
                    var value = data[key];
                    if(!value){
                        isValid = false;
                        break;
                    }
                }
                return isValid;
            }

            function getFormInput(form){
                return {
                    cover_photo: form.find('#cover-photo').val(),
                    title: form.find('#title').val(),
                    description: form.find('#summernote').val(),
                    status: form.find('#status').val()
                }
            }

            function initializeSummerNote($summernote){
                $summernote.summernote({
                    onImageUpload: function(files) {
                        //console.log('image upload:', files);
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
                    }
                });
            }



            function initializeImageUpload(imageUploadDiv){
                imageUploadDiv.imageUpload({
                    defaultImageUrl:'/assets/images/placeholder.png',
                    url : '{{route('image::upload')}}',
                    onUploaded:function(response){
                        $('#cover-photo').val(response.file_name);
                    },
                    onError:function(error){

                    }
                });
            }





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

