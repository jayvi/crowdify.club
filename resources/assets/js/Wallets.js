var deleteId = 0;


function createWallet(){

    $('#create-wallet-modal-error').empty();
    $('#create-wallet-modal-error').fadeToggle(0);

    var walletLabel = $("#wallet-create-wallet-input").val();

    $.ajax({
        url: '/api/wallets-create',
        type: 'post',
        dataType: 'json',
        data: "&wallet-label=" + walletLabel,

        success: function (data) {

            if (data['success'] == true) {
                $("#wallet-create-wallet-input").empty();
                $('#create-wallet-modal-error').append('<div style="text-align:center" class="alert alert-success" role="alert"><b> '+ data['msg'] + ' </b></div>');
                $('#create-wallet-modal-error').fadeIn("slow");
                setTimeout(function() { window.location.reload(); }, 1000);
            } else {

                $('#create-wallet-modal-error').append('<div style="text-align:center" class="alert alert-danger" role="alert"><b> '+ data['msg'] + ' </b></div>');
                $('#create-wallet-modal-error').fadeIn("slow");
            }
        }
    });
}

function deleteWallet() {

    $('#general-error').empty();
    $('#general-error').fadeToggle(0);

    $.ajax({
        url: '/api/wallet-delete',
        type: 'post',
        dataType: 'json',
        data: "&wallet-id=" + deleteId,

        success: function (data) {

            if (data['success'] == true) {
                $('#general-error').append('<div style="text-align:center" class="alert alert-success" role="alert"><b> '+ data['msg'] + ' </b></div>');
                $('#general-error').fadeIn("slow");
                setTimeout(function() { window.location.reload(); }, 1000);
            } else {

                $('#general-error').append('<div style="text-align:center" class="alert alert-danger" role="alert"><b> '+ data['msg'] + ' </b></div>');
                $('#general-error').fadeIn("slow");
            }
        }
    });
}

function updateWallet(walletId) {
    $('#general-error').empty();
    $('#general-error').fadeToggle(0);

    $.ajax({
        url: '/api/wallet-update',
        type: 'post',
        dataType: 'json',
        data: "&wallet-id=" + walletId,

        success: function (data) {

            if (data['success'] == true) {
                $('#general-error').append('<div style="text-align:center" class="alert alert-success" role="alert"><b> '+ data['msg'] + ' </b></div>');
                $('#general-error').fadeIn("slow");
                setTimeout(function() { window.location.reload(); }, 1000);
            } else {

                $('#general-error').append('<div style="text-align:center" class="alert alert-danger" role="alert"><b> '+ data['msg'] + ' </b></div>');
                $('#general-error').fadeIn("slow");
            }
        }
    });
}

function transferBitcoins(walletId) {
    $('#transfer-wallet-bitcoin-amount-input').val('0.0000000');
    $('#transfer-wallet-bitcoin-address-input').val('');

    $('#transfer-wallet-bitcoin-error').empty();
    $('#transfer-wallet-bitcoin-error').fadeToggle(0);

    $.ajax({
        url: '/api/wallet-info',
        type: 'post',
        dataType: 'json',
        data: "&wallet-id=" + walletId,

        success: function (data) {
            console.log(data);
            if (data['success'] == true) {
                $('#transfer-wallet-bitcoin-balance-label').empty();
                $('#transfer-wallet-bitcoin-balance-label').append('Available Balance: ' + data['wallet-balance'])
                $('#transfer-wallet-bitcoin-wallet-id').val(data['wallet-id']);
                $('#transfer-wallet-modal').modal('toggle');
            } else {

                $('#transfer-wallet-bitcoin-error').append('<div style="text-align:center" class="alert alert-danger" role="alert"><b> '+ data['msg'] + ' </b></div>');
                $('#transfer-wallet-bitcoin-error').fadeIn("slow");
            }
        }
    });
}

function performBitcoinTransfer() {

    $('#transfer-wallet-bitcoin-error').empty();
    $('#transfer-wallet-bitcoin-error').fadeToggle(0);

    var transferFromWalletId = $('#transfer-wallet-bitcoin-wallet-id').val();
    var transferAmount = $('#transfer-wallet-bitcoin-amount-input').val();
    var transferAddress = $('#transfer-wallet-bitcoin-address-input').val();


    $.ajax({
        url: '/api/wallet-transfer',
        type: 'post',
        dataType: 'json',
        data: "&wallet-id=" + transferFromWalletId + "&transfer-amount=" + transferAmount +"&transfer-address="+ transferAddress,

        success: function (data) {
            console.log(data);
            if (data['success'] == true) {

                $('#transfer-wallet-bitcoin-error').append('<div style="text-align:center" class="alert alert-success" role="alert"><b> '+ data['msg'] + ' </b></div>');
                $('#transfer-wallet-bitcoin-error').fadeIn("slow");

                setTimeout(function() { window.location.reload(); }, 1000);

            } else {

                $('#transfer-wallet-bitcoin-error').append('<div style="text-align:center" class="alert alert-danger" role="alert"><b> '+ data['msg'] + ' </b></div>');
                $('#transfer-wallet-bitcoin-error').fadeIn("slow");
            }
        }
    });

}

function setDeleteId(walletId) {
    deleteId = walletId;
}


// Setup the confrimation
$(document).ready(function () {
    $('[data-toggle="delete-confirmation"]').confirmation({
        onConfirm: function(event) { deleteWallet(); },
        onCancel: function(event) { alert('cancel') }
      }); 


    $('.numbersOnly').keyup(function () {
        if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
           this.value = this.value.replace(/[^0-9\.]/g, '');
        }
    });
});