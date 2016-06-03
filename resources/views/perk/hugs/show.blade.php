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
                            <div class="row">
                                <div class="col-sm-12">
                                    <h4><span ><a href="{{ route('perk::public_profile', array('username' => $hug->user->username)) }}"> <img class="img-circle profile-avatar" src="{{$hug->user->avatar}}"/></a></span>  {{ $hug->title }}
                                        @if($auth->check())
                                            @if($hug->user_id == $auth->id() || $auth->user()->isAdmin())
                                                <div class="dropdown dropdown-modify">
                                                    <a class="dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                        <i class="fa fa-angle-down"></i>
                                                    </a>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                                        <li><a href="{{ route('hugs::edit', ['id' => $hug->id]) }}">Edit</a></li>
                                                        <li><a class="delete-link" data-toggle="modal" data-target="#delete-hug" data-hug-id="{{ $hug->id }}">Delete</a></li>
                                                    </ul>
                                                </div>
                                            @endif
                                        @endif
                                    </h4>
                                </div>
                            </div>
                        </div>

                        <div class="panel-body">
                            <dl>
                                <dt>Task Description:</dt>
                                <dd>{{ $hug->description }}</dd>
                            </dl>

                            <dl>
                                <dt>Task URL:</dt>
                                <dd><a href="{{ $hug->link }}">{{ $hug->link }}</a></dd>
                            </dl>
                            @if($hug->photo)
                                <dl>
                                    <dt>Photo</dt>
                                    <dd>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <img src="{{$hug->photo}}" alt="" width="100%">
                                            </div>
                                        </div>
                                    </dd>
                                </dl>
                            @endif

                            @if(!$isExpired)
                                @if($auth->id() != $hug->user_id)
                                    @if($hug_completed)
                                        <button class="btn btn-default-outline">Completed</button>
                                    @else
                                        <button class="btn btn-default-outline" id="hug-complete" data-url="{{ $hug->link }}" data-hug-id="{{ $hug->id }}">Complete</button>
                                    @endif
                                @endif
                            @else
                                <button class="btn btn-default-outline">Expired</button>
                            @endif
                        </div>
                        <div class="panel-footer">
                            <h4 class=""><span class="label label-deafult-outline"><i class="fa fa-gift"></i> {{ $hug->reward }}</span></h4>
                        </div>
                    </div>
                    @if($auth->id() == $hug->user_id)
                        @include('perk.includes.hug_completer_details')
                    @endif

                    @if(\App\Helpers\DataUtils::COMMENT_ENABLE)
                        <div class="panel z1">
                            <div class="panel-heading">
                                Leave a Comment
                            </div>

                            <div class="panel-body">
                                <div class="form-group">
                                    <textarea class="form-control" id="comment" name="comment" placeholder="Enter text"></textarea>
                                </div>
                                <button class="btn btn-default-outline" id="btn-comment">Post</button>
                            </div>
                        </div>

                        <div class="panel z1">
                            <div class="panel-heading">
                                Comments
                            </div>
                            <div id="comments-div">
                                @foreach($hug->comments as $comment)
                                    @include('perk.comment', array('comment' => $comment))
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-md-3">
                    @include('includes.right_block')
                </div>
            </div>
        </div>
    </main>

    @include('perk.includes.modal.hug_completers')
    @include('perk.includes.modal.hug_delete',array('task' => $hug))
@stop


@section('scripts')
    @include('perk.includes.scripts.hug')
    <script>
        $(document).ready(function(){
            $('#hug-complete').click(function(e) {
                var button = $(this);
                var url = button.attr('data-url');
                $.ajax({
                    url: '{{ route('hugs::completion', ['id' => $hug->id]) }}',
                    method: 'POST',
                    async : false,
                }).done(function(response){
                    if(response.success){
                        button.replaceWith('<button class="btn btn-success">Completed</button>');
                        window.open(url);
                    }
                }).fail(function(response){
                    console.log(response);
                })
            });

            @if(\App\Helpers\DataUtils::COMMENT_ENABLE)

                var url = '{{ route('hugs::comments::create', array('hug_id'=> $hug->id)) }}'

                $('#btn-comment').click(function(e){
                    var comment = $('#comment').val();
                    if(comment){
                        $.ajax({
                            url: url,
                            type: "POST",
                            data: {
                                //Set an empty response to see the error
                                comment: comment
                            },
                            success: function(result,status,xhr) {
                                $('#comments-div').prepend(result.view);
                                $('#comment').val('');
                            },
                            error: function(xhr,status,error){
                                var error = JSON.parse(xhr.responseText);
                                if(error.message){
                                    toastr.error(error.message);
                                }

                            }
                        });
                    }else{
                        toastr.error('Comment Required','Please Enter a comment');
                    }

                });
            @endif

              $('.delete-link').on('click', function(e){
                        e.preventDefault();
                        var delete_id = $(this).data('hug-id');
                        $('#delete-modal-hug-id').val(delete_id);
                    });
        });
    </script>
@endsection