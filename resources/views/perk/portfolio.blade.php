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
                            Portfolio
                        </div>
                        <div class="panel-body">
                            <table class="table table-striped">
                                <tr>
                                    <th>Stock Name</th>
                                    <th>Current Price</th>
                                    <th>Price Invested At</th>
                                </tr>
                                @foreach($shares as $share)
                                    <tr>
                                        <td>
                                            <a href="{{ route('perk::public_profile', array('username' => $share->item->user->username)) }}" class="p-y-9"><img src="{{ $share->item->user->avatar ? $share->item->user->avatar : '' }}" class="img-circle img-profile"> {{$share->item->user->username}}</a>
                                        </td>
                                        <td>{{ round($share->item->share_price, 3) }}</td>

                                        <td>{{ $share->invested_at_price }}</td>
                                    </tr>
                                @endforeach
                            </table>
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

        });
    </script>
@endsection

