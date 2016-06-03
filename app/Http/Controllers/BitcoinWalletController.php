<?php

namespace App\Http\Controllers;

use App\User;
use App\Services\TweetService;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Contracts\Routing\ResponseFactory;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Wallet;
use BlockIo;
use Illuminate\Support\Facades\Log;

class BitcoinWalletController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(Guard $auth)
    {
        parent::__construct($auth);
        $this->middleware('auth');
        $this->middleware('email.check');
    }

    public function wallets_create(Request $request)
    {

        $block_io = new BlockIo(env('BLOCKIO_APIKEY'), env('BLOCKIO_PIN'), env('BLOCKIO_VERSION'));

        if ($request->all()['wallet-label'] == null) {
            return response()->json(['msg' => 'Wallet Label Field Required', 'success' => false]);
        }
        if ($request->all()['wallet-label'] == $this->auth->user()->username) {

            // First we make sure the address label isn't in our database
            $wallet = Wallet::where('label', '=', $request->all()['wallet-label'])->get();

            if ($wallet->isEmpty()) {

                // THen we make sure its not in our wallets with blockio
                try {
                    $addressCheck = $block_io->get_address_by_label(array('label' => $request->all()['wallet-label']));
                    return response()->json(['msg' => 'Wallet Already Exist In BlockIO', 'success' => false]);
                } catch (\Exception $e) {
                    // Do nothing means our address does not exist
                }

                // Everything checks out create wallet
                try {

                    $newAddress = $block_io->get_new_address(array('label' => $request->all()['wallet-label']));

                    $addressBalance = $block_io->get_address_balance(array('addresses' => $newAddress->data->address));

                    if ($newAddress->status == true) {
                        $wallet = new Wallet;
                        $wallet->user_id = $this->auth->id();
                        $wallet->label = $request->all()['wallet-label'];
                        $wallet->address = $newAddress->data->address;
                        $wallet->balance = floatval($addressBalance->data->available_balance);
                        $wallet->pending_balance = floatval($addressBalance->data->pending_received_balance);

                        $wallet->save();
                    } else {
                        return response()->json(['msg' => 'Error while creating address', 'success' => false]);
                    }

                } catch (\Exception $e) {
                    return response()->json(['msg' => 'Failed to create address', 'success' => false]);
                }

            } else {
                return response()->json(['msg' => 'Wallet Exist In Databse', 'success' => false]);
            }

            return response()->json(['msg' => 'Wallet Created Successfully', 'success' => true]);
        }
    }

    public function wallet_update_api(Request $request)
    {
        $block_io = new BlockIo(env('BLOCKIO_APIKEY'), env('BLOCKIO_PIN'), env('BLOCKIO_VERSION'));

        if ($request->all()['wallet-id'] == null) {
            return response()->json(['msg' => 'Wallet id not specified', 'success' => false]);
        }
        if ($request->all()['wallet-id'] == $this->auth->user()->wallet()->first()->id) {


            // First check to see if we have a wallet with sent id
            $wallet = Wallet::find($request->all()['wallet-id']);

            if ($wallet != null) {

                try {
                    $addressBalance = $block_io->get_address_balance(array('addresses' => $wallet->address));

                    $wallet->balance = $addressBalance->data->available_balance;
                    $wallet->pending_balance = $addressBalance->data->pending_received_balance;

                    $wallet->save();
                    $this->auth->user()->bank->bit_coins = $addressBalance->data->available_balance;
                    $this->auth->user()->bank->update();


                } catch (\Exception $e) {
                    return response()->json(['msg' => 'Unknown error occured', 'success' => false]);
                }

            } else {
                return response()->json(['msg' => 'Unknown Wallet Id Specified', 'success' => false]);
            }

            return response()->json(['msg' => 'Wallet Updated Successfully', 'success' => true]);

        }
    }


    public function wallet_info_api(Request $request)
    {

        if ($request->all()['wallet-id'] == null) {
            return response()->json(['msg' => 'Wallet id not specified', 'success' => false]);
        }
        if ($request->all()['wallet-id'] == $this->auth->user()->wallet()->first()->id) {
            $wallet = Wallet::find($request->all()['wallet-id']);

            if ($wallet != null) {
                return response()->json(['msg' => '', 'success' => true, 'wallet-id' => $wallet->id, 'wallet-label' => $wallet->label, 'wallet-balance' => $wallet->balance]);
            } else {
                return response()->json(['msg' => 'Unknown Wallet Id Specified', 'success' => false]);
            }
        }
    }

    public function wallet_transfer_api(Request $request)
    {


        $block_io = new BlockIo(env('BLOCKIO_APIKEY'), env('BLOCKIO_PIN'), env('BLOCKIO_VERSION'));

        // Run our varaiblec checks
        if ($request->all()['wallet-id'] == null) {
            return response()->json(['msg' => 'Wallet Id Not Specified', 'success' => false]);
        }
        if ($request->all()['wallet-id'] == $this->auth->user()->wallet()->first()->id) {


            if ($request->all()['transfer-amount'] == null) {
                return response()->json(['msg' => 'Transfer Amount Not Specified', 'success' => false]);
            }

            if ($request->all()['transfer-address'] == null) {
                return response()->json(['msg' => 'Transfer Amount Not Specified', 'success' => false]);
            }

            // First check to make sure the wallet id is valid

            $wallet = Wallet::find($request->all()['wallet-id']);

            if ($wallet != null) {

                try {

                    $transferInfo = $block_io->withdraw_from_addresses(array('amounts' => strval($request->all()['transfer-amount']), 'from_addresses' => $wallet->address, 'to_addresses' => $request->all()['transfer-address'], 'pin' => env('BLOCKIO_PIN'), 'priority' => 'low'));
                    $addressBalance = $block_io->get_address_balance(array('addresses' => $wallet->address));

                    // Update our current wallets info
                    $wallet->balance = floatval($addressBalance->data->available_balance);
                    $wallet->pending_balance = floatval($addressBalance->data->pending_received_balance);
                    $wallet->save();

                    // Search for a wallet object with the address we transfered to
                    $wallet2 = Wallet::where('address', '=', $request->all()['transfer-address'])->get();

                    if (!$wallet2->isEmpty()) {

                        $addressBalance2 = $block_io->get_address_balance(array('addresses' => $request->all()['transfer-address']));

                        // We need to update the second wallet
                        $wallet2 = $wallet2->first();

                        $wallet2->balance = floatval($addressBalance2->data->available_balance);
                        $wallet2->pending_balance = floatval($addressBalance2->data->pending_received_balance);
                        $wallet2->save();

                    }

                    return response()->json(['msg' => 'Bitcoin Transfer Success', 'success' => true]);

                } catch (\Exception $e) {

                    return response()->json(['msg' => 'Error', 'success' => false]);

                }
            } else {
                return response()->json(['msg' => 'Unknown Wallet Id Specified', 'success' => false]);
            }

        }
    }

    public function giftBitcoin(Request $request, TweetService $tweetService)
    {
        $block_io = new BlockIo(env('BLOCKIO_APIKEY'), env('BLOCKIO_PIN'), env('BLOCKIO_VERSION'));

        if ($request->has('username')) {
            $user = User::where('username', '=', $request->get('username'))->first();

            if ($user) {
                $twitterProfile = $this->auth->user()->profiles()->where('provider', '=', 'twitter')->first();

                if ($request->has('tweet')) {

                    if (!$request->get('tweet')) {
                        return response()->json(array('message' => "Please enter tweet text"), 400);
                    }
                }

                $walletcheck = $user->wallet;

                if (!$walletcheck) {
                    // THen we make sure its not in our wallets with blockio
                    try {
                        $addressCheck = $block_io->get_address_by_label(array('label' => $request->get('username')));

//                        $log = print_r($addressCheck);
//                        Log::info('address check');
//                        Log::info($log);

                        return response()->json(['msg' => 'Wallet Already Exist In BlockIO', 'success' => false]);
                    } catch (\Exception $e) {
                        // Do nothing means our address does not exist
                    }

                    // Everything checks out create wallet
                    try {

                        $newAddress = $block_io->get_new_address(array('label' => $request->get('username')));

//                        $log = print_r($newAddress);
//                        Log::info('new address');
//                        Log::info($log);



                        $sendAddress = $newAddress;
                        $addressBalance = $block_io->get_address_balance(array('addresses' => $newAddress->data->address));

//                        $log = print_r($addressBalance);
//                        Log::info(' address balance');
//                        Log::info($log);

                        if ($newAddress->status == true) {
                            $wallet = new Wallet;
                            $wallet->user_id = $user->id;
                            $wallet->label = $request->get('username');
                            $wallet->address = $newAddress->data->address;
                            $wallet->balance = floatval($addressBalance->data->available_balance);
                            $wallet->pending_balance = floatval($addressBalance->data->pending_received_balance);

                            $wallet->save();

                            $sendAddress = $wallet->address;
                        } else {
                            return response()->json(['msg' => 'Error while creating address', 'success' => false]);
                        }

                    } catch (\Exception $e) {
                        return response()->json(['msg' => 'Failed to create address', 'success' => false]);
                    }
                }
                else {
                    $sendAddress = $walletcheck->address;
                }
                $wallet = $this->auth->user()->wallet;

                if ($wallet) {
                    $addressBalance = $block_io->get_address_balance(array('addresses' => $wallet->address));
//
//                    $log = print_r($addressBalance);
//                    Log::info(' auth user balance');
//                    Log::info($log);

                    $wallet->balance = $addressBalance->data->available_balance;
                    $wallet->pending_balance = $addressBalance->data->pending_received_balance;
                    $wallet->save();
                    $this->auth->user()->bank->bit_coins = $addressBalance->data->available_balance;
                    $this->auth->user()->bank->update();

                    if ((float)$this->auth->user()->bank->bit_coins < (float)$request->get('amount')) {
                        return response()->json(array('message' => "Sorry you do not have enough BitCoins to send this gift"), 400);
                    }
                }
                else {
                    return response()->json(array('message' => "Sorry you do not have BitCoin to send"), 400);
                }

                if(!$request->get('amount')){
                    return response()->json(array('message'=>"Please Enter gift amount"), 400);
                }
                if($sendAddress) {

                    try{
                        $transferInfo = $block_io->withdraw_from_addresses(array('amounts' => strval($request->get('amount')), 'from_addresses' => $wallet->address, 'to_addresses' => $sendAddress, 'pin' => env('BLOCKIO_PIN'), 'priority' => 'low'));
                    }catch(\Exception $e){
                        return response()->json(array('status' => 400, 'message' => $e->getMessage()),400);
                    }

                    $addressBalance = $block_io->get_address_balance(array('addresses' => $wallet->address));
//
//                    $log = print_r($addressBalance);
//                    Log::info(' auth user balance after transfer');
//                    Log::info($log);

                    // Update our current wallets info
                    $wallet->balance = floatval($addressBalance->data->available_balance);
                    $wallet->pending_balance = floatval($addressBalance->data->pending_received_balance);
                    $wallet->save();
                    
                    $userWallet = $user->wallet()->first();

                    //print_r($userWallet);

                    $userWallet->balance += (float)$request->get('amount');
                    $userWallet->update();
                    $user->bank->bit_coins += (float)$request->get('amount');
                    $user->bank->update();
                    $this->auth->user()->bank->bit_coins = $wallet->balance;
                    $this->auth->user()->bank->update();
                }

                if($request->has('tweet')){
                    if($tweetService->tweet($twitterProfile, $request->get('tweet'))){
                        return response()->json(array('status' => 200,'message'=>'Gift is successfully sent!','bank' => $this->auth->user()->bank), 200);
                    }else{
                        return response()->json(array('status' => 400,'message'=>$tweetService->getErrorMessage()), 400);
                    }
                }


                if($request->ajax()){
                    return response()->json(array('message'=>'Gift is successfully sent!','bank' => $this->auth->user()->bank), 200);
                }
                return redirect()->back()->with('success', 'Gift is successfully sent!');
            }
        }
    }
}