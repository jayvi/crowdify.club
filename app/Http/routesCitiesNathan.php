<?php
/**
 * Created by PhpStorm.
 * User: Nathan Senn
 * Date: 10/27/2015
 * Time: 4:03 PM
 */
Route::group(['namespace' => 'Cities', 'as' => 'cities::'], function(){
    Route::get('/', [
        'as'=> 'home',
        'uses' => 'CitiesController@index'
    ]);
    Route::get('/{id}', [
        'as'=> 'city',
        'uses' => 'CitiesController@city'
    ]);
    Route::get('/{id}/edit', [
        'as' => 'edit',
        'uses' => 'CitiesController@getEdit'
    ]);
    Route::put('/{id}/edit', [
        'as' => 'edit',
        'uses' => 'CitiesController@postEdit'
    ]);
    Route::get('get/pics', [
        'as' => 'getpic',
        'uses' => 'CitiesController@getPic'
    ]);


    Route::get('{name}/leader-board',[
        'as' => 'leader-board',
        'uses' => 'CitiesController@getLeaderBoard'
    ]);
});