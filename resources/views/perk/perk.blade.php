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
                    @include('perk.includes.perk_nav')
                </div>
                <div class="col-md-7">
                    <div class="panel z1">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h4>{{ $perk->title }}

                                    </h4>
                                    @if($perk->user_id == $auth->id())
                                        <div class="dropdown dropdown-modify">
                                            <a class="dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                <i class="fa fa-angle-down"></i>
                                            </a>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                                <li><a class="edit-link" data-perk-id="{{ $perk->id }}" href="">Edit</a></li>
                                                <li><a class="delete-link" data-toggle="modal" data-target="#delete-perk" data-perk-id="{{ $perk->id }}">Delete</a></li>
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <dl>
                                <dt>Perk Description:</dt>
                                <dd>{{ $perk->description }}</dd>
                            </dl>

                            <dl>
                                <dt>Perk URL:</dt>
                                <dd><a href="{{ $perk->link }}" target="_blank">{{$perk->link}}</a></dd>
                            </dl>
                            @if($perk->logo_url)
                                <dl>
                                    <dt>Photo</dt>
                                    <dd>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <img src="{{$perk->logo_url}}" alt="" width="100%">
                                            </div>
                                        </div>
                                    </dd>
                                </dl>
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

    @include('perk.includes.modal.edit_perk');
    @include('perk.includes.modal.delete_perk')

@stop


@section('scripts')

    <script>
        $(document).ready(function(){



            $('.edit-link').click(function(e){
                        e.preventDefault();
                        var id = $(this).data('perk-id');
                        var url = '{{route('perk::home')}}'+'/perks/'+id+'/edit';
                        CrowdifyAjaxService.makeRequest(url, 'GET', {}, function(response){
                            console.log(response);
                            var editPerkModal = $('#edit-perk');
                            editPerkModal.find('.modal-content').html(response.view);
                            editPerkModal.modal('show');
                        }, function(error){
                            console.log(error);
                        });
                    });

            var deleteId = 0;

            $('.delete-link').click(function(e){
                deleteId = $(this).data('perk-id');
            });
            $('.perk-delete-button').click(function(e){

                var url = '{{route('perk::home')}}'+'/perks/'+deleteId;
                if(deleteId){
                    CrowdifyAjaxService.makeRequest(url, 'DELETE', {}, function(response){
                        console.log(response);
                        location.reload();

                    }, function(error){
                        console.log(error);
                    });
                }

            });
        });
    </script>


@endsection