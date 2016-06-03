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
                    @if(!$isLimitExceeded)

                        <div class="panel z1">
                            <div class="panel-heading">
                                <h5><i>Please make sure that all tasks are about content about technology</i></h5>
                            </div>
                            <div class="panel-body">
                                @if($action == 'Update')
                                    {!! Form::open(array('method' => 'put', 'files' => true)) !!}
                                @else
                                    {!! Form::open(array('files' => true)) !!}
                                @endif

                                <div class="form-group">
                                    {!! Form::label('title', 'Title *') !!}
                                    {!! Form::text('title', $hug->title, ['class' => 'form-control']) !!}
                                    @if($errors->has('title'))
                                        {!! Form::label('error', $errors->get('title')[0], ['class' => 'text-danger']) !!}
                                    @endif
                                </div>

                                <div class="form-group">
                                    {!! Form::label('link', 'Task Link *') !!}
                                    {!! Form::text('link', $hug->link, ['class' => 'form-control']) !!}
                                    @if($errors->has('link'))
                                        {!! Form::label('link', $errors->get('link')[0], ['class' => 'text-danger']) !!}
                                    @endif
                                </div>

                                <div class="form-group">
                                    {!! Form::label('description', 'Description *') !!}
                                    {!! Form::textarea('description', $hug->description, ['class' => 'form-control']) !!}
                                    @if($errors->has('description'))
                                        {!! Form::label('error', $errors->get('description')[0], ['class' => 'text-danger']) !!}
                                    @endif
                                </div>

                                <div class="form-group">
                                    @if($hug->photo)
                                        <label for="photo">Change Photo</label>
                                    @else
                                        <label for="photo">Upload Photo</label>
                                    @endif
                                    <input type="file" name="photo" class="form-control" id="photo" accept="image/*">
                                    @if($action == 'Update')
                                        @if($hug->photo)
                                                <div class="photo-section" href="{{ $hug->photo }}" target="_blank" style="padding: 10px;">
                                                    <div class="row">
                                                        <div class="col-sm-6 col-md-12 pull-left">
                                                            <a class="remove-photo pull-right" href="" style="margin-right: -12px; margin-top: -15px;"><span class="ion-ios-close-outline" style="font-size: x-large"></span> </a>
                                                            <div class="thumbnail">
                                                                <img src="{{ $hug->photo }}" alt="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="photo_url" value="{{ $hug->photo }}"/>
                                                </div>
                                        @endif
                                    @endif
                                </div>
                                <div class="form-group">
                                    {!! Form::label('total_amount', 'Total amount *') !!}
                                    {!! Form::number('total_amount', $hug->total_amount, ['class' => 'form-control']) !!}
                                    @if($errors->has('total_amount'))
                                        {!! Form::label('error', $errors->get('total_amount')[0], ['class' => 'text-danger']) !!}
                                    @endif
                                </div>

                                <div class="form-group">
                                    {!! Form::label('reward', 'Reward *') !!}
                                    {!! Form::number('reward', $hug->reward, ['class' => 'form-control']) !!}
                                    @if($errors->has('reward'))
                                        {!! Form::label('error', $errors->get('reward')[0], ['class' => 'text-danger']) !!}
                                    @endif
                                </div>

                                {{--<div class="form-group">--}}
                                    {{--{!! Form::label('expired_date', 'Expiry Date *') !!}--}}
                                    {{--{!! Form::text('expired_date', $hug->expired_date, ['class' => 'form-control date-picker']) !!}--}}
                                    {{--@if($errors->has('expired_date'))--}}
                                        {{--{!! Form::label('error', $errors->get('expired_date')[0], ['class' => 'text-danger']) !!}--}}
                                    {{--@endif--}}
                                {{--</div>--}}

                                {{--<div class="form-group">--}}
                                    {{--{!! Form::label('status', 'Status *') !!}--}}
                                    {{--{!! Form::select('status', array('Active' => 'Active', 'In-Active' => 'In-Active'), 'Active') !!}--}}
                                {{--</div>--}}

                                <div class="form-group">
                                    {!! Form::submit($action.' Task', ['class' => 'btn bt-default btn-default-outline']) !!}
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>

                    @else
                        <div class="alert text-warning alert-dismissible bg-white z1" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            Your Active Tasks Limit Exceeded
                        </div>
                    @endif
                </div>
                <div class="col-md-3">
                    @include('includes.right_block')
                </div>
            </div>
        </div>
    </main>

@stop


@section('scripts')
    <script>
        $(document).ready(function(){
            $('.date-picker').datetimepicker({
                format:'Y-m-d'
            });
            $('.remove-photo').click(function(e){
                e.preventDefault();
                $(this).closest('.photo-section').remove();
            });
        });
    </script>
@endsection