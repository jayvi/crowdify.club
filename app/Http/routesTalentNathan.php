<?php
/**
 * Created by PhpStorm.
 * User: Nathan Senn
 * Date: 11/20/2015
 * Time: 7:26 PM
 */
Route::group(['namespace' => 'Talent', 'as' => 'talent::'], function(){
    Route::get('/', [
        'as'=> 'home',
        'uses' => 'TalentController@index'
    ]);
    Route::get('/post/talent', [
        'as'=> 'get',
        'uses' => 'TalentController@getCreate'
    ]);
    Route::get('/edit/talent/{id}', [
        'as'=> 'edit',
        'uses' => 'TalentController@editTalent'
    ]);
    Route::put('/edit/talent/{id}', [
        'as'=> 'edit',
        'uses' => 'TalentController@postEdit'
    ]);
    Route::get('/manage/talent', [
        'as'=> 'manage',
        'uses' => 'TalentController@manageTalent'
    ]);
    Route::get('/manage/reqmod', [
        'as' => 'reqmod',
        'uses' => 'TalentController@reqMod'
    ]);
    Route::post('/dates/get/{id}', [
        'as'=> 'getdates',
        'uses' => 'TalentController@getDates'
    ]);
    Route::get('/talent/orders', [
        'as'=> 'orders',
        'uses' => 'TalentController@manageOrders'
    ]);
    Route::get('/talent/order/{id}', [
        'as'=> 'order',
        'uses' => 'TalentController@viewOrder'
    ]);
    Route::put('/talent/order/{id}', [
        'as'=> 'order',
        'uses' => 'TalentController@finishOrder'
    ]);
    Route::get('/talent/purchase/{id}', [
        'as'=> 'purchase',
        'uses' => 'TalentController@viewPurchase'
    ]);
    Route::put('/talent/purchase/{id}', [
        'as'=> 'purchase',
        'uses' => 'TalentController@finishPurchase'
    ]);
    Route::get('/talents/{id}', [
        'as'=> 'get',
        'uses' => 'TalentController@getTalents'
    ]);
    Route::put('/post/talent', [
        'as'=> 'post',
        'uses' => 'TalentController@postCreate'
    ]);
    Route::get('/talent/{id}', [
        'as'=> 'view',
        'uses' => 'TalentController@viewTalent'
    ]);
    Route::get('/request/{id}', [
        'as' => 'viewreq',
        'uses' => 'TalentController@viewRequest'
    ]);
    Route::put('/request/{id}', [
        'as' => 'viewreq',
        'uses' => 'TalentController@bidRequest'
    ]);
    Route::put('/talent/{id}', [
        'as'=> 'buy',
        'uses' => 'TalentController@buyTalent'
    ]);
    Route::get('/IT', [
        'as'=> 'IT',
        'uses' => 'TalentController@It'
    ]);
    Route::get('/IT/{id}', [
        'as'=> 'It',
        'uses' => 'TalentController@IT'
    ]);
    Route::post('/talent/rating/{id}', [
        'as'=> 'rating',
        'uses' => 'TalentController@getRatings'
    ]);
    Route::get('/talent/rating/{id}', [
        'as'=> 'rating',
        'uses' => 'TalentController@getRatings'
    ]);
    Route::post('/setrating/', [
        'as'=> 'setrating',
        'uses' => 'TalentController@setRating'
    ]);
    Route::post('/set/time', [
        'as'=> 'addtime',
        'uses' => 'TalentController@addTime'
    ]);
    Route::get('/post/request', [
        'as' => 'request',
        'uses' => 'TalentController@requestTalent'
    ]);
    Route::put('/post/request', [
        'as' => 'request',
        'uses' => 'TalentController@postRequest'
    ]);
    Route::put('/post/accept-bid', [
        'as' => 'accept-bid',
        'uses' => 'TalentController@acceptBid'
    ]);
    Route::put('/post/release-btc', [
        'as' => 'release-btc',
        'uses' => 'TalentController@releaseBtc'
    ]);
    Route::put('/post/cancel-job', [
        'as' => 'cancel-job',
        'uses' => 'TalentController@cancelJob'
    ]);
    Route::get('/all/request', [
        'as' => 'allreq',
        'uses' => 'TalentController@allRequest'
    ]);
    Route::get('/all/post', [
        'as' => 'alltalent',
        'uses' => 'TalentController@allTalent'
    ]);
    Route::get('talent/category/{id}','TalentController@getCategory');

});