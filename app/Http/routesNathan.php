<?php
/**
 * Created by PhpStorm.
 * User: nathan
 * Date: 10/15/2015
 * Time: 5:22 PM
 */

Route::group(['namespace' => 'Affiliate','prefix'=> 'affiliate', 'as' => 'affiliate::'], function() {
    Route::get('/{id}', [
        'as' => 'id',
        'uses' => 'AffiliateController@index',
    ]);
});
Route::group(['namespace' => 'Admin','prefix'=> 'admin', 'as' => 'admin::'], function() {

});
Route::group(['namespace' => 'Perk','prefix'=> 'perk', 'as' => 'status::'], function() {
    Route::post('/statuspost', ['as'=> 'create', 'uses'=>'StatusController@postCreate']);
});
Route::group(['namespace' => 'Perk','prefix'=> 'howto', 'as' => 'howto::'], function() {
    Route::get('/videos', ['as'=> 'videos', 'uses'=>'HowToController@index']);
    Route::post('/edit', ['as'=> 'edit', 'uses'=>'HowToController@postEdit']);
});

Route::group(['namespace' => 'Perk','prefix'=> 'status', 'as' => 'status::'], function() {
    Route::get('/{id}', ['as'=> 'show', 'uses'=>'StatusController@view']);
    Route::get('/{id}', ['as'=> 'delete', 'uses'=>'StatusController@delete']);

});