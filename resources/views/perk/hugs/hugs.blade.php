@extends('layout.master')

@section('navbar')
    @include('perk.includes.navbar')
@stop

@section('content')
    <main class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    @include('perk.includes.hug_nav')
                </div>
                <div class="col-md-8">
                    <div class="panel z1">
                        <div class="panel-heading">
                            <h5><i>Collect 10 Crowdify Coins just by visiting these tasks. After that it is up to the Creator whether they decide to pay you</i></h5>
                        </div>
                    </div>
                    @foreach($hugs as $hug)
                        @include('perk.includes.hug', array('hug' => $hug))
                    @endforeach
                </div>
            </div>
        </div><!-- container -->
    </main>

    {{--hug delete modal--}}
    @include('perk.includes.modal.hug_completers')
    @include('perk.includes.modal.hug_delete')

    @if(isset($firstTimeInTasks) && $firstTimeInTasks)
        @include('perk.hugs.modal.first_time_in_tasks')
    @endif


@stop


@section('scripts')

    <script>
        $(document).ready(function(){
            @if(isset($firstTimeInTasks) && $firstTimeInTasks)
                 $('#modal-first-time-in-tasks').modal('show');
            @endif
        });
    </script>

    @include('perk.includes.scripts.hug')
@endsection

