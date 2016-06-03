@extends('layouts.base')

@section('content')

<h1>Current Wallets   <button type="button" class=" pull-right btn btn-primary" data-toggle="modal" data-target="#create-wallet-modal">Create Wallet</button>

</h1>
<p class="lead"></p>
<hr>
<div id="general-error"></div>
<table class="table table-striped">
    <thead>
      <tr>
        <th style="text-align: center">Wallet Label</th>
        <th style="text-align: center">Wallet Address</th>
        <th style="text-align: center">Wallet Balance</th>
        <th style="text-align: center">Wallet Pending balance</th>
        <th style="text-align: center">Actions</th>
      </tr>
    </thead>

    <tbody>
      @foreach($wallets as $wallet)
      <tr>
        <td style="text-align: center">{{$wallet->label}}</td>
        <td style="text-align: center">{{$wallet->address}}</td>
        <td style="text-align: center">{{$wallet->balance}} BTC</td>
        <td style="text-align: center">{{$wallet->balance}} BTC</td>
        <td style="text-align: center">
            <button id="removeButton" title="Delete Wallet" onclick="setDeleteId({{$wallet->id}})" data-toggle="delete-confirmation" data-placement="top" class="btn btn-sm btn-danger btn confirmation">
                <span class="glyphicon glyphicon-remove " aria-hidden="true"></span>
            </button>
            <button id="updateButton" title="Update Wallet" onclick="updateWallet({{$wallet->id}})"class="btn btn-sm btn-success btn">
                <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>
            </button>
            <button id="transferButton" title="Transfer Bitcoins" onclick="transferBitcoins({{$wallet->id}})"class="btn btn-sm btn-warning btn">
                <span class="glyphicon glyphicon-chevron-up" aria-hidden="true"></span>
            </button>
        </td>
      </tr>
      @endforeach
    </tbody>
</table>

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
          <label for="wallet-create-wallet-label">Wallet Label </label>
          <input id="wallet-create-wallet-input" type="text" class="form-control" placeholder="Wallet Label">
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
        <h5 class="modal-title">Bitcoin Wallet Creation</h5>
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


@stop

@section('scripts')
<script type="text/javascript" src="{{ URL::asset('assets/js/Wallets.js') }}"> </script>
<script type="text/javascript" src="{{ URL::asset('assets/js/bootstrap-confirmation.js') }}"></script>
<script> 
</script>
@stop
