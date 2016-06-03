<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('testing',function(){
   
});

Route::group(['domain' => env('DOMAIN','crowdify.tech')], function(){
    
    Route::get('/terms-of-services',['as'=>'terms-of-services', 'uses' => 'StaticPagesController@getTermsOfServices']);
    Route::get('/compensation-plans', ['as' => 'compensations-plans', 'uses' => 'StaticPagesController@getCompensationPage']);

    Route::group(['as' => 'bitcoin::'], function() {

        Route::get('/wallets', [
        'as' => 'bit-wallet-view',
        'uses' => 'BitcoinWalletController@wallets_view'
        ]);

        Route::post('/api/wallets-create', [
            'as' => 'bit-wallet-create',
            'uses' => 'BitcoinWalletController@wallets_create',
        ]);
        Route::post('/api/wallet-delete', [
            'as' => 'bit-wallet-delete',
            'uses' => 'BitcoinWalletController@wallet_delete_api'
        ]);

        Route::post('/api/wallet-update', [
            'as' => 'bit-wallet-update',
            'uses' => 'BitcoinWalletController@wallet_update_api'
        ]);


        Route::post('/api/wallet-transfer', [
            'as' => 'bit-wallet-transfer',
            'uses' => 'BitcoinWalletController@wallet_transfer_api'
        ]);

        Route::post('/api/wallet-info', [
            'as' => 'bit-wallet-info',
            'uses' => 'BitcoinWalletController@wallet_info_api'
        ]);
        Route::post('/api/gift', [
            'as' => 'sendGift',
            'uses' => 'BitcoinWalletController@giftBitcoin'
        ]);
    });
    include 'routesAuth.php';

    include 'routesNathan.php';
    include 'routesPerkSohel.php';
    include 'routesPerkAdre.php';
    include 'routesAqib.php';
});

Route::group(['domain' => 'tools.'.env('DOMAIN','crowdify.tech')], function(){
    Route::group(['namespace' => 'Tools', 'as' => 'Tools::'], function() {
        Route::get('/', [
            'as' => 'home',
            'uses' => 'WebToolsWikiController@index'
        ]);
        Route::get('/dbmove', [
            'as' => 'webtoolswiki',
            'uses' => 'WebToolsWikiController@dbmove'
        ]);
        Route::get('/{id}', [
            'as' => 'webpost',
            'uses' => 'WebToolsWikiController@post'
        ]);
        Route::get('/{id}/edit', [
            'as' => 'edit',
            'uses' => 'WebToolsWikiController@getEdit'
        ]);
        Route::put('/{id}/edit', [
            'as' => 'edit',
            'uses' => 'WebToolsWikiController@postEdit'
        ]);
        Route::get('tools/create', [
            'as' => 'create',
            'uses' => 'WebToolsWikiController@getCreate'
        ]);
        Route::put('tools/create', [
            'as' => 'create',
            'uses' => 'WebToolsWikiController@postCreate'
        ]);
        Route::get('tools/fixlistly', [
            'as' => 'fix',
            'uses' => 'WebToolsWikiController@fixlistly'
        ]);
    });
});

Route::group(['domain' => 'events.'.env('DOMAIN','crowdify.tech')], function(){
    include 'routesEventSohel.php';
    include 'routesEventAdre.php';
});

Route::group(['domain' => 'blog.'.env('DOMAIN','crowdify.tech')], function(){
    include 'routesBlogAdre.php';
});

/*
 * routes for places.crowdify.tech
 * */
Route::group(['domain' => 'places.'.env('DOMAIN','crowdify.tech')], function(){
    include 'routesPlacesSohel.php';
});

Route::group(['domain' => 'cities.'.env('DOMAIN','crowdify.tech')], function(){
    include 'routesCitiesNathan.php';
});
Route::group(['domain' => 'talent.'.env('DOMAIN','crowdify.tech')], function(){
    include 'routesTalentNathan.php';
});
include 'routesGlobal.php';




