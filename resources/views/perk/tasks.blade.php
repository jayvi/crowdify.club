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
                            @include('perk.includes.hug_nav')
                        </div>
                        <div class="col-md-7">
                            <div class="row">
                                <div class="col-md-12">
                                    @include('perk.includes.task_create_form', array('task' => new \App\Hug()))
                                </div>
                            </div>
                        </div>

                            <div id="tasks">
                                @foreach($tasks as $task)
                                    @include('perk.includes.hug', array('hug' => $task))
                                @endforeach
                            </div>
                        </div>
                            {{--<div class="panel z1">--}}
                                {{--<div class="panel-body">--}}
                                    {{--<div class="table-responsive">--}}
                                        {{--<table class="table table-striped table-bordered table-condensed">--}}
                                            {{--<tr>--}}
                                                {{--<th>Creator</th>--}}
                                                {{--<th>Title</th>--}}
                                                {{--<th>Reward</th>--}}
                                            {{--</tr>--}}
                                            {{--@foreach($tasks as $task)--}}
                                                {{--<tr>--}}
                                                    {{--<td><a href="{{ route('perk::public_profile', array('username' => $task->user->username)) }}"> <img class="img-circle profile-avatar" width="30px" src="{{$task->user->avatar}}"/></a></td>--}}
                                                    {{--<td><a href="{{ route('hugs::show', array('hug_id' => $task->id)) }}">{{ $task->title }}</a></td>--}}
                                                    {{--<td><span class="label label-deafult-outline"><i class="crowd-coin"></i> {{ $task->reward }}</span></td>--}}
                                                {{--</tr>--}}
                                            {{--@endforeach--}}
                                        {{--</table>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        </div>
                        <div class="col-md-3">
                            @include('includes.right_block')
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>
    @include('perk.includes.modal.hug_completers')
@endsection

@section('scripts')
    @include('perk.includes.scripts.tasks')
    @include('perk.includes.scripts.hug')
@endsection