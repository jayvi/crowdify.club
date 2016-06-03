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
                    <div class="row" >
                        <div class="col-md-4">
                            <div class="panel text-center z1">
                                <div class="panel-heading">
                                    {{--<h5>Price</h5>--}}
                                    <h5>News</h5>
                                </div>

                                <div class="panel-body">
                                    {{--<p class="share-price">{{ $item->share_price }}</p>--}}
                                    <p class="share-price">Ability to earn Bitcoin and send Bitcoin to fellow users coming soon!</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="panel text-center z1">
                                        <div class="panel-heading">
                                            <h5>Bank Balance</h5>
                                        </div>

                                        <div class="panel-body">
                                            <p>{{ $bank->crowd_coins }} Crowdify Points</p>
                                            <p>{{ $bank->seed_coins }} Crowdify Coins</p>
                                            @if($wallet)
                                                <p>{{ $wallet->balance }} BTC</p>
                                            @else
                                                <p>0.00 BTC</p>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(!$wallet)
                        <h1><button type="button" class=" pull-right btn btn-primary" data-toggle="modal" data-target="#create-wallet-modal">Create Bitcoin Wallet</button> </h1>
                    @else
                        <p class="lead"></p>
                        <hr>
                        <div id="general-error"></div>
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th style="text-align: center">Wallet Label</th>
                                <th style="text-align: center">Wallet Address</th>
                                <th style="text-align: center">Wallet Balance</th>
                                <th style="text-align: center">Actions</th>
                            </tr>
                            </thead>

                            <tbody>

                            <tr>
                                <td style="text-align: center">{{$wallet->label}}</td>
                                <td style="text-align: center">{{$wallet->address}}</td>
                                <td style="text-align: center">{{$wallet->balance}} BTC</td>
                                <td style="text-align: center">
                                    <button id="updateButton" title="Update Wallet" onclick="updateWallet({{$wallet->id}})"class="btn btn-sm btn-success btn">
                                        <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>
                                    </button>
                                    <button id="transferButton" title="Transfer Bitcoins" onclick="transferBitcoins({{$wallet->id}})"class="btn btn-sm btn-warning btn">
                                        <span class="glyphicon glyphicon-chevron-up" aria-hidden="true"></span>
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    @endif
                </div>
                <div class="col-md-3">
                    @include('includes.right_block')
                </div>
            </div>

            <!-- Modal -->
            <div id="create-wallet-modal" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h5 class="modal-title">Bitcoin Wallet Creation</h5>
                        </div>
                        <div class="modal-body">
                            <div id="create-wallet-modal-error"></div>

                            <div class="form-group">
                                <input id="wallet-create-wallet-input" type="hidden" value="{{$user}}" class="form-control" placeholder="Wallet Label">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" onclick="createWallet()">Create Wallet</button>
                        </div>
                    </div>

                </div>
            </div>


            <!-- Modal -->
            <div id="transfer-wallet-modal" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h5 class="modal-title">Bitcoin Transfer</h5>
                        </div>
                        <div class="modal-body">
                            <div id="transfer-wallet-bitcoin-error"></div>
                            <input id="transfer-wallet-bitcoin-wallet-id" type="hidden" name="walletId" value="English">

                            <label id="transfer-wallet-bitcoin-balance-label">Available Balance: </label>
                            <br/>
                            <br/>
                            <div class="form-group">
                                <label for="transfer-wallet-bitcoin-label">Amount: </label>
                                <input id="transfer-wallet-bitcoin-amount-input" type="text" class=" numbersOnly form-control" placeholder="">
                            </div>

                            <div class="form-group">
                                <label for="wallet-create-wallet-label">Wallet Address: </label>
                                <input id="transfer-wallet-bitcoin-address-input" type="text" class="form-control" placeholder="Bitcoin Address">
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" onclick="performBitcoinTransfer()">Transfer Bitcoin</button>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </main>

    @if(isset($firstTimeInBank) && $firstTimeInBank)
        @include('perk.bank.modal.first_time_in_bank')
    @endif

@stop

@section('scripts')

    <script>
        $(document).ready(function(){
            @if(isset($firstTimeInBank) && $firstTimeInBank)
                $('#modal-first-time-in-bank').modal('show');
            @endif
      });

    </script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/Wallets.js') }}"> </script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/bootstrap-confirmation.js') }}"></script>
    <script>
    </script>
@endsection

@section('styles')

@stop

