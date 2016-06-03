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
                <div class="col-md-2">
                    @include('perk.includes.home_nav')
                </div>

                <div class="col-md-7">
                    <div class="panel z1">
                        <div class="panel-heading">
                            <h4 id="affiliate-heading-text">All Affiliates</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    {!! Form::select('route',$selectionCriteria,'', array('class' => 'form-control', 'id' => 'selection-criteria')) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12" id="affiliates-div">
                            @include('affiliate.includes.affiliates', array('affiliates' => $affiliates))
                        </div>
                    </div>

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
            $('#selection-criteria').change(function(e){
                var url = $(this).val();
                var text = $(this).find(':selected').text();
                $('#affiliate-heading-text').text(text);
               // console.log(url);
                //console.log(text);
                CrowdifyAjaxService.makeRequest(url, 'GET', {}, function(response){
                    $('#affiliates-div').html(response.view);
                },function(response){

                })
            });
        });
    </script>
@endsection